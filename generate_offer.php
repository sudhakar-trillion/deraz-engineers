<?php  include("includes/header.php"); 

    // proposal submit
		if(isset($_POST['submitOffer']))
		{
			$count = count($_POST['item']);
		
			if($_POST['offerToBeSubmitted']==0)
			{
// if offerToBeSubmitted we have to give the offer number, offer committed date and offer acceptance date into daily_reports table.		
				$offerDate = $_POST['offerDate'];
				$offerDate1 = date_create($offerDate);
				$offerDate1 = date_format($offerDate1,'Y-m-d');
//  we write the update query for daily_reports table. 
/*mysql_query("update daily_reports set offerDate = '" . date('Y-m-d') . "' ,   offerNo = '". $_POST['offerNo'] ."', delCommDate = '".$delCommittedDate1."', delAcceptanceDate = '".$delAcceptanceDate1."', paymentTerms = '".$_POST['paymentTerms']."',  offerToBeSubmitted = '2', leadStatus = 'Offer generated' where reportId = '". $_POST['reportId'] ."'");
*/
mysql_query("update daily_reports set offerDate = '" . $offerDate1 . "' ,   offerNo = '". $_POST['offerNo'] ."',  offerToBeSubmitted = '2', leadStatus = 'Offer generated' where reportId = '". $_POST['reportId'] ."'");

//we write the update query for daily_reports_revision table for amount details.
mysql_query("update daily_reports_revision set subTotal = '". $_POST['subTotal'] ."', discount = '". $_POST['discount'] ."', duty = '". $_POST['duty'] ."', dutyPercentage = '". $_POST['dutyPercentage'] ."', pf = '". $_POST['pf'] ."', pfPercentage = '". $_POST['pfPercentage'] ."', total = '". $_POST['total'] ."', grandTotal = '". $_POST['grandTotal'] ."', revisionDateTime = NOW() where reportId = '". $_POST['reportId'] ."' and revision = '0'");
			
			
			for($i=0;$i<$count;$i++)
			{
				
				if($_POST['tax'][$i]==1)
				{
					$tax = 'CST';
					$taxPercentage = $_POST['cstTax'][$i];
				} else if($_POST['tax'][$i]==2)
				{
					$tax = 'VAT';
					$taxPercentage = $_POST['vatTax'][$i];

				}
				else if($_POST['tax'][$i]==3)
				{
					$tax = 'GST';
					$taxPercentage = $_POST['gstTax'][$i];
				}

// we update the daily_reports_data for tax amount and percentage.				
  mysql_query("update daily_reports_data set price = '". $_POST['price'][$i] ."', amount = '". $_POST['amount'][$i] ."', taxSystem = '". $tax ."', taxPercentage = '". $taxPercentage ."', taxAmount = '". $_POST['productTax'][$i] ."' where id = '". $_POST['item'][$i] ."'");
			
			}
			}
			else
			{
				
	$revisionId = $_POST['currentRevisionId']+1;

 mysql_query("update daily_reports set offerNo = '". $_POST['offerNo'] ."', offerToBeSubmitted = '2', currentRevisionId = '". $revisionId ."', leadStatus = 'Offer regenerated' where reportId = '". $_POST['reportId'] ."'");	
 

 mysql_query("insert into daily_reports_revision (`reportId`, `revision`, `subTotal`, `discount`, `duty`, `dutyPercentage`, `pf`, `pfPercentage`, `total`, `grandTotal`, `revisionDateTime`) values ('". $_POST['reportId'] ."', '". $revisionId ."', '". $_POST['subTotal'] ."', '". $_POST['discount'] ."', '". $_POST['duty'] ."', '". $_POST['dutyPercentage'] ."', '". $_POST['pf'] ."', '". $_POST['pfPercentage'] ."', '". $_POST['total'] ."', '". $_POST['grandTotal'] ."', NOW())");
				$lastId = mysql_insert_id();
				
		for($i=0;$i<$count;$i++)
			{
				
				if($_POST['tax'][$i]==1)
				{
					$tax = 'CST';
					$taxPercentage = $_POST['cstTax'][$i];
				} else if($_POST['tax'][$i]==2)
				{
					$tax = 'VAT';
					$taxPercentage = $_POST['vatTax'][$i];
					
				}
				else if($_POST['tax'][$i]==3)
				{
					$tax = 'GST';
					$taxPercentage = $_POST['gstTax'][$i];
				}
				
 mysql_query("insert into  daily_reports_data (`revisionId`,  `categoryId`, `brandId`, `productId`, `price`, `quantity`, `amount`, `taxSystem`, `taxPercentage`, `taxAmount`) values ('". $lastId ."', '". $_POST['category'][$i] ."', '". $_POST['brand'][$i] ."', '". $_POST['product'][$i] ."', '". $_POST['price'][$i] ."', '". $_POST['quantity'][$i] ."', '". $_POST['amount'][$i] ."', '". $tax ."', '". $taxPercentage ."', '". $_POST['productTax'][$i] ."')");
			}
			
				
				
			}
			header("location: generate_offer.php?pid=".$_GET['pid']."&add=1");
		}

