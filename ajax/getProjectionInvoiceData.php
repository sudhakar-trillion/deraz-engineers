<?php require('../includes/db.php');

 
 $invoiceDetails = mysql_query("select daily_reports.invoiceDateTime, customers.company
 from daily_reports
 left join customers on daily_reports.company = customers.customerId 
 where daily_reports.reportId = '". $_GET['reportId'] ."'");
 
  $row = mysql_fetch_array($invoiceDetails);
  
  // total amount;
  //$totalAmount = mysql_query("select amount from daily_reports_data where reportId = '". $_GET['reportId'] ."'");
  
 
 
 
 
   $totalAmount = mysql_query("select daily_reports_revision.revisionId, daily_reports_revision.grandTotal  
	from daily_reports_revision
  
  where daily_reports_revision.reportId = '". $_GET['reportId'] ."' and daily_reports_revision.revision = '". $_GET['rvid'] ."'");
   
   $grandTotal = 0;
  if(mysql_num_rows($totalAmount)>0)
  {
	  
	$total = mysql_fetch_array($totalAmount);
	
		 
	
	   
  }
   
   $items = mysql_query("select categories.category, brands.brand, products.product, products.code
	from daily_reports_data 
	left join categories on daily_reports_data.categoryId = categories.id
	left join brands on daily_reports_data.brandId = brands.id
	left join products on daily_reports_data.productId = products.productid
	
  where daily_reports_data.revisionId = '". $total['revisionId'] ."'");
  
  
  
  
  // paid amount;
  $paidAmount = mysql_query("select * from invoices left join daily_reports on invoices.reportId  = daily_reports.reportId where daily_reports.reportId = '". $_GET['reportId'] ."'");
  
  
  if(mysql_num_rows($paidAmount)>0)
  {
	  
	 while($total = mysql_fetch_array($paidAmount))
	 { 
	 $grandtotal = $total['grandTotal'];
	 
	 	$qry = mysql_query("select sum(amount) totalpaid from collections where invoiceid=".$total['invoiceId']);
		
		if(mysql_num_rows($qry)>0)
		{
			$qry123 = mysql_fetch_array($qry);
			$totalPaid =  $qry123['totalpaid'];
		}
	
	 }
	   
  }
  
  
 ?>
       <div class="row"> 
   <div class="col-sm-12"> 
   <div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">
<table id="sample-table-1" class="table table-striped table-bordered table-hover">  
        <tr><td>Invoice Date</td><td><?php 
		$invoiceDate = explode(' ',$row['invoiceDateTime']); 
														     $invoiceDate = explode('-',$invoiceDate[0]); 
														     $invoiceDate = $invoiceDate[2].'-'.$invoiceDate[1].'-'.$invoiceDate[0];
														
														 if(strcmp('00-00-0000',$poDate)!=0)
												{
													echo $invoiceDate;
												}   ?>
		
		    	</td></tr>
<tr><td>Company Name</td><td><?php echo $row['company'];  ?></td></tr>
<tr> <td>Invoice Amount</td><td><?php echo $grandtotal; ?></td></tr>
<tr> <td>Amount Paid </td><td><?php echo $totalPaid; ?>		</td></tr>
<tr>  <td>Pending Amount </td><td><?php echo $pending = $grandtotal-$totalPaid;  ?>	</td></tr>
     


</table></div>
   
   
   
   
   
<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">
<table id="sample-table-1" class="table table-striped table-bordered table-hover">  
 	   <tr><th>S.no</th>
 	   <th>Category</th>
       <th>Brand</th>
       <th>Product</th>
       <th>Model NO</th></tr>
   <?php 
   $i=1;
   while($item=mysql_fetch_array($items))
   {
	   ?><tr><td><?php echo $i;  ?></td><?php
	   ?><td><?php echo $item['category']  ?></td><?php
	   ?><td><?php echo $item['brand']  ?></td><?php
	   ?><td><?php echo $item['product']  ?></td><?php
  	   ?><td><?php echo $item['code']  ?></td></tr><?php

$i++;
   }
   
   
   ?>
   
   </table></div>
   
   
   
   </div></div> 
      
      <?php if($pending>0) { ?>  				
        <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="expectedAmount"> Expected Amount </label>

										<div class="col-sm-9">
											<input type="text" id="expectedAmount" name="expectedAmount" placeholder="Expected Amount" class="form-control" />
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="remarks"> Remarks </label>

										<div class="col-sm-9">
											<textarea  id="remarks" name="remarks" placeholder="Remarks" class="form-control"></textarea>
										</div>
									</div>
                                  
                                  <div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="submit" name="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>

											&nbsp; &nbsp; &nbsp;
											<button class="btn" type="reset">
												<i class="ace-icon fa fa-undo bigger-110"></i>
												Reset
											</button>
										</div>
									</div>
          <?php	} ?>
                                    
      <div class="space-6"></div>
     
 
 