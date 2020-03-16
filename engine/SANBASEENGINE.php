<?php
/*
------------------------------------------------------------------------------------
|    Application Name	: Riasys SanBase  Ver 1.0             
|    Program Name	: sanbaseengine.php                       
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

include('FILEENGINE.php');



class SANBASEENGINE
{
	private $path;
	public $FileEngine;
	public $sys;
	public $databases;
	public $sysSchema;
	

public function SANBASEENGINE($Path)
{
	$this->path=$Path;
	
		$this->databases=array();
		$this->FileEngine=new FILEENGINE($this->path);


}


public function GetPath()
{
return $this->path;
}


public function System()
{
$data=array();	
	$data=$this->FileEngine->Read("","sanbase.json");
	
	if($data['error']==2)
	{
		$sbdata=$this->SystemSchema($this->path);
		//var_dump($sbdata);
		$data=$this->FileEngine->Create(json_encode($sbdata),"","sanbase.json");
		$this->Update(1);
	}
	else if($data['error']==3)
	{
		$this->Update(1);
		

	}
	else if($data['error']==1)
	{
		return null;

	}
	else
	{
		
		$retdata=json_decode($data['data']);
		$this->sys=$retdata->sys;
		$this->databases=$retdata->databases;
	}

return $this->sys;
}

public function Update($i)
{
$data=array();	
	
		$sbdata=$this->SystemSchema($this->path);
		
		$data=$this->FileEngine->Write(json_encode($sbdata),"","sanbase.json");
		if($data['error']==0)
		{
			$retdata=json_decode(json_encode($sbdata));
			$this->sys=$retdata->sys;
			$this->databases=$retdata->databases;
		}
		else
		{
			$this->sys=null;
			$this->databases=null;
		}
	
}


public function Add($database)
{
$dbcount=count($this->databases);
$this->databases[$dbcount]=$database;

}
public function Remove($database)
{
$status=0;

	foreach ($this->databases as $key => $val) {
		if(strtoupper($val)==strtoupper($database))
		{
			$status=1;
			array_splice($this->databases, $key, 1);
			return $status;
		}
    		
	}

return $status;

}




public function IsDatabaseExists($database)
{
$status=0;
	foreach ($this->databases as $key => $val) {
		if(strtoupper($val)==strtoupper($database))
		{
			$status=1;
			return $status;
		}
    		
	}

return $status;
}

public function DatabaseExists($database)
{
$folderStatus=0;
$status=0;
$arrayStatus=$this->IsDatabaseExists($database);

	if(file_exists($this->path."/".$database))
        {
		
		$folderStatus=1;
	}
	
	if($arrayStatus==1 && $folderStatus==1)
	{
		$status=1;
	}
	elseif($arrayStatus==0 && $folderStatus==1)
	{
		$status=2;
		
	}
	elseif($arrayStatus==1 && $folderStatus==0)
	{
		$status=3;
	}
return $status;
}






public function SystemSchema($path)
{
$data=array();

$data['sys']=$this->Preamble();
$data['path']=$path;
if(is_null($this->databases))
{
$this->databases=array();
}
$data['databases']=$this->databases;

return $data;
}

public function Preamble()
{
$data=array();

$data['app']="Riasys SanBase JSON Database";
$data['engine']="SanBase";
$data['version']['max']="1";
$data['version']['major']="0";
$data['version']['minor']="0";
$data['author']="Santokh Singh Saggu";
$data['copyright']['name']="Santokh Singh Saggu";
$data['copyright']['year']="2020";
$data['organisation']['name']="Riasys Technology";
$data['organisation']['address']="H-No-5,Bari Road,VidyaPati Nagar,Baridih";
$data['organisation']['city']="Jamshedpur";
$data['organisation']['State']="Jharkhand";
$data['organisation']['pin']="831017";
$data['organisation']['email']="santokh@sanbase.in";

return $data;
}




}



?>
