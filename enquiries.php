<?php include("includes/header.php"); 

// delete report
if(isset($_GET['did']))
{
mysql_query("delete from enquiries where enquiryId = '". $_GET['did'] ."'");
header("location: enquiries.php?delete=1"); 
}

if(isset($_POST['po_submit']))
{

mysql_query("update daily_reports set poNo = '". $_POST['po_number'] ."' where reportId = '". $_POST['reportId'] ."'");  
header("location: proposals.php");

}
 
// pagination
$limit = 10;

/* to get the enquiries data we will execute this query with employees table.
relation between enquiries and enquiry_assign is enquiryId.
relation between daily_reports and enquiries is enquiryNumber.
relation between enquiry_assign and employees is enquiry_assign.assignedTo = employees.firstName.
relation between enquiries and customers is enquiries.company = customers.customerId. */
 

$numRecords = mysql_query("select *,dr.leadStatus, enquiries.enquiryNumber, enquiries.enqDate as enqdateTime, customers.company from enquiries
left join enquiry_assign on enquiries.enquiryId= enquiry_assign.enquiryId
left join employees on enquiry_assign.assignedTo = employees.firstName
left join customers on enquiries.company = customers.customerId
left join daily_reports as dr on enquiries.company = dr.company
GROUP BY enquiries.enquiryId desc");
 
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
					/* search form 
					from date to todate search based on "enquiries.dateTime".
					employee search based on employees.id
					customer search based on enquiries.companyId  
					
					*/
									
										
								//	if(isset($_GET['fromDate']) && strlen($_GET['fromDate']>8) && isset($_GET['toDate']) && strlen($_GET['toDate']>8))
								
								if(isset($_GET['fromDate']) && isset($_GET['toDate']))
								
								{
							if($_GET['fromDate']!='' && $_GET['toDate']!='')

									{
									
										$fromDate = explode('-',$_GET['fromDate']);
										$fromDate = $fromDate[2].'-'.$fromDate[1].'-'.$fromDate[0];
										
										$toDate = explode('-',$_GET['toDate']);
										$toDate = $toDate[2].'-'.$toDate[1].'-'.$toDate[0];
									 
									 
									  $whereItems[] = "enquiries.dateTime >=  '". $fromDate ."'";
								
									  $whereItems[] = "enquiries.dateTime <=  '". $toDate ."'";
									}
								}
									
									   // by employee
									   if(isset($_GET['eid']) && $_GET['eid']>0)
									   {  
									    
										    $whereItems[] = "employees.id =  '". $_GET['eid'] ."'";
									   }
									   
									    // by customer
									   if(isset($_GET['cid']) && $_GET['cid']>0)
									   {  
									  
										    $whereItems[] = "enquiries.companyId =  '". $_GET['cid'] ."'";
									   }
									   
									if( isset($_GET['catid']) && $_GET['catid']>0) $whereItems[] = "cat.id=  '". $_GET['catid'] ."'";
									
									if( isset($_GET['prdid']) && $_GET['prdid']>0) $whereItems[] = "drd.productId=  '". $_GET['prdid'] ."'";
									
										
									
									
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
												  $where = '';	
												}
								
							
/*$query = "select daily_reports.reportId, daily_reports.offerNo, daily_reports.reportDate, daily_reports.leadType, daily_reports.leadStatus, daily_reports.source, daily_reports.poNo, customers.company, employees.firstName from daily_reports
left join customers on daily_reports.company = customers.customerId
left join employees on daily_reports.addedBy = employees.id
$where
order by daily_reports.reportId desc";*/

$query = "select *,dr.leadStatus,enquiries.source as src, customers.company, enquiries.enqDate as enqdateTime, enquiries.enquiryNumber,enquiries.phone from enquiries 
left join enquiry_assign on enquiries.enquiryId= enquiry_assign.enquiryId 
left join employees on enquiry_assign.assignedTo = employees.firstName
left join customers on enquiries.company = customers.customerId
left join daily_reports as dr on enquiries.company = dr.company

