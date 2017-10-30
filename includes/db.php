<?php
$dbhost							= "localhost";
// local
/*$db							= "derazrevised";
$dbuser							= "root";
$dbpass							= "";*/

//vsksamsu_derazer
// server
/*
$db							= "vsksamsu_derazerp";

$dbuser							= "vsksamsu_derazer";
$dbpass							= "vG1C^-2oXhZH";
*/

$db							= "derazcms_erp";

$dbuser							= "derazcms_erpuser";
$dbpass							= "QG9BTE?lvcDl";


$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ("Error connecting to mysql");
mysql_select_db($db); ?>