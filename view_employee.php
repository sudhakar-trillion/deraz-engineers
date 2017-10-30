<?php include("includes/header.php"); 

// get the employee data from employees table
$customer = mysql_query("select rolls.roll, employees.firstName, employees.fatherName, employees.dob, employees.bloodGroup, employees.cPincode, employees.email, employees.officialEmail, employees.gender, employees.designation, employees.dateJoining, employees.emergencyContact, employees.emergencyName, employees.emergencyRelation, employees.personalMobile, employees.cHno, employees.cStreet, employees.cLandmark, employees.cCity,employees.cArea, employees.pHno, employees.pStreet, employees.pLandmark, employees.pCity, employees.pArea, employees.pPincode, employees.status, employees.dateTime from employees left join rolls on employees.roll = rolls.roll_Id where employees.id = '". $_GET['eid'] ."'");
	$customer = mysql_fetch_array($customer);
	
	$dob = explode('-',$customer['dob']);
	$dob = $dob[2].'-'.$dob[1].'-'.$dob[0];
	
	$doj = explode('-',$customer['dateJoining']);
	$doj = $doj[2].'-'.$doj[1].'-'.$doj[0];


  

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
								<a href="employees.php">Employees</a>
							</li>

							<li class="active">View Employee</li>
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
									<div class="col-xs-5">
										
										<div class="table-header">
											View Employee
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
													
											<tr><td> Name </td><td><?php echo $customer['firstName']; ?></td></tr>
                                            <tr><td> Gender </td><td><?php echo $customer['gender']; ?></td></tr>
                                           
                                            <tr><td> Department </td><td><?php echo $customer['roll']; ?></td></tr>
                                            <tr><td> Designation </td><td><?php echo $customer['designation']; ?></td></tr>
                                            
                                            <tr><td> Official Email </td><td><?php echo $customer['officialEmail']; ?></td></tr>
                                            <tr><td> Personal Email </td><td><?php echo $customer['email']; ?></td></tr>
                                            <tr><td> Personal Mobile </td><td><?php echo $customer['personalMobile']; ?></td></tr>
                                            
                                            <tr><td> Blood Group </td><td><?php echo $customer['bloodGroup']; ?></td></tr>
                                            <tr><td> Date of Birth </td><td><?php echo $dob; ?></td></tr>
                                            <tr><td> Date of Join </td><td><?php echo $doj; ?></td></tr>
                                                                                       
                               <tr><td>Emergency Contact </td><td><?php echo $customer['emergencyContact']; ?></td></tr>
                            <tr><td> Emergency Name</td><td><?php echo $customer['emergencyName']; ?></td></tr>
                            <tr><td>Relation to the Employee </td><td><?php echo $customer['emergencyRelation']; ?></td></tr>
                                           
                                            <tr><td colspan="2"> <b>Current Address</b>  </td></tr>
                                            <tr><td> H.no  </td><td><?php echo $customer['cHno']; ?></td></tr>
                                            <tr><td> Street / Colony </td><td><?php echo $customer['cStreet']; ?></td></tr>
                                            <tr><td> Landmark </td><td><?php echo $customer['cLandmark']; ?></td></tr>
                                            <tr><td> City </td><td><?php echo $customer['cCity']; ?></td></tr>
                                            <tr><td> Area </td><td><?php echo $customer['cArea']; ?></td></tr>
                                            <tr><td> Pincode </td><td><?php echo $customer['cPincode']; ?></td></tr>
                                            
                                            <tr><td colspan="2"> <b>Permanent Address</b>  </td></tr>
                                            <tr><td> H.no  </td><td><?php echo $customer['pHno']; ?></td></tr>
                                            <tr><td> Street / Colony </td><td><?php echo $customer['pStreet']; ?></td></tr>
                                            <tr><td> Landmark </td><td><?php echo $customer['pLandmark']; ?></td></tr>
                                            <tr><td> City </td><td><?php echo $customer['pCity']; ?></td></tr>
                                            <tr><td> Area </td><td><?php echo $customer['pArea']; ?></td></tr>
                                            <tr><td> Pincode </td><td><?php echo $customer['pPincode']; ?></td></tr>
                                            
                                            
                                            <tr><td> Status </td><td><?php if($customer['status']==1) 
											                            { ?> <span class="label label-sm label-success">Active</span> <?php } else { 
																		?> <span class="label label-sm label-warning">Dective</span> <?php } ?></td></tr>
                                            <tr><td> Added On </td><td><?php $date = explode(' ',$customer['dateTime']);
											                                 $date = explode('-',$date[0]);
																			 echo $date[2].'-'.$date[1].'-'.$date[0]; ?></td></tr>
                                           
                                            
								    
                                </tbody>
                                </table>
                                    
										</div>
									</div>
								</div>

								<!-- PAGE CONTENT ENDS -->
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
