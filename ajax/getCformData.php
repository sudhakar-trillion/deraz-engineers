<?php require('../includes/db.php');

 

 
  $result = mysql_query("select daily_reports_revision.revisionId, products.product, products.code, daily_reports_data.id, daily_reports_data.quantity 
 from daily_reports_revision
 left join daily_reports_data on daily_reports_revision.revisionId = daily_reports_data.revisionId
 left join products on daily_reports_data.productId = products.productId
 where daily_reports_revision.reportId = '". $_GET['reportId'] ."' and daily_reports_revision.revision = '". $_GET['crid'] ."'
 order by daily_reports_data.id desc");
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
 if(mysql_num_rows($result)>0)
 {
$i=1;	 
	while($row = mysql_fetch_array($result))
	{
		// revisionId
		$rid = $row['revisionId'];
		
		// caliculate dispatched quantity
		$dispatched_quantity = mysql_query("select dispatchedQuantity from dispatch_items where itemId = '". $row['id'] ."'");
		
		$alreadyDispatched = 0;
		
		if(mysql_num_rows($dispatched_quantity)>0)
		{
			while($dispatched_quantity_row = mysql_fetch_array($dispatched_quantity))
			{
				$alreadyDispatched = $alreadyDispatched+$dispatched_quantity_row['dispatchedQuantity'];
			}
		}
		else
		{
		   $alreadyDispatched = 0;	
		}
		
		
		
		
	  ?>
      <tr><td class="center"><?php echo $i; ?></td>
          <td><?php echo $row['product']; ?></td>
          <td><?php echo $row['code']; ?></td>
          <td><?php echo $row['quantity']; ?></td>
           <td><?php echo $alreadyDispatched; ?></td>
           <td><?php echo $pending = $row['quantity']-$alreadyDispatched; ?></td>
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
     <?php	$i++;
	}
 }
 else
 {
	?><tr><td colspan="6">No Data found.</td></tr><?php
 }

 ?></tbody></table>
 

 
 </div></div>
 
 
 
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