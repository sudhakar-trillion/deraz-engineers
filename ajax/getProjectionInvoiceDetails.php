<?php ob_start(); session_start(); require('../includes/db.php');


 $like = $_GET['val'].'%';
 $invoices = mysql_query("select reportId, invoice, currentRevisionId from daily_reports where invoice like '". $like ."' and addedBy = '". $_SESSION['id'] ."'");
 
 if(mysql_num_rows($invoices)<0)
 {
	?><li>No Data found.</li><?php
 }
 else
 {
	while($invoice = mysql_fetch_array($invoices))
	{
	  ?><li data-value="Colorado" class=""><a href="javascript:void()" onClick="getInvoiceDetails('<?php echo $invoice['reportId']; ?>','<?php echo $invoice['invoice']; ?>', '<?php echo $invoice['currentRevisionId']; ?>')"><?php echo $invoice['invoice']; ?></a></li><?php	
	}
	 
 }

 ?>