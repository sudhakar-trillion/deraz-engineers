<?php require('../includes/db.php');
 $like = $_GET['val'].'%';
 
 $products = mysql_query("select distinct product,productId from products where product like '". $like ."'");
 
 if(mysql_num_rows($products)<0)
 {
	?><li>No Data found.</li><?php
 }
 else
 {
	while($product = mysql_fetch_array($products))
	{
	  ?><li data-value="Colorado" class=""><a href="javascript:void()" onClick="selectProduct('<?php echo $product['productId']; ?>','<?php echo $product['product']; ?>')"><?php echo $product['product']; ?></a></li><?php	
	}
	 
 }

 ?>