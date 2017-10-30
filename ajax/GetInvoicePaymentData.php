<?php require('../includes/db.php');

 // total amount;
$totalAmount = mysql_query("select reportId, grandTotal from invoices where invoices.invoiceId = '". $_GET['invoiceId'] ."'");
  
 
  if(mysql_num_rows($totalAmount)>0)
  {
	  $total = mysql_fetch_array($totalAmount);
	  $grandTotal = $total['grandTotal']; 
  }
  else
  { $grandTotal = 0; }
  
   
   // Company Details
   
   $companyDetails = mysql_query("select customers.company from daily_reports left join customers on daily_reports.company = customers.customerId where reportId = '" . $total['reportId'] ."' ");
                           $companyDetails = mysql_fetch_array($companyDetails);  
   // pending amount
/*   
  $result = mysql_query("select collections.amount, collections.paidDate from collections                     
                        left join daily_reports on collections.invoiceId = daily_reports.reportId
						where collections.invoiceId = '". $_GET['invoiceId'] ."'
						order by collections.id");	
						*/
						
$result = mysql_query("select collections.amount, collections.paidDate from collections where collections.invoiceId = '". $_GET['invoiceId'] ."'
						order by collections.id");						
						
						 $i = 1;
						 $grandCollected = 0;
 
/*
 echo "select collections.amount, collections.paidDate from collections where collections.invoiceId = '". $_GET['invoiceId'] ."'
						order by collections.id"; exit; 
*/ 
//get the proInvoiceNumber 

$qry = mysql_query("select proInvoiceNumber, reportId from invoices inv where inv.invoiceId='".$_GET['invoiceId']."'");
$proinv = mysql_fetch_object($qry);


$qreey = mysql_query( "select drd.id from daily_reports_data as drd left join daily_reports_revision as drv on drv.revisionId=drd.revisionId left join daily_reports as dr on dr.reportId=drv.reportId where drv.reportId=".$proinv->reportId); 

$prd_name='';
	  $prd_quantity=0;
	  $prd_name='';
	  $prd_quantity='';
	  $model='';

while( $data = mysql_fetch_object($qreey) )
{

$qry_prdct = mysql_query("select drd.price,drd.quantity, prd.product,inv.proInvoiceNumber as invoiceNumber,pm.ModelNo from daily_reports_data drd join invoices inv on drd.invoice_id=inv.invoiceId join products prd on prd.productId=drd.productId left join product_model as pm on pm.ModelId=drd.modelId where inv.invoiceId='".$_GET['invoiceId']."' and drd.id=".$data->id." order by drd.id desc");

//echo mysql_num_rows($qry_prdct); exit;

	  if(mysql_num_rows($qry_prdct)>0)
	  {
		while($prd = mysql_fetch_object($qry_prdct))  
		{
			  $prd_name.=$prd->product.",";
			  $prd_quantity.=$prd->quantity.",";
			  $model.=$prd->ModelNo.",";
		}
	  }
}
	  $prd_name=trim($prd_name,",");
	  $prd_quantity=trim($prd_quantity,",");
	  $model=trim($model,",");
	  
	  
	  ?>
 
  <div class="row">
      <div class="col-sm-9 col-sm-offset-3">
      <b>
      Company Name : <?php echo $companyDetails['company'] ; ?> <br />
      
      Product: <?PHP echo $prd_name; ?><br />
      Model: <?PHP echo $model; ?><br />
      Quantity:<?PHP echo $prd_quantity; ?><br />
      
      Total value : <?php echo $grandTotal; ?>
      </b>
      <br /><br />
<table class="table table-striped table-bordered">
															<thead>
																<tr>
																	<th>S.no</th>
																	<th>Paid Date</th>
																	<th>Paid</th>
                                                                    <th>Pending</th>
																</tr>
															</thead>

															<tbody>
                                                            
                                                            <?php
if(mysql_num_rows($result)>0)
  {
	
	  
	 while($row = mysql_fetch_array($result))
	 {
		$grandCollected = $row['amount']+$grandCollected; 
		$paidDate = explode('-',$row['paidDate']);
		$paidDate = $paidDate[2].'-'.$paidDate[1].'-'.$paidDate[0];
	    $pending = $grandTotal-$grandCollected;
		
		?> <tr><td><?php echo $i; ?></td><td><?php echo $paidDate; ?></td><td><?php echo $row['amount']; ?></td><td><?php echo $pending; ?></td></tr> <?php $i++;
	 }
	 

	   
  } else
  
 {   $pending=$grandTotal; 
	 ?> <tr><td colspan="4">No Data found </td></tr><?php
 } ?>
															
															
                                                                </tbody>
                                                                </table>
                                                 Pending Value: <?php echo $pending; ?> <br /><br />
                                                                
                                                                
                                              <?php                   if($pending!=0)
 {
	
	 
	?> <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="paymentType"> Payment Type </label>

										<div class="col-sm-9">
                                        <div class="clearfix">
                                        <select id="paymentType" name="paymentType" class="col-xs-10 col-sm-12">
                                            <option value="">Select Payment Type</option>
                                            
                                            
                                            
                                             <option value="5">By Check/DD</option>
                                              <option value="6">Bank Transfer</option>
                                               <option value="7">By Cash</option>
                                            
                                            
                                            
                                            </select>
                                        </div></div>
									</div>
                                    <div class="form-group">
                                    
										<label class="col-sm-3 control-label no-padding-right" for="amount"> Amount </label>

										<div class="col-sm-9">
                                        <div class="clearfix">
											<input type="hidden" id="pending" name="pending" value="<?php echo $pending; ?>"  />
                                            <input type="text" id="amount" name="amount" placeholder="Amount" class="col-xs-10 col-sm-12"  />
										</div></div>
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
                                    
                                    
                                    <?php
 }
 
 ?>