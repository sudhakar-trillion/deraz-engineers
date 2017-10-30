<?php require('../includes/db.php');

 
 // total amount;
	 $totalAmount = mysql_query("select stock.stockId, stock.invoiceNumber, stock.invoiceAmount, vendors.company from stock
	 left join vendors on stock.vendorId = vendors.vendorId
	  where stock.stockId = '". $_GET['invoiceId'] ."'" );
 
	 
 
  $grandTotal = 0;
 if(mysql_num_rows($totalAmount)>0)
  {
	  
 $total = mysql_fetch_array($totalAmount);
	
		$grandTotal = $total['invoiceAmount']; 
	    $company = $total['company'];
		$stockId = $total['stockId'];
	
	   
  }
   
   
   //items
   
    $items = mysql_query("select products.product, products.code, stock_products.quantity from stock_products
	left join products on stock_products.productId = products.productId 
	
	where stock_products.stockId = '". $stockId ."' order by stock_products.autoId desc");	
   
   
   
  
   // pending amount
  $result = mysql_query("select vendor_payment.amount, vendor_payment.paidDate from vendor_payment where vendor_payment.invoiceId = '". $_GET['invoiceId'] ."' order by paymentId desc");	
						
						 $i = 1;
						 $grandCollected = 0;
 


  

 ?>
 
  <div class="row">
      <div class="col-sm-9 col-sm-offset-3">
      
      Company : <?php echo $company; ?><br />
      Total value : <?php echo $grandTotal; ?>
      <br />
      
      
      <table class="table table-striped table-bordered">
      <thead>
																<tr>
																	<td>S.no</td>
																	<td>Product</td>
																	<td>Model</th>
                                                                    <td>Quantity</td>
																</tr>
															</thead>
                                                            <tbody>
                                     <?php
									 
									  while($item = mysql_fetch_array($items))
	 {
		 ?> <tr><td><?php echo $i; ?></td><td><?php echo $item['product']; ?></td><td><?php echo $item['code']; ?></td><td><?php echo $item['quantity']; ?></td></tr> <?php $i++;
	
	 }
									 ?>               
                                                            
                                                            </tbody></table>
                            <?php echo $pending;  ?>


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
                                                            
                                                            <?php $pending = $grandTotal;
															if(mysql_num_rows($result)>0)
  {
	$i=1;
	  
	 while($row = mysql_fetch_array($result))
	 {
		$grandCollected = $row['amount']+$grandCollected; 
		$paidDate = explode('-',$row['paidDate']);
		$paidDate = $paidDate[2].'-'.$paidDate[1].'-'.$paidDate[0];
	    $pending = $grandTotal-$grandCollected;
		
		?> <tr><td><?php echo $i; ?></td><td><?php echo $paidDate; ?></td><td><?php echo $row['amount']; ?></td><td><?php echo $pending; ?></td></tr> <?php $i++;
	 }
	 

	   
  }
  else
  {
	?><tr><td colspan="4"><?php echo "No Data Found"  ?> </td></tr> <?php  
	  
  }
															
															?>
                                                                </tbody>
                                                                </table>
                                                                
                                                            <p>Pending : <?php echo $pending; ?></p>
                                                          
                                                          <?php if($pending>0) { ?>      
                                                                
                                                                 <div class="form-group">
							<label class="col-sm-3 control-label no-padding-right" for="paymentType"> Payment Type </label>
										<div class="col-sm-9">
                                        <div class="clearfix">
                                        <select id="paymentType" name="paymentType" class="col-xs-10 col-sm-12">
                                            <option value="">Select Payment Type</option>
                                            <option value="1">Proforma</option>
                                            <option value="2">Through Bank</option>
                                            <option value="3">Direct Payment</option>
                                            <option value="4">Against Delivery </option>
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
											<input type="text" id="amount" name="amount" placeholder="Amount" onkeyup="checkPayment(this.value)" class="col-xs-10 col-sm-12"  />
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
                                    <?php } ?>