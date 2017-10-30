<?php require('../includes/db.php');
 $like = $_GET['term'].'%';
 
 $mocs = mysql_query("select distinct Moc from mocs where Moc like '". $like ."'");
 
 if(mysql_num_rows($mocs)<0)
 {
	?><li>No Data found.</li><?php
 }
 else
 {
	 $mocs_arr=array();
	while($MOC = mysql_fetch_array($mocs))
	{
	 	$mocs_arr[] = $MOC['Moc'];
	}
	 echo json_encode($mocs_arr);
 }

 ?>