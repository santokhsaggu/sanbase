<?php
/*
------------------------------------------------------------------------------------
|    Application Name	: Riasys SanBase  Ver 1.0             
|    Program Name	: document.php                       
|    Start              : Sat Jan  11 2020
|    Copyright          : (C) 2020 Santokh Singh Saggu,All rights Reserved
|    Company            : Riasys Technology
|    Website		: www.riasys.co.in
|    Modified           : FRI May  01 2020
|    Copyright 2020 Santokh Singh Saggu
|
|   Licensed under the Apache License, Version 2.0 (the "License");
|   you may not use this file except in compliance with the License.
|   You may obtain a copy of the License at
|
|       http://www.apache.org/licenses/LICENSE-2.0
|
|   Unless required by applicable law or agreed to in writing, software
|   distributed under the License is distributed on an "AS IS" BASIS,
|   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
|   See the License for the specific language governing permissions and
|   limitations under the License.
|
|-------------------------------------------------------------------------------------
*/

if ( !defined('SAN_BASE') )
{
	die("Hacking attempt");
}

include('PARSER.php');
include('QUERY.php');
include('CURSOR.php');

class DOCUMENTS
{
	public $database;
	public $document;
	private $header;
	private $docData;
	private $orCondtion;
	private $andCondition;
	private $parser;
	private $cache;
	public $query;
	private $fields;
	private $rows;
	private $pointer;
	
	

public function DOCUMENTS($database,$document)
{
		$this->header=array();
		$this->docData=array();
		$this->orCondtion=array();
		$this->andCondition=array();
		$this->fields=array();
		$this->rows=array();
		$this->pointer=0;
		$this->cache=array();
		$this->database=$database;
		$this->document=$document;
		$this->parser=new PARSER();
		$this->query=new Query();
		$this->header['document']=$document;
		$this->header['version']['max']=$database->Engine->sys->version->max;
		$this->header['version']['major']=$database->Engine->sys->version->major;
		$this->header['version']['minor']=$database->Engine->sys->version->minor;
		


}

public function Insert($data)
{
$in_data=str_replace("'","\"",$data);
if($this->json_validator($in_data))
{
$err['object']="document";
$err['error']="0";
$err['msg']="Saved";
$this->Add($in_data);
$this->header['data']=$this->docData;

$err=$this->database->Engine->FileEngine->Write(json_encode($this->header,JSON_PRETTY_PRINT),$this->database->GetActiveDatabase(),$this->document.".json");
return $err;
}
else
{
		$err['object']="document";
	   	$err['error']="1";
	   	$err['msg']="Invalid JSON Data";
		return $err;
}
}


private function Add($data)
{
$err=$this->database->Engine->FileEngine->Read($this->database->GetActiveDatabase(),$this->document.".json");

if($err['error']==0)
{
$docData=json_decode($err['data'],true);
$this->docData=$docData['data'];

if(strpos($data,"_id")==false)
{
$ddata=json_decode($data,true);

$ddata['_id']=md5($data.time());
$data=json_encode($ddata,JSON_PRETTY_PRINT);
}
$this->docData[count($this->docData)]=json_decode($data);

return 1;
}
else if($err['error']==3)
{
	$this->docData[count($this->docData)]=json_decode($data);
}
else
{
return 0;
}
}



private function json_validator($data=NULL) {
  if (!empty($data)) {
                @json_decode($data);
                return (json_last_error() === JSON_ERROR_NONE);
        }
        return false;
}



public function FindAll()
{
$err=$this->database->Engine->FileEngine->Read($this->database->GetActiveDatabase(),$this->document.".json");

if($err['error']==0)
{
$docData=json_decode($err['data']);
return $docData->data;
}
else
{
return null;
}

}

public function FindWithKey($key,$value)
{
$data=array();
$r=0;
$err=$this->database->Engine->FileEngine->Read($this->database->GetActiveDatabase(),$this->document.".json");

if($err['error']==0)
{
$docData=json_decode($err['data']);
	for($i=0;$i<count($docData->data);$i++)
	{
		if($docData->data[$i]->$key==$value)
		{
		$data[$r]=$docData->data[$i];
		$r=$r+1;
		}

	}
return $data;
}
else
{
return $data;
}

}

public function Seek($Index)
{
$data=array();
$r=0;
$err=$this->database->Engine->FileEngine->Read($this->database->GetActiveDatabase(),$this->document.".json");

if($err['error']==0)
{
$docData=json_decode($err['data']);
	$max=count($docData->data);
	if($Index>=0 && $Index<$max)
	{
		
		$data=$docData->data[$Index];
		

	}
return $data;
}
else
{
return $data;
}

}

public function SeekFirst()
{
$data=array();

		
$data=$this->Seek(0);
		

return $data;

}

public function SeekLast()
{
$data=array();

		
$data=array();
$r=0;
$err=$this->database->Engine->FileEngine->Read($this->database->GetActiveDatabase(),$this->document.".json");

if($err['error']==0)
{
$docData=json_decode($err['data']);
	$max=count($docData->data);
	if($max>0)
	{
		$max=$max-1;
		$data=$docData->data[$max];
		

	}
return $data;
}
else
{
return $data;
}

}



public function Find($fields,$query)
{
$data=array();
$err=array();
$r=0;
if(empty($this->cache[$this->document]))
{
$err=$this->database->Engine->FileEngine->Read($this->database->GetActiveDatabase(),$this->document.".json");

if($err['error']==0)
{
$docData=json_decode($err['data'],true);
$this->cache[$this->document]=$docData['data'];
}
else
{
return $data;
}

}
else
{
	//echo "Cache";
	$err['error']=0;

}

if($err['error']==0)
{
	
	$data=$this->Search($this->cache[$this->document],$fields,$query);
	
return $data;
}
else
{
return $data;
}

}



public function GetFields($result)
{

$rows=$result->GetRoute();
$inter_data=$this->cache[$this->document];
$this->pointer=0;
$cursor=new Cursor();
foreach($rows as $key => $value)
{
	
	foreach($this->fields as $fkey => $fvalue)
	{
	
	$row=json_decode($value);
	$tok=explode(".",$fvalue);
	$filter=$this->query->GetFilterRoute($row,$tok);
	
	$sbuffer=$this->query->SeekFilteredData($inter_data,$filter['filter']);
	if(is_string($sbuffer))
	{
		$cursor->Add($fvalue,$sbuffer);
		
	}
	else
	{
		if($filter['start']<count($tok))
		{
			
			$cursor->Add($fvalue,$this->query->SeekNextTokenData($filter['start'],count($tok),$tok,$sbuffer));
		}
		else
		{

			$cursor->Add($fvalue,$sbuffer);
		}
	}
	
	}
	$cursor->pointer++;
}

return $cursor;
	
}







public function Search($data,$fields,$query)
{
$tree=array();
$tok=array();
$result=array();;
$field_array=array();
$lastkey='';
$last_key='';
$last_lop='';
$cur_key='';
$cur_lop='';
$this->query->Init();


	$this->fields=explode(",",$fields);
	
	$key=$this->parser->MatchPattern($query);
	if($key)
	{	$tree=$this->parser->Tokenize($query,$key);
		
		
		if(count($tree)>=1)
		{
			if($tree[0]['expr']['type']=="a")
			{
				$result=$this->Seek($tree[0]['expr']['right']);
				return $result;
			}
			elseif($tree[0]['expr']['type']=="k")
			{
				$tok=explode(".",$tree[0]['expr']['left']);
				$keycount=count($tok);
				$lastkey=$tok[$keycount-1];
				$result=$this->query->Search(0,$keycount,$tok,$data,'','');
				return $result[$this->query->interPointer];
			}
			else
			{
			
				foreach($tree as $key => $value)
        			{				
						
						$tok=explode(".",$tree[$key]['expr']['left']);
						$keycount=count($tok);
						$lastkey=$tok[$keycount-1];
					if($key==0)
					{
						$last_key=$key;
						$last_lop=strtoupper($tree[$key]['lop']);	
						$result=$this->query->Search(0,$keycount,$tok,$data,$tree[$key]['expr']['mop'],$tree[$key]['expr']['right']);
						$this->query->interPointer++;

					}
					else
					{
						$cur_key=count($result)-1;
						$cur_lop=$last_lop;
						$last_key=$key;
						$last_lop=strtoupper($tree[$key]['lop']);

						if(count($result[$cur_key]->GetRoute())>0 && $cur_lop=='AND')
						{
							$buffer=$result[$cur_key]->GetRoute();
							$this->query->CreateInter();
							
							foreach($buffer as $bkey => $bvalue)
        						{
								$row=json_decode($bvalue);
								$filter=$this->query->GetFilterRoute($row,$tok);
								$sbuffer=$this->query->SeekFilteredData($data,$filter['filter']);
									
								$result=$this->query->FilterSearch($filter['start'],$keycount,$tok,$sbuffer,$tree[$key]['expr']['mop'],$tree[$key]['expr']['right'],$filter['route']);
								
							}
							$this->query->interPointer++;

						}
						else if(count($result[$cur_key]->GetRoute())==0 && $cur_lop=='OR')
						{
							$result=$this->query->Search(0,$keycount,$tok,$data,$tree[$key]['expr']['mop'],$tree[$key]['expr']['right']);
							$this->query->interPointer++;

						}
						

					}
				}
				$this->query->interPointer--;
				return $result[$this->query->interPointer];	
			}
		}

		

	}
return $result;
}









function SeekKey($key)
{

$data=array();
$r=0;
$err=$this->database->Engine->FileEngine->Read($this->database->GetActiveDatabase(),$this->document.".json");

if($err['error']==0)
{
$docData=json_decode($err['data'],true);
	foreach($docData['data'] as $skey => $svalue)
        {
		foreach($svalue as $kkey => $kvalue)
        	{
			if($kkey==$key)
			{
			$data[$r]=$docData['data'][$skey];
			$r=$r+1;
			}
		}

	}
return $data;
}
else
{
return $data;
}
}


function SeekFromDataKey($result,$key)
{

$data=array();
$r=0;

	foreach($result as $skey => $svalue)
        {
		
		foreach($svalue as $kkey => $kvalue)
        	{
			if($kkey==$key)
			{
			$data[$r]=$result[$skey][$key];
			$r=$r+1;
			}
		}

	}
return $data;

}




public function SeekIndex($Index)
{
$data=array();;
$r=0;
$err=$this->database->Engine->FileEngine->Read($this->database->GetActiveDatabase(),$this->document.".json");

if($err['error']==0)
{
$docData=json_decode($err['data']);
	$max=count($docData->data);
	if($Index>=0 && $Index<$max)
	{
		
		$data[0]=$Index;
		

	}
return $data;
}
else
{
return $data;
}

}




function SeekKeyIndex($key)
{

$data=array();
$r=0;
$err=$this->database->Engine->FileEngine->Read($this->database->GetActiveDatabase(),$this->document.".json");

if($err['error']==0)
{
$docData=json_decode($err['data'],true);
	foreach($docData['data'] as $skey => $svalue)
        {
		foreach($svalue as $kkey => $kvalue)
        	{
			if($kkey==$key)
			{
			$data[$r]=$skey;
			$r=$r+1;
			}
		}

	}
return $data;
}
else
{
return $data;
}
}


function SeekIndexFromDataKey($result,$key)
{

$data=array();
$r=0;

	foreach($result as $skey => $svalue)
        {
		
		foreach($svalue as $kkey => $kvalue)
        	{
			if($kkey==$key)
			{
			$data[$r]=$skey;
			$r=$r+1;
			}
		}

	}
return $data;

}


public function DeleteAll()
{
$this->header['data']=array();
$err=$this->database->Engine->FileEngine->Write(json_encode($this->header,JSON_PRETTY_PRINT),$this->database->GetActiveDatabase(),$this->document.".json");
return $err;

}


public function Update($data,$query)
{

	$err=$this->database->Engine->FileEngine->Read($this->database->GetActiveDatabase(),$this->document.".json");

	if($err['error']==0)
	{
		$docData=json_decode($err['data'],true);
		$this->docData=$docData['data'];

		$rows=$this->Search($this->docData,"",$query);

		foreach($rows as $key => $value)
        	{
				$row=json_decode($value);
				$this->docData[$row[0]]=json_decode($data,true);
				$this->header['data']=$this->docData;//json_decode($data);

				

		}
		$err=$this->database->Engine->FileEngine->Write(json_encode($this->header,JSON_PRETTY_PRINT),$this->database->GetActiveDatabase(),$this->document.".json");

		return 1;
	}
	else
	{
		return 0;
	}


}






public function Delete($query)
{

	$err=$this->database->Engine->FileEngine->Read($this->database->GetActiveDatabase(),$this->document.".json");

	if($err['error']==0)
	{
		$docData=json_decode($err['data'],true);
		$this->docData=$docData['data'];
		
		$rows=$this->Search($this->docData,"",$query);
		foreach($rows as $key => $value)
        	{		$row=json_decode($value);
				array_splice($this->docData, $row[0], 1);
				

				

		}
		$this->header['data']=$this->docData;
		$err=$this->database->Engine->FileEngine->Write(json_encode($this->header,JSON_PRETTY_PRINT),$this->database->GetActiveDatabase(),$this->document.".json");
	
		return 1;
	}
	else
	{
		return 0;
	}


}



public function Export()
{
$phpdata="";
$err=$this->database->Engine->FileEngine->Read($this->database->GetActiveDatabase(),$this->document.".json");

if($err['error']==0)
{
$docData=json_decode($err['data'],true);
$file="export/".$docData['document'].".php";
$date=Date("Y-m-d");
$phpdata=$this->PhpHeader($file,$date);
$phpdata.='$object['."'".$docData['document']."'".']="'.str_replace("\"","\\\"",json_encode($docData['data'],JSON_PRETTY_PRINT)).'";'."\r\n";
$phpdata.="\r\n".'?>'."\r\n";

$err=$this->database->Engine->FileEngine->Folder->Create("/export");
if($err['error']==0 || $err['error']==2)
{

	if($this->database->Engine->FileEngine->FileExists('',$file))
	{
	$err=$this->database->Engine->FileEngine->Write($phpdata,'',$file);
	}
	else
	{
	$err=$this->database->Engine->FileEngine->Create($phpdata,'',$file);
	}
	var_dump($err);
}
return 0;
}
else
{
return 0;
}

}

function PhpHeader($file,$date)
{

$phpdata='<?php'."\r\n";
$phpdata.='/***************************************************************************'."\r\n";
$phpdata.='*        Thie file is generated through Sanbase Export Utility             '."\r\n";
$phpdata.='*  File Name : '.$file.'	                                                      '."\r\n";
$phpdata.='*  Created Date : '.$date.'	                                              '."\r\n";
$phpdata.='*  Created By :Santokh Singh Saggu,Copyright :2020                                     '."\r\n";
$phpdata.='*  Organization :Riasys Technology                                         '."\r\n";
$phpdata.='***************************************************************************/'."\r\n";

return  $phpdata;

}


}



?>
