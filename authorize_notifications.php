<?php include("includes/header.php"); 

  // delete 
  if(isset($_GET['did']))
  {
	 mysql_query("delete from daily_report_authorization where autoId = '". $_GET['did'] ."'");  
	 header("location: authorize_notifications.php?delete=success");
  }
  
   // update
  if(isset($_GET['aid']))
  {
	 mysql_query("update daily_report_authorization set authorization = 'Yes', authorizedOn = NOW(), authorizedBy = '". $_SESSION['id'] ."' where autoId = '". $_GET['aid'] ."'");  
	 header("location: authorize_notifications.php?update=success");
  }
 
 
 
	 
 $dailyNotifications = mysql_query("select daily_report_authorization.autoId, daily_report_authorization.authorization, daily_report_authorization.reportDate, daily_report_authorization.reportMonth, daily_report_authorization.createdOn, daily_report_authorization.reportType, daily_report_authorization.status, employees.firstName from daily_report_authorization
	 left join employees on daily_report_authorization.employeeId = employees.id");



?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Aurhorize Notifications </li>
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
												<option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
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
								

							        <?php
				if(isset($_GET['delete']))
				{
		$alert = '<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											
											Record has been deleted.
										</div>'; 
				} else if(isset($_GET['update']))
				{
		$alert = '<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											
											Permission granted.
										</div>'; 
				}
									
					if(isset($alert)) { echo $alert; }	?>



								<div class="row">
									<div class="col-xs-12">
										
										<div class="table-header">
											Notifications
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div>
											<table id="sample-table-1" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th class="center">
															<label class="position-relative">
																<input type="checkbox" class="ace" />
																<span class="lbl"></span>
															</label>
														</th>
														<th>Date / Month</th>
                                                        <th>Executive</th>
                                                      	<th>Authorization</th>													
                                                    	<th>Status</th>
                                                        <th>Record Date</th>
                                                        <th>Action</th>
														
													</tr>
												</thead>

												<tbody>
													
														
																	

  <?php 
  
  while($dailyNotification = mysql_fetch_array($dailyNotifications))
  {
	  
	
	
	
	if(strcmp($dailyNotification['reportType'],'daily')==0)
										 {
											 $reportDate = explode('-',$dailyNotification['reportDate']);
										     $reportDate = $reportDate[2].'-'.$reportDate[1].'-'.$reportDate[0];
											 
											 $dateMonth = $reportDate; 
										 }
										 else if(strcmp($dailyNotification['reportType'],'monthly')==0)
										 {  
										 
										       if($dailyNotification['reportMonth']==1) {  $reportMonth = 'January'; }
										  else if($dailyNotification['reportMonth']==2) {  $reportMonth = 'Febraury'; }
										  else if($dailyNotification['reportMonth']==3) {  $reportMonth = 'March'; }
										  else if($dailyNotification['reportMonth']==4) {  $reportMonth = 'April'; }
										  else if($dailyNotification['reportMonth']==5) {  $reportMonth = 'May'; }
										  else if($dailyNotification['reportMonth']==6) {  $reportMonth = 'June'; }
										  else if($dailyNotification['reportMonth']==7) {  $reportMonth = 'July'; }
										  else if($dailyNotification['reportMonth']==8) {  $reportMonth = 'August'; }
										  else if($dailyNotification['reportMonth']==9) {  $reportMonth = 'September'; }
										  else if($dailyNotification['reportMonth']==10) {  $reportMonth = 'October'; }
										  else if($dailyNotification['reportMonth']==11) {  $reportMonth = 'November'; }
										  else if($dailyNotification['reportMonth']==12) {  $reportMonth = 'December'; }
										  else if($dailyNotification['reportMonth']==0) {  $reportMonth = ''; }
										  
										 
											 $dateMonth = $reportMonth;  
										 }
	  
	  
	 
	  
	  
	 ?><tr>
														<td class="center">
															<label class="position-relative">
																<input type="checkbox" class="ace" />
																<span class="lbl"></span>
															</label>
														</td>

														<td>
															<?php echo  $dateMonth; ?>
														</td>
                                                        <td>
															<?php echo ucfirst($dailyNotification['firstName']); ?>
														</td>
                                                        <td>
                                                        <?php 
                                                         if(strcmp($dailyNotification['authorization'],'Yes')==0)
	  {
		   ?> <span class="label label-sm label-success">Granted</span> <?php
	  }
	  else if(strcmp($dailyNotification['authorization'],'No')==0)
	  {
		 ?> <span class="label label-sm label-danger">Pending</span> <?php
	  } ?>
															
														</td>
                                                        <td>
															<?php if($dailyNotification['status']==0) { echo 'Open'; } else { echo 'Closed'; } ?>
														</td>
                                                       
                                                        
                                                        <td>
															<?php $createdDate =  explode('-',$dailyNotification['createdOn']);
															
															echo  $createdDate[2].'-'.$createdDate[1].'-'.$createdDate[0]; 
															 ?>
														</td>
														<td>
                                                        
                                                        <p>
											

                              	<button class="btn btn-danger btn-sm" title="delete" onclick="confirmDelete('<?php echo $dailyNotification['autoId'] ?>')">
												<i class="ace-icon fa fa-trash icon-only"></i>
											</button>
                                          
                                         <?php if(strcmp($dailyNotification['authorization'],'No')==0) { ?>
											
                                            <select name="" onchange="confirmAuthorize(this.value,'<?php echo $dailyNotification['autoId'] ?>')">
                                            <option value="0">select</option>
                                            <option value="1">Authorize</option>
                                            </select>
                                            <?php } ?>
                                            
										
										</p>
                                                          
														</td>

													
													</tr>
<?php
  }
  
  ?>                                               </tbody>
											</table>
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


  <script>
  
  function confirmDelete(did)
  {
	  if(confirm("Confirm Delete"))
	  {
	  window.location = 'authorize_notifications.php?did='+did;
	  }
  }
  
  function confirmAuthorize(id,aid)
  {
	
	if(id==1)
	  {
	  window.location = 'authorize_notifications.php?aid='+aid;
	  }  
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
