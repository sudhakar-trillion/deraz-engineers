<?php include("../includes/db.php");
//$products = mysql_query("select productId, product from products where category = '". $_GET['cid'] ."' and brand = '". $_GET['bid'] ."'  group by product order by productId");
$products = mysql_query("select productId, product from products where category = '". $_GET['cid'] ."' and brand = '". $_GET['bid'] ."' order by productId");

//echo "select productId, product from products where category = '". $_GET['cid'] ."' and brand = '". $_GET['bid'] ."' order by productId";
//exit;

?>
<option value="0">Select Products</option>
<?php
											while($product = mysql_fetch_array($products))
											{	?> <option value="<?php echo $product['productId'] ?>"><?php echo $product['product']; ?></option><?php } ?>