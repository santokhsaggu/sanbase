<?php
/*
------------------------------------------------------------------------------------
|    Application Name	: Riasys SanBase  Ver 1.0             
|    Program Name	: sanbase.php                       
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

define('SAN_BASE', true);


include('engine/SANBASEENGINE.php');
include('engine/DATABASE.php');

class SANBASE
{
	private $path;
	public $Documents;
	public $Document;
	public $databases;
	public $sys;
	public $Engine;
	public $Database;
	private $activeDatabase;

public function SANBASE($Path)
{
	$this->path=$Path;
	$this->Documents=array();
		
		$this->Engine=new SANBASEENGINE($this->path);
		$this->sys=$this->Engine->System();
		$this->databases=$this->Engine->databases;
		$this->Database=new DATABASE($this);

}


public function Show()
{

return $this->databases;

}

public function GetActiveDatabase()
{
	return $activeDatabase;
}

public function Select($Database)
{	
	$status=$this->Engine->DatabaseExists($Database);
	if($status==1)
	{
		$this->activeDatabase=$Database;
		$msg=$this->Database->SetDatabase($Database);
		if($msg['error']==0)
		{
			return $status;
		}
		else
		{
			$status=0;
			$this->activeDatabase="";
			return $status;
		}
		return $status;
	}
	else
	{
		$status=0;
		return $status;
	}

}

public function Create($database)
{
$status=0;
$status=$this->Engine->DatabaseExists($database);
//echo $status;
	if($status==0)
	{
		$err=$this->Engine->FileEngine->Folder->Create("/".$database);
		if($err['error']==0)
		{
			$this->Engine->Add($database);
			$this->Engine->Update(1);
			$this->databases=$this->Engine->databases;
			return $database;
		}
		else
		{
			return "";

		}
	}
	elseif($status==1)
	{
		return "";
	}
	elseif($status==2)
	{
		$this->Engine->Add($database);
		$this->Engine->Update();
		return $database;
	}
	elseif($status==3)
	{
		$err=$this->Engine->FileEngine->Folder->Create("/".$database);
		if($err['error']==0)
		{
			$this->Engine->Add($database);
			$this->Engine->Update(2);
			$this->databases=$this->Engine->databases;
			return $database;
		}
		else
		{
			$this->Engine->Remove($database);
			$this->Engine->Update(3);
			$this->databases=$this->Engine->databases;
			return "";

		}
	}
}




public function Rename($oldDatabase,$newDatabase)
{


}

public function Delete($database)
{
		$err=$this->Engine->FileEngine->Folder->Delete("/".$database);
		if($err['error']==0)
		{
			$this->Engine->Remove($database);
			$this->Engine->Update(4);
			$this->databases=$this->Engine->databases;
			return true;
		}
		else
		{
			
			return false;
		}
					
}



public function GetSystem()
{

}







}



?>
