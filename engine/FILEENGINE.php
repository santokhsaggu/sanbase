<?php
/*
------------------------------------------------------------------------------------
|    Application Name	: Riasys SanBase  Ver 1.0             
|    Program Name	: fileengine.php                       
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

include('FOLDER.php');


class FILEENGINE
{
	private $path;
	public $Folder;
	
	
	

public function FILEENGINE($Path)
{
	$this->path=$Path;
		
		$this->Folder=new FOLDER($this->path);
		

}

public function Create($data,$folder,$file)
{
$error=0;
$errdata=array();
	if($folder=="")
	{
		$path=$this->path."/".$file;

	}
	else
	{

		$path=$this->path."/".$folder."/".$file;
	}

	if(file_exists($path))
        {
		$errdata['object']="file";
	   	$errdata['error']="2";
	   	$errdata['msg']="File Already Exists";
		$error=$errdata['error'];
	}
	if($error==0)
	{
		$dbf = fopen($path, 'w');
		if(!$dbf)
		{
			$errdata['object']="file";
	   		$errdata['error']="1";
	   		$errdata['msg']="Failed to Open File";
			return $errdata;
		}
		else
		{
			
			if($data=="")
			{
				$fwd=fwrite($dbf,$data);
				$errdata['object']="file";
	   			$errdata['error']="0";
				$errdata['length']=$fwd;
	   			$errdata['msg']="Successfully saved File";
				fclose($dbf);
				return $errdata;
			}
			else
			{
				$errdata['object']="file";
	   			$errdata['error']="0";
				$errdata['length']=0;
	   			$errdata['msg']="Successfully saved File";
				fclose($dbf);
				return $errdata;

			}
		}
		
	}
	else
	{
		return $errdata;
	}

}

public function Update($data,$folder,$file)
{


}

public function Delete($folder,$file)
{
$error=0;
$data=array();
	if($folder=="")
	{
		$path=$this->path."/".$file;

	}
	else
	{

		$path=$this->path."/".$folder."/".$file;
	}
	if(!file_exists($path))
        {
		
		$error=1;
	}
	if($error==0)
	{
		if (!unlink($path)) {
			$data['object']="file";
	   		$data['error']="1";
	   		$data['msg']="Error deleting $file";
		
  		
		} else {
			$data['object']="file";
	   		$data['error']="1";
	   		$data['msg']="Deleted $file";
		}
	}
return $data;
}

public function FileExists($folder,$file)
{

	if($folder=="")
	{
		$path=$this->path."/".$file;

	}
	else
	{

		$path=$this->path."/".$folder."/".$file;
	}

	if(file_exists($path))
        {
		return 1;
	}
	else
	{

		return 0;
	}
}


public function Write($data,$folder,$file)
{

$error=0;
$errdata=array();
	if($folder=="")
	{
		$path=$this->path."/".$file;

	}
	else
	{

		$path=$this->path."/".$folder."/".$file;
	}

	if(!file_exists($path))
        {
		$errdata['object']="file";
	   	$errdata['error']="2";
	   	$errdata['msg']="File not Exists";
		$error=$errdata['error'];
	}
	if($error==0)
	{
		$dbf = fopen($path, 'w');
		if(!$dbf)
		{
			$errdata['object']="file";
	   		$errdata['error']="1";
	   		$errdata['msg']="Failed to Open File";
			return $errdata;
		}
		else
		{
			
			if($data=="")
			{
				
				$errdata['object']="file";
	   			$errdata['error']="0";
				$errdata['length']=$fwd;
	   			$errdata['msg']="Successfully saved File";
				fclose($dbf);
				return $errdata;
			}
			else
			{
				if (flock($dbf, LOCK_EX)) {
					$fwd=fwrite($dbf,$data);

					flock($dbf, LOCK_UN);
					$errdata['object']="file";
	   				$errdata['error']="0";
					$errdata['length']=0;
	   				$errdata['msg']="Successfully saved File";
					fclose($dbf);
					
				}
				else
				{
					$errdata['object']="file";
	   				$errdata['error']="3";
					$errdata['length']=0;
					$errdata['data']='';
	   				$errdata['msg']="Access Denied";


				}
				return $errdata;	

			}
		}
		
	}
	else
	{
		return $errdata;
	}


}

public function Read($folder,$file)
{
$error=0;
$errdata=array();

	if($folder=="")
	{
		$path=$this->path."/".$file;

	}
	else
	{

		$path=$this->path."/".$folder."/".$file;
	}

	if(!file_exists($this->path."/".$folder))
        {
		$err=$this->Folder->Create($folder);
		$error=$err['error'];
	}
	if($error==0)
	{
		if(!file_exists($path))
        	{
				$errdata['object']="file";
	   			$errdata['error']="2";
	   			$errdata['msg']="File does not exists";
				return $errdata;
		}
			$dbf = fopen($path, 'r');
			if(!$dbf)
			{
				$errdata['object']="file";
	   			$errdata['error']="1";
	   			$errdata['msg']="Failed to Open File";
				return $errdata;
			}
			else
			{

				if(filesize($path)==0)
				{

					$errdata['object']="file";
	   				$errdata['error']="3";
					$errdata['length']=0;
					$errdata['data']='';
	   				$errdata['msg']="No data in the file";
					fclose($dbf);
				}
				else
				{
				if (flock($dbf, LOCK_SH)) {
					$line="";
					$fwd="";
					//$fwd=fread($dbf, filesize($path));
					while(! feof($dbf)) {
 				 		$line= fgets($dbf);
						$fwd.=$line;
					}
					flock($dbf, LOCK_UN);
					$errdata['object']="file";
	   				$errdata['error']="0";
					$errdata['length']=filesize($path);
					$errdata['data']=$fwd;
	   				$errdata['msg']="Successfully read File";
					fclose($dbf);
				}
				else
				{
					$errdata['object']="file";
	   				$errdata['error']="3";
					$errdata['length']=0;
					$errdata['data']='';
	   				$errdata['msg']="Access Denied";

				}
				return $errdata;
				}
			}
		
	}
	else
	{
		return $errdata;
	}
return $errdata;
}





}



?>
