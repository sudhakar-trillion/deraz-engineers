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
 
// data comes from daily_reports table
$numRecords = mysql_query("select daily_reports.reportId, daily_reports.contactPerson, daily_reports.enquiryNumber, date_format(daily_reports.reportDt,'%d-%m-%Y') as repDt, date_format(enquiries.enqDate,'%d-%m-%Y') as enqDate, daily_reports.designation, daily_reports.phone, daily_reports.email, daily_reports.clientStatus, daily_reports.leadType,daily_reports.futureDate, daily_reports.remarks, daily_reports.poNo, daily_reports.poValue, daily_reports.paymentType, daily_reports.addedOn, customers.company  from daily_reports
left join enquiries on daily_reports.enquiryNumber = enquiries.enquiryNumber 
left join customers on daily_reports.company = customers.customerId

where daily_reports.addedBy = '". $_SESSION['id'] ."'
GROUP BY daily_reports.reportId desc");
 
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


// search options 


  if(isset($_GET['reportSearch']))
									{
										$where = 'where ';
										 $where = "where daily_reports.addedBy = '". $_SESSION['id'] ."' and ";
										
									if(isset($_GET['fromDate']) && isset($_GET['toDate']))
								
								{
							if($_GET['fromDate']!='' && $_GET['toDate']!='')

									{
									
										$fromDate = explode('-',$_GET['fromDate']);
										$fromDate = $fromDate[2].'-'.$fromDate[1].'-'.$fromDate[0];
										
										$toDate = explode('-',$_GET['toDate']);
										$toDate = $toDate[2].'-'.$toDate[1].'-'.$toDate[0];
									 
									 
									  $whereItems[] = "daily_reports.reportDate >=  '". $fromDate ."'";
								
									  $whereItems[] = "daily_reports.reportDate <=  '". $toDate ."'";
									}
								}

									
									  
									   
									    // by customer
									  if(isset($_GET['customer']) && $_GET['customer']!='')
										{
									   if(isset($_GET['cid']) && $_GET['cid']>0)
									   {  
									  
										    $whereItems[] = "daily_reports.company =  '". $_GET['cid'] ."'";
									   }
										}
										else 
										$_GET['customer']='';
									
									   
									
												if(count($whereItems)>1)
												{ 
												$whereCondition = implode(' and ',$whereItems);
												$where = $where.$whereCondition;
												}
												else if(count($whereItems)==1)
												{   
													$whereCondition = $whereItems[0];
													$where = $where.$whereCondition;
												}
												else
												{  
												  $where = "where daily_reports.addedBy = '". $_SESSION['id'] ."'";	
												}
												
// data comes from daily_reports table												
$result = mysql_query("select daily_reports.reportId, daily_reports.enquiryNumber, date_format(daily_reports.reportDt,'%d-%m-%Y') as repDt, date_format(enquiries.enqDate,'%d-%m-%Y') as enqDate, daily_reports.contactPerson, daily_reports.designation, daily_reports.phone, daily_reports.email, daily_reports.leadStatus, daily_reports.futureDate, daily_reports.remarks, daily_reports.poNo, daily_reports.poValue, daily_reports.paymentType, daily_reports.addedOn, daily_reports.reportType, customers.company  from daily_reports 
left join enquiries on daily_reports.enquiryNumber = enquiries.enquiryNumber
                          left join customers on daily_reports.company = customers.customerId
						 
						   $where GROUP BY daily_reports.reportId desc");												

									}
else
{ 

// data comes from daily_reports table

$result = mysql_query("select daily_reports.reportId, daily_reports.enquiryNumber, date_format(daily_reports.reportDt,'%d-%m-%Y') as repDt, date_format(enquiries.enqDate,'%d-%m-%Y') as enqDate , daily_reports.contactPerson, daily_reports.designation, daily_reports.phone, daily_reports.email, daily_reports.leadStatus, daily_reports.futureDate, daily_reports.remarks, daily_reports.poNo, daily_reports.poValue, daily_reports.paymentType, daily_reports.addedOn, daily_reports.reportType, customers.company  from daily_reports 
left join enquiries on daily_reports.enquiryNumber = enquiries.enquiryNumber
                          left join customers on daily_reports.company = customers.customerId
						  
						  where daily_reports.addedBy = '". $_SESSION['id'] ."'

						   $where GROUP BY daily_reports.reportId desc limit $start, $limit");
						   
}
 

?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Daily Reports  </li>
						</ul><!-- /.breadcrumb -->
                        
                                                 <a href="excel/sa_daily_reports.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>


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
					<div class="page-content" id="outclick">
						<!-- #section:settings.box -->
						<!-- /.ace-settings-container -->

						<!-- /section:settings.box -->
					

						<div class="row">
                        <div class="col-xs-12">
                                    
                                    
                                                                        
               <form class="form-inline" method="get" action="" autocomplete="off">
  <div class="row">              
                                   
  <div class="form-group col-sm-2">
   <span>From Date</span>
    <input type="text" class="form-control date-picker input-sm" id="fromDate" name="fromDate" placeholder="From Date" <?php if(isset($_GET['fromDate'])) { ?> value="<?php echo $_GET['fromDate']; ?>" <?php } ?> />
  </div>
  <div class="form-group col-sm-2">
   <span>To Date</span>
   <input type="text" class="form-control date-picker input-sm" id="toDate" name="toDate" placeholder="To Date" <?php if(isset($_GET['toDate'])) { ?> value="<?php echo $_GET['toDate']; ?>" <?php } ?> />
  </div>
  
 
                               
                                <div class="col-sm-2">
                                <span>Customer</span>
                                <input type="hidden" id="cid" name="cid" value="<?PHP echo $_GET['cid']; ?>" />

     <input type="text" id="customer" name="customer" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Customer" <?php if(isset($_GET['customer'])) { ?> value="<?php echo $_GET['customer']; ?>" <?php } ?>  />
     
      <ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="customersList"></ul>
                               </div> 
                                            
  <div class="form-group col-sm-2">
   <span><br></span>
    <input type="submit" class="btn btn-sm btn-success" name="reportSearch">
  </div>
  
  </div>
                                
                              
                                 
                               
  
</form>

</div>
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
											Daily Reports
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
                                                        <th>Enquiry Date</th>
                                                        <th>Enquiry No.</th>
                                                        <th>Company Name</th>
                                                        <th>Contact Person</th>
                                                      	<th>Designation</th>													
                                                    	<th>Email</th>
                                                        <th>Phone</th>
                                                        <th>Status</th>
                                                        <th class="hidden-480">Action</th>
														
													</tr>
												</thead>

												<tbody>
																	

  <?php 
    $list[] = array('S. No', 'Date', 'Company', 'Person', 'Designation', 'Email', 'Phone', 'Status' );

  if(mysql_num_rows($result)>0)
  {
  
  $i = $start+1;
  
  
  while($row = mysql_fetch_array($result))
  {
	//echo '<pre>'; print_r($row);
	
	/*$currentDate = date('Y-m-d');  
	  
$prices = mysql_query("select `price` from product_price where productId = '". $product['productId'] ."' and fromDate <= '$currentDate' order by fromDate desc limit 1");
$price = mysql_fetch_array($prices);*/
	  
	  
	  
	  
	  if($row['reportType']==1)
	  {
		  $bgcolor = '#7BACE4';
	  }
	  else
	  {
		  
		 $bgcolor = ''; 
	  }
	 ?>
     <tr style="background:<?php echo $bgcolor; ?>">
														<td class="center">
															<?php echo $i; 
															
															
															?>
														</td>
                                                      
                                                         <td>
															<?php echo $row['repDt']; ?>
														</td>
                                                        <td>
															<?php 
																	if($row['enquiryNumber'] !='')
																		echo $row['enqDate'];
																	else
																		echo "----";
															 ?>
														</td>
                                                        
                                                        <td>
															<?php
															/* if($row['enquiryNumber'] !=''){ 
															echo $row['enquiryNumber']; }
															else { echo "---"; }
															*/
															echo $row['enquiryNumber']; 
															 ?>
														</td>
                                                        
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
															<?php echo  $row['leadStatus']; ?>
														</td>
                                                        <td>
                                                        
                                   <!--   <a class="btn btn-warning btn-sm" style="margin-bottom:5px" href="sa_edit_daily_report.php?rid=<?php echo $row['reportId'] ?>">
													<i class="ace-icon fa fa-edit icon-only"></i>
											</a>
                                       -->        
                                            <a class="btn btn-success btn-sm" href="sa_view_daily_report.php?rid=<?php echo $row['reportId'] ?>">
												<i class="ace-icon fa fa-eye"></i>
											</a>
                                              
                                                          
														</td>

													
													</tr>
<?php
$rowlist[] = $i;
$rowlist[] = $date;
$rowlist[] = $row['company'];
$rowlist[] = ucfirst($row['contactPerson']);
$rowlist[] = $row['designation']; 
$rowlist[] = $row['email'];
$rowlist[] = $row['phone']; 
$rowlist[] = $row['leadStatus']; 
$list[] = $rowlist;
unset($rowlist);
 



$i++;  }
  }
  
  else 
  {
	  ?><tr><td colspan="10">No Data Found</td></tr><?php
  }  ?>   
   <?php 
   if($numRecords>$limit  && (!isset($_GET['reportSearch']))) {
   
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
  <tr><td colspan="10">
  
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
  <?php } ?>                                            </tbody>
											</table>
                                            
                                            <?php                              
$fp = fopen('excel/sa_daily_reports.csv', 'w');

foreach ($list as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp) ?>
                                            
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
	  $(document).ready(function(){
			//$("#employee").blur(function(){
				$(document).on('click','#outclick,#breadcrumbs',function(){
				$("#customersList").css('display','none'); 
			});
		});

   function goToPage(pid)
{
   window.location = 'sa_daily_reports.php?page='+pid;	
}
  
  function confirmDelete(did)
  {
	  if(confirm("Confirm Delete"))
	  {
	  window.location = 'sa_daily_reports.php?did='+did;
	  }
  }
  		function selectCustomer(id,firstName)
		{
			document.getElementById("customersList").style.display = 'none';
			document.getElementById("cid").value = id;
			document.getElementById("customer").value = firstName;
	
		}		

$(document).on('keyup','#customer',function(){
			var customer = $(this).val();
			
			$.ajax({
				   url: 'ajax/getCustomerList.php',
				   type: 'POST',
				   data: {'customer':customer},
				   success:function(data){ 
				   $("#customersList").html(data);
				$("#customersList").css('display','block');
				   }
				
				
				});
			
			
			});
  
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
                <script src="assets/js/date-time/bootstrap-datepicker.js"></script>

		<script src="assets/js/ace/ace.onpage-help.js"></script>
		<script src="docs/assets/js/rainbow.js"></script>
		<script src="docs/assets/js/language/generic.js"></script>
		<script src="docs/assets/js/language/html.js"></script>
		<script src="docs/assets/js/language/css.js"></script>
		<script src="docs/assets/js/language/javascript.js"></script>
	</body>
</html>
