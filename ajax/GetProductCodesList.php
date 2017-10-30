<?php require('../includes/db.php');
 $like = $_GET['val'].'%';
 
 // echo "select code from products where code like '". $like ."'"; exit;
 
 $codes = mysql_query("select productId, code from products where code like '". $like ."'");
 
 if(mysql_num_rows($codes)<0)
 {
	?><li>No Data found.</li><?php
 }
 else
 {
	while($code = mysql_fetch_array($codes))
	{
		
?><li data-value="Colorado" class=""><a href="javascript:void()" onClick="selectProductCode('<?php echo $code['productId']; ?>','<?php echo $code['code']; ?>')"><?php echo $code['code']; ?></a></li><?php	
	}
	 
 }

 ?>