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
 
 $numRecords = mysql_query("select daily_reports.reportId, daily_reports.reportDate, daily_reports.contactPerson, daily_reports.designation, daily_reports.phone, daily_reports.email, daily_reports.clientStatus, daily_reports.leadType, daily_reports.leadStatus, daily_reports.futureDate, daily_reports.remarks, daily_reports.poNo, daily_reports.poValue, daily_reports.paymentType, daily_reports.addedOn, customers.company  from daily_reports 
                          left join customers on daily_reports.company = customers.customerId
						  where daily_reports.po = '1' and daily_reports.addedBy = '". $_SESSION['id'] ."'
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




$result = mysql_query("select daily_reports.reportId, daily_reports.reportDate, daily_reports.contactPerson, daily_reports.designation, daily_reports.phone, daily_reports.email, daily_reports.clientStatus, daily_reports.leadType, daily_reports.leadStatus, daily_reports.futureDate, daily_reports.remarks, daily_reports.poNo, daily_reports.poValue, daily_reports.poDateTime, daily_reports.paymentType, daily_reports.addedOn, customers.company  from daily_reports 
                          left join customers on daily_reports.company = customers.customerId
						  where daily_reports.po = '1' and daily_reports.addedBy = '". $_SESSION['id'] ."'
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
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Proposals </li>
						</ul><!-- /.breadcrumb -->

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
{ $alertMsg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Report has been deleted!</div>'; }
else if(isset($_GET['error']))
{ $alertMsg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  if(isset($alertMsg)) { echo $alertMsg; }
										   ?> 
							



								<div class="row">
									<div class="col-xs-12">
										
										<div class="table-header">
											Proposals
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
                                                        <th>Report Date</th>
                                                        <th>Po Date</th>
                                                        <th>Po No</th>
														<th>Company</th>
                                                        <th>Person</th>
                                                      	<th>Designation</th>													
                                                    	<th>Email</th>
                                                        <th>Phone</th>
                                                        <th>Status</th>
                                                        <th>Lead Type</th>
                                                        <th>Lead Status</th>
                                                        <th class="hidden-480">Action</th>
														
													</tr>
												</thead>

												<tbody>
													
														
																	

  <?php 
if(mysql_num_rows($result)>0)
  {
  
  $i = $start+1;
    while($row = mysql_fetch_array($result))
  {
	
	
	/*$currentDate = date('Y-m-d');  
	  
$prices = mysql_query("select `price` from product_price where productId = '". $product['productId'] ."' and fromDate <= '$currentDate' order by fromDate desc limit 1");
$price = mysql_fetch_array($prices);*/
	  
	 ?><tr>
														<td class="center">
															<?php echo $i; ?>
														</td>
                                                         <td>
															<?php $date = explode('-',$row['reportDate']);
															      echo $date = $date[2].'-'.$date[1].'-'.$date[0];      ?>
														</td>
                                                         <td>
															<?php
															 $poDate = explode(' ',$row['poDateTime']); 
														     $poDate = explode('-',$poDate[0]); 
														     $poDate = $poDate[2].'-'.$poDate[1].'-'.$poDate[0];
														
														 if(strcmp('00-00-0000',$poDate)!=0)
												{
													echo $poDate;
												}
															?>
														</td>
                                                        <td> <?php echo $row['poNo']; ?> </td>
                                                        
														<td>
															<?php echo $row['company']; ?>
														</td>
                                                       
                                                        <td>
															<?php echo ucfirst($row['contactPerson']); ?>
														</td>
                                                        <td>
															<?php echo $row['designation']; ?>
														</td>
                                                        <td>
															<?php echo $row['email']; ?>
														</td>
                                                        <td>
															<?php echo $row['phone']; ?>
														</td>
                                                        <td>
															<?php if($row['clientStatus']==1) { echo 'Existing'; } else if($row['clientStatus']==2) { echo 'New'; } ?>
														</td>
                                                        
                                                        <td>
															<?php if($row['leadType']==1) { echo 'General Enquiry'; } 
															 else if($row['leadType']==2) { echo 'Customer Call'; } 
															 else if($row['leadType']==3) { echo 'Reference'; } 
															 else if($row['leadType']==4) { echo 'Others'; } 
															 ?>
														</td>
                                                          <td>
															<?php if($row['leadStatus']==1) { echo 'Hot'; } 
															 else if($row['leadStatus']==2) { echo 'Warm'; } 
															 else if($row['leadStatus']==3) { echo 'Cold'; } 
															
															 ?>
														</td>
                                                        <td>
                                                        
                                                      
                                            <a class="btn btn-success btn-sm" href="sa_view_daily_report.php?rid=<?php echo $row['reportId'] ?>">
												<i class="ace-icon fa fa-eye"></i>
											</a>
                                            
                                            <a class="btn btn-success btn-sm" href="add_po.php?rid=<?php echo $row['reportId'] ?>">
												
										add po	</a>
                                              
                                            
                                           
                                            
                                            <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?php echo $row['reportId'] ?>')">
												<i class="ace-icon fa fa-trash icon-only"></i>
											</button>
                                                      
                                                     
                                                          
														</td>

													
													</tr>
<?php $i++; }

  }
  else
  {
  	  ?><tr><td colspan="12">No Data Found</td></tr><?php
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
   window.location = 'sa_proposals.php?page='+pid;	
}
  
  function confirmDelete(did)
  {
	  if(confirm("Confirm Delete"))
	  {
	  window.location = 'sa_daily_reports.php?did='+did;
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