$proposals = mysql_query("select daily_reports.reportId,date_format(enquiries.dateTime,'%d-%m-%Y') as enqDate,date_format(daily_reports.offerDate,'%d-%m-%Y') as offerDate,daily_reports.offerNo, daily_reports.offerToBeSubmitted, daily_reports.enquiryNumber, daily_reports.currentRevisionId, daily_reports.reportDate, daily_reports.company, daily_reports.contactPerson, daily_reports.designation, daily_reports.phone, daily_reports.email, daily_reports.clientStatus, daily_reports.leadType, daily_reports.leadStatus, daily_reports.futureDate, daily_reports.remarks, daily_reports.poNo, daily_reports.poDateTime, daily_reports.paymentType, customers.company, employees.firstName from daily_reports
left join customers on daily_reports.company = customers.customerId
left join employees on daily_reports.addedBy = employees.id
left join enquiries on enquiries.enquiryNumber=daily_reports.enquiryNumber
where daily_reports.reportId = '". $_GET['pid'] ."'");


 

 
 $proposal = mysql_fetch_array($proposals);
 
 
$items = mysql_query("select categories.category, brands.brand, products.productId,daily_reports_data.modelId, products.product, daily_reports_data.id, daily_reports_data.categoryId, daily_reports_data.brandId, daily_reports_data.productId, daily_reports_data.price, daily_reports_data.quantity,  daily_reports_data.amount from 
 daily_reports_revision
 left join  daily_reports_data on daily_reports_revision.revisionId = daily_reports_data.revisionId
 left join categories on daily_reports_data.categoryId = categories.id
 left join brands on daily_reports_data.brandId = brands.id
 left join products on daily_reports_data.productId = products.productId
 
 where daily_reports_revision.reportId = '". $proposal['reportId'] ."' and daily_reports_revision.revision = '". $proposal['currentRevisionId'] ."'");
 
 

?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="offers.php">Offers</a>
							</li>

							<li class="active">Manage Offer</li>
						</ul><!-- /.breadcrumb -->

						<!-- #section:basics/content.searchbox -->
						<!--<div class="nav-search" id="nav-search">
							<form class="form-search">
								<span class="input-icon">
									<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
									<i class="ace-icon fa fa-search nav-search-icon"></i>
								</span>
							</form>
						</div>--><!-- /.nav-search -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- #section:settings.box -->
						<!-- /.ace-settings-container -->

						<!-- /section:settings.box -->
					

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								
								<div class="row">
									<div class="col-xs-12">
										
										<div class="table-header">
											Manage Offer
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										
											
                                           <?php
										   
	   if(isset($_GET['add']))
{ echo '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Offer  has been generated!</div>'; }
else if(isset($_GET['error']))
{ echo '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  
										   ?> 
                                
                                         
									<!-- #section:elements.form -->
                                      <table id="sample-table-1" class="table table-striped table-bordered table-hover">
											<tbody>
												 <tr>
                                                 <tr>
                                                   <td>
													 Offer No.
												   </td>
                                                   <td>
													 <?php echo $proposal['offerNo'];
                                                           
 													  ?>
												  </td>
												</tr>
                                                <tr>
                                                	<td> Offer Date</td>
                                                    <td> <?PHP echo $proposal['offerDate']; ?></td>
                                                </tr>                                                
                                                 <tr>
                                                   <td>
													 Report Date
												   </td>
                                                   <td><?php 
													 $reportDate = explode('-',$proposal['reportDate']);
                                                     $reportDate = $reportDate[2].'-'.$reportDate[1].'-'.$reportDate[0];  	
														   
														   if(strcmp('00-00-0000',$reportDate)!=0)
												{
													echo $reportDate;
												}												  
 													  ?>
												  </td>
												</tr>
                                                <tr>
                                                   <td>
													 Enquiry Number
												   </td>
                                                   <td>
													 <?php if($proposal['enquiryNumber']!='')
													 {
													  echo $proposal['enquiryNumber']; }
													  else
													  {
														  echo '--';
													  }
													  
													  ?>
												  </td>
												</tr>
                                                
                                                 <tr>
                                                   <td>
													 Enquiry Date
												   </td>
                                                   <td>
													 <?php 
													 if($proposal['enqDate']!='')
													  echo $proposal['enqDate'];
													  else
													  	echo "---";
													  
													  
													  ?>
												  </td>
												</tr>
                                                
                                                <tr>
                                                   <td>
													 Executive
												   </td>
                                                   <td>
													 <?php echo ucfirst($proposal['firstName']); ?>
												  </td>
												</tr>
                                                	<tr>
                                                   <td>
													 Company
												   </td>
                                                   <td>
													 <?php echo ucfirst($proposal['company']); ?>
												  </td>
												</tr>
                                                <tr>
                                                   <td>
													 Contact Person
												   </td>
                                                   <td>
													 <?php echo ucfirst($proposal['contactPerson']); ?>
												  </td>
												</tr>
                                                <tr>
                                                   <td>
													 Designation
												   </td>
                                                   <td>
													 <?php echo ucfirst($proposal['designation']); ?>
												  </td>
												</tr>
                                                <tr>
                                                   <td>
													 Email
												   </td>
                                                   <td>
													 <?php echo ucfirst($proposal['email']); ?>
												  </td>
												</tr>
                                                <tr>
                                                   <td>
													 Phone
												   </td>
                                                   <td>
													 <?php echo ucfirst($proposal['phone']); ?>
												  </td>
												</tr>
                                                <tr>
                                                   <td>
													 Client Status
												   </td>
                                                   <td>
													 <?php   if($proposal['clientStatus']==1)
													            {
																	echo 'Existing';
																}
																else if($proposal['clientStatus']==2)
																{
																	echo 'New';
																} ?>
												  </td>
												</tr>
                                                	        <tr>
                                                   <td>
													 Type
												   </td>
                                                   <td>
													 <?php if($proposal['leadType']==1) { echo 'General Enquiry'; }
															    else if($proposal['leadType']==2) { echo 'Customer Call'; }
																else if($proposal['leadType']==3) { echo 'Reference'; }
																else if($proposal['leadType']==4) { echo 'Others'; }
															
															
															 ?>	
												  </td>
												</tr>
                                                <tr>
                                                   <td>
													 Status
												   </td>
                                                   <td>
													 <?php echo $proposal['leadStatus'];
																
															
															
															 ?>
												  </td>
												</tr>
                                                <tr>
                                                   <td>
													 Future Date
												   </td>
                                                   <td>
													 <?php $fDate = explode('-',$proposal['futureDate']);
													   echo $fDate[2].'-'.$fDate[1].'-'.$fDate[0];
															
															
															 ?>
												  </td>
												</tr>
                                                <tr>
                                                   <td>
													 Remarks
												   </td>
                                                   <td>
													 <?php echo $proposal['remarks']; ?>
												  </td>
												</tr>

                                              
                                                <tr>
                                                   <td>
													 Payment Type
												   </td>
                                                   <td>
													  <?php if($proposal['paymentType']==1) { echo 'Proforma'; }
															    else if($proposal['paymentType']==2) { echo 'Through Bank'; }
																else if($proposal['paymentType']==3) { echo 'Direct Payment'; }
																else if($proposal['paymentType']==4) { echo 'Against Delivery'; }
															
															
															 ?>	
												  </td>
												</tr>
												

												
											</tbody>
										</table>
									
								
       
                                
                                
                                
                              <div class="row">
									<div class="col-xs-12">
										
										<div class="table-header">
											Items
										</div>
                          
                          
                          <?php
						  
						  if(strlen($proposal['poNo'])<1)
						  {
						  
						   ?>              
                                        
                                        <form action="" method="post">
                                       
                                        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
											<tbody>
                                            <thead>
                                            
                                            <th>S.no</th>
                                            <th>Category</th>
                                            <th>Brand</th>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Amount</th>
                                            </thead> 
                                     <?php
									 
									 $i = 1; $subTotal = 0;
									 while($item = mysql_fetch_array($items))
									 {
										 
							$currentDate = date('Y-m-d');  
					
							
$price = mysql_query("select price from product_price where productId = '". $item['productId'] ."' and ModelNo='".$item['modelId']."' and fromDate <= '$currentDate' order by autoId desc limit 1");			 

$price = mysql_fetch_array($price);

//$amount = $price['price']*$item['quantity'];

//echo $price['price']."<br>".$item['quantity']."<br>";

$amount = filter_var($price['price'], FILTER_SANITIZE_NUMBER_INT)*filter_var($item['quantity'], FILTER_SANITIZE_NUMBER_INT);
//exit; 
										 
										 $subTotal = $subTotal+$amount;
										 ?>
												<tr>
                                                 <td>   <?php     echo $i; ?>    </td>
                                                 <td>   <?php     echo $item['category']; ?>    </td>
                                                 <td>   <?php     echo $item['brand']; ?>    </td>
                                                 <td>   <?php     echo $item['product']; ?>    </td>
                                                 <td>   <?php     echo $item['quantity']; ?>    </td>
                   <td> 
                  
                  
                
                   <input type="hidden" name="category[]" value="<?php echo $item['categoryId']; ?>" /> 
                      <input type="hidden" name="brand[]" value="<?php echo $item['brandId']; ?>" /> 
                         <input type="hidden" name="product[]" value="<?php echo $item['productId']; ?>" />  
                           
                   <input type="hidden" name="item[]" value="<?php echo $item['id']; ?>"> 
                   <input type="hidden" id="<?php echo 'quantity'.$i; ?>" name="quantity[]"  value="<?php echo $item['quantity']; ?>">
<input type="hidden" name="price[]" id="<?php echo 'price'.$i; ?>" onkeyup="calAmount('<?php echo $i; ?>')" value="<?php echo $price['price']; ?>">
                   
                   <input type="text" disabled="disabled" value="<?php echo $price['price']; ?>">
            </td>
            <td>    <input type="hidden" name="amount[]" id="<?php echo 'amount'.$i; ?>" value="<?php echo $amount; ?>" onkeyup="getPrice('1')">
            
            <input type="text" disabled="disabled" value="<?php echo $amount; ?>" />    </td>
                                                 </tr>
                                                 <tr>
                                                 <td colspan="5"></td>
                                                 <td>
<select id="<?php echo "tax".$i; ?>" name="tax[]" onchange="changeTax('<?php echo $i; ?>',this.value)" style="float:left; margin-right:10px;">
                                                 <option value="0">Tax</option>
                                                 <option value="1">CST</option>
                                                 <option value="2">VAT</option>
                                                 <option value="3">GST</option>
                                                 </select>
                                                 
<select id="<?php echo "vatTax".$i; ?>" name="vatTax[]" style="display:none;" onchange="changeVatTax('<?php echo $i; ?>',this.value)">
                                                 <option value="0">VAT Tax</option>
                                                 <option value="14.5">14.5</option>
                                                 <option value="5">5</option>
                                                 </select>
                                                 
                                                 
                                                 <select id="<?php echo "cstTax".$i; ?>" name="cstTax[]" style="display:none;">
                                                 <option value="2">2</option>
                                                 </select>
                                                 
	<input type="text"  id="GstTax_<?PHP echo $i;?>" style="display:none" name="gstTax[]" onkeyup="changeVatTax('<?php echo $i; ?>',this.value)" />
                                                 
                                                 </td>
                                                 <td>
                                                 
                                                 <input type="hidden" id="<?php echo 'productTax'.$i; ?>" name="productTax[]" value="0" />
                                                 <input type="text" id="<?php echo 'product_Tax'.$i; ?>" disabled="disabled" value="0" />
                                                 
                                                
                                                 
                                                 </td>
                                                 
                                                 </tr>
                          
                                         <?php
										
										
								
								$i++;	 }
									 
									 
									 
									 ?>  
                                      <tr><td colspan="5"></td>
                                     <td>Sub Total</td>
                                     <td>
                                     
                                     <input id="numProducts" type="hidden" value="<?php echo $i-1; ?>" />
                                     
                                     <input type="hidden" name="offerToBeSubmitted" value="<?php echo $proposal['offerToBeSubmitted']; ?>" />
                                     <input type="hidden" name="currentRevisionId" value="<?php echo $proposal['currentRevisionId']; ?>" />
                                     
                                     
                                    
                                     <input type="hidden" name="subTotal" value="<?php echo $subTotal; ?>" /> 
                                     <input type="text" disabled="disabled" id="subTotal" value="<?php echo $subTotal; ?>" />
                                     </td></tr> 
                                     
                                     <tr><td colspan="5"></td>
                                     <td>Discount</td>
                                     <td>
<input type="text"  id="discount" name="discount" onchange="changeDisount(this.value)" value="<?php echo $discount=0; ?>" />
                                     </td></tr> 
                                     
                                     <tr><td colspan="5"></td>
                                     <td>Total</td>
                                     <td>
                                     <?php $total = $subTotal-$discount; ?>
                                     <input type="hidden" id="total" name="total" value="<?php echo $total; ?>" />
                                     <input type="text" disabled="disabled" id="total1"  value="<?php echo $total; ?>" />
                                     
                                 <?php $duty = ($total*12.5)/100;
								       $pf = ($total*3)/100;
									   $grandTotal = $total+$duty+$pf;
									   
									         ?>
                                     
                                     </td></tr> 
                                     
                                     <tr><td colspan="5"></td>
                                     <td>Duty 12.5%</td>
                                     <td>
                                     <input type="hidden"  id="dutyPercentage" name="dutyPercentage" value="<?php echo '12.5'; ?>" />
                                     <input type="hidden" id="duty" name="duty" value="<?php echo $duty; ?>" />
                                     <input type="text" disabled="disabled" id="duty1" value="<?php echo $duty; ?>" />
                                     </td></tr> 
                                     
                                     
                                     <tr><td colspan="5"></td>
                                     <td>P&F 3%</td>
                                     <td>
                                     <input type="hidden"  id="pfPercentage" name="pfPercentage" value="<?php echo '3'; ?>" />
                                     <input type="hidden" id="pf" name="pf" value="<?php echo $pf; ?>" />
                                     <input type="text" disabled="disabled" id="pf1"  value="<?php echo $pf; ?>" />
                                     </td></tr> 
                                     
                                       <tr><td colspan="5"></td>
                                     <td>Grand Total</td>
                                     <td>
                                      <input type="hidden" id="num" value="<?php echo $i;  ?>"  />
                                      <input type="hidden" name="reportId" value="<?php echo $proposal['reportId'];  ?>"  />
                                      <input type="hidden"  id="grandTotal" name="grandTotal" value="<?php echo $grandTotal; ?>" />
                                      <input type="text" disabled="disabled" id="grandTotal1" value="<?php echo $grandTotal; ?>" />
                                     </td></tr> 
                                     
                                     
                                     <?php //if(strlen($proposal['offerNo'])<3) { ?>
                                      <tr><td colspan="5"></td>
                                      <td>
                                     
                                  Offer Number
                                    
                                     </td> 
                                     <td>
                                      <input type="text" name="offerNo" id="offerNo"  placeholder="Offer Number" /></td>
                                   </tr> 
                                   
                                   <tr><td colspan="5"></td>
                                      <td>
                                     
                                  Offer Date
                                    
                                     </td> <td>
<input type="text" name="offerDate" id="offerDate" class="col-xs-10 col-sm-12 date-picker" placeholder="Offer Date" /></td>
                                   </tr>
                                   
                                   <!--<tr><td colspan="5"></td>
                                      <td>
                                     
                                  Delivery Acceptance Date
                                    
                                     </td> <td>
                    <input type="text" name="delAcceptanceDate" id="delAcceptanceDate" class="col-xs-10 col-sm-12 date-picker" placeholder="Delivery Acceptance Date" />
                    </td>
                                   </tr>
                                   
                                   <tr><td colspan="5"></td>
                                      <td>
                                     
                                  Payment Terms
                                    
                                     </td> <td>
                                     <textarea name="paymentTerms" id="paymentTerms"></textarea>
                                   </tr>-->
                                      <tr><td colspan="5"></td>
                                     <td></td>
                                     <td>
                                     
                                     <button class="btn btn-sm btn-info" type="submit" name="submitOffer">
												Submit Offer
											</button>
                                            
                                            
                                            
                                     </td></tr> 
                                   
                                   
                                   
                                   <?php // } ?>
                                     
                                    
                                        
                                              </tbody>
                                </table>
                                        
                                        </form> 
                                        <?php }  ?>
                                        
                                        
                                        
                                        
                                        </div>
                                        </div>                  
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<div class="footer">
				<div class="footer-inner">
					<!-- #section:basics/footer -->
					<div class="footer-content">
						<span class="">
							<span class="orange bolder"><img src="assets/images/smarterp.png" width="90"  /></span>

 <a href="http://www.trillionit.com/" target="_blank">Trillion IT</a> &copy; 2017
						</span>
					</div>

					<!-- /section:basics/footer -->
				</div>
			</div>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='assets/js/jquery.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="assets/js/bootstrap.js"></script>


		<!-- page specific plugin scripts -->
		<script src="assets/js/jquery.dataTables.js"></script>
		<script src="assets/js/jquery.dataTables.bootstrap.js"></script>
        
         <script src="assets/js/jquery.validate.js"></script>

		<!-- ace scripts -->
		<script src="assets/js/ace/elements.scroller.js"></script>
		<script src="assets/js/ace/elements.colorpicker.js"></script>
		<script src="assets/js/ace/elements.fileinput.js"></script>
		<script src="assets/js/ace/elements.typeahead.js"></script>
		<script src="assets/js/ace/elements.wysiwyg.js"></script>
		<script src="assets/js/ace/elements.spinner.js"></script>
		<script src="assets/js/ace/elements.treeview.js"></script>
		<script src="assets/js/ace/elements.wizard.js"></script>
		<script src="assets/js/ace/elements.aside.js"></script>
		<script src="assets/js/ace/ace.js"></script>
		<script src="assets/js/ace/ace.ajax-content.js"></script>
		<script src="assets/js/ace/ace.touch-drag.js"></script>
		<script src="assets/js/ace/ace.sidebar.js"></script>
		<script src="assets/js/ace/ace.sidebar-scroll-1.js"></script>
		<script src="assets/js/ace/ace.submenu-hover.js"></script>
		<script src="assets/js/ace/ace.widget-box.js"></script>
		<script src="assets/js/ace/ace.settings.js"></script>
		<script src="assets/js/ace/ace.settings-rtl.js"></script>
		<script src="assets/js/ace/ace.settings-skin.js"></script>
		<script src="assets/js/ace/ace.widget-on-reload.js"></script>
		<script src="assets/js/ace/ace.searchbox-autocomplete.js"></script>
        
        
        
        <script src="assets/js/date-time/bootstrap-datepicker.js"></script>
		<script src="assets/js/date-time/bootstrap-timepicker.js"></script>
		<script src="assets/js/date-time/moment.js"></script>
		<script src="assets/js/date-time/daterangepicker.js"></script>
		<script src="assets/js/date-time/bootstrap-datetimepicker.js"></script>

 <script>
 
 function changeTax(id,val)
 {
	 
	 if(val==1)
	 {
		
		document.getElementById('vatTax'+id).style.display = 'none'; 
		document.getElementById('cstTax'+id).style.display = 'block'; 
		
		// cst caliculate product tax
		var amount = parseInt(document.getElementById('amount'+id).value);
		var cst = parseInt(document.getElementById('cstTax'+id).value);
		var productTax = (amount*cst)/100;
		
		document.getElementById('productTax'+id).value = productTax; 
		document.getElementById('product_Tax'+id).value = productTax; 
		
		// grand total
		var numProducts = document.getElementById('numProducts').value;
	
	var vat = 0;
	
	for(var i = 1;i<= numProducts;i++)
	{
	   	 vat =  parseInt(document.getElementById('productTax'+i).value)+parseInt(vat); 
	}
	
	 
	 var subTotal =  document.getElementById('subTotal').value; 
	 
	 
	 var discount = (subTotal*val)/100;
	 var total = parseInt(subTotal)-parseInt(discount); 
	 document.getElementById('total').value = total;
	 document.getElementById('total1').value = total;
	 
	 var duty = (total*12.5)/100;
	 document.getElementById('duty').value = duty; 
	 document.getElementById('duty1').value = duty; 
	 
	/* var vat = (total*5)/100;
	 document.getElementById('vat').value = vat; 
	 document.getElementById('vat1').value = vat; */
	 
	 var pf = (total*3)/100;
	 document.getElementById('pf').value = pf; 
	 document.getElementById('pf1').value = pf; 
	 
	 
	 
	 var grandTotal = parseInt(total)+parseInt(duty)+parseInt(vat)+parseInt(pf); 
	  
	 document.getElementById('grandTotal').value = grandTotal; 
	 document.getElementById('grandTotal1').value = grandTotal; 
	 }
	 else if(val==2)
	 {   
		document.getElementById('cstTax'+id).style.display = 'none'; 
		document.getElementById('vatTax'+id).style.display = 'block'; 
	 }
	 else if(val==3)
	 {
		
		
		document.getElementById('vatTax'+id).style.display = 'none'; 
		document.getElementById('cstTax'+id).style.display = 'none'; 
		
		
		document.getElementById('GstTax_'+id).style.display = 'block'; 
	 
	 }
 }
 
 function changeVatTax(id,val)
 {
	 
	 // cst caliculate product tax
		var amount = parseInt(document.getElementById('amount'+id).value);
	    var productTax = (amount*val)/100;
		
		document.getElementById('productTax'+id).value = productTax; 
		document.getElementById('product_Tax'+id).value = productTax; 
		
		// grand Total
		var numProducts = document.getElementById('numProducts').value;
	
	var vat = 0;
	
	for(var i = 1;i<= numProducts;i++)
	{
	   	 vat =  parseInt(document.getElementById('productTax'+i).value)+parseInt(vat); 
	}
	
	 
	 var subTotal =  document.getElementById('subTotal').value; 
	 
	 
	 var discount = (subTotal*val)/100;
	 var total = parseInt(subTotal)-parseInt(discount); 
	 document.getElementById('total').value = total;
	 document.getElementById('total1').value = total;
	 
	 var duty = (total*12.5)/100;
	 document.getElementById('duty').value = duty; 
	 document.getElementById('duty1').value = duty; 
	 
	/* var vat = (total*5)/100;
	 document.getElementById('vat').value = vat; 
	 document.getElementById('vat1').value = vat; */
	 
	 var pf = (total*3)/100;
	 document.getElementById('pf').value = pf; 
	 document.getElementById('pf1').value = pf; 
	 
	 var grandTotal = parseInt(total)+parseInt(duty)+parseInt(vat)+parseInt(pf); 
	  
	 document.getElementById('grandTotal').value = grandTotal; 
	 document.getElementById('grandTotal1').value = grandTotal; 
	 
 }
 
 function changeDisount(val)
 {
	

	
	var numProducts = document.getElementById('numProducts').value;
	
	var vat = 0;
	
	for(var i = 1;i<= numProducts;i++)
	{
	   	 vat =  parseInt(document.getElementById('productTax'+i).value)+parseInt(vat); 
	}
	
	 
	 var subTotal =  document.getElementById('subTotal').value; 
	 
	 
	 var discount = (subTotal*val)/100;
	 var total = parseInt(subTotal)-parseInt(discount); 
	 document.getElementById('total').value = total;
	 document.getElementById('total1').value = total;
	 
	 var duty = (total*12.5)/100;
	 document.getElementById('duty').value = duty; 
	 document.getElementById('duty1').value = duty; 
	 
	/* var vat = (total*5)/100;
	 document.getElementById('vat').value = vat; 
	 document.getElementById('vat1').value = vat; */
	 
	 var pf = (total*3)/100;
	 document.getElementById('pf').value = pf; 
	 document.getElementById('pf1').value = pf; 
	 
	 var grandTotal = parseInt(total)+parseInt(duty)+parseInt(vat)+parseInt(pf); 
	  
	 document.getElementById('grandTotal').value = grandTotal; 
	 document.getElementById('grandTotal1').value = grandTotal; 
 }
 
 function getCompanyDetails(cid)
 {

	 
	 
	  $.ajax({url: "ajax/getCompanyDetails.php?cid="+cid, success: function(result){
        $("#div1").html(result);
    }});
	 
 }
 
 function openList()
 {
	
	if(document.getElementById('po').checked)
	{
		document.getElementById('formContainer').style.display = 'block';	
		document.getElementById('addRowSpan').style.display = 'block';	
		document.getElementById('removeRowSpan').style.display = 'block';	
	}
	else
	{
	   document.getElementById('formContainer').style.display = 'none';	
	   document.getElementById('addRowSpan').style.display = 'none';	
		document.getElementById('removeRowSpan').style.display = 'none';	
	}
 }
 
/* function getProducts(id)
 {  alert('hiii');
 $.ajax({url: "ajax/getCompanyDetails.php?cid="+cid, success: function(result){
        $("#div1").html(result);
    }});
	 
 }*/
 
 function displayFields(id)
 {
	 document.getElementById("addRow").value = parseInt(id)+1;
	  document.getElementById("removeRow").value = parseInt(id)+1;
	  
	  id = parseInt(id)+1;
	  
	 $.ajax({url: "ajax/getFields.php?id="+id, success: function(result){
        $("#formContainer").append(result);
    }});
	
 }
 
  function removeFields(id)
 {
	 document.getElementById("addRow").value = parseInt(id)-1;
	  document.getElementById("removeRow").value = parseInt(id)-1;
	// document.getElementById("pr"+id).value = parseInt(id)-1;
	 document.getElementById("pr"+id).remove();
	
 }
 
 function getBrands(rid)
 {
	 
	
	var category = document.getElementById("category"+rid).value;
	
	
	if(category==0)
	{  
		document.getElementById("category"+rid).focus();
	}
	else 
	{
	    $.ajax({url: "ajax/getBrandsList.php?cid="+category, success: function(result){
        $("#brand"+rid).html(result);
    }});
	}

	 
 }
 
 function getProducts(rid)
 {
	 
	 
	var category = document.getElementById("category"+rid).value;
	var brand =  document.getElementById("brand"+rid).value;
	
	if(category==0)
	{  
		document.getElementById("category"+rid).focus();
	}
	else if(brand==0)
	{ 
		document.getElementById("brand"+rid).focus();
	}
	else if(category>0 && brand>0)
	{
	    $.ajax({url: "ajax/getProductsList.php?bid="+brand+"&cid="+category, success: function(result){
        $("#product"+rid).html(result);
    }});
	}

	 
 }
 
 function getProductPrice(rid,pid)
 {
	 
	  
	   $.ajax({url: "ajax/getProductDetails.php?rid="+rid+"&pid="+pid, success: function(result){
        $("#productdetails"+rid).html(result);
		
		document.getElementById('total').value = parseInt(document.getElementById('total').value)+parseInt(document.getElementById('amount'+rid).value);
    }});
	  
 }
 
 function calAmount(rid)
 {
	 
	 var quantity = document.getElementById("quantity"+rid).value;
	 var price =  document.getElementById("price"+rid).value;
	 document.getElementById("amount"+rid).value = parseInt(quantity)*parseInt(price);
	 
	 
	 var num = document.getElementById('num').value;
	 var total = 0;
     for(var i = 1; i<num; i++)
	 {
	   
	   	total = parseInt(document.getElementById('amount'+i).value)+parseInt(total); 
	 }

   document.getElementById("grandTotal").value = total;
	 
 }
 
 


 </script>


		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
				var oTable1 = 
				$('#sample-table-2')
				//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
				.dataTable( {
					bAutoWidth: false,
					"aoColumns": [
					  { "bSortable": false },
					  null, null,null, null, null,
					  { "bSortable": false }
					],
					"aaSorting": [],
			
					//,
					//"sScrollY": "200px",
					//"bPaginate": false,
			
					//"sScrollX": "100%",
					//"sScrollXInner": "120%",
					//"bScrollCollapse": true,
					//Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
					//you may want to wrap the table inside a "div.dataTables_borderWrap" element
			
					//"iDisplayLength": 50
			    } );
				/**
				var tableTools = new $.fn.dataTable.TableTools( oTable1, {
					"sSwfPath": "../../copy_csv_xls_pdf.swf",
			        "buttons": [
			            "copy",
			            "csv",
			            "xls",
						"pdf",
			            "print"
			        ]
			    } );
			    $( tableTools.fnContainer() ).insertBefore('#sample-table-2');
				*/
				
				
				//oTable1.fnAdjustColumnSizing();
			
			
				$(document).on('click', 'th input:checkbox' , function(){
					var that = this;
					$(this).closest('table').find('tr > td:first-child input:checkbox')
					.each(function(){
						this.checked = that.checked;
						$(this).closest('tr').toggleClass('selected');
					});
				});
			
			
				$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				function tooltip_placement(context, source) {
					var $source = $(source);
					var $parent = $source.closest('table')
					var off1 = $parent.offset();
					var w1 = $parent.width();
			
					var off2 = $source.offset();
					//var w2 = $source.width();
			
					if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
					return 'left';
				}
			
			// datepicker
			$('.date-picker').datepicker({
					autoclose: true,
					todayHighlight: true,
					format: 'dd-mm-yyyy'
					
				})
			
				// validateion
				
				$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					ignore: "",
					rules: {
						date: {
							required: true,
							
						},
						company: {
							required: true,
							
						},
						contactPerson: {
							required: true,
							
						},
						designation: {
							required: true,  
							
						},
						email: {
							required: true,
							email:true
							
						},
						phone: {
							required: true,
							
						},
						leadType: {
							required: true,
							
						},
						leadStatus: {
							required: true,
							
						},
						paymentType: {
							required: true,
							
						}
					},
			
					messages: {
						email: {
							required: "Please provide a valid email.",
							email: "Please provide a valid email."
						}
					},
			
			
					highlight: function (e) {
						$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
					},
			
					success: function (e) {   
						$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
						$(e).remove();
					}
			
				
				});
				// validation
				
		})
		</script>

		<!-- the following scripts are used in demo only for onpage help and you don't need them -->
		<link rel="stylesheet" href="assets/css/ace.onpage-help.css" />
		<link rel="stylesheet" href="docs/assets/js/themes/sunburst.css" />

		<script type="text/javascript"> ace.vars['base'] = '..'; </script>
		<script src="assets/js/ace/elements.onpage-help.js"></script>
		<script src="assets/js/ace/ace.onpage-help.js"></script>
		<script src="docs/assets/js/rainbow.js"></script>
		<script src="docs/assets/js/language/generic.js"></script>
		<script src="docs/assets/js/language/html.js"></script>
		<script src="docs/assets/js/language/css.js"></script>
		<script src="docs/assets/js/language/javascript.js"></script>
	</body>
</html>
