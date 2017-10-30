<?php include("includes/header.php"); 

 // delete
 if(isset($_GET['did']))
 {
	
	mysql_query("delete from products where productId = '". $_GET['did'] ."'"); 
	 header("location: products.php?delete=1");	
 }

$limit = 10;

// to get the payment  data from collections table . 
/* to get the enquiries data we will execute this query with employees table.
relation between daily_reports and enquiries is enquiryNumber.
relation between collections and daily_reports are collections.invoiceId = daily_reports.reportId
relation between daily_reports and employees are daily_reports.addedBy = employees.id
relation between branches and employees are employees.branch = branches.branchId
*/


$numRecords = mysql_query("select employees.firstName, daily_reports.enquiryNumber, collections.paymentType, collections.amount, collections.paidDate, daily_reports.reportId, daily_reports.invoice from collections left join daily_reports on collections.invoiceId = daily_reports.reportId
						left join employees on daily_reports.addedBy = employees.id
					    GROUP BY collections.id desc");

						
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

  
    
  if(isset($_GET['reportSearch']))
									{
										
										
						/* from date to todate search based on "invoices.invoiceDateTime".
					employee search based on employees.id
					customer search based on enquiries.companyId.  
					branch search based on employees.branch
					*/					
										  
										$where = 'where ';
										
									$whereItems[] = "daily_reports.reportType = '0'";
										
									if(isset($_GET['fromDate']) && isset($_GET['toDate']))
								
								{
								if($_GET['fromDate']!='' && $_GET['toDate']!='')

									{
									
										$fromDate = explode('-',$_GET['fromDate']);
										$fromDate = $fromDate[2].'-'.$fromDate[1].'-'.$fromDate[0];
										
										$toDate = explode('-',$_GET['toDate']);
										$toDate = $toDate[2].'-'.$toDate[1].'-'.$toDate[0];
									 
									 
									  $whereItems[] = "invoices.invoiceDateTime >=  '". $fromDate ."'";
								
									  $whereItems[] = "invoices.invoiceDateTime <=  '". $toDate ."'";
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
									   
									
									   // by employee
									  if(isset($_GET['employee']) && $_GET['employee']!='')
									  {
									   if(isset($_GET['eid']) && $_GET['eid']>0)
									   {  
									    
										    $whereItems[] = "employees.id =  '". $_GET['eid'] ."'";
									   }
									  }
									  else
									  $_GET['employee'];
									  
									
									   // by Branch
   									  if(isset($_GET['branch']) && $_GET['branch']!='')
									  {
									   if(isset($_GET['bid']) && $_GET['bid']>0)
									   {  
									  
										    $whereItems[] = "employees.branch =  '". $_GET['bid'] ."'";
									   }
									  }
									  else
									  $_GET['branch'];
										
									
									
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

// to get the payment data from collections table.	
/* relation between collections and invoices are ...invoiceId
relation between invoices and reports are reportId. */
  											
$collections = mysql_query("select employees.firstName,drd.revisionId,  collections.paymentType, customers.company,daily_reports.enquiryNumber, collections.id as collections_id, collections.amount, collections.paidDate, invoices.invoiceId, invoices.invoiceNumber, invoices.grandTotal, branches.branch,date_format(enquiries.dateTime,'%d-%m-%Y') as enqdt  from collections left join invoices on collections.invoiceId = invoices.invoiceId left join daily_reports on invoices.reportId = daily_reports.reportId left join employees on daily_reports.addedBy = employees.id left join branches on employees.branch=branches.branchId left join enquiries on enquiries.enquiryNumber = daily_reports.enquiryNumber left join customers on customers.customerId=daily_reports.company left join daily_reports_revision drv on drv.reportId=daily_reports.reportId
left join daily_reports_data as drd on drd.revisionId=drv.revisionId
left join product_model as pm on pm.ModelId=drd.modelId  $where GROUP BY collections.id desc");


}
else								
{
// to get the payment data from collections table.												
$collections = mysql_query("select employees.firstName,drd.revisionId,  collections.paymentType,customers.company,daily_reports.enquiryNumber, collections.id as collections_id, collections.amount, collections.paidDate, invoices.invoiceId, invoices.invoiceNumber, invoices.grandTotal,branches.branch,date_format(enquiries.dateTime,'%d-%m-%Y') as enqdt from collections  left join invoices on collections.invoiceId = invoices.invoiceId left join daily_reports on invoices.reportId = daily_reports.reportId left join employees on daily_reports.addedBy = employees.id left join branches on employees.branch=branches.branchId left join enquiries on enquiries.enquiryNumber = daily_reports.enquiryNumber left join customers on customers.customerId=daily_reports.company left join daily_reports_revision drv on drv.reportId=daily_reports.reportId
left join daily_reports_data as drd on drd.revisionId=drv.revisionId
left join product_model as pm on pm.ModelId=drd.modelId   $where GROUP BY collections.id desc limit $start, $limit");
						

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
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Payments</li>
						</ul><!-- /.breadcrumb -->

						
                         <a href="excel/payments.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>
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
  
  <div class="col-sm-2"><div class="clearfix">
     <span>Branch</span>
     <input type="hidden" id="bid" name="bid"  <?php if(isset($_GET['bid'])) { ?> value="<?php echo $_GET['bid']; ?>"  <?php } ?>  />
     <input type="text" id="branch" name="branch" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Branch" onkeyup="getBranch(this.value)" <?php if(isset($_GET['branch'])) { ?> value="<?php echo $_GET['branch']; ?>"  <?php } ?>  />
           <ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="branchList">
                                           </ul>
                                           
                                </div></div>
 
 
                                           
                               
                                <div class="col-sm-2">
                                <span>Employee</span>
       <input type="hidden" id="eid" name="eid" value="<?PHP echo $_GET['eid']; ?>" />
     <input type="text" id="employee" name="employee" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Employee" <?php if(isset($_GET['employee'])) { ?> value="<?php echo $_GET['employee']; ?>" <?php } ?> onkeyup="getEmployee(this.value)"  />
           <ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="employeesList">
                                           </ul>
                                           
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
      { echo '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Product has been deleted!</div>'; 
	  } else if(isset($_GET['error'])) 
		{ echo '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; 
	  }

?>                                            

								<div class="row">
									<div class="col-xs-12">
										
										<div class="table-header">
											Payments
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
                                                         <th>Date</th>
                                                         
                                                        <th>Invoice No</th>
                                                        <th>Enquiry No</th>
                                                        <th>Enquiry Date</th>
                                                        <th> Product</th>
                                                          <th> Model</th>
                                                        <th> Quantity</th>
                                                        <th>Company Name</th>                                                      													
                                                    	<th>Employee</th>
                                                        <th>Branch</th>
                                                        <th>Type</th>	
                                                        
                                                        <th>Total</th>
                                                        <th>Collected</th>
                                                        <th>View</th>
                                                       </tr>
												</thead>

												<tbody>
													
														
																	

  <?php
  // to download file in excel format we have written this code.
     $list[] = array('S. No','Date','Invoice No','Employee','Branch','Type','Total','Collected');
  if(mysql_num_rows($collections)>0)
  {
	  $i = $start+1; 
		
	 
	  $totalCollection = 0;
  while($collection = mysql_fetch_array($collections))
  {
	    $totalCollection = $totalCollection+$collection['amount'];
	
	
	
	$totalAmount = mysql_query("select grandTotal from daily_reports_revision where daily_reports_revision.reportId = '". $collection['reportId'] ."' and daily_reports_revision.revision = '". $collection['currentRevisionId'] ."'");
  
 
  if(mysql_num_rows($totalAmount)>0)
  {
	  $total = mysql_fetch_array($totalAmount);
	  $grandTotal = $total['grandTotal']; 
  }
  else
  { $grandTotal = 0; }
  
 
  
//getting the product and its quantity
/* relation between products and daily_reports_data are daily_reports_data.productId = products.productId
relation between invoices and daily_reports_data are daily_reports_data.invoice_id = invoices.invoiceId 

 */




 
// get the data from product table. 
$items = mysql_query("select products.productId, products.product, daily_reports_data.id, pm.ModelNo ,daily_reports_data.quantity from daily_reports_data
 left join products on daily_reports_data.productId = products.productId
 left join product_model as pm on pm.ModelId=daily_reports_data.modelId
 where daily_reports_data.revisionId = '".$collection['revisionId']."'");
 
/* echo "select products.productId, products.product, daily_reports_data.id, pm.ModelNo ,daily_reports_data.quantity from daily_reports_data
 left join products on daily_reports_data.productId = products.productId
 left join product_model as pm on pm.ModelId=daily_reports_data.modelId
 where daily_reports_data.revisionId = '".$collection['revisionId']."'"; exit; 
 */
 
 
		$prd_itm="<ol>";
		$prd_qty = "<ol>";
		$model="<ol>";
		
		
if(mysql_num_rows($items)>0)
{
	while($item = mysql_fetch_array($items))
	{
		$prd_itm.="<li>".$item['product']."</li>";
		$prd_qty.="<li>".$item['quantity']."</li>";
		$model.="<li>".$item['ModelNo']."</li>";
	}
}


		$prd_itm.="</ol>";
		$prd_qty.= "</ol>";
		$model.="</ol>";

	
	  
	 ?><tr>
														<td class="center">
															<?php echo $i; ?>
														</td>
                                                        
                                                         <td>
															<?php $paidOn = explode('-',$collection['paidDate']);
															echo $paidOn[2].'-'.$paidOn[1].'-'.$paidOn[0];
															 ?>
														</td>
                                                        <td>
															<?php echo $collection['invoiceNumber']; ?>
														</td>
                                                        
                                                        <td>
													 <?php if($collection['enquiryNumber']!='')
													 {
													  echo $collection['enquiryNumber']; }
													  else
													  {
														  echo '--';
													  }
													  
													  ?>
												  </td>
                                                  <td><?PHP  if($collection['enqdt']!=''){ echo $collection['enqdt']; } else echo "--";?> </td>
                                                 <td><?PHP echo $prd_itm; ?></td>
                                                          <td><?PHP echo $model; ?></td>
                                                        <td><?PHP echo $prd_qty; ?></td>
                                                       
                                                       <td><?PHP echo $collection['company']; ?></td>
                                                         <td>
															<?php echo ucfirst($collection['firstName']); ?>
														</td>
                                                        
                                                         <td>
															<?php echo ucfirst($collection['branch']); ?>
														</td>
                                                        
                                                         <td><?php
													 if($collection['paymentType']==1) { $paymentType = 'Proforma'; } 		 											                                           else if($collection['paymentType']==2) { $paymentType = 'Through Bank'; } 
											 else if($collection['paymentType']==3) { $paymentType = 'Direct Payment'; } 
											 else if($collection['paymentType']==4) { $paymentType = 'Againast Delivery'; } 

												 else if($collection['paymentType']==5) { $paymentType = 'By Check/DD'; } 
											 else if($collection['paymentType']==6) { $paymentType = 'Bank Transfer'; } 
												 else if($collection['paymentType']==7) { $paymentType = 'By Cash'; } 
															 echo $paymentType;
															 
															 //unset($paymentType);
															 
															 
															  ?>
														</td>
                                                        
                                                         <td>
															<?php echo $collection['grandTotal']; ?>
														</td>
<td>
															<?php echo $collection['amount']; ?>
														</td>
                                                       <td> 
<a href="payments-view.php?invoice=<?PHP echo $collection['invoiceNumber']; ?>" class="btn btn-warning btn-sm"><i class="ace-icon fa fa-eye icon-only"></i></a>
                                                       </td>
                                                        
                                                      
                                                       </tr>
<?php
// download excel sheet code
$rowlist[] = $i;
$rowlist[] = $collection['paidDate'];
$rowlist[] = $collection['invoiceNumber'];
$rowlist[] = $collection['firstName'];
$rowlist[] = $collection['branch'];
$rowlist[] = $paymentType;
$rowlist[] = $collection['grandTotal']; 
$rowlist[] = $collection['amount']; 
 $list[] = $rowlist;
 unset($rowlist);



 $i++; } 
 $rowlist[] = '';
 $rowlist[] = '';
 $rowlist[] = '';
 $rowlist[] = '';
 $rowlist[] = '';
 $rowlist[] = '';
 $rowlist[] = '';
$rowlist[] = $totalCollection;
 $list[] = $rowlist;
 unset($rowlist);
 
 ?>
 <tr><td colspan="11"></td><td></td><td ><?php echo $totalCollection; ?></td></tr>
 <?php } else { ?> <tr><td colspan="14">No Data found.</td></tr> <?php }
 
 
   if($numRecords>$limit &&  !(isset($_GET['reportSearch']))) {
 
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
                                            
                                                                                      <?php                              
$fp = fopen('excel/payments.csv', 'w');

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
		 $(document).on('click','#outclick,#breadcrumbs',function(){
			$('#customersList,#branchList,#employeesList').css('display','none');
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


		
		
		function selectCustomer(id,firstName)
		{
			document.getElementById("customersList").style.display = 'none';
			document.getElementById("cid").value = id;
			document.getElementById("customer").value = firstName;
	
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
		
function goToPage(pid)
{
   window.location = 'payments.php?page='+pid;	
}
		
		</script>

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
		
		$('.date-picker').datepicker({
					autoclose: true,
					todayHighlight: true,
					format: 'dd-mm-yyyy'
				})
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
