<?php ob_start(); session_start(); require('../includes/db.php');

 $like = $_GET['val'].'%';

 $customers = mysql_query("select customerId, company from customers where company like '". $like ."' and addedBy = '". $_SESSION['id'] ."'");
 
 if(mysql_num_rows($customers)<0)
 {
	?><li>No Data found.</li><?php
 }
 else
 {
	while($customer = mysql_fetch_array($customers))
	{
	  ?><li data-value="Colorado" class=""><a href="javascript:void()" onClick="selectCustomer('<?php echo $customer['customerId']; ?>','<?php echo $customer['company']; ?>')"><?php echo $customer['company']; ?></a></li><?php	
	}
	 
 }

 ?>