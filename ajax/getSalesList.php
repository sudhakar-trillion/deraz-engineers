<?php require('../includes/db.php');

 $like = $_GET['val'].'%';
 $employees = mysql_query("select id, firstName from employees where firstName like '". $like ."' and roll = '4' and role = '2'");
 
 if(mysql_num_rows($employees)<0)
 {
	?><li>No Data found.</li><?php
 }
 else
 {
	while($employee = mysql_fetch_array($employees))
	{
	  ?><li data-value="Colorado" class=""><a href="javascript:void()" onClick="selectMember('<?php echo $employee['id']; ?>','<?php echo $employee['firstName']; ?>')"><?php echo $employee['firstName']; ?></a></li><?php	
	}
	 
 }

 ?>