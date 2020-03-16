<?php
/*****************************************************************************************************
* This example shows how to use sanbase and how to :
*
* Use Sanbase
* Set Sanbase Path and data Path
* create and select database
* List number of databases
* example-1.php
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

$db=$sdb->Show();

foreach($db as $key => $value)
{

echo "Database ".$key." :".$value."<BR>"; /* Show function returs number of databases */


}


?>
