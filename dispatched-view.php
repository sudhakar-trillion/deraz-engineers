<?php include("includes/header.php"); 
date_default_timezone_set("Asia/Calcutta");
 $totalQuantity=0;
   $totalDisp=0;
   
   $inv= explode("-",$_GET['invoice']);
   


	//get the invoice id and fetch the number of dispatches
	

// select statement to get the dispatched data from dispatch, dispatch_items table.
/* to get the enquiries data we will execute this query with employees table.
relation between daily_reports and enquiries is enquiryNumber.
relation between collections and daily_reports are collections.invoiceId = daily_reports.reportId
relation between daily_reports and employees are daily_reports.addedBy = employees.id
relation between branches and employees are employees.branch = branches.branchId
relation between dispatches and invoices are dispatch.invoiceId = invoices.invoiceId
relation between dispatch_items and dispatch are dispatch.dispatchedId = dispatch_items.dispatchId
relation between daily_reports_data and products are daily_reports_data.productId = products.productId
*/
/*
$query = "select invoices.invoiceNumber,daily_reports.reportDate, daily_reports.poNo,daily_reports.offerNo, date_format(daily_reports.reportDate,'%d-%m-%Y') as reportDate, invoices.invoiceDateTime,invoices.total,daily_reports.enquiryNumber, daily_reports.runcard, employees.firstName, daily_reports_data.quantity, dispatch.dispatchedId, dispatch.invoiceId, dispatch.dispatchedOn, dispatch_items.remarks, dispatch_items.dispatchedQuantity, products.product, pm.ModelNo as code, customers.company from dispatch left join invoices on dispatch.invoiceId = invoices.invoiceId
left join daily_reports on invoices.reportId = daily_reports.reportId
left join customers on daily_reports.company = customers.customerId
left join dispatch_items on dispatch.dispatchedId = dispatch_items.dispatchId
left join daily_reports_data on dispatch_items.itemId = daily_reports_data.id
left join products on daily_reports_data.productId = products.productId
 left join product_model as pm on pm.ModelId=daily_reports_data.modelId
left join employees on daily_reports.addedBy = employees.id
where invoices.invoiceNumber='".$_GET['invoice']."'  group by dispatch.dispatchedId order by dispatch.dispatchedId desc";

*/


$query = "select invoices.invoiceNumber,daily_reports.reportDate, daily_reports.poNo,daily_reports.offerNo, date_format(daily_reports.reportDate,'%d-%m-%Y') as reportDate, invoices.invoiceDateTime,invoices.total,daily_reports.enquiryNumber, daily_reports.runcard, employees.firstName, daily_reports_data.quantity, dispatch.dispatchedId, dispatch.invoiceId, dispatch.dispatchedOn, dispatch_items.remarks, dispatch_items.dispatchedQuantity, products.product, pm.ModelNo as code, customers.company from dispatch left join invoices on dispatch.invoiceId = invoices.invoiceId
left join daily_reports on invoices.reportId = daily_reports.reportId
left join customers on daily_reports.company = customers.customerId
left join dispatch_items on dispatch.dispatchedId = dispatch_items.dispatchId
left join daily_reports_data on dispatch_items.itemId = daily_reports_data.id
left join products on daily_reports_data.productId = products.productId
 left join product_model as pm on pm.ModelId=daily_reports_data.modelId
left join employees on daily_reports.addedBy = employees.id
where invoices.invoiceNumber='".$_GET['invoice']."'  group by invoices.invoiceNumber order by dispatch.dispatchedId desc";



$qry = mysql_query("select proInvoiceNumber,invoiceId, reportId from invoices inv where inv.invoiceNumber='".$_GET['invoice']."'");
$proinv = mysql_fetch_object($qry);


$qreey = mysql_query( "select drd.id from daily_reports_data as drd left join daily_reports_revision as drv on drv.revisionId=drd.revisionId left join daily_reports as dr on dr.reportId=drv.reportId where drv.reportId=".$proinv->reportId); 

	  $prd_quantity=0;

	  $prd_name="<ol>";
		 $prd_quantity = "<ol>";
		 $model = "<ol>";
		
		$dispatched_quant = "<ol>";
		 
		 
