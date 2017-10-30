<?php include("includes/header.php"); 
/* the relation between daily_reports table and invoices table is 'reportId'.   */

//  get the order data from daily_reports and invoices and enquiries and customers tables
$orders = mysql_query("select daily_reports.reportId, date_format(daily_reports.offerDate,'%d-%m-%Y') as offerDate, date_format(daily_reports.poDateTime, '%d-%m-%Y') as poDateTime, daily_reports.reportDate, daily_reports.poNo,daily_reports.offerNo, date_format(daily_reports.reportDate,'%d-%m-%Y') as reportDate, daily_reports.invoice, daily_reports.invoiceDateTime, daily_reports.enquiryNumber, customers.company, customers.location, customers.contactPerson, customers.email, customers.phone, customers.address, daily_reports.currentRevisionId, invoices.invoiceId, invoices.subTotal, invoices.discount, invoices.total, invoices.dutyPercentage, invoices.dutyAmount, invoices.pfPercentage, invoices.pfAmount, invoices.grandTotal, date_format(enquiries.dateTime,'%d-%m-%Y') as enqDate
from invoices
left join daily_reports on invoices.reportId = daily_reports.reportId
left join customers on daily_reports.company = customers.customerId
left join enquiries on enquiries.enquiryNumber=daily_reports.enquiryNumber
where invoices.invoiceId = '". $_GET['iid'] ."'");
 
$order = mysql_fetch_array($orders);

//get the reviosion id]

$revid = mysql_query("select daily_reports_data.revisionId from daily_reports_data
 
left join categories on daily_reports_data.categoryId = categories.id
left join brands on daily_reports_data.brandId = brands.id
left join products on daily_reports_data.productId = products.productId
left join daily_reports_revision drv on drv.revisionId = daily_reports_data.revisionId
where daily_reports_data.invoice_id = '". $order['invoiceId'] ."'  order by daily_reports_data.id DESC LIMIT 1 ");


$revide = mysql_fetch_assoc($revid);
$revisionId = $revide['revisionId']; 

															
$items = mysql_query("select categories.category, brands.brand, products.product, products.code,daily_reports_data.revisionId,  daily_reports_data.price, daily_reports_data.quantity, daily_reports_data.amount, daily_reports_data.taxSystem, daily_reports_data.taxPercentage, daily_reports_data.taxAmount, pm.ModelNo from daily_reports_data
 
left join categories on daily_reports_data.categoryId = categories.id
left join brands on daily_reports_data.brandId = brands.id
left join products on daily_reports_data.productId = products.productId
left join product_model as pm on pm.ModelId=daily_reports_data.modelId
left join daily_reports_revision drv on drv.revisionId = daily_reports_data.revisionId
where daily_reports_data.revisionId ='".$revisionId."'   order by daily_reports_data.id DESC ");

 

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
								<a href="orders.php">Orders </a>
							</li>

							<li class="active">View Invoice </li>
						</ul><!-- /.breadcrumb -->

						<!-- #section:basics/content.searchbox -->
						<!-- /.nav-search -->

						<!-- /section:basics/content.searchbox -->
					</div>
          <button type="button" class="btn btn-primary btn-sm" onclick="printDiv('print_order<?php echo $order['invoiceId']; ?>')" value="Print">
          <span class="glyphicon glyphicon-print"></span> 
       </button>
                 

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- #section:settings.box -->
						<!-- /.ace-settings-container -->

						<!-- /section:settings.box -->
					
                    <div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="space-6"></div>

								<div class="row">
									<div class="col-sm-10 col-sm-offset-1">
										<!-- #section:pages/invoice -->
										<div id="print_order" class="widget-box transparent">
											<div class="widget-header widget-header-large">
												<h3 class="widget-title grey lighter">
													Invoice: 
													<span class="red">#<?php echo $order['invoice']; ?></span>
												</h3>
                                                
                                                
                                                <?php $date = explode(' ',$order['invoiceDateTime']);
												      $date = explode('-',$date[0]);
												?>

												<!-- #section:pages/invoice.info -->
												<div class="widget-toolbar no-border invoice-info">
													<br>
													<span class="invoice-info-label">Date:</span>
													<span class="blue"><?php  echo $date[2].'-'.$date[1].'-'.$date[0]; ?></span>
												</div>

												<!--<div class="widget-toolbar hidden-480">
													<a href="#">
														<i class="ace-icon fa fa-print"></i>
													</a>
												</div>-->

												<!-- /section:pages/invoice.info -->
											</div>

											<div class="widget-body">
												<div class="widget-main padding-24">
													<div class="row">
														<div class="col-sm-6">
															<div class="row">
																<div class="col-xs-11 label label-lg label-info arrowed-in arrowed-right">
																	<b>Customer Info</b>
																</div>
															</div>

															<div>
																<ul class="list-unstyled spaced">
                                                                <li>
							<i class="ace-icon fa fa-caret-right blue"></i>Enquiry No:<?php if($order['enquiryNumber']!='')
													 {
													  echo $order['enquiryNumber']; }
													  else
													  {
														  echo '--';
													  }
													  
													  ?>
																	</li>
                                                                    <li>
                                                                    	<i class="ace-icon fa fa-caret-right blue"></i>Enquiry Date:
                                                                        <?PHP echo $order['enqDate']; ?>
                                                                    </li>
<!-- daily_reports.reportDate, daily_reports.poNo,daily_reports.offerNo, -->                                                                    
																	
                                                                    <li>
                                                                    	<i class="ace-icon fa fa-caret-right blue"></i>Offer Number:
                                                                        <?PHP echo $order['offerNo'];?>
                                                                        
                                                                    </li>
                                                                    
                                                                       <li>
                                                                    	<i class="ace-icon fa fa-caret-right blue"></i>Offer Date:
                                                                        <?PHP echo $order['offerDate'];?>
                                                                        
                                                                    </li> 
                                                                    
                                                                    
                                                                    
                                                                     <li>
                                                                    	<i class="ace-icon fa fa-caret-right blue"></i>Report Date:
                                                                        <?PHP echo $order['reportDate'];?>
                                                                        
                                                                    </li>
                                                                    
                                                                     <li>
                                                                    	<i class="ace-icon fa fa-caret-right blue"></i>PO Number:
                                                                        <?PHP echo $order['poNo'];?>
                                                                        
                                                                    </li>
                                                                    
                                                                    <li>
                                                                    	<i class="ace-icon fa fa-caret-right blue"></i>PO Date:
                                                                        <?PHP echo $order['poDateTime']; ?>
                                                                    </li>
																	<li>
							<i class="ace-icon fa fa-caret-right blue"></i><?php echo $order['company']; ?>
																	</li>

																	<li>
							<i class="ace-icon fa fa-caret-right blue"></i><?php echo $order['location']; ?>
																	</li>
                                                                    <li>
							<i class="ace-icon fa fa-caret-right blue"></i>Person: <?php echo $order['contactPerson']; ?>
																	</li>

																	<li>
							<i class="ace-icon fa fa-caret-right blue"></i>Email:  <?php echo $order['email']; ?>
																	</li>

																	<li>
																		<i class="ace-icon fa fa-caret-right blue"></i>
Phone:
												<b class="red"><?php echo $order['phone']; ?></b>
																	</li>
     <li>
																		<i class="ace-icon fa fa-caret-right blue"></i>
																		Address: <?php echo $order['address']; ?>
																	</li>
																</ul>
															</div>
														</div><!-- /.col -->

																
															</div>
														</div><!-- /.col -->
													</div><!-- /.row -->

													<div class="space"></div>

													<div>
														<table class="table table-striped table-bordered">
															<thead>
																<tr>
																	<th class="center">#</th>
																	<th>Category</th>
																	<th>Brand</th>
																	<th>Product</th>
                                                                    <th>Model No.</th>
                                                                    <th>Price</th>
                                                                    <th>Quantity</th>
                                                                    <th>Total</th>
																</tr>
															</thead>

															<tbody>
                                                            
                                                            <?php $i = 1; $total = 0;
															

															
															
															
															
															//echo mysql_num_rows($items); exit; 
															 while($item = mysql_fetch_array($items)) {	 ?>
															
                                                                <tr>
																	<td class="center"><?php echo $i; ?></td>

																	<td>
																		<?php echo $item['category']; ?>
																	</td>
                                                                    <td>
																		<?php echo $item['brand']; ?>
																	</td>
																	<td>
																		<?php echo $item['product']; ?>
																	</td>
                                                                    
                                                                    <td>
																		<?php echo $item['ModelNo']; ?>
																	</td>
                                                                    <td>
																		<?php echo $item['price']; ?>
																	</td>
                                                                    <td>
																		<?php echo $item['quantity']; ?>
																	</td>
                                                                    <td>
																		<?php echo $item['amount']; ?>
																	</td>
																</tr>
                                                                
      <tr><td colspan="6"></td><td><?php echo $item['taxSystem'].' '.$item['taxPercentage'].'%'; ?></td><td><?php echo $item['taxAmount']; ?></td></tr>
                                                                
                                                                
                                                                
                                                                <?php $total = $item['amount']+$total; $i++; } 	 ?>
                          <tr><td colspan="6"></td><td>Sub Total</td><td><?php echo $order['subTotal']; ?></td></tr>
                          <tr><td colspan="6"></td><td>Discount</td><td><?php echo $order['discount']; ?>%</td></tr>
                          <tr><td colspan="6"></td><td>Total</td><td><?php echo $order['total']; ?></td></tr>
                          <tr><td colspan="6"></td><td>Duty</td><td><?php echo $order['dutyAmount']; ?></td></tr>
                          <tr><td colspan="6"></td><td>PF</td><td><?php echo $order['pfAmount']; ?></td></tr>
																
																
															</tbody>
														</table>
													</div>

													<div class="hr hr8 hr-double hr-dotted"></div>

													<div class="row">
														<div class="col-sm-5 pull-right">
															<h4 class="pull-right">
																Total amount :
                                                                
                                                              
                                                                
																<span class="red">  <i class="fa fa-inr"></i> <?php echo $order['grandTotal']; ?></span>
															</h4>
														</div>
														<div class="col-sm-7 pull-left">  </div>
													</div>

													<div class="space-6"></div>
													
												</div>
											</div>
										</div>

										<!-- /section:pages/invoice -->
									</div>
								</div>

								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div>

						

									
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
        
        
        
        <script src="assets/js/date-time/bootstrap-datepicker.js"></script>
		<script src="assets/js/date-time/bootstrap-timepicker.js"></script>
		<script src="assets/js/date-time/moment.js"></script>
		<script src="assets/js/date-time/daterangepicker.js"></script>
		<script src="assets/js/date-time/bootstrap-datetimepicker.js"></script>

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
        <script>
	 function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}


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
