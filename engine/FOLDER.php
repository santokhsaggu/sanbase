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

class FOLDER
{
	private $path;
	private $folder;
	
	
	

public function FOLDER($Path)
{
	$this->path=$Path;
		
		
		

}

public function Create($file)
{
$data=array();
	if(file_exists($this->path.$file))
        {
	   $data['object']="folder";
	   $data['error']="2";
	   $data['msg']="Folder $file already exists";
        }
        else
        {
           if(mkdir($this->path.$file))
           { 
           $data['object']="folder";
	   $data['error']="0";
	   $data['msg']="Successfully Created the Folder";
	   $this->folder=$file;
           }
          else
          {
           $data['object']="folder";
	   $data['error']="2";
	   $data['msg']="Folder $file already exists";
          }
        }

return $data;

}

public function Rename($oldName,$newName)
{
$data=array();
	if(file_exists($this->path.$newName))
        {
	   $data['object']="folder";
	   $data['error']="1";
	   $data['msg']="Error while renaming $oldName";
        }
        else
        {
           if(rename($oldName,$newName))
           { 
           $data['object']="folder";
	   $data['error']="0";
	   $data['msg']="Successfully Renamed $oldName to $newName";
	   $this->folder=$newName;
           }
          else
          {
           $data['object']="folder";
	   $data['error']="2";
	   $data['msg']="A folder With The Same Name Already Exists";
          }
        }

return $data;

}

public function Delete($file)
{
$data=array();
	if(!file_exists($this->path.$file))
        {
	   $data['object']="folder";
	   $data['error']="1";
	   $data['msg']="Error deleting $file";
        }
        else
        {
           if(rmdir($this->path.$file))
           { 
           $data['object']="folder";
	   $data['error']="0";
	   $data['msg']="Successfully Deleted the Folder";
	   $this->folder=$file;
           }
          else
          {
           $data['object']="folder";
	   $data['error']="2";
	   $data['msg']="Failed to delete the folder";
          }
        }

return $data;

}


public function Set($Folder)
{

$this->folder=$Folder;
}

public function Get($Folder)
{

return $this->folder;
}


}

?>
