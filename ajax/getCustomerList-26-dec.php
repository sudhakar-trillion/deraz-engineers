<?php require('../includes/db.php');

 $like = $_POST['customer'].'%';
 
 //echo $like;

 $customers = mysql_query("select customerId, contactPerson, email, phone, company from customers where company like '". $like ."'");
 if(mysql_num_rows($customers)<0)
 {
	?><li>No Data found.</li><?php
 }
 else
 {
	while($customer = mysql_fetch_array($customers))
	{
	  ?><li class=""><a href="javascript:void()" onClick="selectCustomer('<?php echo $customer['customerId']; ?>','<?php echo $customer['company']; ?>')"><?php echo $customer['company']; ?></a></li>
	  <?php	
	  

									
									
									}
	 
 } 


 ?>
