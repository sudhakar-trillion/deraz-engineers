<?php include("includes/header.php"); 

 // delet report
 if(isset($_GET['did']))
 {
	
	mysql_query("delete from daily_reports where reportId = '". $_GET['did'] ."'");
	
	$revisions = mysql_query("select revisionId from daily_reports_revision where reportId = '". $_GET['did'] ."'");
	while($revision = mysql_fetch_array($revisions))
	{
	   	mysql_query("delete from daily_reports_data where revisionId = '". $revision['revisionId'] ."'");
	}
	
	mysql_query("delete from daily_reports_revision where reportId = '". $_GET['did'] ."'");
	
	header("location: orders.php?delete=1"); 
 }
 
// update status
if(isset($_POST['submitStatus']))
{
  
 mysql_query("insert into order_status (`orderId`, `status`, `remarks`, `dateTime`) values('". $_POST['orderId'] ."', '". $_POST['status'] ."', '". addslashes($_POST['remarks']) ."', NOW())");	
  
  $lastId = mysql_insert_id();
  
  if($lastId>0)
  {
	 header("location: orders.php?update=1");  
  }
  else
  {
	  header("location: orders.php?error=1");  
  }
 
}

// pagination
$limit = 10;

// get the order data with this query from the daily_reports,customers,employees,enquiries
/* to get the enquiries data we will execute this query with employees table.
relation between enquiries and enquiry_assign is enquiryId.
relation between daily_reports and enquiries is enquiryNumber.
relation between daily reports and employees are daily_reports.addedBy = employees.id
relation between daily_reports and customers is daily_reports.company = customers.customerId.
 */

