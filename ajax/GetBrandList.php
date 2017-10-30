<?php /* include("../includes/db.php");
$category = $_GET['cid'];


$brands = mysql_query("select brands.id, brands.brand from category_brands left join brands on category_brands.brandId = brands.id
   where category_brands.categoryId = '". $category ."' order by brands.brand");

?>
<option value="0">Select Brands</option>
<?php
											while($brand = mysql_fetch_array($brands))
											{	?> <option value="<?php echo $brand['id'] ?>"><?php echo $brand['brand'] ?></option><?php }*/ 

											
include("../includes/db.php");

   
$brands = mysql_query("select brands.id, brands.brand from category_brands left join brands on category_brands.brandId = brands.id
   where category_brands.categoryId = '". $_GET['cid'] ."' order by brands.brand");

?>
<option value="0">Select Brands</option>
<?php
											while($brand = mysql_fetch_array($brands))
											{	?> <option value="<?php echo $brand['id'] ?>"><?php echo $brand['brand'] ?></option><?php } 										
											
											
											?>