while( $data = mysql_fetch_object($qreey) )
{

$qry_prdct = mysql_query("select drd.price,drd.quantity, prd.product,inv.proInvoiceNumber as invoiceNumber,pm.ModelNo from daily_reports_data drd join invoices inv on drd.invoice_id=inv.invoiceId join products prd on prd.productId=drd.productId left join product_model as pm on pm.ModelId=drd.modelId where inv.invoiceNumber='".$_GET['invoice']."' and drd.id=".$data->id." order by drd.id desc");


		
		 
	  if(mysql_num_rows($qry_prdct)>0)
	  {
		//for products and models
		while($prd = mysql_fetch_object($qry_prdct))  
		{
			  $prd_name.="<li>".$prd->product."</li>";
			  $prd_quantity.="<li>".$prd->quantity."</li>";
			  $model.="<li>".$prd->ModelNo."</li>";
		}
		//for dispatches
		
$dispatch_qry = mysql_query("select sum(dispatchedQuantity) dispatchedQuantity from dispatch_items as di left join dispatch as d on d.dispatchedId=di.dispatchId left join invoices as inv on inv.invoiceId=d.invoiceId where d.invoiceId=".$proinv->invoiceId." and di.itemId=".$data->id." group by di.itemId");

//echo "select sum(dispatchedQuantity) from dispatch_items as di left join dispatch as d on d.dispatchedId=di.dispatchId left join invoices as inv on inv.invoiceId=d.invoiceId where d.invoiceId=".$proinv->invoiceId." and di.itemId=".$data->id." group by itemId"; exit;


		while($disp = mysql_fetch_object($dispatch_qry) )
		{
			$dispatched_quant.="<li>".$disp->dispatchedQuantity."</li>";
		}
		
		
		
	  }
}
	  $prd_name.="</ol>";
	  $prd_quantity.="</ol>";
	  $model.="</ol>";
	  
	  $dispatched_quant.="</ol>";




$result = mysql_query($query);

?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Dispatched-View </li>
                            
                            
						</ul><!-- /.breadcrumb -->
                     <?PHP $sheet_time = time();?>   
               <a href="excel/<?PHP echo $sheet_time; ?>-dispatched-view.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>

						
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
											Dispatched-View
										</div>

										<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all table-responsive">
											<table class="table table-striped table-bordered table-hover">
                                            
												<thead>
													<tr>
														<th class="center">
															S.no
														</th>
														<th>schedule Dispatch Date</th>
                                                        <th>Report date</th>
														<th>Executive</th>
                                                        <th>Runcard No.</th>
                                                        <th>Invoice Date</th>
                                                        <th>Invoice</th>
                                                        <th>Invoice Amount</th>
                                                         <th>Enquiry Number</th>
                                                         <th>Offer Number</th>
                                                         <th>PO Number </th>
                                                        <th>Company Name</th>
                                                        <th>Product</th>
                                                        <th>Model No</th>
                                                        <th>Ordered</th>
                                                        <th>Dispatched</th>
                                                        <th>Remarks</th>
                                                       
                                                     
														
													</tr>
												</thead>

												<tbody>
													
														
																	

  <?php   
