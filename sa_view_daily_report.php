<?php include("includes/sa_header.php"); 

// data comes from daily_reports table
$result = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.contactPerson, daily_reports.enquiryNumber, date_format(enquiries.dateTime,'%d-%m-%Y') enqDate, daily_reports.enquiryNumber, daily_reports.reportDate, daily_reports.contactPerson, daily_reports.designation, daily_reports.phone, daily_reports.email, daily_reports.clientStatus, daily_reports.leadType, daily_reports.leadStatus, daily_reports.futureDate, daily_reports.remarks, daily_reports.po, daily_reports.poNo, daily_reports.poValue, daily_reports.paymentType, daily_reports.addedOn, daily_reports.total, customers.company  from daily_reports 
                          left join customers on daily_reports.company = customers.customerId
  						  left join enquiries on daily_reports.enquiryNumber = enquiries.enquiryNumber
						  where daily_reports.reportId = '". $_GET['rid'] ."' and daily_reports.addedBy = '". $_SESSION['id'] ."' group by daily_reports.reportId 
						  order by daily_reports.reportId desc");
						  
						  
						  
						  
						  if(mysql_num_rows($result)<1)
	{ header("location: sa_daily_reports.php"); exit; 	}
	$row = mysql_fetch_array($result);

 $revision = mysql_query("select revisionId, subTotal, discount, duty, dutyPercentage, vat, vatPercentage, pf, pfPercentage, total, grandTotal  from daily_reports_revision where reportId = '". $row['reportId'] ."' and revision = '". $row['currentRevisionId'] ."'");
 
  $revision = mysql_fetch_array($revision);
 
 
  // data comes from daily_reports,daily_reports_data table
 $orders = mysql_query("select categories.category, brands.brand, products.productId, products.product, daily_reports_data.id, daily_reports_data.price, daily_reports_data.quantity, daily_reports_data.amount, pm.ModelNo
 from daily_reports_data
 left join categories on daily_reports_data.categoryId = categories.id
 left join brands on daily_reports_data.brandId = brands.id
 left join products on daily_reports_data.productId = products.productId
 left join product_model as pm on pm.ModelId=daily_reports_data.modelId
 where daily_reports_data.revisionId = '". $revision['revisionId'] ."'");
 
 
 
/*					   
$orders = mysql_query("select categories.category, brands.brand, products.product, daily_reports_data.price, daily_reports_data.quantity, daily_reports_data.amount 
                       from daily_reports_revision
					   left join daily_reports_data on daily_reports_revision.revisionId = daily_reports_data.revisionId 
					   left join categories on daily_reports_data.categoryId = categories.id
					   left join brands on daily_reports_data.brandId = brands.id
					   left join products on daily_reports_data.productId = products.productId
					   where daily_reports_revision.reportId = '". $_GET['rid'] ."'");*/
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
								<a href="sa_daily_reports.php">Daily Reports</a>
							</li>

							<li class="active">View Report</li>
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
									<div class="col-xs-4">
										
										<div class="table-header">
											View Report
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div>
											
                                           <?php
	   if(isset($_GET['add']))
{ $alertMsg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Customer has been added!</div>'; }
else if(isset($_GET['error']))
{ $alertMsg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  if(isset($alertMsg)) { echo $alertMsg; }
										   ?> 
                                            
                                           
                                            
                               
                                            
                                           
                                
                                           <table class="table table-striped table-bordered table-hover">
												<tbody>
											
											<tr><td> Date </td><td><?php $date = explode('-',$row['reportDate']);
															      echo $date = $date[2].'-'.$date[1].'-'.$date[0];  ?></td></tr>

                                            <tr><td> Enquiry Date </td><td>
															<?php if($row['enquiryNumber'] !=''){ 
															echo $row['enqDate']; }
															else { echo "---"; }
															 ?>
														</td></tr>
                                            <tr><td> Enquiry Number </td><td>
															<?php if($row['enquiryNumber'] !=''){ 
															echo $row['enquiryNumber']; }
															else { echo "---"; }
															 ?>
														</td></tr>
                                            <tr><td> Company Name </td><td><?php echo $row['company']; ?></td></tr>
                                            <tr><td> Contact Person </td><td><?php echo $row['contactPerson']; ?></td></tr>
                                            <tr><td> Designation </td><td><?php echo $row['designation']; ?></td></tr>
                                            <tr><td> Email </td><td><?php echo $row['email']; ?></td></tr>
                                            <tr><td> Phone </td><td><?php echo $row['phone']; ?></td></tr>
                                            <tr><td> Remarks </td><td><?php echo $row['remarks']; ?></td></tr>
                                            <tr><td> Client Status </td><td><?php if($row['clientStatus']==1) { echo 'Existing'; } else if($row['clientStatus']==2) { echo 'New'; } ?></td></tr>
                                            <tr><td> Lead Type </td><td><?php if($row['leadType']==1) { echo 'General Enquiry'; } 
															 else if($row['leadType']==2) { echo 'Customer Call'; } 
															 else if($row['leadType']==3) { echo 'Reference'; } 
															 else if($row['leadType']==4) { echo 'Offer Followup'; } 
															  else if($row['leadType']==5) { echo 'Order Followup'; } 
															 else if($row['leadType']==6) { echo 'Payment Followup'; } 
															 else if($row['leadType']==7) { echo 'General Visit'; } 
															 ?></td></tr>
                                                             
                                           <tr><td> Lead Status </td><td><?php echo $row['leadStatus']; 
															
															 ?></td></tr>
                                                             
                                            <tr><td> Future Date </td><td><?php $date = explode('-',$row['futureDate']);
															      echo $date = $date[2].'-'.$date[1].'-'.$date[0];  ?></td></tr>  
                                            <tr><td> Payment Type </td><td><?php if($row['paymentType']==1) { echo 'Proforma'; } 
															 else if($row['paymentType']==2) { echo 'Through Bank'; } 
															 else if($row['paymentType']==3) { echo 'Direct Payment'; } 
															 else if($row['paymentType']==4) { echo 'Against Delivery'; } 
															  else if($row['paymentType']==5) { echo 'By Cheque/DD'; } 
															   else if($row['paymentType']==6) { echo 'Bank Transfer'; } 
															    else if($row['paymentType']==7) { echo 'By Cash'; } 
															 
															 
															 
															 ?></td></tr>                         
                                             <tr><td> Total Value </td><td><?php  echo $revision['grandTotal']; ?></td></tr>
                                            
                                                                             
                                           
                                            
								    
                                </tbody>
                                </table>
                                    
										</div>
									</div>
                                    
                                    <div class="col-xs-8">
										
										<div class="table-header">
											Order Data
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div>
                                        
                                        
                                           <table class="table table-striped table-bordered table-hover">
                                           <thead>
                                           <tr>
                                           <th>S.no</th>
                                           <th>Category</th>
                                           <th>Brand</th>
                                           <th>Product</th>
                                           <th>Model </th>
                                           <th>Price</th>
                                           <th>Quantity</th>
                                           <th>Amount</th>
                                           </tr>
                                           </thead>
												<tbody>
                                                <?php 
												if(mysql_num_rows($orders)>0) {
												$i=1; while($order = mysql_fetch_array($orders))
												{ ?>
											<tr><td><?php echo $i; ?></td>
                                                <td><?php  echo $order['category']; ?></td>
                                                <td><?php  echo $order['brand']; ?></td>
                                                  <td><?php  echo $order['product']; ?></td>
                                                  <td> <?PHP echo $order['ModelNo'];?></td>
                                                <td><?php  echo $order['price']; ?></td>
                                                <td><?php  echo $order['quantity']; ?></td>
                                                <td><?php  echo $order['amount']; ?></td>
                                                </tr>
                                                <?php $i++; } } else { ?>
                                                <tr><td colspan="7">No Data found.</td></tr>
                                                <?php } ?>
                           <tr><td colspan="5"></td><td>Sub Total</td><td><?php  echo $revision['subTotal']; ?></td></tr>
                           <tr><td colspan="5"></td><td>Discount</td><td><?php  echo $revision['discount']; ?></td></tr>
                           <tr><td colspan="5"></td><td>Total</td><td><?php  echo $revision['total']; ?></td></tr>
                           <tr><td colspan="5"></td><td>Duty</td><td><?php  echo $revision['duty']; ?></td></tr>
                           <tr><td colspan="5"></td><td>VAT</td><td><?php  echo $revision['vat']; ?></td></tr>
                           <tr><td colspan="5"></td><td>PF</td><td><?php  echo $revision['pf']; ?></td></tr>
                           <tr><td colspan="5"></td><td>Grand Total</td><td><?php  echo $revision['grandTotal']; ?></td></tr>
                                                                  </tbody>
                                                                  </table>
                                        
                                        
                                        </div>
                                        </div>
								</div> <!-- row -->

								<div id="modal-table" class="modal fade" tabindex="-1">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">&times;</span>
													</button>
													Results for "Latest Registered Domains
												</div>
											</div>

											

											<div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close
												</button>

												<ul class="pagination pull-right no-margin">
													<li class="prev disabled">
														<a href="#">
															<i class="ace-icon fa fa-angle-double-left"></i>
														</a>
													</li>

													<li class="active">
														<a href="#">1</a>
													</li>

													<li>
														<a href="#">2</a>
													</li>

													<li>
														<a href="#">3</a>
													</li>

													<li class="next">
														<a href="#">
															<i class="ace-icon fa fa-angle-double-right"></i>
														</a>
													</li>
												</ul>
											</div>
										</div><!-- /.modal-content -->
									</div><!-- /.modal-dialog -->
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
