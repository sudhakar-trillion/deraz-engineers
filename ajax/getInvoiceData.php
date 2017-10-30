<?php require('../includes/db.php');
 $qry = mysql_query("select proInvoiceNumber, reportId from invoices inv where inv.invoiceId='".$_GET['invoiceId']."'");
$proinv = mysql_fetch_object($qry);

#echo $proinv->proInvoiceNumber; exit;
$qreey = mysql_query( "select drd.id from daily_reports_data as drd left join daily_reports_revision as drv on drv.revisionId=drd.revisionId left join daily_reports as dr on dr.reportId=drv.reportId where drv.reportId=".$proinv->reportId); 

$prd_name='';
	  $prd_quantity=0;
	  $prd_name='<ol>';
	  $prd_quantity='<ol>';
	  $model='<ol>';
?>
  <div class="row">
      <div class="col-sm-12">
<table class="table table-striped table-bordered">
															<thead>
																<tr>
																	<th class="center">S.no</th>
																	<th>Product</th>
																	<th>Model No.</th>
																	<th>Ordered Quantity</th>
                                                                    <th>Dispatched</th>
                                                                    <th>Pending</th>
                                                                    <th>Dispatching</th>
                                                                    <th>Remarks</th>
																</tr>
															</thead>

															<tbody>
                                                                
                                                                																
                                                                
                                                                
																

																
															
    
   <?php 
$i=1;   
$tot_disp= 0;
$total_ordered = 0;
while( $data = mysql_fetch_object($qreey) )
{

  $result = mysql_query("select invoices.invoiceId,products.productId,daily_reports_data.revisionId,product_model.ModelNo, products.product, products.code, daily_reports_data.id, daily_reports_data.quantity 
 from invoices
 left join daily_reports_data on invoices.invoiceId = daily_reports_data.invoice_id
 left join daily_reports_revision as drv on drv.revisionId=daily_reports_data.revisionId
 left join categories on daily_reports_data.categoryId = categories.id
 left join products on daily_reports_data.productId = products.productId
 left join product_model on product_model.ModelId=daily_reports_data.modelId
 where invoices.invoiceId = '". $_GET['invoiceId'] ."' and daily_reports_data.id=".$data->id." group by daily_reports_data.modelId
 order by daily_reports_data.id DESC ");

   
   $totalPending = 0;
 if(mysql_num_rows($result)>0)
 {
	 
	while($row = mysql_fetch_array($result))
	{
		// revisionId
		$rid = $row['revisionId'];
		
		// caliculate dispatched quantity
$dispatched_quantity = mysql_query("select sum(dispatchedQuantity) dispatchedQuantity from dispatch_items where dispatchId IN (SELECT dispatchedId from dispatch where invoiceId=".$_GET['invoiceId'].") and itemId=".$row['id']);


		if(mysql_num_rows($dispatched_quantity)>0)
		{
			$dispatched_quantity_row = mysql_fetch_array($dispatched_quantity);
			
			 $alreadyDispatched = $dispatched_quantity_row['dispatchedQuantity'];		
			 
		}
		else
		{
		   $alreadyDispatched = 0;	
		}
		
		if($row['product']!='')
		{
		
	  ?>
      <tr><td class="center"><?php echo $i; ?></td>
          <td><?php echo $row['product']; ?></td>
          <td><?php echo $row['ModelNo']; ?></td>
          <td><?php echo $row['quantity']; $total_ordered = $total_ordered + $row['quantity']; ?></td>
           <td><?php echo $alreadyDispatched; $tot_disp =$tot_disp+$alreadyDispatched; ?></td>
           <td><?php echo $pending = $row['quantity']-$alreadyDispatched;  
		   
		   $totalPending = $totalPending+$pending;
		   ?></td>
          <td><input type="hidden"  name="item[]" value="<?php echo $row['id']; ?>" />
              
              <?php if($alreadyDispatched<$row['quantity']) { ?>
              
               <input type="hidden"  id="<?php echo 'pending'.$i; ?>" value="<?php echo $row['quantity']-$alreadyDispatched;  ?>"  />
               <input type="text"  name="dispatchQuantity[]" id="<?php echo 'dispatchQuantity'.$i; ?>" onkeyup="checkQuantity(this.value,'<?php echo $i; ?>')" placeholder="Quantity" class="col-xs-10 col-sm-6" value="0"  />
              <?php } else { ?>
              <input type="hidden"  name="dispatchQuantity[]" class="col-xs-10 col-sm-6" value="0"   />
              <?php } ?>
              </td>
              <td>
               <?php if($alreadyDispatched<$row['quantity']) { ?>
              <textarea class="form-control limited" id="remarks" name="remarks[]"></textarea>
               <?php } else { ?>
               <input type="hidden"  name="remarks[]" class="col-xs-10 col-sm-6" value=""   />
              <?php } ?>
              
              </td>
																</tr>
                                                                <div class="space"></div>
     <?php	}
	}
 }
 else
 {
	?><tr><td colspan="6">No Data found.</td></tr><?php
 }
$i++;
}
 ?></tbody></table>
 
<?PHP //echo $tot_disp."-".$total_ordered; ?>
 
 </div></div>

 <?php if($total_ordered>$tot_disp) { ?>
 
 <div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
                                        <input type="hidden" name="rid" id="rid" value="<?php echo $rid; ?>" />
 											<button class="btn btn-info" type="submit" name="addDispatch">
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
                                    <?php } ?>