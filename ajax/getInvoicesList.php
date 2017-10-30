<?php require('../includes/db.php');

 $like = $_GET['val'].'%';
 $invoices = mysql_query("select invoiceId, invoiceNumber from invoices where invoiceNumber like '". $like ."'");
 
 if(mysql_num_rows($invoices)<0)
 {
	?><li>No Data found.</li><?php
 }
 else
 {
	while($invoice = mysql_fetch_array($invoices))
	{
	  ?><li data-value="Colorado" class=""><a href="javascript:void()" onClick="selectInvoice('<?php echo $invoice['invoiceId']; ?>','<?php echo $invoice['invoiceNumber']; ?>')"><?php echo $invoice['invoiceNumber']; ?></a></li><?php	
	}
	 
 }

 ?>