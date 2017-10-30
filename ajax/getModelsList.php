<?php include("../includes/db.php");
//$prdct = preg_replace("#2 {,}#"," ",$_GET['product']);
$prdct = $_POST['product'];

//Query commented by sudhaker on 22-03-2017
//we need to bring the models of the products from the new table product_model


//$products = mysql_query("select productId, code from products where category = '". $_POST['cid'] ."' and brand = '". $_POST['bid'] ."' and productId = '".$prdct."' group by product  order by product"); 

$products = mysql_query("SELECT ModelId,ModelNo FROM product_model WHERE ProductId=".$prdct);

?>
                                           
                                            <option value="0">Select Model</option>
                                            <?php
											while($product = mysql_fetch_array($products))
											{	
											?> 
                                            <!--<option value="<?php #echo $product['productId'] ?>"><?php #echo $product['code']; ?></option>-->
											<option value="<?php echo $product['ModelId']; ?>"><?php echo $product['ModelNo']; ?></option><?php 
											} ?>