left join daily_reports_revision as drv on drv.reportId=dr.reportId
left join daily_reports_data as drd on drd.revisionId=drv.revisionId
left join categories as cat on cat.id=drd.categoryId

$where GROUP BY enquiries.enquiryId desc";

 				
											
}
else
{
	
/*$query = "select daily_reports.reportId, daily_reports.offerNo, daily_reports.reportDate, daily_reports.leadType,  daily_reports.source, daily_reports.leadStatus, daily_reports.poNo, customers.company, employees.firstName from daily_reports
left join customers on daily_reports.company = customers.customerId
left join employees on daily_reports.addedBy = employees.id
where daily_reports.reportType = '1'
order by daily_reports.reportId desc limit $start, $limit";
*/	
					
$query = "select *,dr.leadStatus,enquiries.source as src,customers.company, enquiries.enqDate as enqdateTime, enquiries.enquiryNumber,enquiries.phone from enquiries 
left join enquiry_assign on enquiries.enquiryId= enquiry_assign.enquiryId 
left join employees on enquiry_assign.assignedTo = employees.firstName
left join customers on enquiries.company = customers.customerId
left join daily_reports as dr on enquiries.company = dr.company
left join daily_reports_revision as drv on drv.reportId=dr.reportId
left join daily_reports_data as drd on drd.revisionId=drv.revisionId
left join categories as cat on cat.id=drd.categoryId

GROUP BY enquiries.enquiryId desc limit $start, $limit";
}

$proposals = mysql_query($query);


?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Enquiries</li>
						</ul><!-- /.breadcrumb -->

<a href="excel/enquiries.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content" id="outclick">
						<!-- #section:settings.box -->
						<!-- /.ace-settings-container -->

						<!-- /section:settings.box -->
					

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								

							



								<div class="row">
                                
                                 <div class="col-xs-12">
                                    
                                    
                                                                        
               <form class="form-inline" method="get" action="" autocomplete="off">
                                  
                      
  <div class="row">              
                                   
<div class="col-sm-2" style="width:11%">
   <span>From Date</span>
    <input type="text" class="form-control date-picker input-sm" id="fromDate" name="fromDate" placeholder="From Date" <?php if(isset($_GET['fromDate'])) { ?> value="<?php echo $_GET['fromDate']; ?>" <?php } ?> style="width:100%" />
  </div>
<div class="col-sm-2" style="width:11%">
   <span>To Date</span>
   <input type="text" class="form-control date-picker input-sm" id="toDate" name="toDate" placeholder="To Date" <?php if(isset($_GET['toDate'])) { ?> value="<?php echo $_GET['toDate']; ?>" <?php } ?> style="width:100%" />
  </div>
 
 <div class="col-sm-2" style="width:13%">
<span>Category</span><br />
<?PHP
	$categ = mysql_query("SELECT * FROM `categories`");
	
?>
<select id="catid" name="catid" style="width:100%">
<option value="">Select Category</option>
<?php  
while ($categories = mysql_fetch_array($categ))
{  ?>
<option <?php if(isset($_GET['catid']) && $_GET['catid']==$categories['id']) { ?> selected="selected" <?php } ?>  value="<?php echo $categories['id']; ?>"><?php echo $categories['category'] ?></option>


<?php
}  ?>     </select> 
</div>

<div class="col-sm-2" style="width:13%">
<span>Product</span><br />
<?PHP
	$prdcts = mysql_query("SELECT * FROM `products` order by product ASC");
	
?>
<select id="prdid" name="prdid"  style="width:100%">
<option value="">Select Product</option>
<?php  
while ($products = mysql_fetch_array($prdcts))
{  ?>
<option <?php if(isset($_GET['prdid']) && $_GET['prdid']==$products['productId']) { ?> selected="selected" <?php } ?>  value="<?php echo $products['productId']; ?>"><?php echo $products['product'] ?></option>


<?php
}  ?>     </select> 
</div>


                                           
                               
