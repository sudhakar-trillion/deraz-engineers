<?php  include("includes/header.php"); 

  // generate Invoice
		if(isset($_POST['generateInvoice']))
		{
		   mysql_query("update daily_reports set inv = '1', invoice = '". $_POST['invoice'] ."', invoiceDateTime = NOW() where reportId = '". $_GET['pid'] ."'");
		   header("location: generate_invoice.php?pid=".$_GET['pid']."&success=1");     
		   
		}
		
// this total data comes from daily_reports, customers, employees, invoices, enquiries
$proposals = mysql_query("select daily_reports.reportId,daily_reports.runcard,date_format(daily_reports.offerDate,'%d-%m-%Y') as offerDate, daily_reports.offerNo as offerNo, date_format(daily_reports.poDateTime,'%d-%m-%y') as poDateTime, date_format(daily_reports.proformaInvoiceDateTime,'%d-%m-%Y') as proformaInvoiceDateTime,  invoices.proInvoiceNumber,daily_reports.runcard, daily_reports.enquiryNumber, daily_reports.paymentTerms, daily_reports.offerNo, daily_reports.poNo ,daily_reports.reportDate, daily_reports.currentRevisionId, daily_reports.company, daily_reports.contactPerson, daily_reports.designation, daily_reports.phone, daily_reports.email, daily_reports.clientStatus, daily_reports.leadType, daily_reports.leadStatus, daily_reports.futureDate, daily_reports.remarks, daily_reports.poNo, daily_reports.poDateTime, daily_reports.paymentType, daily_reports.inv, daily_reports.invoice, daily_reports.invoiceDateTime, customers.company, employees.firstName, date_format(enquiries.dateTime,'%d-%m-%Y') as enqdt from daily_reports
left join customers on daily_reports.company = customers.customerId
left join employees on daily_reports.addedBy = employees.id
left join invoices on daily_reports.reportId = invoices.reportId
left join enquiries on enquiries.enquiryNumber = daily_reports.enquiryNumber
where daily_reports.reportId = '". $_GET['pid'] ."'");


 $proposal = mysql_fetch_array($proposals);
 
$runcard = $proposal['runcard']; 



 $revision = mysql_query("select * from daily_reports_revision where reportId = '". $proposal['reportId'] ."' and revision = '". $proposal['currentRevisionId'] ."'");
 $revision = mysql_fetch_array($revision);
 
 // get data from categories, brands, products, daily_reports_data
 $items = mysql_query("select categories.category,products.productId,brands.brand, products.productId,product_model.ModelNo, products.product, products.code, daily_reports_data.id, daily_reports_data.price, daily_reports_data.quantity,  daily_reports_data.amount, daily_reports_data.taxSystem, daily_reports_data.taxPercentage, daily_reports_data.taxAmount from daily_reports_data 
 left join categories on daily_reports_data.categoryId = categories.id
 left join brands on daily_reports_data.brandId = brands.id
  left join product_model on product_model.ModelId=daily_reports_data.modelId
 left join products on daily_reports_data.productId = products.productId
 
 where daily_reports_data.revisionId = '". $revision['revisionId'] ."'");



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
								<a href="offers.php">PO'S</a>
							</li>

							<li class="active">Manage Offer</li>
						</ul><!-- /.breadcrumb -->

						<!-- #section:basics/content.searchbox -->
						<!-- /.nav-search -->

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
											View Po
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										
											
                                           <?php
										   
	   if(isset($_GET['add']))
{ echo '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Report has been added!</div>'; }
else if(isset($_GET['error']))
{ echo '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }
										   ?> 
                                            
                                           
                                            
                              
                                            
                                           
                                
                                         
									<!-- #section:elements.form -->
                                      <table id="sample-table-1" class="table table-striped table-bordered table-hover">
											<tbody>
												
											  <tr>
                                                   <td>
													 Offer Number
												   </td>
                                                   <td>
													 <?php echo $proposal['offerNo'];
                                                           
 													  ?>
												  </td>
												</tr>
                                           <tr>
                                           <tr>
                                           	<td>Offer Date</td>
                                            <td><?PHP echo $proposal['offerDate'];?> </td>
                                           </tr>
                                           
                                                   <td>
													 Proforma Invoice 
												   </td>
                                                   <td>
													 <?php echo $proposal['proInvoiceNumber'];
                                                           
 													  ?>
												  </td>
												</tr>     
                                                
                                                
                                                 <tr>
                                           <tr>
                                           	<td> Proforma Invoice Date</td>
                                            <td><?PHP echo $proposal['proformaInvoiceDateTime'];?> </td>
                                           </tr>
                                                
                                  <tr>
                                                   <td>
													 PO Number
												   </td>
                                                   <td>
													 <?php echo $proposal['poNo'];
                                                           
 													  ?>
												  </td>
												</tr>   
                                                
   <tr>
                                                   <td>
													 PO Date
												   </td>
                                                   <td>
													 <?php echo $proposal['poDateTime'];
                                                           
 													  ?>
												  </td>
												</tr> 
                                                <tr>
                                                	<td> Enquiry Date</td>
                                                    <td><?php if($proposal['enqdt']!='')
													 {
													  echo $proposal['enqdt']; }
													  else
													  {
														  echo '--';
													  }
													  
													  ?>
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
													Runcard Number
												   </td>
                                                   <td>
													 <?php echo $proposal['runcard'];
                                                           
 													  ?>
												  </td>
												</tr>
                                                <tr>
                                                   <td>
													Payment Terms
												   </td>
                                                   <td>
													 <?php echo $proposal['paymentTerms'];
                                                           
 													  ?>
												  </td>
												</tr>
                                                 
                                            	
                                                 <tr>
                                                   <td>
													 Report Date
												   </td>
                                                   <td>
													 <?php
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
                                                   <td><?php echo $proposal['leadStatus']; ?></td>
												</tr>
                                                <tr>
                                                   <td>
													 Followup Date
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
                          
                          
                                    
                                       
                                       
                                        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
											<tbody>
                                            <thead>
                                            
                                            <th>S.no</th>
                                            <th>Category</th>
                                            <th>Brand</th>
                                            <th>Product</th>
                                            <th>Model No</th>
                                            <th>Exstock </th>
                                            <th>Purchase Po Number</th>
                                            
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Amount</th>
                                            </thead> 
                                     <?php
									 
									 $i = 1; $grandTotal = 0;
									 while($item = mysql_fetch_array($items))
									 {
									
									//check whether the product, model with the report id isinstockor not
									
	$exist_not = mysql_query("select * from runcards where  (ReportId=".$_GET['pid']." and RunCard='".$runcard."' ) and (ProductId=".$item['productId']." and ModelId='".$item['ModelNo']."') " );

	if(mysql_num_rows($exist_not)>0)
	{
		$chk = mysql_fetch_object($exist_not);
		$Exstock = $chk->Exstock;
		$PurchaseNo = $chk->PurchaseNo;
		
	}
	else
	{
		$Exstock = "Not available";
		$PurchaseNo = '---';	
	}

	?>
                                         <tr>
                                                 <td>   <?php     echo $i; ?>    </td>
                                                 <td>   <?php     echo $item['category']; ?>    </td>
                                                 <td>   <?php     echo $item['brand']; ?>    </td>
                                                 <td>   <?php     echo $item['product']; ?>    </td>
                                                 <td>   <?php     echo $item['ModelNo']; ?>    </td>
                                                 <td> 	<?PHP 	  echo $Exstock;?></td>
                                                 <td> 	<?PHP 	  echo $PurchaseNo;?></td>
                                                 <td>   <?php     echo $item['quantity']; ?>    </td>
                   <td> 
                  
                   <input type="hidden" name="item[]" value="<?php echo $item['id']; ?>"> 
                   <input type="hidden" id="<?php echo 'quantity'.$i; ?>"  value="<?php echo $item['quantity']; ?>">
              <?php echo $item['price']; ?>    </td>
            <td>  <?php echo $item['amount']; ?>    </td>
                                                 </tr>
                                                 
                                                 <tr><td colspan="6"></td><td><?php echo $item['taxSystem'].' '.$item['taxPercentage'].'%'; ?></td><td><?php echo $item['taxAmount']; ?></td></tr>
                          
                                         <?php
										
										
								
								$i++;	 }
									 
									 
									 
									 ?>  
                                    
                                       <tr><td colspan="6"></td><td>Sub Total</td><td><?php echo $revision['subTotal']; ?></td></tr> 
                                       <tr><td colspan="6"></td><td>Discount</td><td><?php echo $revision['discount'].' %'; ?></td></tr> 
                                       <tr><td colspan="6"></td><td>Total</td><td><?php echo $revision['total']; ?></td></tr> 
                                       <tr><td colspan="6"></td><td>Duty</td><td><?php echo $revision['duty']; ?></td></tr> 
                                       <tr><td colspan="6"></td><td>PF</td><td><?php echo $revision['pf']; ?></td></tr>  
                                       <tr><td colspan="6"></td><td>Grand Total</td><td><?php echo $revision['grandTotal']; ?></td></tr> 
                                   
                                        
                                              </tbody>
                                </table>
                                        
                                    
                                       
                                        
                                     
                                     
                                     

                                    	 
                                    
                                        
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
					todayHighlight: true
					
				})
				
				// validateion
				
				$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					ignore: "",
					rules: {
						invoice: {
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
