<?php
/*
------------------------------------------------------------------------------------
|    Application Name	: Riasys SanBase  Ver 1.0             
|    Program Name	: document.php                       
|    Start              : Sat Jan  11 2020
|    Copyright          : (C) 2020 Santokh Singh Saggu,All rights Reserved
|    Company            : Riasys Technology
|    Website		: www.riasys.co.in
|    
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


class DOCUMENT
{
	public $database;
	public $document;
	private $header;
	private $docData;
	private $orCondtion;
	private $andCondition;
	private $parser;
	private $cache;
	

public function DOCUMENT($database,$document)
{
		$this->header=array();
		$this->docData=array();
		$this->orCondtion=array();
		$this->andCondition=array();
		$this->cache=array();
		$this->database=$database;
		$this->document=$document;
		$this->parser=new PARSER();
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


public function Find($query)
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

	$data=$this->Search($this->cache[$this->document],$query);
return $data;
}
else
{
return $data;
}

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





public function Search($data,$query)
{
$tree=array();
$depth=array();
$result=array();;
$lastkey='';
	$key=$this->parser->MatchPattern($query);
	if($key!=false)
	{	$tree=$this->parser->Tokenize($query,$key);
		//var_dump(count($tree));
		
		if(count($tree)==2)
		{
			if($tree[0][3]=="a")
			{
				$result=$this->Seek($tree[0][1]);
				return $result;
			}
			elseif($tree[0][3]=="key")
			{
				$result=$this->SeekKey($tree[0][0]);
				return $result;
			}
			else
			{
			
				$depth=explode(".",$tree[0][0]);
				$keycount=count($depth);
				$lastkey=$depth[$keycount-1];
				$result=$this->Scan(0,$data,$depth,$lastkey,$tree[0][1]);
				return $result;	
			}
		}
		else
		{
				$depth=explode(".",$tree[0][0]);
				$keycount=count($depth);
				$lastkey=$depth[$keycount-1];
				$result=$this->Scan(0,$data,$depth,$lastkey,$tree[0][1]);
				if($tree[2]=='or')
				{
					if(empty($result))
					{
						$depth=explode(".",$tree[1][0]);
						$keycount=count($depth);
						$lastkey=$depth[$keycount-1];
						$result=$this->Scan(0,$data,$depth,$lastkey,$tree[1][1]);
					}

				}
				elseif($tree[2]=='and') 
				{
				
					if($tree[1][3]=="key")
					{
						$result=$this->SeekFromDataKey($result,$tree[1][0]);
						return $result;
					}
					else
					{
						$depth=explode(".",$tree[1][0]);
						$keycount=count($depth);
						
						$lastkey=$depth[$keycount-1];
						$result=$this->Scan(0,$result,$depth,$lastkey,$tree[1][1]);
					}
				}
				return $result;	



		}
		

	}
return $result;
}



public function Scan($start,$data,$depth,$key,$value)
{
$dataIndex=0;
	$level=count($depth);
	$result=array();;
	
		

	// indexed array level 0
       foreach($data as $skey => $svalue)
       {	
		if(is_array($svalue))
		{	// Key search Level 1
			foreach($svalue as $s1key => $s1value)
       			{	$si=0; // Level 0
				
				if(is_array($s1value) && $depth[$si]==$s1key)
				{	// indexed array of Key 2
					foreach($data[$skey][$depth[0]] as $s2key => $s2value)
       					{	
						if(is_array($s2value) && is_numeric($s2key))
						{	// Key search Level 3
							foreach($data[$skey][$depth[0]][$s2key] as $s3key => $s3value)
       							{        $si=1; // Level 1
								
								if(is_array($s3value) && $depth[$si]==$s3key)
								{	
									foreach($data[$skey][$depth[0]][$s2key][$depth[1]] as $s4key => $s4value)
       									{	
										if(is_array($s4value))
										{	
											foreach($s4value as $s5key => $s5value)
       											{
												
												$si=$level-2;
												
												if(is_array($s5value) && $depth[$si]==$s5key)
												{	
													foreach($data[$skey][$depth[0]][$s2key][$depth[1]][$s4key] as $s6key => $s6value)
       													{	
														if(is_array($s6value))
														{
															foreach($data[$skey][$depth[0]][$s2key][$depth[1]][$s4key][$depth[2]] as $s7key => $s7value)
       															{
																
																if(is_array($s7value) && $s7value[$key]==trim($value,"'"))
																{
																	if($level>=4)
																	{
																		$result[$dataIndex]=$s7value;
																		$dataIndex=$dataIndex+1;
																		
																	}
																	
																}
																else
																{
																	if($s7key==$key && $s7value==trim($value,"'"))
																	{			
																		
																	}
																	



																}
																	
															}
														}
														else
														{
																
																

														}
													}
												}
												else
												{
													
													$si=$level-1;	
													
													if(!is_array($s5value) && $key==$s5key && trim($value,"'")==$s5value && $depth[$si]==$s5key && $level==3)
													{
														$result[$dataIndex]=$data[$skey][$depth[0]][$s2key][$depth[1]][$s4key];
														$dataIndex=$dataIndex+1;
														
													}
													


												}
											
											}
										}
									}
								}
								else
								{

									
										$si=1;	
										if(!is_array($s3value) && $key==$s3key && trim($value,"'")==$s3value && $depth[$si]==$s3key && $level==2)
										{
											$result[$dataIndex]=$data[$skey][$depth[0]][$s2key];
											$dataIndex=$dataIndex+1;
											
										}
										


								}
							}
						}
						else
						{
							$si=1; 
							if(is_array($s2value) && is_string($s2key) && $depth[$si]==$s2key)
							{
								foreach($data[$skey][$depth[0]][$s2key] as $s3key => $s3value)
       								{    
									
									$si=2;
									
									if(is_array($s3value) && $level>3 && array_key_exists($depth[2],$s3value))
									{
							
										foreach($data[$skey][$depth[0]][$s2key][$s3key][$depth[2]] as $s4key => $s4value)
       										{
											if(array_key_exists($key,$s4value) && $s4value[$key]==trim($value,"'"))
											{
												
												$result[$dataIndex]=$data[$skey][$depth[0]][$s2key][$s3key][$depth[2]];
												$dataIndex=$dataIndex+1;
											}
										}
									}
									else
									{
										if(array_key_exists($key,$s3value))
										{
											if($s3value[$key]==trim($value,"'") && $level==3)
											{
										
												$result[$dataIndex]=$data[$skey][$depth[0]][$s2key][$s3key];
												$dataIndex=$dataIndex+1;	

											}
										}
										

									}
								}
							}



























						}
					}
				}
				else
				{

					$si=0;	
					if(!is_array($s1value) && $key==$s1key && trim($value,"'")==$s1value && $depth[$si]==$s1key && $level==1)
					{
						$result[$dataIndex]=$data[$skey];
						$dataIndex=$dataIndex+1;	
						
					}
					


				}
		
			}
		}
		


       }
	
return $result;
}


// Search Index of Documents for given query


public function FindIndex($query)
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

	$data=$this->SearchIndex($this->cache[$this->document],$query);
return $data;
}
else
{
return $data;
}

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








public function SearchIndex($data,$query)
{
$tree=array();
$depth=array();
$result=array();;
$lastkey='';
	$key=$this->parser->MatchPattern($query);
	if($key!=false)
	{	$tree=$this->parser->Tokenize($query,$key);
		//var_dump(count($tree));
		
		if(count($tree)==2)
		{
			if($tree[0][3]=="a")
			{
				$result=$this->SeekIndex($tree[0][1]);
				return $result;
			}
			elseif($tree[0][3]=="key")
			{
				$result=$this->SeekKeyIndex($tree[0][0]);
				return $result;
			}
			else
			{
			
				$depth=explode(".",$tree[0][0]);
				$keycount=count($depth);
				$lastkey=$depth[$keycount-1];
				$result=$this->ScanForIndex(0,$data,$depth,$lastkey,$tree[0][1]);
				return $result;	
			}
		}
		else
		{
				$depth=explode(".",$tree[0][0]);
				$keycount=count($depth);
				$lastkey=$depth[$keycount-1];
				$result=$this->ScanForIndex(0,$data,$depth,$lastkey,$tree[0][1]);
				if($tree[2]=='or')
				{
					if(empty($result))
					{
						$depth=explode(".",$tree[1][0]);
						$keycount=count($depth);
						$lastkey=$depth[$keycount-1];
						$result=$this->ScanForIndex(0,$data,$depth,$lastkey,$tree[1][1]);
					}

				}
				elseif($tree[2]=='and') 
				{
				
					if($tree[1][3]=="key")
					{
						$result=$this->SeekIndexFromDataKey($result,$tree[1][0]);
						return $result;
					}
					else
					{
						$depth=explode(".",$tree[1][0]);
						$keycount=count($depth);
						
						$lastkey=$depth[$keycount-1];
						$result=$this->ScanForIndex(0,$result,$depth,$lastkey,$tree[1][1]);
					}
				}
				return $result;	



		}
		

	}
return $result;
}


public function ScanForIndex($start,$data,$depth,$key,$value)
{
$dataIndex=0;
	$level=count($depth);
	$result=array();;
	
		

	// indexed array level 0
       foreach($data as $skey => $svalue)
       {	
		if(is_array($svalue))
		{	// Key search Level 1
			foreach($svalue as $s1key => $s1value)
       			{	$si=0; // Level 0
				
				if(is_array($s1value) && $depth[$si]==$s1key)
				{	// indexed array of Key 2
					foreach($data[$skey][$depth[0]] as $s2key => $s2value)
       					{	
						if(is_array($s2value) && is_numeric($s2key))
						{	// Key search Level 3
							foreach($data[$skey][$depth[0]][$s2key] as $s3key => $s3value)
       							{        $si=1; // Level 1
								
								if(is_array($s3value) && $depth[$si]==$s3key)
								{	
									foreach($data[$skey][$depth[0]][$s2key][$depth[1]] as $s4key => $s4value)
       									{	
										if(is_array($s4value))
										{	
											foreach($s4value as $s5key => $s5value)
       											{
												
												$si=$level-2;
												
												if(is_array($s5value) && $depth[$si]==$s5key)
												{	
													foreach($data[$skey][$depth[0]][$s2key][$depth[1]][$s4key] as $s6key => $s6value)
       													{	
														if(is_array($s6value))
														{
															foreach($data[$skey][$depth[0]][$s2key][$depth[1]][$s4key][$depth[2]] as $s7key => $s7value)
       															{
																
																if(is_array($s7value) && $s7value[$key]==trim($value,"'"))
																{
																	if($level>=4)
																	{
																		$result[$dataIndex]=$skey;
																		$dataIndex=$dataIndex+1;
																		
																	}
																	
																}
																else
																{
																	if($s7key==$key && $s7value==trim($value,"'"))
																	{			
																		
																	}
																	



																}
																	
															}
														}
														else
														{
																
																

														}
													}
												}
												else
												{
													
													$si=$level-1;	
													
													if(!is_array($s5value) && $key==$s5key && trim($value,"'")==$s5value && $depth[$si]==$s5key && $level==3)
													{
														$result[$dataIndex]=$skey;
														$dataIndex=$dataIndex+1;
														
													}
													


												}
											
											}
										}
									}
								}
								else
								{

									
										$si=1;	
										if(!is_array($s3value) && $key==$s3key && trim($value,"'")==$s3value && $depth[$si]==$s3key && $level==2)
										{
											$result[$dataIndex]=$skey;
											$dataIndex=$dataIndex+1;
											
										}
										


								}
							}
						}
						else
						{
							$si=1; 
							if(is_array($s2value) && is_string($s2key) && $depth[$si]==$s2key)
							{
								foreach($data[$skey][$depth[0]][$s2key] as $s3key => $s3value)
       								{    
									
									$si=2;
									
									if(is_array($s3value) && $level>3 && array_key_exists($depth[2],$s3value))
									{
							
										foreach($data[$skey][$depth[0]][$s2key][$s3key][$depth[2]] as $s4key => $s4value)
       										{
											if(array_key_exists($key,$s4value) && $s4value[$key]==trim($value,"'"))
											{
												
												$result[$dataIndex]=$skey;
												$dataIndex=$dataIndex+1;
											}
										}
									}
									else
									{
										if(array_key_exists($key,$s3value))
										{
											if($s3value[$key]==trim($value,"'") && $level==3)
											{
										
												$result[$dataIndex]=$skey;
												$dataIndex=$dataIndex+1;	

											}
										}

									}
								}
							}



























						}
					}
				}
				else
				{

					$si=0;	
					if(!is_array($s1value) && $key==$s1key && trim($value,"'")==$s1value && $depth[$si]==$s1key && $level==1)
					{
						$result[$dataIndex]=$skey;
						$dataIndex=$dataIndex+1;	
						
					}
					


				}
		
			}
		}
		


       }
	
return $result;
}







public function ScanLeaf($data,$key,$value)
{
	if(is_null($data))
	{	
		return '';
	}
	foreach($data as $skey => $svalue)
        {
		if(!is_array($svalue))
		{
			if($skey==$key && $svalue==trim($value,"'"))
			{
				return $svalue;

			}
		}

	}

return '';

}




public function DeleteAll()
{
$this->header['data']=array();
$err=$this->database->Engine->FileEngine->Write(json_encode($this->header,JSON_PRETTY_PRINT),$this->database->GetActiveDatabase(),$this->document.".json");
return $err;

}


public function Update($data,$query)
{
$Indexes=array();

$Indexes=$this->FindIndex($query);



if(count($Indexes)==0)
{

return 0;
}
else
{

	$err=$this->database->Engine->FileEngine->Read($this->database->GetActiveDatabase(),$this->document.".json");

	if($err['error']==0)
	{
		$docData=json_decode($err['data'],true);
		$this->docData=$docData['data'];
		foreach($Indexes as $key => $value)
        	{
				$this->docData[$value]=json_decode($data,true);
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




}






public function Delete($query)
{
$Indexes=array();

$Indexes=$this->FindIndex($query);



if(count($Indexes)==0)
{

return 0;
}
else
{

	$err=$this->database->Engine->FileEngine->Read($this->database->GetActiveDatabase(),$this->document.".json");

	if($err['error']==0)
	{
		$docData=json_decode($err['data'],true);
		$this->docData=$docData['data'];
		foreach($Indexes as $key => $value)
        	{
				array_splice($this->docData, $value, 1);
				

				

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