$numRecords = mysql_query("select daily_reports.reportId, daily_reports.reportDate, daily_reports.runcard,  daily_reports.poNo, date_format(enquiries.dateTime,'%d-%m-%Y') enqDate, daily_reports.enquiryNumber, daily_reports.poDateTime, daily_reports.invoice, daily_reports.invoiceDateTime, daily_reports.leadStatus, customers.company, employees.firstName 
from daily_reports
left join customers on daily_reports.company = customers.customerId
left join employees on daily_reports.addedBy = employees.id
left join enquiries on daily_reports.enquiryNumber = enquiries.enquiryNumber
where daily_reports.po = '1' and daily_reports.inv > '0'
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
    $where = 'where ';
 //   $whereItems[] = "daily_reports.po = '1'";
  //  $whereItems[] = "daily_reports.inv > '0'";
  if(isset($_GET['ordersSearch']))
									{
										
									/* from date to todate search based on "invoices.invoiceDateTime".
					employee search based on employees.id
					customer search based on customers.customerId.  
					branch search based on employees.branch
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
									 
									 
									  $whereItems[] = "invoices.invoiceDateTime >=  '". $fromDate ."'";
								
									  $whereItems[] = "invoices.invoiceDateTime <=  '". $toDate ."'";
									}
								}
									
									
									   // by employee
									   if(isset($_GET['employee']) && $_GET['employee']!='')
									   if(isset($_GET['eid']) && $_GET['eid']>0)
									   {  
									    
										    $whereItems[] = "employees.id =  '". $_GET['eid'] ."'";
									   }
									   else
									   $_GET['employee'];
									   
									    // by customer
									   if(isset($_GET['customer']) && $_GET['customer']!='')
									   if(isset($_GET['cid']) && $_GET['cid']>0)
									   {  
									  
										    $whereItems[] = "customers.customerId =  '". $_GET['cid'] ."'";
									   }
									   else
									   $_GET['customer']='';
									   
									   
									     // by Branch
									   if(isset($_GET['bid']) && $_GET['bid']>0)
									   {  
									  
										    $whereItems[] = "employees.branch =  '". $_GET['bid'] ."'";
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
												
											
								
// get the order search data with this query from the daily_reports,customers,employees,enquiries,invoices
	$proposals = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.runcard, daily_reports.enquiryNumber, date_format(enquiries.dateTime,'%d-%m-%Y') enqDate, daily_reports.reportDate, daily_reports.leadStatus, daily_reports.poNo, daily_reports.poDateTime, daily_reports.inv, customers.company, employees.firstName, branches.branch, invoices.invoiceId, invoices.proInvoiceNumber, invoices.proInvoiceDateTime, invoices.invoiceNumber, invoices.invoiceDateTime,  invoices.grandTotal 

from invoices
left join daily_reports on invoices.reportId = daily_reports.reportId

left join customers on daily_reports.company = customers.customerId
left join employees on daily_reports.addedBy = employees.id
left join enquiries on daily_reports.enquiryNumber = enquiries.enquiryNumber

left join daily_reports_revision as drv on drv.reportId=daily_reports.reportId
left join daily_reports_data as drd on drd.revisionId=drv.revisionId
left join categories as cat on cat.id=drd.categoryId

left join branches on employees.branch = branches.branchId

$where 
GROUP BY daily_reports.reportId desc limit $start, $limit");

 }
 
 else if(isset($_GET['invs']))
 {
//to get the pending orders data we	have written this query and the count of pending orders displayed in header file. 
$proposals = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.enquiryNumber, date_format(enquiries.dateTime,'%d-%m-%Y') enqDate, daily_reports.reportDate, daily_reports.leadStatus, daily_reports.runcard,  daily_reports.poNo, daily_reports.poDateTime, daily_reports.inv, customers.company, employees.firstName, branches.branch, invoices.invoiceId, invoices.proInvoiceNumber, invoices.proInvoiceDateTime, invoices.invoiceNumber, invoices.invoiceDateTime,  invoices.grandTotal, invoices.invoiceStatus 

from invoices
left join daily_reports on invoices.reportId = daily_reports.reportId
left join customers on daily_reports.company = customers.customerId
left join employees on daily_reports.addedBy = employees.id
left join branches on employees.branch = branches.branchId
left join enquiries on daily_reports.enquiryNumber = enquiries.enquiryNumber
where invoices.paymentStatus = 'open' and invoiceStatus = 'Invoice generated'
GROUP BY daily_reports.reportId desc");
	 
 }
 
 
else if(isset($_GET['invId']))
{
//to get the pending orders data we have written this query and the count of pending orders displayed in header file. based on invoiceId...data comes
$proposals = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.enquiryNumber, date_format(enquiries.dateTime,'%d-%m-%Y') enqDate, daily_reports.reportDate, daily_reports.leadStatus, daily_reports.runcard,  daily_reports.poNo, daily_reports.poDateTime, daily_reports.inv, customers.company, employees.firstName, branches.branch, invoices.invoiceId, invoices.proInvoiceNumber, invoices.proInvoiceDateTime, invoices.invoiceNumber, invoices.invoiceDateTime,  invoices.grandTotal, invoices.invoiceStatus 

from invoices
left join daily_reports on invoices.reportId = daily_reports.reportId
left join customers on daily_reports.company = customers.customerId
left join employees on daily_reports.addedBy = employees.id
left join branches on employees.branch = branches.branchId
left join enquiries on daily_reports.enquiryNumber = enquiries.enquiryNumber

left join daily_reports_revision as drv on drv.reportId=daily_reports.reportId
left join daily_reports_data as drd on drd.revisionId=drv.revisionId
left join categories as cat on cat.id=drd.categoryId


where invoices.paymentStatus = 'open' and invoiceStatus = 'Invoice generated' and invoices.invoiceId = '".$_GET['invId']."' 
GROUP BY daily_reports.reportId desc");
	 
 }
 
 
 else
 {
	 
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



/*$proposals = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.reportDate, daily_reports.leadStatus, daily_reports.poNo, daily_reports.poDateTime, daily_reports.invoice, daily_reports.invoiceDateTime, daily_reports.proformaInvoice, daily_reports.proformaInvoiceDateTime,  customers.company, employees.firstName, branches.branch 

from daily_reports

left join customers on daily_reports.company = customers.customerId
left join employees on daily_reports.addedBy = employees.id
left join branches on employees.branch = branches.branchId
$where 
order by daily_reports.reportId desc limit $start, $limit");*/


// get the order data with this query from the daily_reports,customers,employees,enquiries
$proposals = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.enquiryNumber, date_format(enquiries.dateTime,'%d-%m-%Y') enqDate, daily_reports.reportDate, daily_reports.leadStatus, daily_reports.runcard,  daily_reports.poNo, daily_reports.poDateTime, daily_reports.inv, customers.company, employees.firstName, branches.branch, invoices.invoiceId, invoices.proInvoiceNumber, invoices.proInvoiceDateTime, invoices.invoiceNumber, invoices.invoiceDateTime,  invoices.grandTotal, invoices.invoiceStatus 

from invoices
left join daily_reports on invoices.reportId = daily_reports.reportId
left join customers on daily_reports.company = customers.customerId
left join employees on daily_reports.addedBy = employees.id
left join branches on employees.branch = branches.branchId
left join enquiries on daily_reports.enquiryNumber = enquiries.enquiryNumber

left join daily_reports_revision as drv on drv.reportId=daily_reports.reportId
left join daily_reports_data as drd on drd.revisionId=drv.revisionId
left join categories as cat on cat.id=drd.categoryId

$where 
GROUP BY daily_reports.reportId desc limit $start, $limit");


 }
 

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
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Orders</li>
                            
						</ul><!-- /.breadcrumb -->

						<a href="excel/orders.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>
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
<input type="text" class="form-control date-picker input-sm" id="toDate" name="toDate" placeholder="To Date" <?php if(isset($_GET['toDate'])) { ?> value="<?php echo $_GET['toDate']; ?>" <?php } ?>  style="width:100%"/>
</div>


<div class="col-sm-2" style="width:11%">
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

<input type="text" id="customer" name="customer" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Customer" <?php if(isset($_GET['customer'])) { ?> value="<?php echo $_GET['customer']; ?>" <?php } ?> style="100%"  />

<ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="customersList"></ul>
</div> 

<div class="form-group col-sm-2">
<span><br></span>
<input type="submit" class="btn btn-sm btn-success" name="ordersSearch">
</div>

</div>





</form>

        <div class="space-6"></div>

</div>

									<div class="col-xs-12">
										
										<div class="table-header">
											Orders
										</div>


<?php
	   if(isset($_GET['delete']))
{ echo '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Order has been deleted!</div>'; }
else if(isset($_GET['update']))
{ echo '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Status has been updated!</div>'; }
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
                                                         <th>Invoice Date</th>
                                                        <th>Invoice No.</th>
                                                        <th>Enquiry Date</th>
                                                         <th>Enquiry No.</th>
                                                        <th>Invoice Amount</th>
                                                        <th>Branch</th>
                                                        <th>Executive</th>
                                                        <th>Company Name</th>
                                                      
                                                        <th>Collected</th>
                                                        <th>Pending</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
														
													</tr>
												</thead>

												<tbody>
													
														
																	

  <?php 
    // to download file in excel format we have written this code.
   $list[] = array('S. No', 'Proforma Date', 'Proforma Invoice', 'Invoice Date', 'Invoice No', 'Invoice Amount', 'Executive', 'Company', 'Collected', 'Pending');
   $i=0;
  if(mysql_num_rows($proposals)>0) {
  $i = $start+1; 
  
  $finalTotal = 0;
  $finalPending = 0;
  $finalCollected = 0;
  
  while($proposal = mysql_fetch_array($proposals))
  {
	
	
	/*$totalAmount = mysql_query("select grandTotal from daily_reports_revision where daily_reports_revision.reportId = '". $proposal['reportId'] ."' and daily_reports_revision.revision = '". $proposal['currentRevisionId'] ."'");
  
 
  if(mysql_num_rows($totalAmount)>0)
  {
	  $total = mysql_fetch_array($totalAmount);
	  $grandTotal = $total['grandTotal']; 
  }
  else
  { $grandTotal = 0; }
  
  $finalTotal = $finalTotal+$grandTotal;  */
  
	
						
		
  
  
  // dispatched	
$dispatchedResult = mysql_query("select daily_reports_data.id, daily_reports_data.quantity, dispatch_items.dispatchedQuantity 
from daily_reports_data
left join dispatch_items on daily_reports_data.id = dispatch_items.itemId
where daily_reports_data.reportId = '". $proposal['reportId'] ."'
order by daily_reports_data.reportId desc");

	 ?><tr>
														<td class="center">
														<?php  echo $i; ?>
														</td>
                                                       	
                                                        	
                                                        	<td>
															<?php
													     $invoiceDate = explode(' ',$proposal['invoiceDateTime']); 
														 $invoiceDate = explode('-',$invoiceDate[0]); 
														 $invoiceDate = $invoiceDate[2].'-'.$invoiceDate[1].'-'.$invoiceDate[0];
														 
														 if(strcmp('00-00-0000',$invoiceDate)!=0)
												{
													echo $invoiceDate;
												}
														 
															?>
														</td>
                                                        	<td>
															<?php
															
														
															 echo $proposal['invoiceNumber']; 
															?>
                                
														</td>
                                                        
                                                        <td>
													 <?php if($proposal['enqDate']!='')
													 {
													  echo $proposal['enqDate']; }
													  else
													  {
														  echo '--';
													  }
													  
													  ?>
												  </td>
                                                        
                                                        <td>
													 <?php if($proposal['enquiryNumber']!='')
													 {
													  echo $proposal['enquiryNumber']; }
													  else
													  {
														  echo '--';
													  }
													  
													  ?>
												  </td>
                                                        <td>
															<?php echo $proposal['grandTotal'];
															
															 $finalTotal = $finalTotal+$proposal['grandTotal']; 
															 ?>
														</td>
                                                        <td>
															<?php echo $proposal['branch']; ?>
														</td>
                                                        <td>
															<?php echo ucfirst($proposal['firstName']); ?>
														</td>
                                                         <td>
															<?php echo ucfirst($proposal['company']); ?>
														</td>
                                                        
                                                         <td>
															<?php 
															
													 // collected
	$collections = mysql_query("select amount, paidDate from collections where invoiceId = '". $proposal['invoiceId'] ."'  order by id desc");
	
			
							?>
                            
                            
                            <div id="<?php echo 'modal-collection'.$proposal['reportId']; ?>" class="modal in" tabindex="-1" aria-hidden="false" style="display: none;"><div class="modal-backdrop  in"></div>
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
                                            
                                            
                                           <?php
										   
										   if(strlen($proposal['invoiceNumber'])>0) { echo $proposal['invoiceNumber'];  }
										   else { echo $proposal['proInvoiceNumber'];  }
										   
										   
										    ?>
												<button type="button" class="close" data-dismiss="modal">×</button>
												<h4 class="blue bigger"><?php  echo $proposal['invoice'];  ?></h4>
											</div>

											<div class="modal-body">
												<div class="row">
													<div class="col-xs-12 col-sm-12">
														
                            <table class="table table-striped table-bordered">
															<thead>
																<tr>
																	<th>S.no</th>
																	<th>Date</th>
																	<th>Collected</th>
                                                                   
																</tr>
															</thead>

															<tbody>
                                                            
                                                             <?php
	                                                    
														$collectedAmount = 0;
	if(mysql_num_rows($collections)>0)
	{
		$sno=1;  
	   while($collected = mysql_fetch_array($collections))	
	   {
		  $collectedAmount = $collected['amount']+$collectedAmount;   
	   
				  ?>
                  
                  <tr><td><?php  echo $sno; ?></td><td><?php $paidDate = explode('-',$collected['paidDate']);
				echo  $paidDate = $paidDate[2].'-'.$paidDate[1].'-'.$paidDate[0];
				   ?></td><td><?php echo $collected['amount']; ?></td></tr>
                  <?php
			$sno++;
		 }
	}
	else
	{
	   ?> <tr><td colspan="3">No data found.</td></tr> <?php 	
	}
  
	
    ?>			</tbody>
														</table>


													</div>
												</div>
											</div>

											<div class="modal-footer">
												<button class="btn btn-sm" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close
												</button>

												
											</div>
										</div>
									</div>
								</div>
                                            <!--Modal Box-->
                                            
                                           
                                            
                     <a href="<?php echo '#modal-collection'.$proposal['reportId']; ?>" role="button" class="blue" data-toggle="modal">
                     <span><?php echo $collectedAmount; ?></span>
                     </a>
                     
                     
                     <?php 
					 
					 $finalCollected = $finalCollected+$collectedAmount;
					 
					 ?>
                  
														</td>
                                                        <td>
                                                        <?php echo $diff = $proposal['grandTotal']-$collectedAmount; 
														
												 $finalPending = $finalPending+$diff;		
														 ?>
                                                        </td>
                                                        <td>
     
	
                                                              <!--Modal Box-->
                                            <div id="<?php echo 'modal-form'.$proposal['invoiceId']; ?>" class="modal in" tabindex="-1" aria-hidden="false" style="display: none;"><div class="modal-backdrop  in"></div>
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">×</button>
												<h4 class="blue bigger"><?php  echo $proposal['invoice'];  ?></h4>
											</div>

											<div class="modal-body">
												<div class="row">
													<div class="col-xs-12 col-sm-12">
														
<!--<form class="form-horizontal" method="post">

                              <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Status</label>

										<div class="col-sm-9">
                                        <input type="hidden" name="orderId" value="<?php // echo $proposal['id']; ?>" />
											<select id="status" name="status" class="col-xs-12 col-sm-10">
                                            <option value="Open">Open</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Dispatched">Dispatched</option>
                                            
                                            </select>
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="remarks"> Remarks </label>

										<div class="col-sm-9">
											<textarea id="remarks" placeholder="Remarks" name="remarks" class="col-xs-12 col-sm-10"></textarea>
										</div>
									</div>
                                    
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="submit">  </label>

										<div class="col-sm-9">
											<button id="submit" name="submitStatus" class="btn btn-info btn-sm">
                                              Submit
                                            </button>
										</div>
									</div>
                                    
                                    </form>-->
                                    
                                    
                                    <table class="table table-striped table-bordered">
															<thead>
																<tr>
																	<th class="center">#</th>
																	<th>Date</th>
																	<th>Status</th>
																	<th>Remarks</th>
                                                                   
																</tr>
															</thead>

															<tbody>
                                                            
                                                             <?php
	                                                    
           if(mysql_num_rows($dispatchedResult)>0)
	          {
		        $inc = 1;
				while($row = mysql_fetch_array($dispatchedResult))
				{
				  
				  
				  ?>
                  
                  <tr><td></td><td><?php  echo $row['id']; ?></td><td><?php echo $row['quantity']; ?></td><td><?php echo $row['dispatchedQuantity'];	  ?></td></tr>
                  <?php
			   }
															
	
	
	}
  
	
    ?>
    
    
                                                            
                                                            															
                                                                
                                                                
                                                                
																

																
															</tbody>
														</table>


													</div>
												</div>
											</div>

											<div class="modal-footer">
												<button class="btn btn-sm" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Cancel
												</button>

												<button class="btn btn-sm btn-primary">
													<i class="ace-icon fa fa-check"></i>
													Save
												</button>
											</div>
										</div>
									</div>
                                    
                                                             
                     <a href="<?php echo '#modal-form'.$proposal['invoiceId']; ?>" role="button" class="blue" data-toggle="modal"><?php // echo $link; ?>
                     <span class="label label-warning">Status</span>
                     </a>
								 
								</div>
                                            <!--Modal Box-->
                                            
                                           <?php echo $proposal['leadStatus'] ?>
                  						</td>
                                                        
                                                      
														<td>
                                                        
                                                        <p>
											<?php 
                                             if($proposal['inv']==1 && $proposal['runcard']!='')   { ?>

 <a href="generate_invoice.php?iid=<?php echo $proposal['invoiceId']; ?>" class="btn btn-pink btn-sm">Add Invoice</a>
  <?php } ?>
        
                                        
											<a class="btn btn-success btn-sm" title="view" href="view_order.php?iid=<?php echo $proposal['invoiceId']; ?>">
											<i class="fa fa-eye" aria-hidden="true"></i>
											</a>
                                            
                                            
                                            <button class="btn btn-danger btn-sm" title="delete" onclick="confirmDelete('<?php echo $proposal['reportId']; ?>')">
												<i class="ace-icon fa fa-trash icon-only"></i>
											</button>
                                          
										</p>
                                                
														</td>
													
													</tr>
<?php
// to download file in excel format we have written this code.
$rowlist[] = $i;
$rowlist[] = $proInvoiceDate;
$rowlist[] = $proposal['proInvoiceNumber'];
$rowlist[] = $invoiceDate;
$rowlist[] = $proposal['invoiceNumber'];
$rowlist[] = $proposal['grandTotal']; 
$rowlist[] = $proposal['firstName']; 
$rowlist[] = $proposal['company']; 
$rowlist[] = $collectedAmount;
$rowlist[] = $diff;
	 
	 
	 
 $list[] = $rowlist;
 unset($rowlist);
 
 $i++;
 
  } ?><tr><td colspan="5"></td><td><?php echo $finalTotal; ?></td><td colspan="3"></td><td><?php echo $finalCollected; ?></td><td><?php echo $finalPending; ?></td><td colspan="2"></td></tr> <?php } else {
  
  ?><tr><td colspan="13">No Data found.</td></tr>
  <?php }    if($numRecords>$limit &&  !(isset($_GET['ordersSearch']))) {

   
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
  echo $start;
   
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
  <?php } ?>                                                       </tbody>
											</table>
                                            
                                            
              <?php                              
$fp = fopen('excel/orders.csv', 'w');


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

		/*function getBranch(val)
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
		*/
		
		
 	
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
				   $("#customersList").css('display','block');
				   $("#customersList").html(data);
				//
				   }
				
				
				});
			
			
			});	

		
		$(document).ready(function(e){
				$(document).on('click','#outclick,#breadcrumbs',function(){
				$("#employeesList,#customersList").css('display','none'); 
			});
		});
		

 function confirmDelete(did)
 {
	if(confirm('Do you want to delete the order')) 
	{
	 window.location = 'orders.php?did='+did; 
	}
 }

   function goToPage(pid)
{
   window.location = 'orders.php?page='+pid;	
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
