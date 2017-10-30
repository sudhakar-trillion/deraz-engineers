<?php require('../includes/db.php');

 $resultList = array();
 $like = $_GET['val'].'%';
 $customers = mysql_query("select customerId, company from customers where company like '". $like ."'");
 
 if(mysql_num_rows($customers)<0)
 {
	 
	 $status = 0;
	 
	
 }
 else
 {
	 $status = 1;
	while($customer = mysql_fetch_array($customers))
	{
		
		$resultList[] = array('id' => $customer['customerId'], 'val' => $customer['company']);
	}
	 
 }
 
 
 $arr = array('status' => $status, 'resultlist' => $resultList); 
 echo json_encode($arr);

 ?>