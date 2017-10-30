<?php require('../includes/db.php');

 $like = $_GET['val'].'%';

 $vendors = mysql_query("select vendorId, company from vendors where company like '". $like ."'");
 
 if(mysql_num_rows($vendors)<0)
 {
	?><li>No Data found.</li><?php
 }
 else
 {
	while($vendor = mysql_fetch_array($vendors))
	{
	  ?><li data-value="Colorado" class=""><a href="javascript:void()" onClick="selectVendor('<?php echo $vendor['vendorId']; ?>','<?php echo $vendor['company']; ?>')"><?php echo $vendor['company']; ?></a></li><?php	
	}
	 
 }

 ?>