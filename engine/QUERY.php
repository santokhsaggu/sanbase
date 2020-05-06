<?php
/*
------------------------------------------------------------------------------------
|    Application Name	: Riasys SanBase  Ver 1.0             
|    Program Name	: query.php                       
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


include('INTER.php');

class Query
{
	
	
	private $pointer;
	
	private $previous_route;
	
	private $stack;
	private $stack_pointer;
	private $number;
	private $matchFlag;
	private $inter;
	public $interPointer;

public function Query()
{
		
		$this->stack=array();
		$this->stack_pointer=array();
		
		
		$this->previous_route='';
		$this->pointer=0;
		
		$this->number=0;
		$this->matchFlag=false;
		$this->inter=array();
		$this->interPointer=0;
}

public function Init()
{
		
		$this->stack=array();
		$this->stack_pointer=array();
		
		
		$this->previous_route='';
		$this->pointer=0;
		
		$this->number=0;
		$this->matchFlag=false;
		$this->inter=array();
		$this->interPointer=0;
}


function Search($start,$end,$tok,$buffer,$mop,$value){
$buffer_end=count($buffer);

		$this->inter[$this->interPointer]=new Inter();
		$this->inter[$this->interPointer]->Init();
		
		for($i=0;$i<$buffer_end;$i++)
		{
			
			$sbuffer=$buffer[$i];
			$this->number=0;
			$sbuffer=$this->SearchKey($start,$end,$tok,$sbuffer,$mop,$value,"[\"".$i."\"");
			
			
				
		}

return $this->inter;
}


function CreateInter()
{
		$this->inter[$this->interPointer]=new Inter();
		$this->inter[$this->interPointer]->Init();
}



function FilterSearch($start,$end,$tok,$buffer,$mop,$value,$path){

		
			$this->number=0;
			$sbuffer=$this->SearchKey($start,$end,$tok,$buffer,$mop,$value,$path);
			
			
				

return $this->inter;
}









function SearchKey($start,$end,$tok,$buffer,$mop,$value,$path)
{

	if($start>=$end)
	{
		if($value=="")
		{
			$this->inter[$this->interPointer]->Add($buffer,$path."]");
				
		}
		else
		{
			if($mop=="=")
			{
				if(trim($value,"'")==$buffer)
				{	
					$this->inter[$this->interPointer]->Add($buffer,$path.",\"".$buffer."\"]");
					
				
				}
				else
				{	
				
				}
			}
			elseif($mop==">=")
			{

				if($buffer>=trim($value,"'"))
				{	
					$this->inter[$this->interPointer]->Add($buffer,$path.",\"".$buffer."\"]");
					
				
				}
				else
				{	
				
				}

			}
			elseif($mop=="<=")
			{

				if($buffer<=trim($value,"'"))
				{	
					$this->inter[$this->interPointer]->Add($buffer,$path.",\"".$buffer."\"]");
					
				
				}
				else
				{	
				
				}

			}
			
		}
		return $buffer;;

	}

	
	
	
	if($this->isIndexArray($buffer))
	{	$buffer_end=count($buffer);
		
		for($i=0;$i<$buffer_end;$i++)
		{	
			array_push($this->stack_pointer,$start);
			array_push($this->stack,$buffer);
			$buffer=$buffer[$i];
			
			$this->number++;
			$buffer=$this->SearchKey($start,$end,$tok,$buffer,$mop,$value,$path.",\"".$i."\"");
			$buffer=array_pop($this->stack);
			$start=array_pop($this->stack_pointer);
				
		}
			
	}
	else{
		if(array_key_exists($tok[$start],$buffer))
		{	
			$buffer=$buffer[$tok[$start]];
			
			
			$this->number++;
			$buffer=$this->SearchKey($start+1,$end,$tok,$buffer,$mop,$value,$path.",\"".$tok[$start]."\"");
			
				
			
			
				
		}
		
		
	}

						
return $buffer;

}


function GetFilterRoute($FilteredRoute,$Tok)
{
$data=array();
$i=0;
$data['start']=0;
foreach($FilteredRoute as $key => $value)
{
	if(is_numeric($value))
	{
		$data['filter'][$i]=$value;
		$i++;
		if($key==0)
		{
			$data['route']="[\"".$value."\"";
		}
		else
		{
			$data['route']=$data['route'].",\"".$value."\"";
		}
	}
	else
	{
		if(in_array($value,$Tok))
		{
			$data['filter'][$i]=$value;
			$data['start']=$data['start']+1;
			$data['route']=$data['route'].",\"".$value."\"";
			$i++;		
		}
		else
		{
			
			return $data;
		}
	}

}
return $data;
}


function SeekFilteredData($data,$filter)
{
	$buffer=$data;
	foreach($filter as $key => $value)
        {

		$buffer=$buffer[$value];
	}
	
return $buffer;
}

function SeekNextTokenData($start,$end,$tok,$buffer)
{
	
	if($start>=$end)
	{
		
		return $buffer;;

	}

	
	
	
	if($this->isIndexArray($buffer))
	{	$buffer_end=count($buffer);
		
		for($i=0;$i<$buffer_end;$i++)
		{	
			array_push($this->stack_pointer,$start);
			array_push($this->stack,$buffer);
			$buffer=$buffer[$i];
			
			$this->number++;
			$buffer=$this->SeekNextTokenData($start,$end,$tok,$buffer);
			$buffer=array_pop($this->stack);
			$start=array_pop($this->stack_pointer);
				
		}
			
	}
	else{
		if(array_key_exists($tok[$start],$buffer))
		{	
			$buffer=$buffer[$tok[$start]];
			
			//echo $tok[$start].":".json_encode($buffer)."<BR>";
			$this->number++;
			$buffer=$this->SeekNextTokenData($start+1,$end,$tok,$buffer);
			//echo $tok[$start].":".json_encode($buffer)."<BR>";
				
			
			
				
		}
		else
		{
			return null;
		}
		
	}

						
return $buffer;
}


function isIndexArray($arr)
{
if(array_keys($arr) === range(0, count($arr) - 1)) 
    return true;  
else
    return false;

}





}



?>
