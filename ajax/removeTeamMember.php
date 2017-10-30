<?php require('../includes/db.php');

 if(mysql_query("delete from team_members where teamId = '". $_GET['tid'] ."' and memberId = '". $_GET['mid'] ."'"))
 {
	 
	echo $_GET['name'].' Removed'; 
 } else {
	 
	echo 'Error occured'; 
 }
	 

 ?>