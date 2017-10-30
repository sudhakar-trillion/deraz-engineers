<?php require('../includes/db.php');

 echo "select * from daily_reports where invoice = '". $_GET['invoice']  ."'";
  $invoices = mysql_query("select * from daily_reports where invoice = '". $_GET['invoice']  ."'");
 
 if(mysql_num_rows($invoices)<0)
 {
	?><li>No Data found.</li><?php
 }
 else
 {
	while($invoice = mysql_fetch_array($invoices))
	
	{  echo "select * from daily_reports where invoice = '". $_GET['invoice']  ."'";
	  ?><li data-value="Colorado" class=""><a href="javascript:void()" onClick="getCform('<?php echo $invoice['poDate']; ?>','<?php echo $invoice['poNo']; ?>','<?php echo $invoice['poValue']; ?>')"><?php echo $invoice['addedBy']; ?></a></li><?php	
	}
	 
 }

 ?>