<div class="col-sm-2" style="width:11%">
                                <span>Employee</span>
       <input type="hidden" id="eid" name="eid" value="<?PHP echo $_GET['eid']; ?>" />
     <input type="text" id="employee" name="employee" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Employee" <?php if(isset($_GET['employee'])) { ?> value="<?php echo $_GET['employee']; ?>" <?php } ?> onkeyup="getEmployee(this.value)" style="width:100%"  />
           <ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="employeesList">
                                           </ul>
                                           
                                </div> 
                               
                                          
                               
<div class="col-sm-2" style="width:11%">
                                <span>Customer</span>
                                <input type="hidden" id="cid" name="cid" value="<?PHP echo $_GET['cid']; ?>" />

     <input type="text" id="customer" name="customer" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Customer" <?php if(isset($_GET['customer'])) { ?> value="<?php echo $_GET['customer']; ?>" <?php } ?> style="width:100%"  />
     
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
										
										<div class="table-header">
											Enquiries
										</div>


    <?php
										   
	   if(isset($_GET['delete']))
{ echo '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Enquiry has been deleted!</div>'; }
else if(isset($_GET['error']))
{ echo '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }
										   ?> 

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">
											<table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                            
												<thead>
													<tr>
														<th class="center">
															S.no
														</th>
													    <th>Enquiry Date</th>
													    <th>Enquiry Number</th>
                                                        <th>Assigned To Executive</th>
                                                        <th>Company Name</th>
                                                        <th>Phone</th>
                                                       	<th>Source</th>													
                                                       <th>Status</th>
                                                       <th>Action</th>
													</tr>
												</thead>

												<tbody>
													
<?php 

// to download file in excel format we have written this code.
$list[] = array('S. No', 'Enquiry Date', 'Enquiry Number', 'Assigned To Executive', 'Company Name', 'Phone', 'Source', 'Status');
 
if(mysql_num_rows($proposals)>0)
{
  $i = $start+1;
  
  while($proposal = mysql_fetch_array($proposals))
  {
	  
	 ?><tr>
														<td class="center">
															<?php echo $i; ?>
														</td>
                                                        <td>
															<?php $date = explode(' ',$proposal['enqdateTime']);
															 	 	 	 
															   $date =  explode('-',$date[0]);
															   
															 echo $date = $date[2].'-'.$date[1].'-'.$date[0];
															?>
														</td>
                                                        <td>
															<?php echo $proposal['enquiryNumber']; ?>
														</td>
                                                        <td>
															<?php echo ucfirst($proposal['assignedTo']); ?>
														</td>
                                                         
                                                         
                                                         <td>
															<?php echo ucfirst($proposal['company']); ?>
														</td>
                                                        
                                                         <td>
															<?php echo $proposal['phone']; ?>
														</td>
														
                                                        <td><?php echo $proposal['src']; ?></td>
                                                        <td>
                                                        <?PHP echo $proposal['leadStatus']; ?>
                                                     
                      
                                            </td>
                                                        <td>
                                                        
                                                        <p>
											

										<a class="btn btn-success btn-sm" title="view" href="view_enquiry.php?eid=<?php echo $proposal['enquiryId']; ?>">
											<i class="fa fa-eye" aria-hidden="true"></i>
											</a>
                                            
                                      <a class="btn btn-primary btn-sm" title="Edit" href="edit_enquiry.php?eid=<?php echo $proposal['enquiryId']; ?>">
												<i class="ace-icon fa fa-edit icon-only"></i>
											</a>
                                 <button class="btn btn-danger btn-sm" title="delete" onclick="confirmDelete('<?php echo $proposal['enquiryId']; ?>')">
												<i class="ace-icon fa fa-trash icon-only"></i>
											</button>
                                          
										</p>
                                                
                                                
                                                          
														</td>

													
													</tr>
<?php
// to download file in excel format we have written this code.
$rowlist[] = $i;
$rowlist[] = $date;
$rowlist[] = $proposal['enquiryNumber'];
$rowlist[] = $proposal['assignedTo']; 
$rowlist[] = $proposal['company']; 
$rowlist[] = $proposal['phone']; 
$rowlist[] = $proposal['source']; 
$rowlist[] = $status; 
$rowlist[] = $leadStatus; 


	 
	 
 $list[] = $rowlist;
 unset($rowlist);
 
  $i++; 
  
  
  } } else { ?> <tr><td colspan="8">No Data found.</td></tr> <?php }
  
 
   if($numRecords>$limit && !(isset($_GET['reportSearch']))) {
   
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
  <td id="next_grid-pager" class="ui-pg-button ui-corner-all <?php echo $thirdlink; ?>" style="cursor: default;" <?PHP if($numPages>$currentPage) { ?> onclick="goToPage('<?php echo $currentPage+1; ?>') <?PHP } ?>">
                <span class="ui-icon ace-icon fa fa-angle-right bigger-140">></span>
  </td>
  <td id="last_grid-pager" class="ui-pg-button ui-corner-all <?php echo $fourthlink; ?>" onclick="goToPage('<?php echo $numPages; ?>')">
                <span class="ui-icon ace-icon fa fa-angle-double-right bigger-140"></span>
  </td>
  </tr></tbody></table>
  
  
  
  
  
  </td></tr>
  <?php }
  
 
?>                                         </tbody>
											</table>
                                            
                                            <?php                              
$fp = fopen('excel/enquiries.csv', 'w');

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
        <script src="assets/js/date-time/bootstrap-datepicker.js"></script>

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
				$("#employeesList,#customersList").css('display','none'); 
			});
		});

 function confirmDelete(did)
 {
	if(confirm('Do you want to delete the report')) 
	{
	 window.location = 'enquiries.php?did='+did; 
	}
 }
 
   function goToPage(pid)
{
   window.location = 'enquiries.php?page='+pid;	
}
 
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

 
   	/*function getCustomer(val)
		{
			
			document.getElementById("customersList").style.display = 'block';
		$.ajax({url: "ajax/getCustomerList.php?val="+val, success: function(result){
		$("#customersList").html(result);
    }});	
			
		}*/
		
		
		function selectCustomer(id,firstName)
		{
			document.getElementById("customersList").style.display = 'none';
			document.getElementById("cid").value = id;
			document.getElementById("customer").value = firstName;
	
		}		

