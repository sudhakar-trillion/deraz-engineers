<?php include("includes/header.php"); 
date_default_timezone_set("Asia/Calcutta");
error_reporting(0);
// delete report
 if(isset($_GET['did']))
 {
	mysql_query("delete from orders where id = '". $_GET['did'] ."'");
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
$where = '';
$limit = 10;


/* to get the enquiries data we will execute this query with employees table.
relation between daily_reports and enquiries is enquiryNumber.
relation between collections and daily_reports are collections.invoiceId = daily_reports.reportId
relation between daily_reports and employees are daily_reports.addedBy = employees.id
relation between branches and employees are employees.branch = branches.branchId
relation between dispatches and invoices are dispatch.invoiceId = invoices.invoiceId
relation between dispatch_items and dispatch are dispatch.dispatchedId = dispatch_items.dispatchId
relation between daily_reports_data and products are daily_reports_data.productId = products.productId
*/
 
$numRecords = mysql_query("select invoices.invoiceNumber, invoices.invoiceDateTime, daily_reports.poNo, date_format(daily_reports.poDateTime,'%d-%m-%Y') podtm, daily_reports.runcard, invoices.proInvoiceNumber, date_format(invoices.proInvoiceDateTime,'%d-%m-%Y') prodate, date_format(daily_reports.rcDate,'%d-%m-%Y') rcDate, daily_reports.enquiryNumber, branches.branch, employees.firstName, daily_reports.runcard, daily_reports_data.quantity, dispatch.dispatchedId, dispatch.invoiceId, date_format(dispatch.dispatchedOn,'%d-%m-%Y') disOn, dispatch_items.remarks, dispatch_items.dispatchedQuantity, products.product, products.code, customers.company from dispatch

left join invoices on dispatch.invoiceId = invoices.invoiceId
left join daily_reports on invoices.reportId = daily_reports.reportId
left join customers on daily_reports.company = customers.customerId
left join dispatch_items on dispatch.dispatchedId = dispatch_items.dispatchId
left join daily_reports_data on dispatch_items.itemId = daily_reports_data.id
left join products on daily_reports_data.productId = products.productId
left join employees on daily_reports.addedBy = employees.id
left join branches on employees.branch = branches.branchId
$where
order by dispatch.dispatchedId desc");
 
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


// branchwise, employeewise, fromdate and todate and customerwise search options 
  if(isset($_GET['dispatchSearch']))
									{
										
						
						/* from date to todate search based on "dispatch.dispatchedOn".
					employee search based on employees.id
					customer search based on enquiries.companyId.  
					branch search based on employees.branch
					invoices search based on invoices.invoiceId
					*/					
										  				
										
										$where = 'where ';
										
									//if(isset($_GET['fromDate']) && strlen($_GET['fromDate']>8) && isset($_GET['toDate']) && strlen($_GET['toDate']>8))
						if( (isset($_GET['fromDate']) && isset($_GET['toDate']) ) && ( $_GET['toDate']!='' && $_GET['fromDate']!='' ))
									{
										
									
										$fromDate = explode('-',$_GET['fromDate']);
										$fromDate = $fromDate[2].'-'.$fromDate[1].'-'.$fromDate[0];
										
										$toDate = explode('-',$_GET['toDate']);
										$toDate = $toDate[2].'-'.$toDate[1].'-'.$toDate[0];
									 
									 
									 $whereItems[] = "dispatch.dispatchedOn >=  '". $fromDate ."'";
									 
									 $whereItems[] = "dispatch.dispatchedOn <= '". $toDate ."'";
								
								
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
									   
										if( isset($_GET['catid']) && $_GET['catid']>0) $whereItems[] = "cat.id=  '". $_GET['catid'] ."'";
										
										if( isset($_GET['prdid']) && $_GET['prdid']>0) $whereItems[] = "drd.productId=  '". $_GET['prdid'] ."'";		
									
									   // by invoice
									   if(isset($_GET['invid']) && $_GET['invid']>0)
									   {  
									    
										    $whereItems[] = "invoices.invoiceId='". $_GET['invid'] ."'";
									   }
									   
									     if(isset($_GET['invoice']) && $_GET['invoice']!='')
									   {  
									    
										    $whereItems[] = "invoices.invoiceNumber='". $_GET['invoice'] ."'";
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
												
#echo $where; exit; 											
							 $query = "select invoices.invoiceNumber,dispatch_items.itemId,invoices.invoiceId, daily_reports.poNo, date_format(daily_reports.poDateTime,'%d-%m-%Y') podtm, daily_reports.runcard, invoices.proInvoiceNumber, date_format(invoices.proInvoiceDateTime,'%d-%m-%Y') prodate, date_format(daily_reports.rcDate,'%d-%m-%Y') rcDate, employees.firstName, branches.branch, invoices.invoiceDateTime, daily_reports.runcard, daily_reports_data.quantity, dispatch.dispatchedId, daily_reports.enquiryNumber, dispatch.invoiceId, date_format(dispatch.dispatchedOn,'%d-%m-%Y') disOn, dispatch_items.remarks, dispatch_items.dispatchedQuantity, products.product, products.code, customers.company from dispatch

left join invoices on dispatch.invoiceId = invoices.invoiceId
left join daily_reports on invoices.reportId = daily_reports.reportId
left join customers on daily_reports.company = customers.customerId
left join dispatch_items on dispatch.dispatchedId = dispatch_items.dispatchId
left join daily_reports_data on dispatch_items.itemId = daily_reports_data.id
left join products on daily_reports_data.productId = products.productId
left join employees on daily_reports.addedBy = employees.id
left join branches on employees.branch = branches.branchId

left join daily_reports_revision as drv on drv.reportId=daily_reports.reportId
left join daily_reports_data as drd on drd.revisionId=drv.revisionId
left join categories as cat on cat.id=drd.categoryId


$where
group by invoices.invoiceNumber order by dispatch.dispatchedId desc limit $start, $limit";			

												
}
else if(isset($_GET['expDate']))
{
	// fetching the 7 days before dispatch data with this query. This GET element code written in header.php file   
	
	
	$where = '';	
$query = "select invoices.invoiceNumber, invoices.total, dispatch_items.itemId,invoices.invoiceId, invoices.invoiceDateTime, date_format(daily_reports.poDateTime,'%d-%m-%Y') podtm, daily_reports.runcard, daily_reports.poNo, employees.firstName, branches.branch, customers.customerId, invoices.proInvoiceNumber, date_format(invoices.proInvoiceDateTime,'%d-%m-%Y') prodate, date_format(daily_reports.rcDate,'%d-%m-%Y') rcDate, daily_reports.runcard, daily_reports_data.quantity, dispatch.dispatchedId, daily_reports.enquiryNumber, dispatch.invoiceId,  daily_reports.poNo, date_format(dispatch.dispatchedOn,'%d-%m-%Y') disOn, dispatch_items.remarks, dispatch_items.dispatchedQuantity, products.product, products.code, customers.company from dispatch

left join invoices on dispatch.invoiceId = invoices.invoiceId
left join daily_reports on invoices.reportId = daily_reports.reportId
left join customers on daily_reports.company = customers.customerId
left join dispatch_items on dispatch.dispatchedId = dispatch_items.dispatchId
left join daily_reports_data on dispatch_items.itemId = daily_reports_data.id
left join products on daily_reports_data.productId = products.productId
left join employees on daily_reports.addedBy = employees.id
left join branches on employees.branch = branches.branchId

left join daily_reports_revision as drv on drv.reportId=daily_reports.reportId
left join daily_reports_data as drd on drd.revisionId=drv.revisionId
left join categories as cat on cat.id=drd.categoryId


where dispatch.dispatchedOn = '".$_GET['expDate']."' group by invoices.invoiceNumber 
order by dispatch.dispatchedId desc limit $start, $limit";
	
}

else if(isset($_GET['dispatchedId']))
{
		// fetching the 7 days before dispatch data with this query. But the data comes when we click on particular dispatchedId .. only that data comes and this GET element code written in header.php file 
	$where = '';	
	$query = "select invoices.invoiceNumber, invoices.total, dispatch_items.itemId, daily_reports.poNo, invoices.invoiceId, date_format(daily_reports.poDateTime,'%d-%m-%Y') podtm, daily_reports.runcard, invoices.invoiceDateTime, customers.customerId, branches.branch, invoices.proInvoiceNumber, date_format(invoices.proInvoiceDateTime,'%d-%m-%Y') prodate, date_format(daily_reports.rcDate,'%d-%m-%Y') rcDate, employees.firstName, daily_reports.runcard, daily_reports_data.quantity, dispatch.dispatchedId, daily_reports.enquiryNumber, dispatch.invoiceId, date_format(dispatch.dispatchedOn,'%d-%m-%Y') disOn, dispatch_items.remarks, dispatch_items.dispatchedQuantity, products.product, products.code, customers.company from dispatch

left join invoices on dispatch.invoiceId = invoices.invoiceId
left join daily_reports on invoices.reportId = daily_reports.reportId
left join customers on daily_reports.company = customers.customerId
left join dispatch_items on dispatch.dispatchedId = dispatch_items.dispatchId
left join daily_reports_data on dispatch_items.itemId = daily_reports_data.id
left join products on daily_reports_data.productId = products.productId
left join employees on daily_reports.addedBy = employees.id
left join branches on employees.branch = branches.branchId

left join daily_reports_revision as drv on drv.reportId=daily_reports.reportId
left join daily_reports_data as drd on drd.revisionId=drv.revisionId
left join categories as cat on cat.id=drd.categoryId


where dispatch.dispatchedId = '".$_GET['dispatchedId']."' group by invoices.invoiceNumber
order by dispatch.dispatchedId desc limit $start, $limit";
}

else								
{
$where = '';	
$query = "select invoices.invoiceNumber, invoices.total, dispatch_items.itemId,invoices.invoiceId, daily_reports.poNo, date_format(daily_reports.poDateTime,'%d-%m-%Y') podtm, daily_reports.runcard,invoices.invoiceDateTime, daily_reports.runcard, branches.branch, invoices.proInvoiceNumber, date_format(invoices.proInvoiceDateTime,'%d-%m-%Y') prodate,date_format(daily_reports.rcDate,'%d-%m-%Y') rcDate, employees.firstName, daily_reports_data.quantity, dispatch.dispatchedId, daily_reports.enquiryNumber, dispatch.invoiceId, date_format(dispatch.dispatchedOn,'%d-%m-%Y') disOn, dispatch_items.remarks, dispatch_items.dispatchedQuantity, products.product, products.code, customers.company from dispatch
  
left join invoices on dispatch.invoiceId = invoices.invoiceId
left join daily_reports on invoices.reportId = daily_reports.reportId
left join customers on daily_reports.company = customers.customerId
left join dispatch_items on dispatch.dispatchedId = dispatch_items.dispatchId
left join daily_reports_data on dispatch_items.itemId = daily_reports_data.id
left join products on daily_reports_data.productId = products.productId
left join employees on daily_reports.addedBy = employees.id
left join branches on employees.branch = branches.branchId

left join daily_reports_revision as drv on drv.reportId=daily_reports.reportId
left join daily_reports_data as drd on drd.revisionId=drv.revisionId
left join categories as cat on cat.id=drd.categoryId


$where
group by invoices.invoiceNumber order by dispatch.dispatchedId desc limit $start, $limit";
}


$result = mysql_query($query);
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
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Dispatched </li>
                            
                            
						</ul><!-- /.breadcrumb -->
                        
               <a href="excel/dispatched.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>

						
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
    <input type="text" class="form-control date-picker input-sm" id="fromDate" name="fromDate" placeholder="From Date" <?php if(isset($_GET['fromDate'])) { ?> value="<?php echo $_GET['fromDate']; ?>" <?php } ?>  style="width:100%"/>
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
                 
                 
                 
                 
                 
                                            
                               
<div class="col-sm-2" style="width:11%">
                               
                                <span>Employee</span>
       <input type="hidden" id="eid" name="eid" value="<?PHP echo $_GET['eid']; ?>" />
     <input type="text" id="employee" name="employee" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Employee" <?php if(isset($_GET['employee'])) { ?> value="<?php echo $_GET['employee']; ?>" <?php } ?> onkeyup="getEmployee(this.value)" style="100%"  />
           <ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="employeesList">
                                           </ul>
                                           
                                </div> 
                                
                                
                     
                               
<div class="col-sm-2" style="width:11%">
                                <span>Customer</span>
                                <input type="hidden" id="cid" name="cid" value="<?PHP echo $_GET['cid']; ?>" />

     <input type="text" id="customer" name="customer" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Customer" <?php if(isset($_GET['customer'])) { ?> value="<?php echo $_GET['customer']; ?>" <?php } ?> style="100%"  />
     
      <ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="customersList"></ul>
                               </div>           
                                               
                               
<div class="col-sm-2" style="width:11%">
                                <span>Invoice</span>
 <input type="hidden" id="invid" name="invid"  <?php if(isset($_GET['invid'])) { ?> value="<?php echo $_GET['invid']; ?>" <?php } ?>  />
 <input type="text" id="invoice" name="invoice" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Invoice" onkeyup="getInvoice(this.value)"  <?php if(isset($_GET['invoice'])) { ?> value="<?php echo $_GET['invoice']; ?>" <?php } ?>  style="100%" />
           <ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="invoicesList">
                                           </ul>
                                           
                                </div> 
                                       
  <div class="form-group col-sm-2">
   <span><br></span>
    <input type="submit" class="btn btn-sm btn-success" name="dispatchSearch">
  </div>
  
  </div>
                                
                              
                                 
                               
  
</form>

</div>
									<div class="col-xs-12">
										
										<div class="table-header">
											Dispatched
										</div>


    <?php
										   
	   if(isset($_GET['delete']))
{ $alertMsg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Order has been deleted!</div>'; }
else if(isset($_GET['update']))
{ $alertMsg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Status has been updated!</div>'; }
else if(isset($_GET['error']))
{ $alertMsg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  if(isset($alertMsg)) { echo $alertMsg; }
  
  
  
//get the enquirynumber and date for this invoice
/*if($row['enquiryNumber']!='')
{
$enq_qry = mysql_query("select DATE_FORMAT(dateTime,'%Y-%m-%d') as enddtd from enquiries where enquiryNumber=".$row['enquiryNumber']);
//echo "select DATE_FORMAT(dateTime,'%Y-%m-%d') as enddtd from enquiries where enquiryNumber=".$row['enquiryNumber']; exit; 
if(mysql_num_rows($enq_qry)>0)
{
	while($enqdata = mysql_fetch_object($enq_qry))
	{
		$eqn_date = $enqdata->enddtd;	
	}
}
else
	$eqn_date = '';
}
else
{
	$eqn_date = '---';
	$enq_number='---';
}
*/?> 
										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">
											<table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                            
												<thead>
													<tr>
														<th class="center">
															S.no
														</th>
														<!--<th>schedule Dispatch Date</th>-->
														<th>Executive</th>
                                                         <th>Company Name</th>
                                                        <th>Customer PO No.</th>
                                                        <th>Customer PO Date</th>
                                                        <th>Value</th>
                                                        <th>RC No.</th>
                                                        <th>RC Date</th>
                                                        <th>Customer PRO No.</th>
                                                        <th>Customer PRO Date</th>
                                                      
                                                        <th>Delivery Date</th>
                                                      <!--  <th>Remarks</th>-->
                                                        <th>Action</th>
                                                     
														
													</tr>
												</thead>

												<tbody>

  <?php   
  // to download the data in excel format we write this code
  $list[] = array('S. No', 'Delivery Date', 'Customer PO No.', 'Customer PO Date', 'Invoice Date', 'Invoice', 'Company Name', 'Product', 'Model No');
  if(mysql_num_rows($result)>0) {
   $i = $start+1;
		
   
   
  while($row = mysql_fetch_array($result))
  {
	  
///	$currentStatus = mysql_query("select `status`, `remarks`, `dateTime` from order_status where orderId = '". $proposal['id'] ."' order by statusId desc");
	
	
	  
	 ?><tr>
														<td class="center">
															<?php echo $i; ?>
														</td>
<td>
                                                         <?php
														echo $row['firstName'];
 													  ?>
                                                      </td>
                                                       <td>
															<?php
															
														
															 echo $row['company']; 
															?>
                                
														</td>
                                                         <td>
                                                     <?PHP echo $row['poNo'];  ?> 
                                                      
                                                      </td>
                                                      <td><?PHP
                                                     echo $row['podtm'];
												?>
                                                      </td>
                                                   
                                                    
                                                    
                                                       <td>
                                                     <?PHP echo $row['total'];  ?> 
                                                      
                                                      </td>  
                                                      
                                                     
                                                      
														
                                                      
                                                      <td>
															<?php
															
														
															 echo $row['runcard']; 
															?>
                                
														</td>
                                                        
                                                        <td>
													 <?php 
													  echo $row['rcDate']; 
													 
													  ?>
												  </td>
                                                        
                                                        	<td>
															<?php
															 echo $row['proInvoiceNumber']; 
															?>
                                
														</td>
                                                        	<td>
															<?php
															
														
															  echo $row['prodate']; 
															?>
                                
														</td>
                                                        <td>
															<?php echo $row['disOn']; ?>
														</td>
                                                         
                                                        
                                                        
                                                        <td>
                                                        <a href="dispatched-view.php?invoice=<?php echo $row['invoiceNumber']; ?>" class="btn btn-warning btn-sm"><i class="ace-icon fa fa-eye icon-only"></i></a>
                                                        &nbsp;&nbsp;
                                                         <a href="add_dispatch.php?invoicenumber=<?php echo $row['proInvoiceNumber']; ?>&invoiceid=<?PHP echo $row['invoiceId']; ?>" class="btn btn-success btn-sm"><i class="ace-icon fa fa-plus icon-only"></i></a>
                                                        </td>
<!--                                                        <td>
<a class="btn btn-success btn-sm" title="view" href="view_dispatch.php?diid=<?php echo $row['dispatchedId']; ?>">
												<i class="icon-only ace-icon fa fa-align-justify"></i>
											</a>
-->
                                                        </td>
                                                        
                                                        
                                                       
                                                        
                                                      
														

													
													</tr>
<?php

// to download the data in excel format we write this code

$rowlist[] = $i;
$rowlist[] = $dispatchedOn;
$rowlist[] = $invoiceDate;
$rowlist[] = $row['invoiceNumber']; 
$rowlist[] = $row['company'];
$rowlist[] = $row['product']; 
$rowlist[] = $row['code']; 
$rowlist[] = $row['quantity'];
$rowlist[] = $row['dispatchedQuantity'];
$list[] = $rowlist;
unset($rowlist);


 $i++;
  } } else {
  
  ?>        
  
  <tr><td colspan="14">No Data found.</td></tr>
  <?php } 
  
  if($numRecords>$limit &&  !(isset($_GET['dispatchSearch'])))
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
  <tr><td colspan="14">
  
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
  <td id="next_grid-pager" class="ui-pg-button ui-corner-all <?php echo $thirdlink; ?>" style="cursor: default;" <?PHP if($currentPage < $numPages) { ?> onclick="goToPage('<?php echo $currentPage+1; ?>')" <?PHP } ?>>
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
$fp = fopen('excel/dispatched.csv', 'w');

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

  function goToPage(pid)
{
   window.location = 'dispatched.php?page='+pid;	
}
 
 		
		function getInvoice(val)
		{
			
			document.getElementById("invoicesList").style.display = 'block';
				$.ajax({url: "ajax/getInvoicesList.php?val="+val, success: function(result){
		$("#invoicesList").html(result);
    }});	
			
		}
		
		
		function selectInvoice(invid,invoice)
		{
			document.getElementById("invoicesList").style.display = 'none';
			document.getElementById("invid").value = invid;
			document.getElementById("invoice").value = invoice;
	
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

		
		
		$(document).ready(function(e){
				$(document).on('click','#outclick,#breadcrumbs',function(){
				$("#employeesList,#customersList,#invoicesList").css('display','none'); 
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
