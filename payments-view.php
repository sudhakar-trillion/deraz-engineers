<?php include("includes/header.php"); 

// to get the payment data from collections table.			
/* to get the enquiries data we will execute this query with employees table.
relation between daily_reports and enquiries is enquiryNumber.
relation between collections and daily_reports are collections.invoiceId = daily_reports.reportId
relation between daily_reports and employees are daily_reports.addedBy = employees.id
relation between branches and employees are employees.branch = branches.branchId
*/
$collections = mysql_query("select employees.firstName, collections.paymentType, customers.company, collections.id as collections_id, collections.amount, collections.paidDate, invoices.invoiceId, invoices.invoiceNumber, invoices.grandTotal,branches.branch from collections  left join invoices on collections.invoiceId = invoices.invoiceId left join daily_reports on invoices.reportId = daily_reports.reportId left join employees on daily_reports.addedBy = employees.id left join branches on employees.branch=branches.branchId left join customers on customers.customerId=daily_reports.company where  invoices.invoiceNumber='".$_GET['invoice']."'");


$qry = mysql_query("select proInvoiceNumber,invoiceId, reportId from invoices inv where inv.invoiceNumber='".$_GET['invoice']."'");
$proinv = mysql_fetch_object($qry);

//echo $proinv->invoiceId; exit;
	
$qreey = mysql_query("select drd.id from daily_reports_data as drd left join daily_reports_revision as drv on drv.revisionId=drd.revisionId left join daily_reports as dr on dr.reportId=drv.reportId where drv.reportId=".$proinv->reportId); 



	  $prd_quantity=0;
	  $prd_name='';
	  $prd_quantity='';
	  $model='';
	  
		$prd_name="<ol>";
		 $prd_quantity = "<ol>";
		 $model = "<ol>";

while( $data = mysql_fetch_object($qreey) )
{

$qry_prdct = mysql_query("select drd.price,drd.quantity, prd.product,inv.proInvoiceNumber as invoiceNumber,pm.ModelNo from daily_reports_data drd join invoices inv on drd.invoice_id=inv.invoiceId join products prd on prd.productId=drd.productId left join product_model as pm on pm.ModelId=drd.modelId where inv.invoiceId='".$proinv->invoiceId."' and drd.id=".$data->id." order by drd.id desc");


//echo "select drd.price,drd.quantity, prd.product,inv.proInvoiceNumber as invoiceNumber,pm.ModelNo from daily_reports_data drd join invoices inv on drd.invoice_id=inv.invoiceId join products prd on prd.productId=drd.productId left join product_model as pm on pm.ModelId=drd.modelId where inv.invoiceId='".$proinv->invoiceId."' and drd.id=".$data->id." order by drd.id desc"; exit; 
//echo mysql_num_rows($qry_prdct); exit;
		
	  if(mysql_num_rows($qry_prdct)>0)
	  {
		while($prd = mysql_fetch_object($qry_prdct))  
		{
			  $prd_name.="<li>".$prd->product."</li>";
			  $prd_quantity.="<li>".$prd->quantity."</li>";
			  $model.="<li>".$prd->ModelNo."</li>";
		}
	  }
}

		$prd_name.="</ol>";
		 $prd_quantity.= "</ol>";
		 $model.= "</ol>";


