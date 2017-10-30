<?php require('../includes/db.php');

 $like = $_GET['val'].'%';
 
 
 $categories = mysql_query("select categories.id, categories.category from categories 
 where categories.category like '". $like ."'");
 
 if(mysql_num_rows($categories)<0)
 {
	?><li>No Data found.</li><?php
 }
 else
 {
	while($category = mysql_fetch_array($categories))
	{
	  ?><li data-value="Colorado" class=""><a href="javascript:void()" onClick="selectCategory('<?php echo $category['id']; ?>','<?php echo $category['category'];?>')"><?php echo $category['category']; ?></a></li><?php	
	}
	 
 }

 ?>