<?php require('../includes/db.php');

 $like = $_GET['val'].'%';
 $branches = mysql_query("select branchId, branch from branches where branch like '". $like ."'");
 
 if(mysql_num_rows($branches)<0)
 {
	?><li>No Data found.</li><?php
 }
 else
 {
	while($branch = mysql_fetch_array($branches))
	{
	  ?><li data-value="Colorado" class=""><a href="javascript:void()" onClick="selectBranch('<?php echo $branch['branchId']; ?>','<?php echo $branch['branch']; ?>')"><?php echo $branch['branch']; ?></a></li><?php	
	}
	 
 }

 ?>