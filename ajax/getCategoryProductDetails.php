<?php require('../includes/db.php');

 $like = $_GET['val'].'%';
 
 
 $products = mysql_query("select products.productId, products.product, products.code  from products where (products.product like '". $like ."' || products.code like '". $like ."') and category = '" . $_GET['category'] . "'");
 
 if(mysql_num_rows($products)<0)
 {
	?><li>No Data found.</li><?php
 }
 else
 {
	while($product = mysql_fetch_array($products))
	{
	  ?><li data-value="Colorado" class=""><a href="javascript:void()" onClick="selectProduct('<?php echo $product['productId']; ?>','<?php echo $product['product'].' - '.$product['code']; ?>')"><?php echo $product['product'].' - '.$product['code'] ; ?></a></li><?php	
	}
	 
 }

 ?>