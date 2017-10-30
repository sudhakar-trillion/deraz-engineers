<?php ob_start(); session_start(); require('../includes/db.php');

 $like = $_GET['val'].'%';
 

 $brands = mysql_query("select id, brand from brands where brand like '". $like ."'");

 if(mysql_num_rows($brands)<0)
 {
	?><li>No Data found.</li><?php
 }
 else
 {
	while($brand = mysql_fetch_array($brands))
	{
	  ?><li data-value="Colorado" class=""><a href="javascript:void()" onClick="selectBrand('<?php echo $brand['id']; ?>','<?php echo $brand['brand']; ?>')"><?php echo $brand['brand']; ?></a></li><?php	
	}
	 
 }

 ?>