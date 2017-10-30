<?php ob_start(); session_start(); require('../includes/db.php');

 $like = $_GET['val'].'%';
 

 $codes = mysql_query("select productId,ModelId,ModelNo from product_model where ModelNo like '". $like ."'");

 if(mysql_num_rows($codes)<0)
 {
	?><li>No Data found.</li><?php
 }
 else
 {
	while($code = mysql_fetch_array($codes))
	{
	  ?><li data-value="Colorado" class=""><a href="javascript:void()" onClick="selectCode('<?php echo $code['ModelId']; ?>','<?php echo $code['ModelNo']; ?>')"><?php echo $code['ModelNo']; ?></a></li><?php	
	}
	 
 }

 ?>