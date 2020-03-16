<?php
/*
------------------------------------------------------------------------------------
|    Application Name	: Riasys SanBase  Ver 1.0             
|    Program Name	: database.php                       
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

include('DOCUMENTS.php');


class DATABASE
{
	
	public $Engine;
	private $sanbase;
	public $databases;
	public $documents;
	private $activeDatabase;
	

public function DATABASE($sanbase)
{
	$this->activeDatabase="";
	$this->documents=array();
		$this->sanbase=$sanbase;	
		$this->Engine=$sanbase->Engine;
		$this->databases=$this->Engine->databases;
		
		


}


public function Show()
{
return array_keys($this->documents);
}


public function SetDatabase($Database)
{
$this->activeDatabase=$Database;
$this->documents=array();
return $this->LoadDocuments();
}

public function GetActiveDatabase()
{
return $this->activeDatabase;
}

private function LoadDocuments()
{
$data=array();
$files=array();
	$dbpath=$this->Engine->GetPath()."/".$this->activeDatabase;


    $data['error']=0;
    if (!is_dir($dbpath)) {
	$data['error']=1;
	$data['documents']=$files;
        return $data;
    }

    $files = array();
    foreach (scandir($dbpath) as $file) {
        if ($file !== '.' && $file !== '..') {
            $files[] = $file;
        }
    }

    $data['documents']=$files;

    	foreach ($data['documents'] as $key => $val) {
			
			$doc=str_replace(".json","",$val);
			$this->documents[$doc]=new DOCUMENT($this,$doc);
		
    		
	}
    
return $data;

}


public function Documents($document)
{
$dbpath=$this->Engine->GetPath()."/".$this->activeDatabase."/".$document.".json";

	if (!file_exists($dbpath)) {
        	return null;
    	}
	else
	{
		return $this->documents[$document];
	}


}

public function Create($document)
{
$status=0;

		$err=$this->Engine->FileEngine->Create("",$this->activeDatabase,$document.".json");
		if($err['error']==0)
		{
			$this->LoadNewDocument($document);
			return $document;
		}
		else
		{
			return "";

		}
	
}




public function Rename($oldDocument,$newDocument)
{


}

public function Delete($document)
{
	
$err=$this->Engine->FileEngine->Delete($this->activeDatabase,$document.".json");
return $err;


			
}



private function LoadNewDocument($document)
{
	
	
	$this->documents[$document]=new Document($this,$document);

}




}



?>
