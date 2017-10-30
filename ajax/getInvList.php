<?php require('../includes/db.php');

 $like = '%'.$_GET['val'].'%';

 $invs = mysql_query("select stockId, invoiceNumber from stock where invoiceNumber like '". $like ."'");
 
 if(mysql_num_rows($invs)<0)
 {
	?><li>No Data found.</li><?php
 }
 else
 {
	while($inv = mysql_fetch_array($invs))
	{
	  ?><li data-value="Colorado" class=""><a href="javascript:void()" onClick="selectInvoice('<?php echo $inv['stockId']; ?>','<?php echo $inv['invoiceNumber']; ?>')"><?php echo $inv['invoiceNumber']; ?></a></li><?php	
	}
	 
 }

 ?>