<?php
/*
------------------------------------------------------------------------------------
|    Application Name	: Riasys SanBase  Ver 1.0             
|    Program Name	: cursor.php                       
|    Start              : WED May  6 2020
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




class Cursor
{
	private $data;
	public $pointer;
	private $dataPointer;

public function Cursor()
{
		$this->data=array();
		$this->pointer=0;
		$this->dataPointer=0;
		
}

public function Init()
{
		$this->data=array();
		$this->pointer=0;
		$this->dataPointer=0;
}

public function Add($Key,$Value)
{

		$this->data[$this->pointer][$Key]=$Value;
		//$this->pointer++;


}


public function MoveNext()
{
	$dcount=count($this->data);
	if($this->dataPointer<$dcount)
	{
		$value=$this->data[$this->dataPointer];
		$this->dataPointer++;
		return $value;

	}
	else
	{	$this->dataPointer=$dcount;
		return false;

	}

}

public function MovePrevious()
{
	
	$dcount=count($this->data);
	if($this->dataPointer>=0)
	{
		$this->dataPointer--;
		$value=$this->data[$this->dataPointer];
		return $value;

	}
	else
	{	$this->dataPointer=0;
		return false;

	}

}

public function MoveFirst()
{
	$dcount=count($this->data);
	if($dcount>0)
	{
		$this->dataPointer=0;
		$value=$this->data[$this->dataPointer];
		return $value;

	}
}


public function MoveLast()
{
	$dcount=count($this->data);
	if($dcount>0)
	{
		$this->dataPointer=$dcount-1;
		$value=$this->data[$this->dataPointer];
		return $value;

	}
}

public function RowNumbers()
{
	return $this->pointer;
}


}

?>