?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Payments</li>
						</ul><!-- /.breadcrumb -->

						   <?PHP
								$sheet_num=time();
						?>
                         <a href="excel/<?PHP echo $sheet_num;?>-payments-view.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- #section:settings.box -->
						<!-- /.ace-settings-container -->

						<!-- /section:settings.box -->
					

						<div class="row">
                        
							<div class="col-xs-12">
                             
								<div class="row">
									<div class="col-xs-12">
										
										<div class="table-header">
											Payments
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
                                                         <th>Date</th>
                                                        <th>Invoice No</th>
                                                         <th> Product</th>
                                                          <th> Model</th>
                                                        <th> Quantity</th>
														
                                                      	<th>Customer</th>												
                                                    	<th>Employee</th>
                                                        <th>Branch</th>
                                                        <th>Type</th>	
                                                        
                                                        <th>Total</th>
                                                        <th>Collected</th>
                                                       
                                                       </tr>
												</thead>

												<tbody>
													
														
																	

  <?php
   // to download file in excel format we have written this code.
     $list[] = array('S. No','Date','Invoice No','Product','Quantity','Employee','Branch','Type','Total','Collected');
  if(mysql_num_rows($collections)>0)
  {
	  $i = $start+1; 
		
	 
	  $totalCollection = 0;
  while($collection = mysql_fetch_array($collections))
  {
	    $totalCollection = $totalCollection+$collection['amount'];
	
	
	
	$totalAmount = mysql_query("select grandTotal from daily_reports_revision where daily_reports_revision.reportId = '". $collection['reportId'] ."' and daily_reports_revision.revision = '". $collection['currentRevisionId'] ."'");
  
 
  if(mysql_num_rows($totalAmount)>0)
  {
	  $total = mysql_fetch_array($totalAmount);
	  $grandTotal = $total['grandTotal']; 
  }
  else
  { $grandTotal = 0; }
  
 
 
 
	  
	 ?><tr>
														<td class="center">
															<?php echo $i; ?>
														</td>
                                                        
                                                         <td>
															<?php $paidOn = explode('-',$collection['paidDate']);
															echo $paidOn[2].'-'.$paidOn[1].'-'.$paidOn[0];
															 ?>
														</td>
                                                        <td>
															<?php echo $collection['invoiceNumber']; ?>
														</td>
                                                         <td><?PHP echo $prd_name; ?></td>
                                                          <td><?PHP echo $model; ?></td>
                                                        <td><?PHP echo $prd_quantity; ?></td>
                                                        <td><?PHP echo $collection['company']; ?></td>
                                                       
                                                         <td>
															<?php echo ucfirst($collection['firstName']); ?>
														</td>
                                                        
                                                         <td>
															<?php echo ucfirst($collection['branch']); ?>
														</td>
                                                        
                                                         <td><?php
													 if($collection['paymentType']==1) { $paymentType = 'Proforma'; } 		 											                                           else if($collection['paymentType']==2) { $paymentType = 'Through Bank'; } 
											 else if($collection['paymentType']==3) { $paymentType = 'Direct Payment'; } 
											 else if($collection['paymentType']==4) { $paymentType = 'Againast Delivery'; } 

												 else if($collection['paymentType']==5) { $paymentType = 'By Check/DD'; } 
											 else if($collection['paymentType']==6) { $paymentType = 'Bank Transfer'; } 
												 else if($collection['paymentType']==7) { $paymentType = 'By Cash'; } 
															 echo $paymentType;
															 
															 //unset($paymentType);
															 
															 
															  ?>
														</td>
                                                        
                                                         <td>
															<?php echo $collection['grandTotal']; ?>
														</td>
<td>
															<?php echo $collection['amount']; ?>
														</td>
                                                       
                                                      
                                                       </tr>
<?php
 // to download file in excel format we have written this code.
$rowlist[] = $i;
$rowlist[] = $collection['paidDate'];
$rowlist[] = $collection['invoiceNumber'];
$rowlist[]	= $prd_name;
$rowlist[]	= $prd_quantity;
$rowlist[] = $collection['firstName'];
$rowlist[] = $collection['branch'];
$rowlist[] = $paymentType;
$rowlist[] = $collection['grandTotal']; 
$rowlist[] = $collection['amount']; 
 $list[] = $rowlist;
 unset($rowlist);



 $i++; } 
 $rowlist[] = '';
 $rowlist[] = '';
 $rowlist[] = '';
 $rowlist[] = '';
 $rowlist[] = '';
 $rowlist[] = '';
 $rowlist[] = '';
 $rowlist[] = '';
$rowlist[] = $totalCollection;
 $list[] = $rowlist;
 unset($rowlist);
 
 ?>
 <tr><td colspan="10"></td><td></td><td ><?php echo $totalCollection; ?></td></tr>
 <?php } else { ?> <tr><td colspan="7">No Data found.</td></tr> <?php }
 ?>                                        
  </tbody>
											</table>
                                            
                                                                                      <?php                              
$fp = fopen('excel/'.$sheet_num.'-payments-view.csv', 'w');

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
        
        
        <!-- page specific plugin scripts -->
		<script src="assets/js/date-time/bootstrap-datepicker.js"></script>
		<script src="assets/js/jqGrid/jquery.jqGrid.src.js"></script>
		<script src="assets/js/jqGrid/i18n/grid.locale-en.js"></script>
        
        <!-- page specific plugin styles -->
		<link rel="stylesheet" href="assets/css/jquery-ui.css" />
		<link rel="stylesheet" href="assets/css/datepicker.css" />
		<link rel="stylesheet" href="assets/css/ui.jqgrid.css" />




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
		
		
		function getEmployee(val)
		{
			
			document.getElementById("employeesList").style.display = 'block';
				$.ajax({url: "ajax/getEmployeesList.php?val="+val, success: function(result){
		$("#employeesList").html(result);
    }});	
			
		}
		
		
		function selectEmployee(id,firstName)
		{
			document.getElementById("employeesList").style.display = 'none';
			document.getElementById("eid").value = id;
			document.getElementById("employee").value = firstName;
	
		}
		
		
		function getBranch(val)
		{
			
			document.getElementById("branchList").style.display = 'block';
				$.ajax({url: "ajax/getBranchList.php?val="+val, success: function(result){
		$("#branchList").html(result);
    }});	
			
		}
		
		
		function selectBranch(id,firstName)
		{
			document.getElementById("branchList").style.display = 'none';
			document.getElementById("bid").value = id;
			document.getElementById("branch").value = firstName;
	
		}	
		
function goToPage(pid)
{
   window.location = 'payments.php?page='+pid;	
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
