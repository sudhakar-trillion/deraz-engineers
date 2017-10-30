<?php include("includes/header.php"); 

// pagination
$limit = 10;

// get the data from vendors, stock, vendor_payment,    
/* relation between vendor_payment and stock are vendor_payment.invoiceId = stock.stockId
relation between stock and vendors are stock.vendorId = vendors.vendorId
   */
$numRecords = mysql_query("select  vendors.company, vendor_payment.amount, vendor_payment.paidDate from vendor_payment 
	                     left join stock on vendor_payment.invoiceId = stock.stockId
	                     left join vendors on stock.vendorId = vendors.vendorId
                         order by vendor_payment.paymentId desc");
 
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
// search options  where daily_reports.po = '0'

    $where = 'where ';
    if(isset($_GET['paymentSearch']))
									{
									
							/* 	from date to todate search based on "vendor_payment.dateTime".
								 
								vendor search based on vendors.vendorId
								*/		
										
										
									if(isset($_GET['fromDate']) && strlen($_GET['fromDate']>8) && isset($_GET['toDate']) && strlen($_GET['toDate']>8))
									{
										
									
										$fromDate = explode('-',$_GET['fromDate']);
										$fromDate = $fromDate[2].'-'.$fromDate[1].'-'.$fromDate[0];
										
										$toDate = explode('-',$_GET['toDate']);
										$toDate = $toDate[2].'-'.$toDate[1].'-'.$toDate[0];
									 
									 
									 $whereItems[] = "vendor_payment.dateTime >=  '". $fromDate ."'";
									 
									 $whereItems[] = "vendor_payment.dateTime <=  '". $toDate ."'";
									}
						 			
									
									   // by vendor
									   if(isset($_GET['vid']) && $_GET['vid']>0)
									   {  
									    
										    $whereItems[] = "vendors.vendorId =  '". $_GET['vid'] ."'";
									   }
									   
									
												if(count($whereItems)>1)
												{ 
												$whereCondition = implode(' and ',$whereItems);
												$where = $where.$whereCondition;
												}
												else if(count($whereItems)==1)
												{   
													$whereCondition = $whereItems[0];
													$where = $where.$whereCondition;
												}
												else
												{  
												  $where = '';	
												}
												
											
$payments = mysql_query("select  vendors.company, vendor_payment.amount, vendor_payment.paidDate, stock.invoiceAmount from vendor_payment 
left join stock on vendor_payment.invoiceId = stock.stockId
left join vendors on stock.vendorId = vendors.vendorId
order by vendor_payment.paymentId desc");						
									}
else								
{
 		 
						 
						 
 $vendorInvoices = mysql_query("select stock.stockId, date_format(stock.firstduedate,'%d-%m-%Y') as firstduedate, date_format(stock.secondduedate,'%d-%m-%Y') as secondduedate, date_format(stock.thirdduedate,'%d-%m-%Y') as thirdduedate,stock.disdate, date_format(stock.disdate, '%d-%m-%Y') as invoicedate,  stock.invoiceNumber, stock.invoiceAmount, vendors.company from stock left join vendors on stock.vendorId = vendors.vendorId order by stock.stockId desc");		 
 
}
 
?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Payments Made </li>
                            
                            
						</ul><!-- /.breadcrumb -->
     <a href="excel/vendor_payments.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>


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
<span>Vendor</span>
<input type="hidden" id="vid" name="vid"   />
<input type="text" id="vendor" name="vendor" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Vendor" <?php if(isset($_GET['vendor'])) { ?> value="<?php echo $_GET['vendor']; ?>" <?php } ?> onkeyup="getVendor(this.value)" />
<ul class="typeahead dropdown-menu" style="left: 10px; display: none;" id="vendorsList">
</ul>

</div> 


<div class="form-group col-sm-2">
<br/>
<input type="submit" class="btn btn-sm btn-success" name="paymentSearch" value="Search" />
</div>

</div>





</form>
<div class="space"></div>
</div>

									<div class="col-xs-12">
										
										<div class="table-header">
											Payments Made
										</div>


    <?php
										   
	   if(isset($_GET['delete']))
{ $alertMsg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Proposal has been deleted!</div>'; }
else if(isset($_GET['error']))
{ $alertMsg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  if(isset($alertMsg)) { echo $alertMsg; }
										   ?> 
										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">
											<table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                            
												<thead>
													<tr>
														<th class="center">
															S.no
														</th>
														<th>Invoice Date</th>
                                                        <th>Vendor </th>
                                                       
                                                        <th>Invoice Number</th>
                                                        <th>Invoice Amount</th>
                                                        <th>First due date</th>
                                                        <th>Second due date</th>
                                                        <th>Third due date</th>
                                                        <th>Paid Amount</th>
                                                        <th>Pending Amount</th>
                                                      
														
													</tr>
												</thead>
												<tbody>
													
  <?php 
 // to download file in excel format we have written this code. 
  $list[] = array('S. No', 'Paid Date', 'Invoice Amount', 'Paid Amount', 'company' );
 
  
  if(mysql_num_rows($vendorInvoices)>0)
  {
  $i = $start+1; 

 $totalInvoiceAmount = 0;  $totalPaid = 0; $totalPending = 0;
  
  while($vendorInvoice = mysql_fetch_array($vendorInvoices))
  {
	
	
  $payments = mysql_query("select  vendor_payment.amount, vendor_payment.paidDate from vendor_payment 
	                       left join stock on vendor_payment.invoiceId = stock.stockId where vendor_payment.invoiceId = '". $vendorInvoice['stockId'] ."'
					       order by vendor_payment.paymentId desc"); 
						   
						
 
	 ?><tr>
														<td class="center">
															<?php echo $i; ?>
														</td>
                                                        <td><?php
															 /*$invoiceDate = explode('-',$vendorInvoice['date']); 
														     $invoiceDate = $invoiceDate[2].'-'.$invoiceDate[1].'-'.$invoiceDate[0];
														
														 if(strcmp('00-00-0000',$invoiceDate)!=0)
															{
																echo $invoiceDate;
															}
															*/
															echo $vendorInvoice['invoicedate'];
															?>
                                                        </td>
														
                                                        
                                                         <td><?php	echo  $vendorInvoice['company']; ?></td>
                                                         <td><?php  echo  $vendorInvoice['invoiceNumber']; ?></td>
                                                         <td><?php 
														 $totalInvoiceAmount = $vendorInvoice['invoiceAmount']+$totalInvoiceAmount;
														 echo $vendorInvoice['invoiceAmount']; ?></td>
                                                         
                                                         <td> <?PHP  echo $vendorInvoice['firstduedate']; ?></td>
                                                          <td><?PHP  echo $vendorInvoice['secondduedate']; ?></td>
                                                           <td><?PHP  echo $vendorInvoice['thirdduedate']; ?></td>
                                                         
                                                         
                                                         <td><?php // echo  $payment['company']; 
														
														?>
                                                        
     <div id="<?php echo 'modal-form'.$vendorInvoice['stockId']; ?>" class="modal in" tabindex="-1" aria-hidden="false" style="display: none;"><div class="modal-backdrop  in"></div><div class="modal-backdrop  in"></div>
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">×</button>
												<h4 class="blue bigger">inv30.1</h4>
											</div>

											<div class="modal-body">
												<div class="row">
													<div class="col-xs-12 col-sm-12">
														
                                             
													
                                                        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                            
												<thead>
													<tr>
														<th class="center">
															S.no
														</th>
														<th>Paid Date</th>
                                                        <th>Paid Amount </th>
                                                        <th>Pending Amount</th>
                                                      
														
													</tr>
												</thead>
												<tbody>
                                                
                                                <?php
												  $paidAmount = 0;
												  $invoicePaid = 0;
														if(mysql_num_rows($payments)>0)
														{ $sno = 1;
														while($payment = mysql_fetch_array($payments))
														{
															 $paidAmount = $payment['amount']+$paidAmount;
															 $invoicePaid = $payment['amount']+$invoicePaid;
														?><tr><td>
                                                        <?php echo $sno; ?>
                                                        
                                                        </td>
                                                              <td>
                                                              <?php
															  $paidDate = explode('-',$payment['paidDate']);
															  echo $paidDate = $paidDate[2].'-'.$paidDate[1].'-'.$paidDate[0]; ?>
                                                              </td>
                                                              <td><?php echo $payment['amount']; ?></td>
                                                              <td><?php echo $vendorInvoice['invoiceAmount']-$invoicePaid; ?></td>
                                                              </tr> 
                                                                 <?php
														
															
													$sno++;	}
                                                        } ?>
                                                         </tbody></table>
                                                         
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
                                
                             <!--   class="label label-warning"-->
                                <a href="<?php echo '#modal-form'.$vendorInvoice['stockId']; ?>" role="button"  data-toggle="modal"> <?php echo $paidAmount; ?> </a>
                                                         
                                                         <?php
														 
														 $totalPaid = $paidAmount+$totalPaid;
														 
														 ?>
                                                         
                                                         </td>
                                                         <td>
                                                         <?php
														 echo $pendingAmount = $vendorInvoice['invoiceAmount']-$paidAmount; 
														 
														 $totalPending = $pendingAmount+$totalPending;
                                                        ?> 
                                                         </td>
                                                         
                                                      </tr>
<?php
// to download file in excel format we have written this code.
 $rowlist[] = $i;
 $rowlist[] = $reportDate;
 $rowlist[] = $payment['invoiceAmount']; 
 $rowlist[] = $payment['amount']; 
 $rowlist[] =  $payment['company']; 
$list[] = $rowlist;
 unset($rowlist);

$i++;
  }
  
$rowlist[] = '';
$rowlist[] = '';
$rowlist[] = '';



$rowlist[] = $totalPaid;
$list[] = $rowlist;
 unset($rowlist);
 
  
  ?><tr><td colspan="4"></td>
   <td><?php echo $totalInvoiceAmount; ?></td>
   <td><?php echo $totalPaid; ?></td>
   <td><?php echo $totalPending; ?></td></tr><?php
  
  
   } else { ?> <tr><td colspan="5">No Data found.</td></tr> <?php }
  
    if($numRecords>$limit &&  !(isset($_GET['paymentSearch']))) 
	{
   
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
  <tr><td colspan="12">
  
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
  <?php } ?>                                              </tbody>
											</table>
                                            <?php                              
$fp = fopen('excel/vendor_payments.csv', 'w');

foreach ($list as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp) ?>
                                            
                                            
                                            
										</div>
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
        <script src="assets/js/date-time/bootstrap-datepicker.js"></script>

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

 function confirmDelete(did)
 {
	if(confirm('Do you want to delete the report')) 
	{
	 window.location = 'proposals.php?did='+did; 
	}
 }

  function goToPage(pid)
{
   window.location = 'vendor_payments.php?page='+pid;	
}

function getVendor(val)
		{
			
			document.getElementById("vendorsList").style.display = 'block';
				$.ajax({url: "ajax/getVendorsList.php?val="+val, success: function(result){
		$("#vendorsList").html(result);
    }});	
			
		}
		
		
		function selectVendor(vid,company)
		{
			document.getElementById("vendorsList").style.display = 'none';
			document.getElementById("vid").value = vid;
			document.getElementById("vendor").value = company;
	
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
