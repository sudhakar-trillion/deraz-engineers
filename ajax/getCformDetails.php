<?php require('../includes/db.php');


 /*$invoices = mysql_query("select daily_reports.poNo, daily_reports.addedBy, daily_reports.poDateTime, customers.company, daily_reports.total, daily_reports.poNo, daily_reports.invoiceDateTime, employees.firstName from daily_reports 
  left join invoices on daily_reports.reportId = invoices.id
  left join employees on daily_reports.addedBy = employees.id
  left join customers on daily_reports.company = customers.customerId
  where daily_reports.invoice = '". $_GET['invoiceNumber'] ."'");*/
  

  $invoices = mysql_query("select daily_reports.poNo, daily_reports.addedBy, daily_reports.poDateTime, customers.company, daily_reports.total, daily_reports.poNo, daily_reports.invoiceDateTime, employees.firstName, invoices.invoiceId from invoices
  left join daily_reports on invoices.reportId = daily_reports.reportId
  
  left join employees on daily_reports.addedBy = employees.id
  left join customers on daily_reports.company = customers.customerId
  where invoices.proInvoiceNumber= '". $_GET['invoiceNumber'] ."'");
  
 
 if(mysql_num_rows($invoices) < 1)
 {
	?>No Data found.<?php
 }
 else
 {
	
	$invoice = mysql_fetch_array($invoices);
	
		?>
		 <div class="form-group">
         
         <input type="hidden" name="invoiceId" value="<?php echo $invoice['invoiceId']; ?>" />
         
<label class="col-sm-3 control-label no-padding-right" for="PO Number"> PO Number</label>

		<div class="col-sm-9">
        <div class="clearfix">
	<label class="col-sm-3 control-label no-padding-right" for="PO Number"><?php echo $invoice['poNo']; ?></label>
                                            </div>
										</div>
									</div>
                                    
                                    
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="PO Date"> PO Date</label>

										<div class="col-sm-9">
                                        <div class="clearfix">
<label class="col-sm-3 control-label no-padding-right" for="PO Date"><?php echo $invoice['poDateTime']; ?></label>
                                            </div>
										</div>
									</div>
                                    
   <!--<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="Invoice Value"> Invoice Value</label>

								<div class="col-sm-9"><div class="clearfix">
									<input type="invoiceValue" id="Invoice Value" name="Invoice Value" placeholder="Invoice Value" class="col-xs-10 col-sm-12"  value="<?php //echo $invoice['amount']; ?>"  />
										</div></div>
									</div>-->
                                    
 <div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="Executive"> Executive</label>

								<div class="col-sm-9"><div class="clearfix">
	<label class="col-sm-3 control-label no-padding-right" for="Executive"><?php echo $invoice['firstName']; ?></label>
										</div></div>
									</div>
                                    
  <div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="Customer Name"> Customer Name</label>

								<div class="col-sm-9"><div class="clearfix">
									<?php echo $invoice['company']; ?>
										</div></div>
									</div> <div class="form-group">
							<label class="col-sm-3 control-label no-padding-right" for="Invoice Date"> Invoice Date</label>

								<div class="col-sm-9"><div class="clearfix">
<label class="col-sm-3 control-label no-padding-right" for="Invoice Date"><?php echo $invoice['invoiceDateTime']; ?></label>
										</div></div>
									</div>
                                    
                                    
                                    
                                    <div class="form-group">
							<label class="col-sm-3 control-label no-padding-right" for="quarter"> Quarter </label>

								<div class="col-sm-9"><div class="clearfix">
<select class="col-sm-3 control-label no-padding-right" id="quarter" name="quarter">
<option value="1">1st Quarter</option>
<option value="2">2nd Quarter</option>
<option value="3">3rd Quarter</option>
<option value="4">4th Quarter</option>
</select>
										</div></div>
									</div>
                                    
                                    	
									</div> <div class="form-group">
							<label class="col-sm-3 control-label no-padding-right" for="status"> Status </label>

								<div class="col-sm-9"><div class="clearfix">
<select class="col-sm-3 control-label no-padding-right" id="status" name="status">
<option value="pending">Pending</option>
<option value="cleared">Cleared</option>

</select>
										</div></div>
									</div>
                                                                                                                                         

<?php		
	  

	
 
 }

 ?>
                                    
                                    
                                    