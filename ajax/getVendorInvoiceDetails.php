<?php require('../includes/db.php');

 $like = $_GET['val'].'%';
 $invoices = mysql_query("select stock.stockId, stock.invoiceNumber from stock where stock.invoiceNumber like '". $like ."'");
 
 if(mysql_num_rows($invoices)<0)
 {
	?><li>No Data found.</li><?php
 }
 else
 {
	while($invoice = mysql_fetch_array($invoices))
	{
	  ?><li data-value="Colorado" class=""><a href="javascript:void()" onClick="getInvoiceDetails('<?php echo $invoice['stockId']; ?>','<?php echo $invoice['invoiceNumber']; ?>')"><?php echo $invoice['invoiceNumber']; ?></a></li><?php	
	}
	 
 }

 ?>