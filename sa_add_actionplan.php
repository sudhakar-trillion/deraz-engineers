<?php include("includes/sa_header.php"); 

 
    if(isset($_GET['aid']))  
	{
		
		$permission = mysql_query("select autoId, reportMonth from daily_report_authorization where autoId = '". $_GET['aid'] ."' and authorization = 'Yes' and reportType = 'monthly' and status = '0'");
		
		if(mysql_num_rows($permission)<1)
		{
		   header("location: authorizations.php");	
		}
		$permission = mysql_fetch_array($permission);
		
		if(isset($_POST['submit']))
		{	
					$visitDate = explode('/',$_POST['visitDate']);           
$visitDate = $visitDate[2].'-'.$visitDate[0].'-'.$visitDate[1];

		   mysql_query("insert into actionplans (`employeeId`, `month`, `visitDate`, `customerId`, `purposeVisit`, `expectedValue`, `remarks`, `dateTime`) values ('". $_SESSION['id'] ."', '". $_POST['month'] ."', '". $visitDate ."', '". $_POST['customer'] ."', '". $_POST['purposeVisit'] ."', '". $_POST['expectedValue'] ."', '". addslashes($_POST['remarks']) ."', NOW())");		
			
					$lastId = mysql_insert_id();
					
					if($lastId>0)
					{
					
					   mysql_query("update daily_report_authorization set status = '1' where autoId = '". $_GET['aid'] ."' and authorization = 'Yes' and reportType = 'monthly'");	
					   header("location: sa_add_actionplan.php?add=1");	
					}
					else
					{
					  header("location: sa_add_actionplan.php?error=1");	
					}
			 
		}
			
			
			
			 $currentDate =   date('d');
   $currentMonth =  $permission['reportMonth'];
  
   if($currentDate>2)
   {
	 $currentMonth = $currentMonth+1; 
   } 
			
	}
	else
	{


		if(isset($_POST['submit']))
		{
			
			$cm = date('m');
			$cd = date('d');
			if(($cm==$_POST['month'] && $cd<3) || ($cm<$_POST['month']))
			{
				
				
				
				
		
			


  
$visitDate = explode('/',$_POST['visitDate']);           
$visitDate = $visitDate[2].'-'.$visitDate[0].'-'.$visitDate[1];

		   mysql_query("insert into actionplans (`employeeId`, `month`, `visitDate`, `customerId`, `purposeVisit`, `expectedValue`, `remarks`, `dateTime`) values ('". $_SESSION['id'] ."', '". $_POST['month'] ."', '". $visitDate ."', '". $_POST['customer'] ."', '". $_POST['purposeVisit'] ."', '". $_POST['expectedValue'] ."', '". addslashes($_POST['remarks']) ."', NOW())");			
			
			$lastId = mysql_insert_id();
			
			if($lastId>0)
			{
			  header("location: sa_add_actionplan.php?add=1");	
			}
			else
			{
			  header("location: sa_add_actionplan.php?error=1");	
			}
			
			}
			else
			{
				
					mysql_query("insert into daily_report_authorization (`employeeId`, `authorization`, `reportMonth`, `createdOn`, `reportType`) values ('". $_SESSION['id'] ."', 'No', '". $_POST['month'] ."', NOW(), 'monthly')");			
			
					$lastId = mysql_insert_id();
					
					if($lastId>0)
					{
					   header("location: sa_add_actionplan.php?authorizationRequired=1");	
					}
					else
					{
					  header("location: sa_add_actionplan.php?error=1");	
					}
			 
			 
			}
			
		}
		
		
   $currentDate =   date('d');
   $currentMonth =  date('m');
  
   if($currentDate>2)
   {
	 $currentMonth = $currentMonth+1; 
   }	
		
	}

