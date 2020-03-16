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
		$this->match_patterns['array']="/\s*{\s*\[\]\s*(=|>=|<=)\s*\d*\s*}/";
		$this->match_patterns['key']="/\s*{\s*\w+\s*}\s*/";
		$this->match_patterns['sd']="/\s*{\s*\w*\.?\w*\.?\w*\.?\w*\.?\w*\.?\w+\s*(=|>=|<=)\s*\d*\s*}/";
		$this->match_patterns['sw']="/\s*{\s*\w*\.?\w*\.?\w*\.?\w*\.?\w*\.?\w+\s*(=|>=|<=)\s*(\')\w*(\')s*}/";
		$this->match_patterns['ad']="/\s*{\s*\w*\.?\w*\.?\w*\.?\w*\.?\w*\.?\w+\s*(=|>=|<=)\s*\d*\s*and?\s*\w*\.?\w*\.?\w*\.?\w*\.?\w*\.?\w+\s*(=|>=|<=)\s*\d*\s*}/";
		$this->match_patterns['od']="/\s*{\s*\w*\.?\w*\.?\w*\.?\w*\.?\w*\.?\w+\s*(=|>=|<=)\s*\d*\s*or?\s*\w*\.?\w*\.?\w*\.?\w*\.?\w*\.?\w+\s*(=|>=|<=)\s*\d*\s*}/";
		$this->match_patterns['aw']="/\s*{\s*\w*\.?\w*\.?\w*\.?\w*\.?\w*\.?\w+\s*(=|>=|<=)\s*(\')\w*(\')\s*and?\s*\w*\.?\w*\.?\w*\.?\w*\.?\w*\.?\w+\s*(=|>=|<=)\s*(\')\w*(\')\s*}/";
		$this->match_patterns['ow']="/\s*{\s*\w*\.?\w*\.?\w*\.?\w*\.?\w*\.?\w+\s*(=|>=|<=)\s*(\')\w*(\')\s*or?\s*\w*\.?\w*\.?\w*\.?\w*\.?\w*\.?\w+\s*(=|>=|<=)\s*(\')\w*(\')\s*}/";
		$this->match_patterns['ak']="/\s*{\s*\w*\.?\w*\.?\w*\.?\w*\.?\w*\.?\w+\s*(=|>=|<=)\s*(\')\w*(\')\s*and?\s*\w+\s*}/";
		
		$this->exp_patterns['=']['a']="/\[\]\s*(=)\s*\d*/";
		$this->exp_patterns['>=']['a']="/\[\]\s*(>=)\s*\d*/";
		$this->exp_patterns['<=']['a']="/\[\]\s*(<=)\s*\d*/";
		$this->exp_patterns['=']['w']="/\w*\.?\w*\.?\w*\.?\w*\.?\w*\.?\w+\s*(=)\s*(\')\w*(\')/";
		$this->exp_patterns['>=']['w']="/\w*\.?\w*\.?\w*\.?\w*\.?\w*\.?\w+\s*(>=)\s*(\')\w*(\')/";
		$this->exp_patterns['<=']['w']="/\w*\.?\w*\.?\w*\.?\w*\.?\w+\s*(<=)\s*(\')\w*(\')/";	
		$this->exp_patterns['=']['d']="/\w*\.?\w*\.?\w*\.?\w*\.?\w*\.?\w+\s*(=)\s*\d*/";
		$this->exp_patterns['>=']['d']="/\w*\.?\w*\.?\w*\.?\w*\.?\w*\.?\w+\s*(>=)\s*\d*/";
		$this->exp_patterns['<=']['d']="/\w*\.?\w*\.?\w*\.?\w*\.?\w*\.?\w+\s*(<=)\s*\d*/";
		
		$this->exp_patterns['key']['key']="/\s*\w+\s*/";
		$this->keydelim['ad']="and";
		$this->keydelim['od']="or";
		$this->keydelim['aw']="and";
		$this->keydelim['ow']="or";
		$this->keydelim['ak']="and";
		$delim[0]=">=";
		$delim[1]="<=";
		$delim[3]="=";
}


public function MatchPattern($query)
{
	foreach($this->match_patterns as $key => $value)
	{
			if(preg_match($this->match_patterns[$key],$query))
			{

				return $key;

			}
	}

return false;
}


public function Tokenize($query,$key)
{
$opkey='nop';
$i=0;
	if(array_key_exists($key,$this->keydelim))
	{
		$tok=explode($this->keydelim[$key],$query);
		$opkey=$this->keydelim[$key];
	}
	else
	{
		$tok[0]=$query;

	}
	
	foreach($tok as $key => $value)
	{
		$tok[$key]=trim($value,"{");
		$tok[$key]=trim($tok[$key],"}");
		$tok[$key]=trim($tok[$key]);
		$ptok[$opkey][$key]=$tok[$key];
	}
	foreach($ptok as $key => $value)
	{
		foreach($value as $ekey => $evalue)
		{
			$data=$this->GetExpTokens($evalue);
			$this->tree[$i]=$data;
			$i=$i+1; 
		}
		$this->tree[$i]=$key;
	}
	
	return $this->tree;

}

private function GetExpTokens($exp)
{
$data=array();
	foreach($this->exp_patterns as $ekey => $evalue)
	{
			
		foreach($evalue as $key => $value)
		{
			if(preg_match($this->exp_patterns[$ekey][$key],$exp))
			{
				$data=explode($ekey,$exp);
				
				if($ekey=="key")
				{
					$data[0]=trim($data[0]);
					$data[1]=trim($data[0]);
				}
				else
				{
					$data[0]=trim($data[0]);
					$data[1]=trim($data[1]);
				}
				$data[2]=$ekey;
				$data[3]=$key;
				return $data;

			}
		}
	}
return  $data;

}




}



?>