</script>
		<!-- inline scripts related to this page -->
		<script type="text/javascript">
		
		$(document).ready(function(){
			 $("#customer").keyup(function(){
				var customer = $(this).val();
				$.ajax({
							type:'POST',
							url:'ajax/getCustomerList.php',
							data:{'customer':customer},
							success:function(resp){
								$("#customersList").html(resp);
								$("#customersList").css('display','block');
							}
						})
			}) 
		});
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
					todayHighlight: true,
					format: 'dd-mm-yyyy'
				})
				
				
			})
		</script>
        <script>
		$(document).ready(function(){
			
		/*	$(document).on('change','.statusChange',function(){ 
			var statusChange = $(this).val();
			var enqid = $(this).attr('lang');
			
			
			$.ajax({ 
			  url: "ajax/statusUpdate.php",
			  type: 'POST',
			  data: {'enqid':enqid,'statusChange':statusChange},
			  success:function(data){
			//$('#success-message').html(data);
			
			  }
			})	
			
			});*/
			
		});
		
		</script>

		<!-- the following scripts are used in demo only for onpage help and you don't need them -->
		<!--<link rel="stylesheet" href="assets/css/ace.onpage-help.css" />
		<link rel="stylesheet" href="docs/assets/js/themes/sunburst.css" />-->

		<!--<script type="text/javascript"> ace.vars['base'] = '..'; </script>
		<script src="assets/js/ace/elements.onpage-help.js"></script>
		<script src="assets/js/ace/ace.onpage-help.js"></script>
		<script src="docs/assets/js/rainbow.js"></script>
		<script src="docs/assets/js/language/generic.js"></script>
		<script src="docs/assets/js/language/html.js"></script>
		<script src="docs/assets/js/language/css.js"></script>
		<script src="docs/assets/js/language/javascript.js"></script>-->
	</body>
</html>