// to download the excel sheet we write this code.  
$list[] = array('S. No', 'Dispatch Date','Executive','Runcard No', 'Invoice Date', 'Invoice','enquirynumber','enquiry date', 'Company', 'Product', 'Model No', 'Ordered', 'Dispatched','Remarks');
  
  if(mysql_num_rows($result)>0) {
   $i = $start+1;
		
  $cnt=0;
   
  while($row = mysql_fetch_array($result))
  {
	  $upperLimit = $cnt+1;
	  $qry_prdct = mysql_query("select drd.price,drd.quantity, prd.product,inv.invoiceNumber,pm.ModelNo from daily_reports_data drd join invoices inv on drd.invoice_id=inv.invoiceId join products prd on prd.productId=drd.productId left join product_model as pm on pm.ModelId=drd.modelId where drd.id='".$data->id."' order by drd.id ASC limit $cnt,$upperLimit");

	 
$prd=mysql_fetch_object($qry_prdct);
	
//echo $prd->ModelNo; exit; 	  
	  
	$cnt++;    
//get the enquirynumber and date for this invoice
if($row['enquiryNumber']!='')
{
$enq_qry = mysql_query("select DATE_FORMAT(dateTime,'%Y-%m-%d') as enddtd from enquiries where enquiryNumber='".$row['enquiryNumber']."'");
//echo "select DATE_FORMAT(dateTime,'%Y-%m-%d') as enddtd from enquiries where enquiryNumber=".$row['enquiryNumber']; exit; 
if(mysql_num_rows($enq_qry)>0)
{
	while($enqdata = mysql_fetch_object($enq_qry))
	{
		$eqn_date = $enqdata->enddtd;	
	}
}
else
	$eqn_date = '';
}
else
{
	$eqn_date = '---';
	$enq_number='---';
}

	  
	
///	$currentStatus = mysql_query("select `status`, `remarks`, `dateTime` from order_status where orderId = '". $proposal['id'] ."' order by statusId desc");
	
	
	  
	 ?><tr>
														<td class="center">
															<?php echo $i; ?>
														</td>
<td>
                                                         <?php
														
													 	 $dispatchedOn = explode('-',$row['dispatchedOn']); 
														 $dispatchedOn = $dispatchedOn[2].'-'.$dispatchedOn[1].'-'.$dispatchedOn[0];
												
												if(strcmp('00-00-0000',$dispatchedOn)!=0)
												{
													echo $dispatchedOn;
												}
                                                           
 													  ?>
                                                      </td>
                                                      <td><?PHP echo $row['reportDate']; ?></td>
                                                      <td>
                                                     <?PHP echo $row['firstName'];  ?> 
                                                      
                                                      </td>
                                                      
                                                      <td>
                                                     <?PHP echo $row['runcard'];  ?> 
                                                      
                                                      </td>
                                                      
														<td>
                                                         <?php
														
													 	 $invoiceDate = explode(' ',$row['invoiceDateTime']); 
														 $invoiceDate = explode('-',$invoiceDate[0]); 
														 $invoiceDate = $invoiceDate[2].'-'.$invoiceDate[1].'-'.$invoiceDate[0];
												
												if(strcmp('00-00-0000',$invoiceDate)!=0)
												{
													echo $invoiceDate;
												}
                                                           
 													  ?>
                                                      </td>
                                                      
                                                      <td>
															<?php
															
														
															 echo $row['invoiceNumber']; 
															?>
                                
														</td>
                                                        <td><?PHP echo $row['total']; ?> </td>
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
                                                  <td><?PHP echo $row['offerNo'];?></td>
                                                  <td><?PHP echo $row['poNo'];?></td>
                                                         <td>
															<?php
															
														
															 echo $row['company']; 
															?>
                                
														</td>
                                                        	<td>
															<?php
															
															echo $prd_name;

															//echo $row['product']; 
															//echo $prd_name;
															?>
                                
														</td>
                                                        	<td>
															<?php
															
															 echo $model;
															?>
                                
														</td>
                                                        <td>
															<?php 

																 echo $prd_quantity;
																//echo $row['dispatchedQuantity']; $totalQuantity = $row['quantity']; ?>
														</td>
                                                         <td>
															<?php 
															//echo $row['dispatchedQuantity']; 
															echo $dispatched_quant;
															$totalDisp=($totalDisp)+($row['dispatchedQuantity']); ?>
														</td>
                                                        
                                                        <td><?PHP echo $row['remarks'];  ?></td>
                                                        
                                                        
                                                        
                                                        
                                                       
                                                        
                                                      
														

													
													</tr>
<?php
//$list[] = array('S. No', 'Dispatch Date','Executive','Runcard No', 'Invoice Date', 'Invoice', 'Company', 'Product', 'Model No', 'Ordered', 'Dispatched','Remarks');

// to download the excel sheet we write this code.
$rowlist[] = $i;
$rowlist[] = $dispatchedOn;

$rowlist[] = $row['firstName'];;
$rowlist[] = $row['runcard'];
$rowlist[] = $invoiceDate;

$rowlist[] = $row['invoiceNumber']; 
$rowlist[] = $enq_number; 
$rowlist[] = $eqn_date; 
$rowlist[] = $row['company'];
$rowlist[] = $row['product']; 
$rowlist[] = $row['code']; 
$rowlist[] = $row['quantity'];
$rowlist[] = $row['dispatchedQuantity'];

$rowlist[] = $invoiceDate;
$rowlist[] = $row['remarks'];
$list[] = $rowlist;

unset($rowlist);


 $i++;
  } } else {
  
  ?>        
  
  <tr><td colspan="10">No Data found.</td></tr>
  <?php } 
   ?>
  <!-- <tr> <td colspan="13"></td><td ><strong>Total </strong></td> <td><?PHP echo $totalQuantity; ?> </td> <td><?PHP echo $totalDisp;?> </td> <td> </td> </tr>-->
                                   </tbody>
											</table>
                                            
<?PHP
//excel/<?PHP echo $sheet_time;-dispatched-view.csv

$fp = fopen('excel/'.$sheet_time.'-dispatched-view.csv', 'w');

foreach ($list as $fields) {
    fputcsv($fp,$fields);
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

  function goToPage(pid)
{
   window.location = 'dispatched.php?page='+pid;	
}
 
 		
		function getInvoice(val)
		{
			
			document.getElementById("invoicesList").style.display = 'block';
				$.ajax({url: "ajax/getInvoicesList.php?val="+val, success: function(result){
		$("#invoicesList").html(result);
    }});	
			
		}
		
		
		function selectInvoice(invid,invoice)
		{
			document.getElementById("invoicesList").style.display = 'none';
			document.getElementById("invid").value = invid;
			document.getElementById("invoice").value = invoice;
	
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
