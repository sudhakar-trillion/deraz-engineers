<?php require('../includes/db.php');

 $checkUser = mysql_query("select id from employees where username = '". $_GET['user'] ."'");
 
 if(mysql_num_rows($checkUser)>0)
 {
	 
	echo 'username already exists'; 
 }

 ?>