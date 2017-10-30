<?php include("includes/header.php"); 

?>

<?php
 // delet report
 if(isset($_GET['did']))
 {
	mysql_query("delete from daily_reports where reportId = '". $_GET['did'] ."'");
	header("location: proposals.php?delete=1"); 
 }
 

  if(isset($_POST['po_submit']))
  {
	  
	  mysql_query("update daily_reports set poNo = '". $_POST['po_number'] ."' where reportId = '". $_POST['reportId'] ."'");  
	  header("location: proposals.php");
  }
  
  
  
// pagination
 $limit = 10;
 

 $numRecords = mysql_query("select daily_reports.reportId, daily_reports.reportDate, daily_reports.leadType, daily_reports.leadStatus, daily_reports.poNo, daily_reports.poDateTime, daily_reports.inv, daily_reports.invoice, daily_reports.invoiceDateTime, daily_reports.runcard, customers.company, employees.firstName from daily_reports
left join customers on daily_reports.company = customers.customerId
left join employees on daily_reports.addedBy = employees.id
where daily_reports.offer > '0' and daily_reports.po > '0' 
order by reportId desc");
 
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
    $whereItems[] = "daily_reports.offer > '0'";
	$whereItems[] = "daily_reports.po > '0'";
  if(isset($_GET['proposalSearch']))
									{
										
										
										
									if(isset($_GET['fromDate']) && strlen($_GET['fromDate']>8) && isset($_GET['toDate']) && strlen($_GET['toDate']>8))
									{
										
									
										$fromDate = explode('-',$_GET['fromDate']);
										$fromDate = $fromDate[2].'-'.$fromDate[1].'-'.$fromDate[0];
										
										$toDate = explode('-',$_GET['toDate']);
										$toDate = $toDate[2].'-'.$toDate[1].'-'.$toDate[0];
									 
									 
									 $whereItems[] = "daily_reports.reportDate >  '". $fromDate ."'";
									 
									 $whereItems[] = "daily_reports.reportDate <  '". $toDate ."'";
									}
									
									
									   // by employee
									   if(isset($_GET['eid']) && $_GET['eid']>0)
									   {  
									    
										    $whereItems[] = "employees.id =  '". $_GET['eid'] ."'";
									   }
									   
									    // by customer
									   if(isset($_GET['cid']) && $_GET['cid']>0)
									   {  
									  
										    $whereItems[] = "customers.customerId =  '". $_GET['cid'] ."'";
									   }
									   
									
									// by Branch
									   if(isset($_GET['bid']) && $_GET['bid']>0)
									   {  
									  
										    $whereItems[] = "employees.branch =  '". $_GET['bid'] ."'";
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
												
										
					$proposals = mysql_query("select daily_reports.reportId, daily_reports.offer, daily_reports.offerNo, daily_reports.offerToBeSubmitted,  daily_reports.currentRevisionId, daily_reports.reportDate, daily_reports.leadType, daily_reports.leadStatus, daily_reports.po, daily_reports.poNo, daily_reports.poDateTime, daily_reports.inv, daily_reports.invoice, daily_reports.invoiceDateTime, daily_reports.runcard, daily_reports.proformaInvoice, daily_reports.proformaInvoiceDateTime, customers.company, employees.firstName from daily_reports

left join customers on daily_reports.company = customers.customerId
left join employees on daily_reports.addedBy = employees.id
$where
 order by reportId desc");							
												
									}
									else
									{
										
											$proposals = mysql_query("select daily_reports.reportId, daily_reports.offer, daily_reports.offerNo, daily_reports.offerToBeSubmitted,  daily_reports.currentRevisionId, daily_reports.reportDate, daily_reports.leadType, daily_reports.leadStatus, daily_reports.po, daily_reports.poNo, daily_reports.poDateTime, daily_reports.inv, daily_reports.invoice, daily_reports.invoiceDateTime, daily_reports.proformaInvoice, daily_reports.proformaInvoiceDateTime, customers.company, daily_reports.runcard, employees.firstName from daily_reports

left join customers on daily_reports.company = customers.customerId
left join employees on daily_reports.addedBy = employees.id
where daily_reports.offer > '0' and daily_reports.po > '0'
 order by reportId desc limit $start, $limit");		}

										
											
 
 $branches = mysql_query("select * from branches order by branch");






?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> PO's</li>
						</ul><!-- /.breadcrumb -->

					  <a href="excel/pos.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>
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
     <span>Branch</span><br />
     <select id="bid" name="bid">
     <option value="">Select Branch</option>
     <?php  
	 while ($branch = mysql_fetch_array($branches))
	 {  ?>
 <option <?php if(isset($_GET['bid']) && $_GET['bid']==$branch['branchId']) { ?> selected="selected" <?php } ?>  value="<?php echo $branch['branchId'] ?>"><?php echo $branch['branch'] ?></option>
                                         
                                            
                                        <?php
                                           }  ?>     </select> 
                               </div>      
                 
                 
                 
                 
                 
                                            
                               
                                <div class="col-sm-2">
                                <span>Employee</span>
     <input type="hidden" id="eid" name="eid"   />
     <input type="text" id="employee" name="employee" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Employee" <?php if(isset($_GET['employee'])) { ?> value="<?php echo $_GET['employee']; ?>" <?php } ?> onkeyup="getEmployee(this.value)"  />
           <ul class="typeahead dropdown-menu" style="left: 10px; display: none;" id="employeesList">
                                           </ul>
                                           
                                </div> 
                                
                     
                               
                                <div class="col-sm-2">
                                <span>Customer</span>
      <input type="hidden" id="cid" name="cid"   />
     <input type="text" id="customer" name="customer" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Customer" <?php if(isset($_GET['customer'])) { ?> value="<?php echo $_GET['customer']; ?>" <?php } ?> onkeyup="getCustomer(this.value)" />
     
      <ul class="typeahead dropdown-menu" style="left: 10px; display: none;" id="customersList"></ul>
                                           
                                </div> 
                                
                                
                                       
  <div class="form-group col-sm-2">
   <br/>
    <input type="submit" class="btn btn-sm btn-success" name="proposalSearch" value="Search" />
  </div>
  
  </div>
                                
                              
                                 
                               
  
</form>
<div class="space"></div>
</div>

									<div class="col-xs-12">
										
										<div class="table-header">
											PO's
										</div>


    <?php
										   
	   if(isset($_GET['delete']))
{ $alertMsg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Proposal has been deleted!</div>'; }
else if(isset($_GET['error']))
{ $alertMsg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  if(isset($alertMsg)) { echo $alertMsg; }
										   ?> 
										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">
											
													
													
																	

  <?php  $list[] = array('S. No', 'Report Date', 'Offer No', 'Po No', 'Po Date', 'Value', 'Executive', 'Company', 'Status' );
  $finalTotal = 0;
  if(mysql_num_rows($proposals)>0)
  {
	  ?>
      
      <?PHP
  $i = $start+1; 
  
$cnte=0;
$data_exists=0;
  while($proposal = mysql_fetch_array($proposals))
  {
	  
	  if($cnte == 0)
	  {
		  ?>
          <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                            
												<thead>
													<tr>
														<th class="center">
															S.no
														</th>
														<th>Po No</th>
                                                        <th>Po Date</th>
                                                        <th>Value</th>
                                                        <th>Executive</th>
                                                        <th>Company</th>
                                                    	<th>Status</th>
                                                        <th>Action</th>
														
													</tr>
												</thead>

												<tbody>	
          <?PHP
	  }
	  $cnte++; 

  if( $proposal['leadStatus'] == 'Pending Invoice' && $proposal['leadStatus'] == 'Po Collected' && !isset($_SESSION['ordersandExecution'])) 
	   {
		   $data_exists=1;
		   
	  $totalAmount = mysql_query("select grandTotal from daily_reports_revision where daily_reports_revision.reportId = '". $proposal['reportId'] ."' and daily_reports_revision.revision = '". $proposal['currentRevisionId'] ."'");
  
  if(mysql_num_rows($totalAmount)>0)
  {
	  $total = mysql_fetch_array($totalAmount);
	 
	  $grandTotal = $total['grandTotal']; 
  }
  else
  
  { $grandTotal = 0; }
  
  $finalTotal = $finalTotal+$grandTotal;  
	 ?><tr>
														<td class="center">
															<?php echo $i; ?>
														</td>
                                                        <td>
                                                        
															<?php
															
														
															 echo $proposal['poNo']; 
															?>
                                
														</td>
                                                        
                                                        <td><?php
															 $poDate = explode(' ',$proposal['poDateTime']); 
														     $poDate = explode('-',$poDate[0]); 
														     $poDate = $poDate[2].'-'.$poDate[1].'-'.$poDate[0];
														
														 if(strcmp('00-00-0000',$poDate)!=0)
												{
													echo $poDate;
												}
															?>
                                                        </td>
                                                         
                                                          
                                                         
                                                        <td>
                                                        
															<?php
															
														
															 echo $grandTotal; 
															?>
                                
														</td>
                                                        <td>
															<?php echo ucfirst($proposal['firstName']); ?>
														</td>
                                                         <td>
															<?php echo ucfirst($proposal['company']); ?>
														</td>
                                                         
                                                        <td>
															<?php echo $proposal['leadStatus']; ?>
															
														</td>
                                                        
 														<td>
                                                       
                                         
                                           
                                                        <?php 
													
														if($proposal['offerToBeSubmitted']<2 && $proposal['po']==0)
														{ ?>
     <a class="btn btn-primary btn-sm" href="generate_offer.php?pid=<?php echo $proposal['reportId']; ?>">Add Offer</a>
                                                   <?php  } else  if($proposal['offer']>0 && $proposal['po']==1 && $proposal['inv']==0)  {  ?>
     <a href="generate_proinvoice.php?pid=<?php echo $proposal['reportId']; ?>" class="btn btn-pink btn-sm">Add Pro Invoice</a>
    
     <?php }
	 
	 
	 
	  else { ?>
     <a href="view_po.php?pid=<?php echo $proposal['reportId']; ?>" class="btn btn-pink btn-sm">view</a>
                                             <?php } 
				if($_SESSION['ordersandExecution'])
				{ ?>
                
<button type="button" class="btn runcardId btn-info btn-sm" data-toggle="modal" id="<?php echo $proposal['reportId'] ?>" data-target="#myModal">Add run card</button>
               
			<?php	} ?>
                                             
 <!-- run card for order and execution department only  --> 

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Run Card</h4>
        <div id="success-msg"
      </div>
      <div class="modal-body">
       <input type="text" name="runcard" id="runcard" placeholder="Run card" />
       <input type="hidden" name="po_no" id="po_no" />
       <span id="runcard-err" class="err-msg"></span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn runcardSub btn-default" id="runcardSub">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>                                           
                                                        

                                        		</td>

													
													</tr>
<?php

$rowlist[] = $i;
$rowlist[] = $reportDate;
$rowlist[] = $proposal['offerNo'];
$rowlist[] = $proposal['poNo'];
$rowlist[] = $poDate;
$rowlist[] = $grandTotal;
$rowlist[] = ucfirst($proposal['firstName']);
$rowlist[] = ucfirst($proposal['company']);
$rowlist[] = $proposal['leadStatus'];
$list[] = $rowlist;
 unset($rowlist);


 $i++;
 
 		} //if close

 }//while close 
 $rowlist[] = '';
 $rowlist[] = '';   
  $rowlist[] = '';
 $rowlist[] = '';
 $rowlist[] = '';
  $rowlist[] = $finalTotal;
  $list[] = $rowlist;
 unset($rowlist);
  
if($data_exists==0)
{
	?>
    <tr> <td colspan="8">No data found</td> </tr>
    <?PHP	
}
   
  
   } else { ?> <tr><td colspan="15">No Data found.</td></tr> <?php }
  
    if($numRecords>$limit &&  !(isset($_GET['proposalSearch']))) 
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
	  else
	  {
		 
	  }
	  
	  
  }
  
  ?> 
  <tr><td colspan="12">
  
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
  <?php } ?>                                              </tbody>
											</table>
                                            
                                            
                                                                                 <?php                              
$fp = fopen('excel/pos.csv', 'w');

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

 
   	function getCustomer(val)
		{
			
			document.getElementById("customersList").style.display = 'block';
				$.ajax({url: "ajax/getCustomerList.php?val="+val, success: function(result){
		$("#customersList").html(result);
    }});	
			
		}
		
		
		function selectCustomer(id,firstName)
		{
			document.getElementById("customersList").style.display = 'none';
			document.getElementById("cid").value = id;
			document.getElementById("customer").value = firstName;
	
		}	
		
		// submitting run card number through ajax
		$(document).ready(function(e){
			
			$(document).on('click','.runcardId',function(){
				var ide = $(this).attr('id');
				
				$("#po_no").val(ide);
				
				
			});
			
			
			
			$(document).on('focus','#runcard',function(){
			var id = $(this).next().attr('id');
			$("#"+id).html('');
			
			});
			
			$(document).on('click','.runcardSub',function(e){
				var runcard = $("#runcard").val();
				runcard = $.trim(runcard);
				var ide = $("#po_no").val();
				
				var err=0;
				if(runcard == '')
				{
					$("#runcard-err").html("Please enter the run card number");
					err=1;
				}
				if(err==0)
				{ 
					$.ajax({   
						url: 'ajax/sub_runcard.php',
					    type: 'POST',
						data:{'ide':ide,'runcard':runcard},
						success:function(data){
						$("#success-msg").html(data);	
						$("form").trigger("reset");
						}
						});
				}
				else
				{
					e.preventDefault();
				}
				
				
				
			});
			
		});
			


  function goToPage(pid)
{
   window.location = 'proposals.php?page='+pid;	
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
		<script src="assets/js/ace/ace.onpage-help.js"></script>
		<script src="docs/assets/js/rainbow.js"></script>
		<script src="docs/assets/js/language/generic.js"></script>
		<script src="docs/assets/js/language/html.js"></script>
		<script src="docs/assets/js/language/css.js"></script>
		<script src="docs/assets/js/language/javascript.js"></script>
	</body>
</html>
