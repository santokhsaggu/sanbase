<?php
/*
------------------------------------------------------------------------------------
|    Application Name	: Riasys SanBase  Ver 1.0             
|    Program Name	: parser.php                       
|    Start              : Sat Feb 14 2020
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




class PARSER
{
	public $match_patterns;
	public $delim;
	public $keydelim;
	public $exp_patterns;
	public $tree;
	

public function PARSER()
{
		$this->match_patterns=array();
		$this->exp_patterns=array();
		$this->delim=array();
		$this->keydelim=array();
		$this->tree=array();
		$left_key="\s*(_*[a-zA-Z0-9]+\.?)+\s*";
		$right_number="\s*[0-9]+\.?[0-9]*\s*";
		$right_word="\s*\'(_*[a-zA-Z0-9]\.*_*)+\'\s*";
		$op="(=|>=|<=)";
		$this->match_patterns['a']="/\s*{\s*\[\]\s*(=|>=|<=)\s*\d*\s*}/";
		$this->match_patterns['k']="/\s*{".$left_key."}\s*/";
		$this->match_patterns['q']="/\s*{".$left_key.$op."(".$right_number."|".$right_word.")(\s*(and|AND|or|OR)?\s*".$left_key.$op."(".$right_number."|".$right_word.")\s*)*}/";
		


}


public function MatchPattern($query)
{
	foreach($this->match_patterns as $key => $value)
	{
			if(preg_match($this->match_patterns[$key],$query))
			{

				return true;

			}
	}

return false;
}


function Tokenize($query,$key)
{
$opkey=array();
$i=0;
$j=0;
$space_token='';
$this->tree=array();
if($key==1)
{
	$space_token=preg_split( "/\s+/", $query );

	foreach($space_token as $key => $value)
	{
		if($value=='and' || $value=='AND' || $value=='or' || $value=='OR')
		{
			$opkey[$j]=$value;
			$j++;
		}
		else
		{
			$tok[$i]=trim($value,"{");
			$tok[$i]=trim($tok[$i],"}");
			$tok[$i]=trim($tok[$i]);
			$i++;
		}
	}


	//var_dump($opkey);
	//var_dump($tok);
	
	foreach($tok as $key => $value)
	{
		
		$data=$this->GetExpTokens($value);
		$this->tree[$key]['expr']=$data;
 
		
		if($key<count($opkey))
		{
			$this->tree[$key]['lop']=$opkey[$key];
			$this->tree[$key]['next']=$key+1;
		}
		else
		{
			$this->tree[$key]['lop']="end";
			$this->tree[$key]['next']=0;
		}
	}
}
	
	return $this->tree;

}



function GetExpTokens($exp)
{
$data=array();
$tok=array();	
			
		$tok=preg_split( "/>=|=|<=/", $exp );
		
	
		if(count($tok)==2)
		{
			
			$data['left']=trim($tok[0]);
			$data['right']=trim($tok[1]);
			$data['mop']=$this->GetMathOp($exp);
			if($data['left']=="[]")
			{
				$data['type']="a";
			}
			else
			{
				$data['type']="e";
			}
		}
		else
		{
			$data['left']=trim($tok[0]);
			$data['right']='';
			$data['mop']='';
			$data['type']="k";
		}
		
	
return  $data;

}

function GetMathOp($exp){

if(strpos($exp, ">=") !== false){
	return ">=";
}
elseif(strpos($exp, "<=") !== false){
	return "<=";
}
else
{
	return "=";
}
}




}



?>
