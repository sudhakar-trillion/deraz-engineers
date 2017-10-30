<?php  include("includes/header.php"); 

  // generate Proforma Invoice if leadStatus is pending proforma invoice
		if(isset($_POST['generateProformaInvoice']))
		{
			$status = 'Pending Invoice';
		  
		  $proformaInvoiceDateTime = $_POST['proformaInvoiceDateTime'];
				$proformaInvoiceDateTime1 = date_create($proformaInvoiceDateTime);
				$proformaInvoiceDateTime1 = date_format($proformaInvoiceDateTime1,'Y-m-d');
				
				
		 mysql_query("update daily_reports set proformaInvoiceDateTime = '". $proformaInvoiceDateTime1 ."', leadStatus = '". $status ."', inv = '1'  where reportId = '". $_GET['pid'] ."'");
		
		
		
		mysql_query("insert into invoices (`reportId`, `subTotal`, `discount`, `total`, `dutyPercentage`, `dutyAmount`, `pfPercentage`, `pfAmount`, `grandTotal`, `proInvoiceNumber`, `proInvoiceDateTime`, `paymentStatus`, `invoiceStatus`) values('". $_GET['pid'] ."',  '". $_POST['subTotal'] ."', '". $_POST['discount'] ."', '". $_POST['total'] ."', '". $_POST['dutyPercentage'] ."', '". $_POST['dutyAmount'] ."', '". $_POST['pfPercentage'] ."', '". $_POST['pfAmount'] ."', '". $_POST['grandTotal'] ."', '". $_POST['proformaInvoice'] ."', NOW(), 'open', '". $status ."')");   
		   $lastId = mysql_insert_id();
		   
		   if($lastId>0)
		   {
		   $count = count($_POST['itemId']);
		   
		   for($i=0;$i<$count;$i++)
		   {
			   
			   mysql_query("update daily_reports_data set invoice_id = '". $lastId ."' where id = '". $_POST['itemId'][$i] ."'");
		   }
		   }
		   
		   header("location: generate_proinvoice.php?pid=".$_GET['pid']."&success=1");     
		   
		}
		
  // generate Invoice
	/*	if(isset($_POST['generateInvoice']))
		{
			$status = 'Invoice generated';
		   mysql_query("update daily_reports set leadStatus = '". $status ."', inv = '2', invoice = '". $_POST['invoice'] ."', invoiceDateTime = NOW() where reportId = '". $_GET['pid'] ."'");
		   header("location: generate_invoice.php?pid=".$_GET['pid']."&success=1");     
		   
		}		
*/
//in this we will join tables customers, employees, enquiries, daily_reports.
$proposals = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId,date_format(enquiries.dateTime,'%d-%m-%Y') as enqDate,date_format(daily_reports.offerDate,'%d-%m-%Y') as offerDate,daily_reports.offerNo, date_format(daily_reports.proformaInvoiceDateTime,'%d-%m-%Y') as proformaInvoiceDateTime, daily_reports.enquiryNumber, daily_reports.reportDate, daily_reports.company, daily_reports.contactPerson, daily_reports.designation, daily_reports.phone, daily_reports.email, daily_reports.clientStatus, daily_reports.leadType, daily_reports.leadStatus, daily_reports.futureDate, daily_reports.remarks, daily_reports.poNo, daily_reports.poDateTime, daily_reports.paymentType, daily_reports.inv, daily_reports.invoice, daily_reports.invoiceDateTime, customers.company, employees.firstName from daily_reports
left join customers on daily_reports.company = customers.customerId
left join employees on daily_reports.addedBy = employees.id
left join enquiries on enquiries.enquiryNumber=daily_reports.enquiryNumber
where daily_reports.reportId = '". $_GET['pid'] ."'");
 
 
 $proposal = mysql_fetch_array($proposals);
 
// we using table here daily_reports_revision. 
 $revision = mysql_query("select revisionId, subTotal, discount, duty, dutyPercentage, pf, pfPercentage, total, grandTotal  from daily_reports_revision where reportId = '". $proposal['reportId'] ."' and revision = '". $proposal['currentRevisionId'] ."'");
 
  $revision = mysql_fetch_array($revision);
 
