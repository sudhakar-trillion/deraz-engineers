<?php include("includes/sa_header.php"); 

// delete 
if(isset($_GET['did']))
{
  if(mysql_query("delete from daily_reports where reportId = '". $_GET['did'] ."' and addedBy = '". $_SESSION['id'] ."'"))
  {
	header("location: sa_daily_reports.php?delete=success");  
  }
  else
  {
	header("location: sa_daily_reports.php?error=fail");    
  }
}

// pagination
 $limit = 10;
 if( isset($_GET['tmid']) )
 {
	
	$qry = mysql_query("select firstName from employees where id=".$_GET['tmid']);
	if(mysql_num_rows($qry)>0)
	{
		$empl = mysql_fetch_object($qry);
		$Emply_ide = $_GET['tmid'];
		$EmployeeName = $empl->firstName;

	}
	else
	{
		$Emply_ide = $_SESSION['id'];
		$EmployeeName = $_SESSION['firstName'];
	}

 }
 else
 { 
	$Emply_ide = $_SESSION['id'];
		$EmployeeName = $_SESSION['firstName'];
 }
 
 if(isset($_GET['status']) && $_GET['status']=='pending')
 {
	$where=" and invoices.invoiceStatus != 'Order Closed'"; 
 }
 else
 	$where ='';
	
 	 $numRecords = mysql_query("select daily_reports.reportId, daily_reports.contactPerson, daily_reports.enquiryNumber, date_format(enquiries.dateTime,'%d-%m-%Y') enqDate, daily_reports.designation, daily_reports.phone, daily_reports.email, daily_reports.clientStatus, daily_reports.leadType, daily_reports.leadStatus, daily_reports.poNo, customers.company  from daily_reports 
                          left join customers on daily_reports.company = customers.customerId
 						left join enquiries on daily_reports.enquiryNumber = enquiries.enquiryNumber
						left join invoices on invoices.reportId=daily_reports.reportId
						  where daily_reports.addedBy = '".$Emply_ide."' $where
						  order by daily_reports.reportId desc");

   $numRecords = mysql_num_rows($numRecords);  
 
   $numPages = (int)($numRecords/$limit);  
   $reminder = ($numRecords%$limit);
 
 if($reminder>0)
 {
	 $numPages = $numPages+1;
 }
 else
 {
	  $numPages = $numPages;
 }
 
 
 if(isset($_GET['page']) && $_GET['page']>1)
 {
	$start = ($_GET['page']*$limit)-$limit;  
 }
 else
 {
	$start = 0; 
 }
 
 




$result = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.enquiryNumber, daily_reports.offerNo, date_format(enquiries.dateTime,'%d-%m-%Y') enqDate, daily_reports.offerToBeSubmitted, daily_reports.contactPerson,  daily_reports.phone,  daily_reports.po, daily_reports.poNo, daily_reports.poValue, daily_reports.poDateTime, invoices.invoiceId, invoices.grandTotal,   invoices.invoiceNumber, invoices.invoiceDateTime, invoices.proInvoiceNumber, invoices.proInvoiceDateTime, invoices.invoiceStatus, customers.company 
 from invoices  
                          left join daily_reports on invoices.reportId = daily_reports.reportId
                          left join customers on daily_reports.company = customers.customerId
						left join enquiries on daily_reports.enquiryNumber = enquiries.enquiryNumber
						  where daily_reports.addedBy = '".$Emply_ide."' $where
						  order by daily_reports.reportId desc limit $start, $limit");





?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Orders Raised  </li>
						</ul><!-- /.breadcrumb -->
<a href="excel/sa_orders.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>


						<!-- #section:basics/content.searchbox -->
						<div class="nav-search" id="nav-search">
							<!--<form class="form-search">
								<span class="input-icon">
									<input type="text" placeholder="Search ... " class="nav-search-input" id="nav-search-input" autocomplete="off" />
									<i class="ace-icon fa fa-search nav-search-icon"></i>
								</span>
							</form>-->
						</div><!-- /.nav-search -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content" id="outclick">
						<!-- #section:settings.box -->
						<!-- /.ace-settings-container -->

						<!-- /section:settings.box -->
					

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								
 <?php
	   if(isset($_GET['delete']))
{ $alertMsg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Report has been deleted!</div>'; }
else if(isset($_GET['error']))
{ $alertMsg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  if(isset($alertMsg)) { echo $alertMsg; }
										   ?> 
							



								<div class="row">
                                
									<div class="col-xs-12">
                                    <form class="form-inline" method="get" action="" autocomplete="off">
                                  
                      
  <div class="row">              
                              
  <div class="form-group col-sm-2">
   <span>From Date</span>
    <input type="text" class="form-control date-picker input-sm" id="fromDate" name="fromDate" placeholder="From Date" <?php if(isset($_GET['fromDate'])) { ?> value="<?php echo $_GET['fromDate']; ?>" <?php } ?> />
  </div>
  <div class="form-group col-sm-2">
   <span>To Date</span>
   <input type="text" class="form-control date-picker input-sm" id="toDate" name="toDate" placeholder="To Date" <?php if(isset($_GET['toDate'])) { ?> value="<?php echo $_GET['toDate']; ?>" <?php } ?> />
  </div>
 
                               
                                <div class="col-sm-2">
                                <span>Customer</span>
                                <input type="hidden" id="cid" name="cid" value="<?PHP echo $_GET['cid']; ?>" />

     <input type="text" id="customer" name="customer" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Customer" <?php if(isset($_GET['customer'])) { ?> value="<?php echo $_GET['customer']; ?>" <?php } ?>  />
     
      <ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="customersList"></ul>
                               </div> 
                                             
  <div class="form-group col-sm-2">
   <span><br></span>
    <input type="submit" class="btn btn-sm btn-success" name="ordersSearch">
  </div>
  
  </div>
                                
                              
                                 
                               
  
</form>
										
										<div class="table-header">
											Orders
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
																				<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">

											<table id="sample-table-1" class="table table-striped table-bordered table-hover">
												<thead>

													<tr>
														<th class="center">
															S.no
														</th>
                                                        <th>Offer</th>
                                                        <th>Enquiry Date</th>
                                                        <th>Enquiry No.</th>
                                                        <th>Po No</th>
                                                        <th>proforma Date</th>
                                                        <th>Proforma </th>
                                                        <th>Invoice Date</th>
                                                        <th>Invoice</th>
                                                    	<th>Company</th>
                                                        <th>Person</th>
                                                        <th>Phone</th>
                                                        <th>Status</th>
                                                        <th class="hidden-480">Action</th>
														
													</tr>
												</thead>

												<tbody>
													
														
																	

  <?php 
     $list[] = array('S. No', 'Offer', 'Po No', 'Proforma Date', 'Proforma', 'Invoice Date', 'Invoice', 'Company', 'Person', 'Phone', 'Status');

if(mysql_num_rows($result)>0)
  {
  
  $i = $start+1; $grandTotal = 0;
    while($row = mysql_fetch_array($result))
  {
	  
/* $rows =	mysql_query("select grandTotal from daily_reports_revision where revision = '". $row['currentRevisionId'] ."' and reportId = '". $row['reportId'] ."'");
	
   $rows = mysql_fetch_array($rows);*/
   
    $grandTotal = $row['grandTotal']+$grandTotal;
	
	/*$currentDate = date('Y-m-d');  
	  
$prices = mysql_query("select `price` from product_price where productId = '". $product['productId'] ."' and fromDate <= '$currentDate' order by fromDate desc limit 1");
$price = mysql_fetch_array($prices);*/
	  
	 ?><tr>
														<td class="center">
															<?php echo $i; ?>
														</td>
                                                        
                                                         <td> <?php echo $row['offerNo']; ?> </td>
                                                         
                                                         <td>
													 <?php if($row['enqDate']!='')
													 {
													  echo $row['enqDate']; }
													  else
													  {
														  echo '--';
													  }
													  
													  ?>
												  </td>
                                                         <td>
													 <?php if($row['enquiryNumber']!='')
													 {
													  echo $row['enquiryNumber']; }
													  else
													  {
														  echo '--';
													  }
													  
													  ?>
												  </td>
                                                         <td> <?php echo $row['poNo']; ?> </td>
                                                        
                                                          <td>
															<?php
															 $proDate = explode(' ',$row['proInvoiceDateTime']); 
														     $proDate = explode('-',$proDate[0]); 
														     $proDate = $proDate[2].'-'.$proDate[1].'-'.$proDate[0];
														
														 if(strcmp('00-00-0000',$poDate)!=0)
												{
													echo $proDate;
												}
															?>
														</td>
                                                        
                                                         <td> <?php echo $row['proInvoiceNumber']; ?> </td>
                                                           <td>
															<?php 
															 $inDate = explode(' ',$row['invoiceDateTime']); 
														     $inDate = explode('-',$inDate[0]); 
														     $inDate = $inDate[2].'-'.$inDate[1].'-'.$inDate[0];
														
														 if(strcmp('00-00-0000',$inDate)!=0)
												{
													echo $inDate;
												}
															?>
														</td>
                                                         
                                                          <td> <?php echo $row['invoiceNumber']; ?> </td>
                                                         
														
                                                        
                                                        
														<td>
															<?php echo $row['company']; ?>
														</td>
                                                       
                                                        <td>
															<?php echo ucfirst($row['contactPerson']); ?>
														</td>
                                                       
                                                        <td>
															<?php echo $row['phone']; ?>
														</td>
                                                         <td>
															<?php echo $row['invoiceStatus'];
															
															 ?>
														</td>
                                                        <td>
                                                        
                                           <?PHP if(isset($_GET['tmid']) && $_GET['tmid']>0 ){ ?>           
                                           <a class="btn btn-success btn-sm" href="sa_view_order.php?iid=<?php echo $row['invoiceId'] ?>&tmid=<?PHP echo trim($_GET['tmid']);?>">
										   <?PHP } else { ?>
                                            <a class="btn btn-success btn-sm" href="sa_view_order.php?iid=<?php echo $row['invoiceId'] ?>">
                                           <?PHP } ?> 
												<i class="ace-icon fa fa-eye"></i>
											</a>
                                           
                                            <?php if($row['offerToBeSubmitted']==2 && $row['po']==0) { ?>
                                            <a class="btn btn-success btn-sm" href="add_po.php?rid=<?php echo $row['reportId'] ?>">
												
										add po	</a>
                                              <?php } ?>
                                              
                                              <br />
                          
                                              <a href="<?php echo '#modal-form'.$row['invoiceId']; ?>" role="button" class="label label-warning" data-toggle="modal"> Status </a> 
                                                              <!--Modal Box-->
                                            <div id="<?php echo 'modal-form'.$row['invoiceId']; ?>" class="modal in" tabindex="-1" aria-hidden="false" style="display: none;"><div class="modal-backdrop  in"></div>
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
                                            <?php echo $invId; ?>
												<button type="button" class="close" data-dismiss="modal">×</button>
												<h4 class="blue bigger"><?php  echo $row['invoiceNumber'];  ?></h4>
											</div>

											<div class="modal-body">
												<div class="row">
													<div class="col-xs-12 col-sm-12">
														
                                             <?php 
											
	$orderProducts = mysql_query("select daily_reports_data.id, daily_reports_data.quantity, products.product, products.code from 
	daily_reports_data 
	left join products on daily_reports_data.productId = products.productId
	where daily_reports_data.invoice_id = '". $row['invoiceId'] . "' order by daily_reports_data.id DESC LIMIT 1");
	
	?>
    	<table id="sample-table-1" class="table table-striped table-bordered table-hover">
												<thead>

													<tr>
														<th class="center">
															S.no
														</th>
                                                        <th>Product</th>
                                                        <th>Model No</th>
                                                        <th>Ordered Quantity</th>
                                                        <th>Dispatched </th>
                                                        <th>Pending</th>
                                                        
													</tr>
												</thead>

												<tbody>
    
    <?php $sno = 1;
	while($orderProduct = mysql_fetch_array($orderProducts))
	{
	
	
//	$dispatchData = mysql_query("select dispatchedQuantity from dispatch_items where itemId = '". $orderProduct['dispatchedQuantity'] ."'");
$dispatchData = mysql_query("select sum(dispatchedQuantity) dispatchedQuantity from dispatch_items where dispatchId IN (SELECT dispatchedId from dispatch where invoiceId=".$row['invoiceId'].") and itemId=".$orderProduct['id']);
 
	
	$dispatchedQuantity = 0;	
	if(mysql_num_rows($dispatchData)>0)
	{
		while($dispatch_Data = mysql_fetch_array($dispatchData))
		{
			 //$dispatchedQuantity = $dispatchedQuantity+$dispatch_Data['dispatchedQuantity'];
			 $dispatchedQuantity = $dispatch_Data['dispatchedQuantity'];
		}
	}
	else
	$dispatchedQuantity=0;
	
	
	
	   ?>
       <tr><td><?php echo $sno; ?></td><td><?php echo $orderProduct['product']; ?></td>
       <td><?php echo $orderProduct['code']; ?></td>
       <td><?php echo $orderProduct['quantity']; ?></td>
       <td><?php if($dispatchedQuantity!=''){ echo $dispatchedQuantity; } else echo "0"; ?></td>
       <td><?php echo $orderProduct['quantity']-$dispatchedQuantity; ?></td>
       </tr>
       
        <?php	
 $sno++;	} 									 
											   ?>
                                               </tbody>
                                               </table>

													</div> 
												</div> 
											</div> 

											<div class="modal-footer">
												<button class="btn btn-sm" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close
												</button>

												
											</div>
										</div>
									</div>
								</div>
                                            <!--Modal Box-->
                                            
                                            
                                                     
                                                          
														</td>

													
													</tr>
<?php 
 $rowlist[] = $i;
$rowlist[] = $row['offerNo']; 
$rowlist[] = $row['poNo'];
$rowlist[] = $proDate;
$rowlist[] = $row['proformaInvoice'];
$rowlist[] = $poDate;
$rowlist[] = $row['invoice']; 
$rowlist[] = $row['company']; 
$rowlist[] = ucfirst($row['contactPerson']);
$rowlist[] = $row['phone'];
$rowlist[] = $row['leadStatus'];
	 
	 
	 
 $list[] = $rowlist;
 unset($rowlist);
 



$i++; }


 ?>
 <tr><td colspan="11"></td><td><?php #echo $grandTotal; ?></td><td colspan="5"></td></tr>
 
 <?php
  }
  else
  {
  	  ?><tr><td colspan="19">No Data Found</td></tr><?php
  }
  
  ?>         
<?php 
   if($numRecords>$limit) {
   
  if(isset($_GET['page']))
 {
	$start = $_GET['page']*$limit; 
	$currentPage = $_GET['page']; 
 }
 else
 {
	$start = 0;
	$currentPage = 1;  
 }
  
   
  if($currentPage==$numPages)
  {
	  $firstlink = '';
	  $secondlink = '';
	  $thirdlink = 'ui-state-disabled';
	  $fourthlink = 'ui-state-disabled';
	  
	  
  }
  else if($currentPage<$numPages)
  {
	  if($currentPage==1)
	  {
		  
	  $firstlink = 'ui-state-disabled';
	  $secondlink = 'ui-state-disabled';  
	  $thirdlink = '';
	  $fourthlink = '';
	  }
	  else
	  {
		 
	  }
	  
	  
  }
  
  ?> 
  <tr><td colspan="11">
  
  <table cellspacing="0" cellpadding="0" border="0" style="table-layout:auto;" class="ui-pg-table"><tbody>
  <tr>
  <td id="first_grid-pager" class="ui-pg-button ui-corner-all <?php echo $firstlink; ?>" onclick="goToPage('1')">
                 <span class="ui-icon ace-icon fa fa-angle-double-left bigger-140"></span>
  </td>
  <td id="prev_grid-pager" class="ui-pg-button ui-corner-all <?php echo $secondlink; ?>" onclick="goToPage('<?php echo $currentPage-1; ?>')">
               <span class="ui-icon ace-icon fa fa-angle-left bigger-140"></span>
  </td>
  <td class="ui-pg-button ui-state-disabled" style="width:4px;">
       <span class="ui-separator"></span>
  </td>
  <td dir="ltr">
             Page <input class="ui-pg-input" type="text" onkeyup="goToPage(this.value)" size="2" maxlength="7" value="<?php echo $currentPage; ?>" role="textbox"> of <span id="sp_1_grid-pager"><?php echo $numPages; ?></span>
  </td>
  <td class="ui-pg-button ui-state-disabled" style="width: 4px; cursor: default;">
         <span class="ui-separator"></span>
  </td>
  <td id="next_grid-pager" class="ui-pg-button ui-corner-all <?php echo $thirdlink; ?>" style="cursor: default;" onclick="goToPage('<?php echo $currentPage+1; ?>')">
                <span class="ui-icon ace-icon fa fa-angle-right bigger-140">></span>
  </td>
  <td id="last_grid-pager" class="ui-pg-button ui-corner-all <?php echo $fourthlink; ?>" onclick="goToPage('<?php echo $numPages; ?>')">
                <span class="ui-icon ace-icon fa fa-angle-double-right bigger-140"></span>
  </td>
  </tr></tbody></table>
  
  
  
  
  
  </td></tr>
  <?php } ?>      

                                      </tbody>
											</table>
                                            
                                            <?php                              
$fp = fopen('excel/sa_orders.csv', 'w');

foreach ($list as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp) ?>
										</div>
									</div>
								</div>

								<!--<div id="modal-table" class="modal fade" tabindex="-1">
								
								</div><!-- PAGE CONTENT ENDS -->
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

	  <script>
	  
	   function goToPage(pid)
{
   window.location = 'sa_daily_reports.php?page='+pid;	
}
  
  function confirmDelete(did)
  {
	  if(confirm("Confirm Delete"))
	  {
	  window.location = 'sa_daily_reports.php?did='+did;
	  }
  }
  
  	$(document).on('click','#outclick,#breadcrumbs',function(){
			$('#customersList').css('display','none');
			});
	

$(document).on('keyup','#customer',function(){
			var customer = $(this).val();
			
			$.ajax({
				   url: 'ajax/getCustomerList.php',
				   type: 'POST',
				   data: {'customer':customer},
				   success:function(data){ 
				   $("#customersList").html(data);
				$("#customersList").css('display','block');
				   }
				
				
				});
			
			
			});


	
		
		function selectCustomer(id,firstName)
		{
			document.getElementById("customersList").style.display = 'none';
			document.getElementById("cid").value = id;
			document.getElementById("customer").value = firstName;
	
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
