<?php
/*****************************************************************************************************
* This example shows how to :
*
* find a specific record within the document
* 
* 
* 
* example-3.php
******************************************************************************************************/


/* Enable Error reporting so that if any error occurs during usage of sanbase error can be displayed */

error_reporting  (E_ALL);
ini_set('display_errors', '1');


$riasys_root_path = './../'; /* Sanbase path is assigned here to point the location where sanbase is installed */

$dbpath='./../../'; /* data path for sanbase is assigned here to point the location where sanbase should store database */

include($riasys_root_path.'SANBASE.php'); /* here you include the Sanbase main file */


$sdb=new SANBASE($dbpath."data"); /* An instance of sanbase is created here and data path is passed while creating the sanbase instance */



if($sdb->Select("example1")==0)  /* Select Database,If database exists then the given database is selected and return 1 */
{

$sdb->Create("example1"); /* If database does not exists then create here */

}
$sdb->Select("example1"); /* select the created database here */


// Find data using record number

$query="{[]=2}";

$fdata=$sdb->Database->Documents('document1')->Find($query); /* Find function is used to list json data according to the search query */



foreach($fdata as $key => $value)
{

echo $key."=>".$value."<BR>";


}


// Find data using key value pair

$query="{id=1}";

$fdata=$sdb->Database->Documents('document1')->Find($query); /* Find function is used to list json data according to the search query */



foreach($fdata as $key => $value)
{

echo $value['id'].":".$value['_id'].":".$value['name']."<BR>";


}




?>
