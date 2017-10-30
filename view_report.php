<?php include("includes/header.php"); 

// get the data from daily_reports,customers,enquiries tables 
$proposals = mysql_query("select daily_reports.reportId, daily_reports.offerNo, date_format(daily_reports.reportDt,'%d-%m-%Y') as repDt, date_format(enquiries.enqDate,'%d-%m-%Y') as enqDate, daily_reports.company, daily_reports.enquiryNumber, daily_reports.contactPerson, daily_reports.designation, daily_reports.phone, daily_reports.email, daily_reports.clientStatus, daily_reports.leadType, daily_reports.leadStatus, daily_reports.futureDate, daily_reports.remarks, daily_reports.paymentType, customers.company, employees.firstName from daily_reports
left join customers on daily_reports.company = customers.customerId
left join employees on daily_reports.addedBy = employees.id
left join enquiries on daily_reports.company = enquiries.company
where daily_reports.reportId = '". $_GET['rid'] ."'");
 
 $proposal = mysql_fetch_array($proposals);
 

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
								<a href="reports.php">Reports </a>
							</li>

							<li class="active">View Report </li>
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
									<div class="col-xs-6">
										
										<div class="table-header">
											View Report
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div>
											
                                           <?php

										   ?> 
                                           
                                           
                                           <table id="sample-table-1" class="table table-striped table-bordered table-hover">
											<tbody>
												
												
                                                <tr>
                                                   <td>
													Report Date
												   </td>
                                                   <td>
													 <?php 
													 echo $proposal['repDt'];  													  
														  
 													  ?>
												  </td>
												</tr>
                                                
                                                
                                                <tr>
                                                   <td>
													Offer Number
												   </td>
                                                   <td>
													 <?php 
													 echo $proposal['offerNo'];  
														  
 													  ?>
												  </td>
												</tr>
                                                
                                                <tr>
                                                <td>
													Enquiry Date
												   </td>
                      <td><?PHP if($proposal['enqDate']!='') echo $proposal['enqDate'];  else echo '---'; ?></td>

                                                </tr>
                                                <tr>
                                                   <td>
													Enquiry Number
												   </td>
                                                   <td>
													 <?php echo ucfirst($proposal['enquiryNumber']); ?>
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
                                   <!-- #section:elements.form -->
									
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
