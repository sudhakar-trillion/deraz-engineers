<?php include("includes/header.php"); 

$limit = 10;


 $currentMonth = date('m');
 // to get the outstanding data we use invoices table ...based on paymentstatus the outstanding data comes
 /* to get the enquiries data we will execute this query with employees table.
relation between daily_reports and invoices are reportId.
relation between collections and daily_reports are collections.invoiceId = daily_reports.reportId
relation between daily_reports and employees are daily_reports.addedBy = employees.id
relation between branches and employees are employees.branch = branches.branchId
relation between daily_reports and customers are daily_reports.company = customers.customerId

*/
$numRecords = mysql_query("select DISTINCT employees.id, employees.firstName, daily_reports.enquiryNumber, daily_reports.poNo, date_format(daily_reports.poDateTime,'%d-%m-%Y') podt, customers.company from invoices 
						  left join daily_reports on invoices.reportId = daily_reports.reportId
						  left join employees on employees.id = daily_reports.addedBy
						  left join customers on daily_reports.company = customers.customerId
						where employees.roll = '4' and  daily_reports.inv > '0' and invoices.paymentStatus = 'open' 
						and  invoices.proInvoiceDateTime like '%-". $currentMonth ."-%'  order by employees.firstName");
						
						
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


// search options  where daily_reports.po = '0'



     $where = 'where ';
	 $whereItems[] = "employees.roll = '4'";
	 $whereItems[] = "daily_reports.inv > '0'";
	 $whereItems[] = "invoices.paymentStatus = 'open'";
	
	
  if(isset($_GET['paymentsSearch']))
									{
									/* numDays search based on "invoices.invoiceDateTime".
					employee search based on employees.id
					customer search based on enquiries.companyId.  
					branch search based on employees.branch
					*/					
							
										
										
									   // by employee
									   if(isset($_GET['eid']) && $_GET['eid']>0)
									   {  
									    
										    $whereItems[] = "employees.id =  '". $_GET['eid'] ."'";
											
									   }
									   
									   // by branch
									   if(isset($_GET['bid']) && $_GET['bid']>0)
									   {  
									    
										    $whereItems[] = "branches.branchId =  '". $_GET['bid'] ."'";
											
									   }
									   
									   // by customer
									   if(isset($_GET['cid']) && $_GET['cid']>0)
									   {  
									  
										    $whereItems[] = "daily_reports.company =  '". $_GET['cid'] ."'";
									   }
									   
									   
									   
									    // by num days
									   if(isset($_GET['numDays']) && $_GET['numDays']>0)
									   {  
									   
									   
		 							    $numDays = '-'.$_GET['numDays'];
									    $fromDate = strtotime($numDays." days", time() + (86400));
									    $fromDate = date("Y-m-d",$fromDate);
										$whereItems[] = "invoices.invoiceDateTime > '". $fromDate ."'";
									   }
									   
									
										
									
									
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
	
 // to get the outstanding data we use invoices table ..based on paymentstatus the outstanding data comes						
$employees = mysql_query("select DISTINCT employees.id,invoices.invoiceNumber,date_format(invoices.invoiceDateTime,'%d-%m-%y') as invoiceDateTime, date_format(daily_reports.poDateTime,'%d-%m-%Y') podt, customers.company,  daily_reports.poNo, branches.branch, daily_reports.reportId,invoices.invoiceId, daily_reports.enquiryNumber, employees.firstName from invoices 
						  left join daily_reports on invoices.reportId = daily_reports.reportId
						  left join employees on employees.id = daily_reports.addedBy
						  left join branches on employees.branch=branches.branchid
						  left join customers on daily_reports.company = customers.customerId

						  $where
						  order by invoices.invoiceId desc limit $start, $limit
						  ");
						  
/*						  
echo "select DISTINCT employees.id,invoices.invoiceNumber,date_format(invoices.invoiceDateTime,'%d-%m-%y') as invoiceDateTime, customers.company,branches.branch, daily_reports.reportId,invoices.invoiceId, daily_reports.enquiryNumber, employees.firstName from invoices 
						  left join daily_reports on invoices.reportId = daily_reports.reportId
						  left join employees on employees.id = daily_reports.addedBy
						  left join branches on employees.branch=branches.branchid
						  left join customers on daily_reports.company = customers.customerId

						  $where
						  order by invoices.invoiceId desc limit $start, $limit
						  "; exit; 						  
						  
	*/											
									}
else								
{
	
	$datetime = new DateTime(date('Y-m-d'));
$datetime->modify('-7 days');
$fromDate = $datetime->format('Y-m-d'); 
	 $whereItems[] = "invoices.proInvoiceDateTime > '".$fromDate."'";

	
 
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
				
$employees = mysql_query("select DISTINCT employees.id,invoices.invoiceNumber,  daily_reports.poNo, invoices.invoiceId,date_format(invoices.invoiceDateTime,'%d-%m-%y') as invoiceDateTime, employees.firstName, daily_reports.reportId, date_format(daily_reports.poDateTime,'%d-%m-%Y') podt, daily_reports.enquiryNumber, customers.company, branches.branch from invoices 
						  left join daily_reports on invoices.reportId = daily_reports.reportId
						  left join employees on employees.id = daily_reports.addedBy 
						  left join branches on employees.branch=branches.branchid
  						  left join customers on daily_reports.company = customers.customerId

						  $where
						   order by invoices.invoiceId desc limit $start, $limit
						  ");

					  
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
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Outstandings</li>
						</ul><!-- /.breadcrumb -->


     <a href="excel/outstandings.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>
     
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content" id="outclick">
						<!-- #section:settings.box -->
						<!-- /.ace-settings-container -->

						<!-- /section:settings.box -->
					

						<div class="row">
                        <div class="col-xs-12">
                                    
                                    
                                                                        
<form class="form-inline" method="get" action="" autocomplete="off">
<!-- outstanding data based on search days criteria-->                                 

<div class="row">              
<div class="col-sm-2"><div class="clearfix">
<span>No. Days</span>    <br />         
<select class="form-control date-picker input-sm" id="numDays" name="numDays">
<option value="0">Select Days</option>
<option value="7" selected="selected">7 Days</option>
<option value="30" <?php  if(isset($_GET['numDays']) && $_GET['numDays']==30) { ?> selected="selected" <?php  } ?>>30 Days</option>
<option value="60" <?php  if(isset($_GET['numDays']) && $_GET['numDays']==60) { ?> selected="selected" <?php  } ?>>60 Days</option>
<option value="90" <?php  if(isset($_GET['numDays']) && $_GET['numDays']==90) { ?> selected="selected" <?php  } ?>>90 Days</option>
<option value="120" <?php  if(isset($_GET['numDays']) && $_GET['numDays']==120) { ?> selected="selected" <?php  } ?>>120 Days</option>
<option value="150" <?php  if(isset($_GET['numDays']) && $_GET['numDays']==150) { ?> selected="selected" <?php  } ?>>150 Days</option>
<option value="180" <?php  if(isset($_GET['numDays']) && $_GET['numDays']==180) { ?> selected="selected" <?php  } ?>>180 Days</option>
</select>    
</div> </div>

<div class="col-sm-2"><div class="clearfix">
<span>Branch</span>
<input type="hidden" id="bid" name="bid"  <?php if(isset($_GET['bid'])) { ?> value="<?php echo $_GET['bid']; ?>"  <?php } ?>  />
<input type="text" id="branch" name="branch" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Branch" onkeyup="getBranch(this.value)" <?php if(isset($_GET['branch'])) { ?> value="<?php echo $_GET['branch']; ?>"  <?php } ?>  />
<ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="branchList">
</ul>

</div></div>


<div class="col-sm-2"><div class="clearfix">
<span>Employee</span>
<input type="hidden" id="eid" name="eid"  <?php if(isset($_GET['eid'])) { ?> value="<?php echo $_GET['eid']; ?>"  <?php } ?>  />
<input type="text" id="employee" name="employee" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Employee" onkeyup="getEmployee(this.value)" <?php if(isset($_GET['employee'])) { ?> value="<?php echo $_GET['employee']; ?>"  <?php } ?>  />
<ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="employeesList">
</ul>

</div></div>


<div class="col-sm-2">
<span>Customer</span>
<input type="hidden" id="cid" name="cid" value="<?PHP echo $_GET['cid']; ?>" />

<input type="text" id="customer" name="customer" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Customer" <?php if(isset($_GET['customer'])) { ?> value="<?php echo $_GET['customer']; ?>" <?php } ?>  />

<ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="customersList"></ul>
</div>



<div class="form-group col-sm-2">
<br>
<input type="submit" class="btn btn-sm btn-success" name="paymentsSearch" value="Search">
</div>

</div>

</form>
<div class="space"></div>
</div>
							<div class="col-xs-12">
                              <!-- PAGE CONTENT BEGINS -->
								

							

  <?php
   if(isset($_GET['delete']))
      { echo '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Product has been deleted!</div>'; 
	  } else if(isset($_GET['error'])) 
		{ echo '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; 
	  }

 
										   ?> 
                                            
                                           
                                            

								<div class="row">
									<div class="col-xs-12">
										
										<div class="table-header">
											Outstandings
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
                                                      <th>PO No.</th>
                                                      <th>PO Date</th>
                                                      <th>Invoice No</th>
                                                      <th>Invoice Date</th>
                                                      <th>Action</th>
                                                     
                                                       </tr>
												</thead>

												<tbody>
													
														
																	

  <?php 
  
// to download the excel sheet 
 $list[] = array( 'S. No', 'PO NO', 'PO Date','Invoice No.', 'Invoice Date' );

if(mysql_num_rows($employees)>0)
  {
	    $i = $start+1; 
	 $grandTotalPending = 0; 
	 $slno=0;
  while($employee = mysql_fetch_array($employees))
  {
	 $slno++;
	 //echo '<pre>'; print_r($employee).'jihih';
	 
	 // to get the product details on particular invoiceId we use this query
/* relation between products and daily_reports_data are daily_reports_data.productId = products.productId
relation between invoices and daily_reports_data are daily_reports_data.invoice_id = invoices.invoiceId 
 */
	 $qry_prdct = mysql_query("select drd.price,drd.quantity, date_format(enquiries.dateTime,'%d-%m-%Y') as enqdt, prd.product,inv.invoiceNumber from daily_reports_data drd join invoices inv on drd.invoice_id=inv.invoiceId join products prd on prd.productId=drd.productId left join daily_reports on daily_reports.reportId=inv.reportId left join enquiries on enquiries.enquiryNumber = daily_reports.enquiryNumber where drd.invoice_id='".$employee['invoiceId']."' order by drd.id desc limit 1");

	  $prd_name='';
	  $prd_quantity=0;
	  $enqdated='';
	  if(mysql_num_rows($qry_prdct)>0)
	  {
		while($prd = mysql_fetch_object($qry_prdct))  
		{
			  $prd_name=$prd->product;
			  $prd_quantity=$prd->quantity;
			  $enqdated=$prd->enqdt;
		}
		
	  }
	 ?>
      <tr>
      
														<td class="center">
															<?php echo $slno; ?>
														</td>
                                                      <td><?php echo $employee['poNo'] ?></td>
                                                      <td><?php echo $employee['podt'] ?></td>
                                                     <td> <?PHP echo $employee['invoiceNumber']; ?></td> 
                                                    <td> <?PHP echo $employee['invoiceDateTime'];?></td>                                                       
														<td> 
                                         <a href="outstandings-view.php?empid=<?PHP echo $employee['id']; ?>&invid=<?PHP echo $employee['invoiceNumber'];?>" class="btn btn-warning btn-sm"><i class="ace-icon fa fa-eye icon-only"></i></a> </td>
                                                       </tr>
                                                       <?php 
  }
 
 
 }
 
 else { ?> <tr><td colspan="9">No Data found.</td></tr> <?php }
 
 
    if($numRecords>$limit &&  !(isset($_GET['paymentsSearch']))) 
	{
 
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
	  
	  
	  
  }
  
  ?>  
 <tr><td colspan="6">
  
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
             Page <input class="ui-pg-input" type="text" size="2" maxlength="7" value="<?php echo $currentPage; ?>" role="textbox"> of <span id="sp_1_grid-pager"><?php echo $numPages; ?></span>
  </td>
  <td class="ui-pg-button ui-state-disabled" style="width: 4px; cursor: default;">
         <span class="ui-separator"></span>
  </td>
  <td id="next_grid-pager" class="ui-pg-button ui-corner-all <?php echo $thirdlink; ?>" style="cursor: default;"  <?PHP if($numPages>$currentPage) { ?>onclick="goToPage('<?php echo $currentPage+1; ?>')"<?PHP }  ?> >
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
                                            
<?php           
// to download excel sheet document                  
$fp = fopen('excel/outstandings.csv', 'w');

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
        
        
        <!-- page specific plugin scripts -->
		<script src="assets/js/date-time/bootstrap-datepicker.js"></script>
		<script src="assets/js/jqGrid/jquery.jqGrid.src.js"></script>
		<script src="assets/js/jqGrid/i18n/grid.locale-en.js"></script>
        
        <!-- page specific plugin styles -->
		<link rel="stylesheet" href="assets/css/jquery-ui.css" />
		<link rel="stylesheet" href="assets/css/datepicker.css" />
		<link rel="stylesheet" href="assets/css/ui.jqgrid.css" />




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
			if(confirm("Do you want to delete the product."))
			{
				window.location = 'products.php?did='+did;
				
			}
		   	
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
		
		
	function getBranch(val)
		{
			
			document.getElementById("branchList").style.display = 'block';
				$.ajax({url: "ajax/getBranchList.php?val="+val, success: function(result){
		$("#branchList").html(result);
    }});	
			
		}
		
		
		function selectBranch(id,firstName)
		{
			document.getElementById("branchList").style.display = 'none';
			document.getElementById("bid").value = id;
			document.getElementById("branch").value = firstName;
	
		}	
		
		
		function selectCustomer(id,firstName)
		{
			document.getElementById("customersList").style.display = 'none';
			document.getElementById("cid").value = id;
			document.getElementById("customer").value = firstName;
	
		}	
		
		$(document).ready(function(){
			
			
			//$("#employee").blur(function(){
				$(document).on('click','#outclick,#breadcrumbs',function(){
				$("#employeesList,#customersList").css('display','none'); 
			});

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
		});
		
		
function goToPage(pid)
{
   window.location = 'outstandings.php?page='+pid;	
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
