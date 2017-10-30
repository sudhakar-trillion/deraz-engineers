<?php include("includes/header.php"); 

 
 
// delete 
if(isset($_GET['did']))
{
  if(mysql_query("delete from employees where id = '". $_GET['did'] ."'"))
  {
	header("location: employees.php?delete=success");  
  }
  else
  {
	header("location: employees.php?error=fail");    
  }
}

$employees = mysql_query("select employees.id, employees.firstName, employees.email, employees.dateJoining, employees.personalMobile, rolls.roll from employees 
 left join rolls on employees.roll = rolls.roll_id
 where employees.roll = '4'
 order by employees.firstName");
 
 




?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Sales Targets </li>
						</ul><!-- /.breadcrumb -->
                        
<a href="excel/targets.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>
                        

						
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
{ $msg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Customer has been deleted!</div>'; }
else if(isset($_GET['error']))
{ $msg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  if(isset($msg)) {  //echo $msg; 
  }
		
		
		 $currentYear = date('Y');
 $currentMonth = date('m');
 $previousYear = $currentYear-1;
 $nextYear = $currentYear+1;
 				
							
 if($currentMonth>3)
 {
	$financialYear = $currentYear.'-'.$nextYear;
	$financialYear1 = $currentYear;
	$financialYear2 = $nextYear;
 }
 else
 {
	 $financialYear = $previousYear.'-'.$currentYear; 
	  $financialYear1 = $previousYear;
	 $financialYear2 = $currentYear;

 }
 
$rowlist[] = $financialYear.' Sales Targets';
$list[] = $rowlist;

unset($rowlist);

$rowlist[] = 'S. No';
$rowlist[] = 'Name';
$rowlist[] = date('M Y').' Target';
$rowlist[] = date('M Y').' Achieved';
$rowlist[] = 'Yr Target';
$rowlist[] = 'Yr Achieved';
$list[] = $rowlist;

unset($rowlist);
	
			
			
										   ?> 


								<div class="row">
									<div class="col-xs-12">
										
										<div class="table-header">
										<?php echo $financialYear.' Sales Targets'; ?>
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div>
											<table id="sample-table-1" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th class="center">
															S.no
														</th>
														<th>Name</th>
                                                        <th><?php echo date('M Y'); ?> Target</th>
                                                        <th><?php echo date('M Y'); ?> Achieved</th>
                                                        <th>Yr Target</th>
													    <th>Yr Achieved</th>
													</tr>
												</thead>

												<tbody>
													
														
																	

  <?php 
  
  if(mysql_num_rows($employees)>0)
  {
   if(!isset($start))
   $start=0;

  $sno = $start+1; 

   
  while($employee = mysql_fetch_array($employees))
  {
	
	$currentYear = date('Y');  
	// get target
	$nextYear = $currentYear+1;
	$trlike = $currentYear.'-'.$nextYear;
	
	// year target
	$year_target = 0;
	$yrTarget =  mysql_query("select target from targets where employeeId = '". $employee['id'] ."' and financialYear = '". $trlike ."'");		
	while($yearTarget = mysql_fetch_array($yrTarget))
	{
		$year_target = $year_target+$yearTarget['target'];
	}
	
	
	
	
	
	// month data
	 $m = $currentMonth = date('m');
	 if(strlen($m)==1) { $m = '0'.$m; } else { $m = $m; }
	 
	 
	
  // month target
 $monthTarget = mysql_query("select target from targets where employeeId = '". $employee['id'] ."' and financialMonth = '". $m ."'");
 $monthTarget = mysql_fetch_array($monthTarget); 
 
 // month achieved
	
	 
	 
	// $like = $currentYear.'-'.$m.'-'.'%';
	 $like = '%'.'-'.$m.'-'.'%';
	
 $invoices = mysql_query("select reportId, currentRevisionId from daily_reports where addedBy = '". $employee['id'] ."' and inv > '0' and proformaInvoiceDateTime like '$like' order by reportId desc");
    
	if(mysql_num_rows($invoices)>0)
	{
 
 $monthAchieved = 0;
 while($invoice = mysql_fetch_array($invoices))
 {
 // invoices amount:	 
 $invoicesAmount = mysql_query("select grandTotal from daily_reports_revision where reportId = '". $invoice['reportId'] ."' and revision = '". $invoice['currentRevisionId'] ."'");
 $invoiceAmount = mysql_fetch_array($invoicesAmount);
 
 $monthAchieved = $invoiceAmount['grandTotal']; 


 $currentMonthAmount[$m] = $monthAchieved;
 }
 

 
 
	}
	
	
	// year achieved
	 $m = $currentMonth = date('m');
	 if(strlen($m)==1) { $m = '0'.$m; } else { $m = $m; }
	 $like = $currentYear.'-'.$m.'-'.'%';
	
 $invoices = mysql_query("select reportId, currentRevisionId from daily_reports where addedBy = '". $employee['id'] ."' and inv > '0' and proformaInvoiceDateTime like '$like' order by reportId desc");
    
	if(mysql_num_rows($invoices)>0)
	{
 
 $yearAchieved = 0;
 while($invoice = mysql_fetch_array($invoices))
 {
 // invoices amount:	 
 $invoicesAmount = mysql_query("select grandTotal from daily_reports_revision where reportId = '". $invoice['reportId'] ."' and revision = '". $invoice['currentRevisionId'] ."'");
 $invoiceAmount = mysql_fetch_array($invoicesAmount);
 
 $yearAchieved = $invoiceAmount['grandTotal']; 
 

 }
 

 
 
	}
			
	
	
	  
	 ?><tr>
														<td class="center">
															<?php echo $sno; ?>
														</td>

														<td>
															<?php 
															echo ucfirst($employee['firstName']); ?>
														</td>
                                                        <td><?php echo  $monthTarget['target']; ?></td>
                                                        <td>
														
														<?php
		     $salesAchieved = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.invoice, daily_reports.invoiceDateTime, customers.company from daily_reports 
		   left join customers on daily_reports.company = customers.customerId
		   where daily_reports.addedBy = '". $employee['id'] ."' and daily_reports.inv > '0' and daily_reports.invoiceDateTime like '". $like ."'");
		   
		   
		   
		   ?>
           <div id="<?php echo 'modal-table4_'.$employee['id']; ?>" class="modal fade in" tabindex="-1" aria-hidden="false" style="display: none;">
              <div class="modal-backdrop fade in"></div>
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">×</span>
													</button>
													<?php echo ucfirst($employee['firstName']); echo ' - Sales Achieved';  ?>
												</div>
											</div>

											<div class="modal-body no-padding">
												<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
													<thead>
														<tr>
															<th>S.no</th>
                                                           	<th>Invoice Date</th>
                                                            <th>Invoice</th>
                                                             <th>Company</th>
                                                            <th>Value</th>
                                                          </tr>
													</thead>

													<tbody>
        <?php $achievedSaleAmount = 0;
	if(mysql_num_rows($salesAchieved)>0)
	{ $cid=1;   
	   while($salesAchieve = mysql_fetch_array($salesAchieved))	
	   {
		 /* $salesResult = mysql_query("select daily_reports_data.amount from daily_reports_revision 
		   left join daily_reports_data on daily_reports_revision.revisionId = daily_reports_data.revisionId
		   where daily_reports_revision.reportId = '". $salesAchieve['reportId'] ."' and daily_reports_revision.revision = '". $salesAchieve['currentRevisionId'] ."'");
		   
		  
		   */
		   
		    $salesResult = mysql_query("select grandTotal from invoices where reportId = '". $salesAchieve['reportId'] ."'");
		   
		   
		   
		   
		   $invoiceAmount = 0;
		   while($salesRow = mysql_fetch_array($salesResult))
		   {
		       $achievedSaleAmount = $salesRow['grandTotal']+$achievedSaleAmount;  
			   $invoiceAmount = $salesRow['grandTotal']+$invoiceAmount;  
		   }
		  ?> <tr>
                 <td>
			<?php echo $cid; ?>
										</td>
								<td><?php $invoiceDate = explode(' ',$salesAchieve['invoiceDateTime']);
							              $invoiceDate = explode('-',$invoiceDate[0]);
								     	  echo $invoiceDate[2].'-'.$invoiceDate[1].'-'.$invoiceDate[0];
								 ?></td>
                                 <td><?php echo $salesAchieve['invoice']; ?></td>
                                <td><?php echo $salesAchieve['company']; ?></td>
                                <td><?php echo $invoiceAmount; ?></td>
                               
															
														</tr> <?php
	   $cid++; }
	   
	   $grandAchievedSaleAmount  =  $achievedSaleAmount+$grandAchievedSaleAmount;
	}
	
	
	?>
														

														
													</tbody>
												</table>
											</div>

											<div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close
												</button>

												
											</div>
										</div><!-- /.modal-content -->
									</div><!-- /.modal-dialog -->
								</div>
            
              <a href="<?php echo '#modal-table4_'.$employee['id']; ?>" role="button" class="green" data-toggle="modal"><?php echo $achievedSaleAmount; ?></a>                                      
														
														<?php echo  $currentMonthAmount[$m]; ?></td>
                                                        <td><a href="year_target.php?eid=<?php echo $employee['id']; ?>"><?php echo $year_target;  ?></a></td>
                                                        <td>
														
														<?php
														
														  $like1 = $financialYear1.'-%';
		   $like2 = $financialYear2.'-%';
		   
		     $salesAchieved = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.invoice, daily_reports.invoiceDateTime, customers.company from daily_reports 
		   left join customers on daily_reports.company = customers.customerId
		   where daily_reports.addedBy = '". $employee['id'] ."' and daily_reports.inv > '0' and (daily_reports.invoiceDateTime like '". $like1 ."' OR daily_reports.invoiceDateTime like '". $like2 ."')");
		   
		   
		   
		   ?>
           <div id="<?php echo 'modal-table4_'.$employee['id']; ?>" class="modal fade in" tabindex="-1" aria-hidden="false" style="display: none;">
              <div class="modal-backdrop fade in"></div>
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">×</span>
													</button>
													<?php echo ucfirst($employee['firstName']); echo ' - Sales Achieved';  ?>
												</div>
											</div>

											<div class="modal-body no-padding">
												<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
													<thead>
														<tr>
															<th>S.no</th>
                                                           	<th>Invoice Date</th>
                                                            <th>Invoice</th>
                                                             <th>Company</th>
                                                            <th>Value</th>
                                                          </tr>
													</thead>

													<tbody>
        <?php $achievedSaleAmount = 0;
	if(mysql_num_rows($salesAchieved)>0)
	{ $cid=1;   
	   while($salesAchieve = mysql_fetch_array($salesAchieved))	
	   {
		 /* $salesResult = mysql_query("select daily_reports_data.amount from daily_reports_revision 
		   left join daily_reports_data on daily_reports_revision.revisionId = daily_reports_data.revisionId
		   where daily_reports_revision.reportId = '". $salesAchieve['reportId'] ."' and daily_reports_revision.revision = '". $salesAchieve['currentRevisionId'] ."'");
		   
		  
		   */
		   
		    $salesResult = mysql_query("select grandTotal from invoices where reportId = '". $salesAchieve['reportId'] ."'");
		   
		   
		   
		   
		   $invoiceAmount = 0;
		   while($salesRow = mysql_fetch_array($salesResult))
		   {
		       $achievedSaleAmount = $salesRow['grandTotal']+$achievedSaleAmount;  
			   $invoiceAmount = $salesRow['grandTotal']+$invoiceAmount;  
		   }
		  ?> <tr>
                 <td>
			<?php echo $cid; ?>
										</td>
								<td><?php $invoiceDate = explode(' ',$salesAchieve['invoiceDateTime']);
							              $invoiceDate = explode('-',$invoiceDate[0]);
								     	  echo $invoiceDate[2].'-'.$invoiceDate[1].'-'.$invoiceDate[0];
								 ?></td>
                                 <td><?php echo $salesAchieve['invoice']; ?></td>
                                <td><?php echo $salesAchieve['company']; ?></td>
                                <td><?php echo $invoiceAmount; ?></td>
                               
															
														</tr> <?php
	   $cid++; }
	   
	   $grandAchievedSaleAmount  =  $achievedSaleAmount+$grandAchievedSaleAmount;
	}
	
	
	?>
														

														
													</tbody>
												</table>
											</div>

											<div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close
												</button>

												
											</div>
										</div><!-- /.modal-content -->
									</div><!-- /.modal-dialog -->
								</div>
            
              <a href="<?php echo '#modal-table4_'.$employee['id']; ?>" role="button" class="green" data-toggle="modal"><?php echo $achievedSaleAmount; ?></a>                                      
														
														<?php echo  $currentMonthAmount[$m]; ?></td>
                                                                                                                
                                                        
													</tr>
<?php 



$rowlist[] = $sno;
$rowlist[] = ucfirst($employee['firstName']);
$rowlist[] =  @$monthTarget['target'];
$rowlist[] = @$currentMonthAmount[$m];
$rowlist[] = @$year_target;
$rowlist[] = @$yearAchieved;
	 
	 
 $list[] = $rowlist;
 unset($rowlist);

$grandMonthTarget = $monthTarget['target']+$grandMonthTarget;
$grandMonthAchieved = $currentMonthAmount[$m]+$grandMonthAchieved;
$grandYearTarget = $year_target+$grandYearTarget;
$grandYearAchieved = $yearAchieved+$grandYearAchieved;


$rowlist[] = '';
$rowlist[] = '';
$rowlist[] =  $grandMonthTarget;
$rowlist[] = $grandMonthAchieved;
$rowlist[] = $grandYearTarget;
$rowlist[] = $grandYearAchieved;
	 
	 
 $list[] = $rowlist;
 unset($rowlist);

unset($currentMonthAmount);
unset($monthTarget);
unset($year_target);
unset($yearAchieved);



$sno++;
  } } 
  
  ?>    
  <tr><td colspan="2"></td>
   <td><?php echo $grandMonthTarget; ?></td>
   <td><?php echo $grandMonthAchieved; ?></td>
   <td><?php echo $grandYearTarget; ?></td>
   <td><?php echo $grandYearAchieved; ?></td></tr>                                          </tbody>
											</table>
                                                                                  
              <?php                              
$fp = fopen('excel/targets.csv', 'w');

foreach ($list as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp) ?>
           
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

											<div class="modal-body no-padding">
												<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
													<thead>
														<tr>
															<th>Domain</th>
															<th>Price</th>
															<th>Clicks</th>

															<th>
																<i class="ace-icon fa fa-clock-o bigger-110"></i>
																Update
															</th>
														</tr>
													</thead>

													<tbody>
														<tr>
															<td>
																<a href="#">ace.com</a>
															</td>
															<td>$45</td>
															<td>3,330</td>
															<td>Feb 12</td>
														</tr>

														<tr>
															<td>
																<a href="#">base.com</a>
															</td>
															<td>$35</td>
															<td>2,595</td>
															<td>Feb 18</td>
														</tr>

														<tr>
															<td>
																<a href="#">max.com</a>
															</td>
															<td>$60</td>
															<td>4,400</td>
															<td>Mar 11</td>
														</tr>

														<tr>
															<td>
																<a href="#">best.com</a>
															</td>
															<td>$75</td>
															<td>6,500</td>
															<td>Apr 03</td>
														</tr>

														<tr>
															<td>
																<a href="#">pro.com</a>
															</td>
															<td>$55</td>
															<td>4,250</td>
															<td>Jan 21</td>
														</tr>
													</tbody>
												</table>
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
	  window.location = 'employees.php?did='+did;
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