$customers = mysql_query("select customerId, company from customers where addedBy = '". $_SESSION['id'] ."' order by company");

  
  

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
								<a href="sa_actionplans.php">Action Plans</a>
							</li>

							<li class="active">Add Action Plan</li>
						</ul><!-- /.breadcrumb -->
                       
                     

						<!-- #section:basics/content.searchbox -->
						<div class="nav-search" id="nav-search">
							<form class="form-search">
								<span class="input-icon">
									<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
									<i class="ace-icon fa fa-search nav-search-icon"></i>
								</span>
							</form>
						</div><!-- /.nav-search -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- #section:settings.box -->
						<div class="ace-settings-container" id="ace-settings-container">
							<div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
								<i class="ace-icon fa fa-cog bigger-130"></i>
							</div>

							<div class="ace-settings-box clearfix" id="ace-settings-box">
								<div class="pull-left width-50">
									<!-- #section:settings.skins -->
									<div class="ace-settings-item">
										<div class="pull-left">
											<select id="skin-colorpicker" class="hide">
												<option data-skin="no-skin" value="#438EB9">#438EB9</option>
												<option data-skin="skin-1" value="#222A2D">#222A2D</option>
												<option data-skin="skin-2" value="#C6487E">#C6487E</option>
												<option data-skin="skin-3" value="#D0D0D0" selected="selected">#D0D0D0</option>
											</select>
										</div>
										<span>&nbsp; Choose Skin</span>
									</div>

									<!-- /section:settings.skins -->

									<!-- #section:settings.navbar -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
										<label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
									</div>

									<!-- /section:settings.navbar -->

									<!-- #section:settings.sidebar -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
										<label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
									</div>

									<!-- /section:settings.sidebar -->

									<!-- #section:settings.breadcrumbs -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
										<label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
									</div>

									<!-- /section:settings.breadcrumbs -->

									<!-- #section:settings.rtl -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
										<label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
									</div>

									<!-- /section:settings.rtl -->

									<!-- #section:settings.container -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container" />
										<label class="lbl" for="ace-settings-add-container">
											Inside
											<b>.container</b>
										</label>
									</div>

									<!-- /section:settings.container -->
								</div><!-- /.pull-left -->

								<div class="pull-left width-50">
									<!-- #section:basics/sidebar.options -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-hover" />
										<label class="lbl" for="ace-settings-hover"> Submenu on Hover</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-compact" />
										<label class="lbl" for="ace-settings-compact"> Compact Sidebar</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-highlight" />
										<label class="lbl" for="ace-settings-highlight"> Alt. Active Item</label>
									</div>

									<!-- /section:basics/sidebar.options -->
								</div><!-- /.pull-left -->
							</div><!-- /.ace-settings-box -->
						</div><!-- /.ace-settings-container -->

						<!-- /section:settings.box -->
					

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								

							



								<div class="row">
									<div class="col-xs-6">
										<?php

										?>
										<div class="table-header">
											Add Action plan
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div>
											
                                           <?php
	   if(isset($_GET['add']))
{ $alertMsg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Action Plan has been added!</div>'; }
else if(isset($_GET['error']))
{ $alertMsg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }
else if(isset($_GET['authorizationRequired'])) 
{ $alertMsg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Authorization required.</div>'; }


  if(isset($alertMsg)) { echo $alertMsg; }
									
									// 							9885486645
	   ?> 
                                            
                                           
                                            
                               
                                            
                                           
                                
                                            <form class="form-horizontal" role="form" action="" method="post">
									<!-- #section:elements.form -->
									 <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="customer"> Month </label>

										<div class="col-sm-9">
											<select id="month" name="month" class="form-control">
                                            <option value="0">Select Month</option>
                                            <option value="01" <?php if($currentMonth=='01') { ?> selected="selected" <?php } ?>>January</option>
                                            <option value="02" <?php if($currentMonth=='02') { ?> selected="selected" <?php } ?>>Febraury</option>
                                            <option value="03" <?php if($currentMonth=='03') { ?> selected="selected" <?php } ?>>March</option>
                                            <option value="04" <?php if($currentMonth=='04') { ?> selected="selected" <?php } ?>>April</option>
                                            <option value="05" <?php if($currentMonth=='05') { ?> selected="selected" <?php } ?>>May</option>
                                            <option value="06" <?php if($currentMonth=='06') { ?> selected="selected" <?php } ?>>June</option>
                                            <option value="07" <?php if($currentMonth=='07') { ?> selected="selected" <?php } ?>>July</option>
                                            <option value="08" <?php if($currentMonth=='08') { ?> selected="selected" <?php } ?>>August</option>
                                            <option value="09" <?php if($currentMonth=='09') { ?> selected="selected" <?php } ?>>September</option>
                                            <option value="10" <?php if($currentMonth=='10') { ?> selected="selected" <?php } ?>>October</option>
                                            <option value="11" <?php if($currentMonth=='11') { ?> selected="selected" <?php } ?>>November</option>
                                            <option value="12" <?php if($currentMonth=='12') { ?> selected="selected" <?php } ?>>December</option>
											</select>
										</div>
									</div>
                                    
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="visitDate"> Visit Date </label>

										<div class="col-sm-9">
			<input type="text" id="visitDate" name="visitDate" placeholder="Visit Date" class="col-xs-10 col-sm-12 date-picker"  />
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="customer"> Customer </label>

										<div class="col-sm-9">
											<select id="customer" name="customer" class="form-control">
                                            <option value="0">Select Customer</option>
                                            <?php
											while($customer = mysql_fetch_array($customers))
											{ ?> <option value="<?php echo $customer['customerId']; ?>"><?php echo ucfirst($customer['company']); ?></option>
											<?php }
											?>
                                            
                                            </select>
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="purposeVisit"> Purpose of Visit </label>

										<div class="col-sm-9">
											<select id="purposeVisit" name="purposeVisit" class="form-control">
                                            <option value="0">Select Purpose of Visit</option>
                                            <option value="Follow up">Follow up</option>
                                            <option value="Payment collection">Payment collection</option>
                                            <option value="PO collection">PO collection</option>
                                            <option value="New enquiry">New enquiry</option>
                                            </select>
										</div>
									</div>
                                    
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="expectedValue"> Expected Value </label>

										<div class="col-sm-9">
											<input type="text" id="expectedValue" name="expectedValue" placeholder="Expected Value" class="form-control" />
										</div>
									</div>
                                    
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="remarks"> Remarks </label>

										<div class="col-sm-9">
											<textarea  id="remarks" name="remarks" placeholder="Remarks" class="form-control"></textarea>
										</div>
									</div>
                                    
                                    
                                    <div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="submit" name="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>

											&nbsp; &nbsp; &nbsp;
											<button class="btn" type="reset">
												<i class="ace-icon fa fa-undo bigger-110"></i>
												Reset
											</button>
										</div>
									</div>

                                    
                                    </form>
                                    
										</div>
									</div>
								</div>

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
