<?php require('../includes/db.php');

 
 mysql_query("insert into  enquiry_assign (`enquiryId`, `assignedTo`, `assignedBy`, `dateTime`)  values ('". $_GET['enquiryId']  ."', '". $_GET['employeeId']  ."', '". $_SESSION['id']  ."', NOW())");
 $lastId = mysql_insert_id();
 
 
 
 if($lastId>0)
 {
	  $result = mysql_query("select employees.firstName, enquiry_assign.dateTime from enquiry_assign 
 left join employees on enquiry_assign.assignedTo = employees.id where enquiry_assign.eaId = '". $lastId ."'");
 $result = mysql_fetch_array($result);
 $date = explode(' ',$result['dateTime']);
 $date = explode('-',$date[0]);
 $date = $date[2].'-'.$date[1].'-'.$date[0];
 
 
 $arr = array('success' => $lastId, 'message' => 'Enquiry has been assigned to!', 'name' => $result['firstName'], 'date' => $date);
 
 }
 else
 {
	 $arr = array('success' => 0, 'message' => 'Error occured, please try again!');	  
 }
 echo json_encode($arr); ?>