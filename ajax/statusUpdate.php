<?php require('../includes/db.php'); 
if($_POST)
{
$enqid = $_POST['enqid'];	
$statusChange = $_POST['statusChange'];	
$result =  mysql_query("update enquiries set status = '" . $statusChange . "' where enquiryId = '" . $enqid . "'");

	
	
	//	echo "Status updated successfully";
	
	
}






?>