// get the data from products table by joining daily_reports_data and products.  
 $items = mysql_query("select categories.category, brands.brand, products.productId, products.product, daily_reports_data.id, daily_reports_data.price, daily_reports_data.quantity, daily_reports_data.amount, daily_reports_data.taxSystem, daily_reports_data.taxPercentage, daily_reports_data.taxAmount, daily_reports_data.invoice_id , pm.ModelNo
from daily_reports_data
left join categories on daily_reports_data.categoryId = categories.id
left join brands on daily_reports_data.brandId = brands.id
left join products on daily_reports_data.productId = products.productId
left join product_model as pm on pm.ModelId=daily_reports_data.modelId
where daily_reports_data.revisionId = '". $revision['revisionId'] ."'");
 
/* 
 $items = mysql_query("select categories.category, brands.brand, products.productId, products.product, daily_reports_data.id, daily_reports_data.price, daily_reports_data.quantity,  daily_reports_data.amount from  daily_reports_revision
 left join daily_reports_data on daily_reports_revision.revisionId = daily_reports_data.revisionId
 left join categories on daily_reports_data.categoryId = categories.id
 left join brands on daily_reports_data.brandId = brands.id
 left join products on daily_reports_data.productId = products.productId
 
 where daily_reports_revision.reportId = '". $proposal['reportId'] ."' and daily_reports_revision.revision = '". $proposal['currentRevisionId'] ."'");*/

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
								<a href="offers.php">Proposals</a>
							</li>

							<li class="active">Manage Proposal</li>
						</ul><!-- /.breadcrumb -->

						
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
											Manage Proposal
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
                                                 <tr>
                                                   <td>
													 Proforma Invoice
												   </td>
                                                   <td>
													 <?php 
													 
													/*  $invoiceDate = explode(' ',$proposal['invoiceDateTime']); 
														 $invoiceDate = explode('-',$invoiceDate[0]); 
												 $invoiceDate = $invoiceDate[2].'-'.$invoiceDate[1].'-'.$invoiceDate[0];
												
												if(strcmp('00-00-0000',$invoiceDate)!=0)
												{
													echo $invoiceDate;
												}*/
												
												$proInvoices = mysql_query("select proInvoiceNumber from invoices where reportId = '". $_GET['pid'] ."'");
												
												if(mysql_num_rows($proInvoices)>0)
												{
												 while($proInvoice = mysql_fetch_array($proInvoices))
												 {
													 $pro_inv[] = $proInvoice['proInvoiceNumber']; 
												 }
													
												}
												
												if(count($pro_inv)==1)
												{
													echo $pro_inv[0];
												} else if(count($pro_inv)>1)
												{
												 
													$pro_inv = implode(', ',$pro_inv);
													echo $pro_inv;
												}
											               
 													  ?>
												  </td>
												</tr>
                                                <tr> 
                                                	<td> Proformainvoice Date</td>
                                                    <td> <?PHP echo $proposal['proformaInvoiceDateTime']; ?> </td>
                                                </tr>	
                                                <tr>
                                                   <td>
													 Proposal No.
												   </td>
                                                   <td>
													 <?php echo $proposal['poNo'];
                                                           
 													  ?>
												  </td>
												</tr>
                                                <tr>
                                                   <td>
													 Proposal Date
												   </td>
                                                   <td>
													 <?php 
													 
													 $poDate = explode(' ',$proposal['poDateTime']); 
													 $poDate = explode('-',$poDate[0]); 
												     $poDate = $poDate[2].'-'.$poDate[1].'-'.$poDate[0];
												
												if(strcmp('00-00-0000',$poDate)!=0)
												{
													echo $poDate;
												}
											               
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
													 <?php if($proposal['enqdate']!='')
													 {
													  echo $proposal['enqdate']; }
													  else
													  {
														  echo '--';
													  }
													  
													  ?>
												  </td>
												</tr>
                                                
                                                <tr>
                                                	<td> Offer Number</td>
                                                    <td><?PHP echo $proposal['offerNo'];?> </td>
                                                </tr>
                                                <tr>
                                                	<td> Offer Date</td>
                                                    <td><?PHP echo $proposal['offerDate'];?> </td>
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
											PO
										</div>
                          
                              <table id="sample-table-1" class="table table-striped table-bordered table-hover">
											<tbody>
                                            <thead>
                                            
                                            <th>S.no</th>
                                            <th>Category</th>
                                            <th>Brand</th>
                                            <th>Product</th>
                                            <th> Model</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Amount</th>
                                            </thead> 
                                     <?php
									 
									 $i = 1; //$grandTotal = 0;
									 while($item = mysql_fetch_array($items))
									 {
										 
/*							$currentDate = date('Y-m-d');  
					
							
$price = mysql_query("select price from product_price where productId = '". $item['productId'] ."' and fromDate <= '$currentDate' order by autoId desc limit 1");			 

$price = mysql_fetch_array($price);

$amount = $price['price']*$item['quantity'];
										 
										 $grandTotal = $grandTotal+$amount;*/
										 ?>
                                         
                                           
												<tr>
       <td>
       
       
      <?php if($item['invoice_id']==0) { ?> 
       <input type="checkbox" name="items[]"  onclick="addItem('<?php echo $item['id']; ?>')" id="<?php echo $item['id']; ?>" value="<?php echo $i; ?>" /> <?php echo $i; ?>
       <?php } ?>
       
       </td>
                                                 <td>   <?php     echo $item['category']; ?>    </td>
                                                 <td>   <?php     echo $item['brand']; ?>    </td>
                                                 <td>   <?php     echo $item['product']; ?>    </td>
                                                 <td> <?PHP echo $item['ModelNo']; ?></td>
                                                 <td>   <?php     echo $item['quantity']; ?>    </td>
                   <td> 
                  
                  <?php echo $item['price']; ?>    </td>
            <td>   <?php echo $item['amount']; ?>    </td>
                                                 </tr>
                                                 
             <tr><td colspan="5"></td><td><?php echo $item['taxSystem'].' '.$item['taxPercentage'].'%'; ?></td><td><?php echo $item['taxAmount']; ?></td></tr>                                      
                                                 
                          
                                         <?php
										
										
								
								$i++;	 }
									 
									 
									 
									 ?>  
                                     
                                    <tr><td colspan="5"></td><td>Sub Total</td><td><?php echo $revision['subTotal']; ?></td></tr> 
                                    <tr><td colspan="5"></td><td>Discount</td><td><?php echo $revision['discount']; ?></td></tr> 
                                    <tr><td colspan="5"></td><td>Total</td><td><?php echo $revision['total']; ?></td></tr> 
                                    <tr><td colspan="5"></td><td>Duty 12.5%</td><td><?php echo $revision['duty']; ?></td></tr> 
                                    <tr><td colspan="5"></td><td>PF 3%</td><td><?php echo $revision['pf']; ?></td></tr> 
                                    <tr><td colspan="5"></td><td>Grand Total</td><td><?php echo $revision['grandTotal']; ?>
                              
                                    <input type="hidden" id="crid" value="<?php echo $revision['revisionId']; ?>" />
                                    
                                    </td></tr> 
                                    </tbody>
                                </table>
                                        
                                    
                                     <form class="form-horizontal" role="form" id="validation-form" method="post">   
                           <div id="invoiceBox" style="display:none;">
                           <div class="table-header">
											Generate Invoice
										</div>
                         <table id="sample-table-1" class="table table-striped table-bordered table-hover">
											
                                            <thead>
                                            
                                            <th>S.no</th>
                                            <th>Category</th>
                                            <th>Brand</th>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Amount</th>
                                            </thead> 
                                            <tbody id="invoiceBody">
                                            
                                            </tbody>
                                                   </table>  
                                                   
                           
                           </div>
                           </form>
                           
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
 
 function addItem(itemId)
 {
	 
	 //alert(document.getElementsByName("items")[0]);
	 
	 var choices = [];
	 var itemsLength = document.getElementsByName('items[]').length;
         for (var i=0;i<itemsLength;i++){
             if(document.getElementsByName("items[]")[i].checked==true)
	 {
			choices.push(document.getElementsByName("items[]")[i].id);
	 }
	 }
		 
	

      var crid = document.getElementById("crid").value;
	  document.getElementById("invoiceBox").style.display = 'block';
   $.ajax({url: "ajax/getSelectedItems.php?itemId="+choices+"&crid="+crid, success: function(result){
		  $("#invoiceBody").html(result);
    }});
	/* 
	
	 if(document.getElementById(itemId).checked==true)
	 {
		 
	 }
	 else
	 {
		 $("#itemDiv_1_"+itemId).remove();
		  $("#itemDiv_2_"+itemId).remove();
	 }*/
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
