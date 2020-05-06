<?php
/*
------------------------------------------------------------------------------------
|    Application Name	: Riasys SanBase  Ver 1.0             
|    Program Name	: inter.php                       
|    Start              : Sat March  11 2020
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




class Inter
{
	private $data;
	private $pointer;
	private $route;

public function Inter()
{
		$this->data=array();
		$this->route=array();
		$this->pointer=0;
		
}

public function Init()
{
		$this->data=array();
		$this->route=array();
		$this->pointer=0;
}

public function Add($dataValue,$routeValue)
{

		$this->data[$this->pointer]=$dataValue;
		$this->route[$this->pointer]=$routeValue;
		$this->pointer++;


}


public function Display()
{

$this->route=array_unique($this->route);


foreach($this->route as $key => $value)
{
echo $value;
echo "<BR>";
}
echo "<BR>";
var_dump($this->data);
echo "<BR>";
}

public function GetRoute()
{
return $this->route;

}

public function GetData()
{
return $this->data;
}



}

?>


