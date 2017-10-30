<?php include("../includes/db.php");

 
 
  $item_ids = explode(',',$_GET['itemId']);
  

  
  if(count($item_ids)==1)
  {
	$where = "where daily_reports_data.id =  '". $_GET['itemId'] ."'";
  }
  else
  {
	  $itemIds = '('.$_GET['itemId'].')';
	$where = "where daily_reports_data.id in  $itemIds";
  }
  
  
$revision = mysql_query("select revisionId, discount, duty, dutyPercentage, pf, pfPercentage  from daily_reports_revision where revisionId = '". $_GET['crid'] ."'");
 
  $revision = mysql_fetch_array($revision);
  
  
  $selectedItems = mysql_query("select categories.category, brands.brand, products.product, daily_reports_data.id, daily_reports_data.price, daily_reports_data.quantity, daily_reports_data.amount, daily_reports_data.taxSystem, daily_reports_data.taxPercentage, daily_reports_data.taxAmount  from daily_reports_data 
  
  
  left join categories on daily_reports_data.categoryId = categories.id
  left join brands on daily_reports_data.brandId = brands.id
  left join products on daily_reports_data.productId = products.productId
  $where");
  
 
  ?>
  
   
  
  <?php
  if(mysql_num_rows($selectedItems)>0)
  {
	  $i = 1; 
	  $subTotal = 0;
	  $total = 0;
	  $vat = 0;
	  
  while($selectedItem = mysql_fetch_array($selectedItems))
  {
	  
	  
	
	?>
    
    
   
   
    <tr id="<?php echo 'itemDiv_1_'.$_GET['itemId']; ?>">
       <td><?php echo $i; ?></td>
                                                 <td>   <input type="hidden" name="itemId[]" value="<?php echo $selectedItem['id'];  ?>" />
                                                  <?php     echo $selectedItem['category']; ?>    </td>
                                                 <td>   <?php     echo $selectedItem['brand']; ?>    </td>
                                                 <td>   <?php     echo $selectedItem['product']; ?>    </td>
                                                 <td>   <?php     echo $selectedItem['quantity']; ?>    </td>
                   <td> 
                  
                  <?php echo $selectedItem['price']; ?>    </td>
            <td>   <?php echo $selectedItem['amount']; ?>    </td>
                                                 </tr>
                                                 
   <tr id="<?php echo 'itemDiv_2_'.$_GET['itemId']; ?>"><td colspan="5"></td><td><?php echo $selectedItem['taxSystem'].' '.$selectedItem['taxPercentage'].'%'; ?></td><td><?php echo $selectedItem['taxAmount']; ?></td></tr>
    

    <?php 
	$subTotal = $selectedItem['amount']+$subTotal;
	$vat = $selectedItem['taxAmount']+$vat;
$i++;	
	 }
	
	$discount = ($subTotal*$revision['discount'])/100;
	$total = $subTotal-$discount;
	
	// duty & ph
	$duty = ($total*$revision['dutyPercentage'])/100;
	$pf =   ($total*$revision['pfPercentage'])/100;
	
	$grandTotal = $total+$duty+$pf+$vat;
	 
	
	?>
    
                                    <tr><td colspan="5"></td><td>Sub Total</td><td><?php echo $subTotal; ?>
                                    
                                    
                                    </td></tr> 
                                    <tr><td colspan="5"></td><td>Discount</td><td><?php echo $revision['discount']; ?></td></tr> 
                                    <tr><td colspan="5"></td><td>Total</td><td><?php echo $total; ?></td></tr> 
                                    <tr><td colspan="5"></td><td>Duty 12.5%</td><td><?php echo $duty; ?></td></tr> 
                                    <tr><td colspan="5"></td><td>PF 3%</td><td><?php echo $pf; ?></td></tr> 
                                    <tr><td colspan="5"></td><td>Grand Total</td><td><?php echo $grandTotal; ?>
                                    
                                    
                                    
                                     <input type="hidden" name="subTotal" value="<?php echo $subTotal;  ?>" />
                                     <input type="hidden" name="discount" value="<?php echo $revision['discount'];  ?>" />
                                     <input type="hidden" name="total" value="<?php echo $total;  ?>" />
                                     
                                     <input type="hidden" name="dutyPercentage" value="12.5" />
                                     <input type="hidden" name="dutyAmount" value="<?php echo $duty;  ?>" />
                                     
                                     <input type="hidden" name="pfPercentage" value="3" />
                                     <input type="hidden" name="pfAmount" value="<?php echo $pf;  ?>" />
                                     
                                     
                                     <input type="hidden" name="grandTotal" value="<?php echo $grandTotal;  ?>" />
                                    </td></tr> 
	
    
    <tr><td colspan="5"></td><td>ProInvoice Number</td><td><input type="text" id="proformaInvoice" name="proformaInvoice" placeholder="Proforma Invoice">
    
    
    </td></tr> 
    <tr><td colspan="5"></td><td>Proforma Date</td> 
<td><input type="text" name="proformaInvoiceDateTime" id="proformaInvoiceDateTime" class="date-picker" placeholder="Proforma Date" /></td>
                                   </tr>
    
     <tr><td colspan="5"></td><td></td><td>
    <button class="btn btn-sm btn-info" type="submit" name="generateProformaInvoice">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>
    </td></tr> 
    
	
	<?php } else { ?>
    <tr><td colspan="7">No Items selected.</td></tr>
    
    <?php } ?>
    <script src='assets/js/jquery.js'></script>
      <script src="assets/js/date-time/bootstrap-datepicker.js"></script>
		<script src="assets/js/date-time/bootstrap-timepicker.js"></script>
		<script src="assets/js/date-time/moment.js"></script>
		<script src="assets/js/date-time/daterangepicker.js"></script>
		<script src="assets/js/date-time/bootstrap-datetimepicker.js"></script>
	<script>
	$('.date-picker').datepicker({
					autoclose: true,
					todayHighlight: true,
					format: 'dd-mm-yyyy'
					
				});
	</script>