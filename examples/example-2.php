<?php
/*****************************************************************************************************
* This example shows how to :
*
* Create Document
* Insert Data into document
* View Data from document
* 
* example-2.php
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


$sdb->Database->Create("document1"); /* document inside the database is created here by using the Create Function and passing the document name */

$data['id']="1";
$data["name"]="Sample1";



$sdb->Database->Documents('document1')->Insert(json_encode($data)); /* Insert function is used here to insert data into the document,the data passed to the function is in json format */

$data['id']="2";
$data["name"]="Sample2";

$sdb->Database->Documents('document1')->Insert(json_encode($data)); /* Insert function is used here to insert data into the document,the data passed to the function is in json format */

$data['id']="3";
$data["name"]="Sample3";

$sdb->Database->Documents('document1')->Insert(json_encode($data)); /* Insert function is used here to insert data into the document,the data passed to the function is in json format */



$fdata=$sdb->Database->Documents('document1')->FindAll(); /* FindAll function is used to list all the json data in the document */



foreach($fdata as $key => $value)
{

echo $value->_id."=>".$value->name."<BR>";


}







?>
