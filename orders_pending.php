<?php include("includes/header.php"); 

 // delet report
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
$limit = 10;
// get the data of pending orders from daily_reports table
/*relation between daily reports and employees are daily_reports.addedBy = employees.id
relation between daily_reports and customers is daily_reports.company = customers.customerId.
 and daily_reports.leadStatus = 'Invoice generated' */
 
$numRecords = mysql_query("select daily_reports.reportId, daily_reports.reportDate, daily_reports.remarks, daily_reports.runcard, daily_reports.purchasePoDrop, date_format(daily_reports.delAcceptanceDate,'%d-%m-%Y') delaccDate, date_format(daily_reports.rcDate,'%d-%m-%Y') rcDate,date_format(daily_reports.purchasePoDate,'%d-%m-%Y') purpoDate, daily_reports.purchasePoDrop, daily_reports.purchasePoNumber, daily_reports.purchasePoType, invoices.proInvoiceNumber, date_format(invoices.proInvoiceDateTime,'%d-%m-%Y') proDate,  invoices.invoiceId, daily_reports.poNo, date_format(daily_reports.poDateTime,'%d-%m-%Y') podt, daily_reports.invoice, daily_reports.invoiceDateTime, customers.company, employees.firstName 
from daily_reports
left join invoices on daily_reports.reportId = invoices.reportId
left join customers on daily_reports.company = customers.customerId
left join employees on daily_reports.addedBy = employees.id
where daily_reports.inv = '2' and daily_reports.leadStatus = 'Invoice generated' group by daily_reports.reportId
order by daily_reports.reportId desc");
 
 $numRecords = mysql_num_rows($numRecords);  
 $totcollected=0;
	$totalpending=0;
 
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
 //   $whereItems[] = "daily_reports.leadStatus = 'Invoice generated'";
    $whereItems[] = "daily_reports.inv = '2'";
  if(isset($_GET['ordersSearch']))
									{
										
										/* from date to todate search based on "invoices.invoiceDateTime".
					employee search based on employees.id
					customer search based on customers.customerId.  
					branch search based on employees.branch
					*/	
		
										
									if(isset($_GET['fromDate']) && strlen($_GET['fromDate']>8) && isset($_GET['toDate']) && strlen($_GET['toDate']>8))
									{
										
									
										$fromDate = explode('-',$_GET['fromDate']);
										$fromDate = $fromDate[2].'-'.$fromDate[1].'-'.$fromDate[0];
										
										$toDate = explode('-',$_GET['toDate']);
										$toDate = $toDate[2].'-'.$toDate[1].'-'.$toDate[0];
									 
									 
									 $whereItems[] = "invoices.invoiceDateTime >=  '". $fromDate ."'";
									 
									 $whereItems[] = "invoices.invoiceDateTime <=  '". $toDate ."'";
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
		// get the data of pending orders search from daily_reports table										
	$proposals = mysql_query("select daily_reports.reportId,  invoices.invoiceId, daily_reports.currentRevisionId, daily_reports.remarks, daily_reports.purchasePoDrop, daily_reports.reportDate, daily_reports.leadStatus, daily_reports.poNo, date_format(daily_reports.delAcceptanceDate,'%d-%m-%Y') delaccDate, date_format(daily_reports.poDateTime,'%d-%m-%Y') podt, daily_reports.invoice, daily_reports.invoiceDateTime, daily_reports.runcard, date_format(daily_reports.rcDate,'%d-%m-%Y') rcDate,date_format(daily_reports.purchasePoDate,'%d-%m-%Y') purpoDate, daily_reports.purchasePoDrop, daily_reports.purchasePoNumber, daily_reports.purchasePoType, invoices.proInvoiceNumber, date_format(invoices.proInvoiceDateTime,'%d-%m-%Y') proDate, customers.company, employees.firstName 

from daily_reports
left join invoices on daily_reports.reportId = invoices.reportId
left join customers on daily_reports.company = customers.customerId
left join employees on daily_reports.addedBy = employees.id

left join daily_reports_revision as drv on drv.reportId=daily_reports.reportId
left join daily_reports_data as drd on drd.revisionId=drv.revisionId
left join categories as cat on cat.id=drd.categoryId


$where
group by daily_reports.reportId order by daily_reports.reportId desc");
}


else if(isset($_GET['pendingId']))
{
	

	$proposals = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.remarks, daily_reports.purchasePoDrop, invoices.invoiceId, daily_reports.reportDate, daily_reports.leadStatus, daily_reports.poNo, date_format(daily_reports.poDateTime,'%d-%m-%Y') podt, date_format(daily_reports.delAcceptanceDate,'%d-%m-%Y') delaccDate, daily_reports.invoice, daily_reports.invoiceDateTime, daily_reports.runcard, date_format(daily_reports.rcDate,'%d-%m-%Y') rcDate,date_format(daily_reports.purchasePoDate,'%d-%m-%Y') purpoDate, daily_reports.purchasePoDrop, daily_reports.purchasePoNumber, daily_reports.purchasePoType, invoices.proInvoiceNumber, date_format(invoices.proInvoiceDateTime,'%d-%m-%Y') proDate, customers.company, employees.firstName 

from daily_reports
left join invoices on daily_reports.reportId = invoices.reportId
left join customers on daily_reports.company = customers.customerId
left join employees on daily_reports.addedBy = employees.id

left join daily_reports_revision as drv on drv.reportId=daily_reports.reportId
left join daily_reports_data as drd on drd.revisionId=drv.revisionId
left join categories as cat on cat.id=drd.categoryId

where daily_reports.reportId = '".$_GET['pendingId']."' and daily_reports.inv = '2' and daily_reports.leadStatus = 'Invoice generated' group by daily_reports.reportId
order by daily_reports.reportId desc");
}
 
 else
 {
// get the data of pending orders from daily_reports table
$proposals = mysql_query("select daily_reports.reportId,employees.id as empid,daily_reports.leadStatus, daily_reports.remarks, daily_reports.purchasePoDrop, invoices.invoiceId, daily_reports.reportDate, daily_reports.runcard, date_format(daily_reports.rcDate,'%d-%m-%Y') rcDate, date_format(daily_reports.delAcceptanceDate,'%d-%m-%Y') delaccDate,date_format(daily_reports.purchasePoDate,'%d-%m-%Y') purpoDate, daily_reports.purchasePoDrop, daily_reports.purchasePoNumber, daily_reports.purchasePoType, invoices.proInvoiceNumber, date_format(invoices.proInvoiceDateTime,'%d-%m-%Y') proDate, daily_reports.poNo, date_format(daily_reports.poDateTime,'%d-%m-%Y') podt, daily_reports.invoice, daily_reports.invoiceDateTime,  customers.company, employees.firstName from daily_reports 
left join invoices on daily_reports.reportId = invoices.reportId
left join customers on daily_reports.company = customers.customerId 
left join employees on daily_reports.addedBy = employees.id 

left join daily_reports_revision as drv on drv.reportId=daily_reports.reportId
left join daily_reports_data as drd on drd.revisionId=drv.revisionId
left join categories as cat on cat.id=drd.categoryId

where daily_reports.inv = '2' and daily_reports.leadStatus = 'Invoice generated' group by daily_reports.reportId  order by daily_reports.reportId desc  limit $start, $limit");


 }
 

 
//echo mysql_num_rows($proposals); echo "<PRE>";  print_r( mysql_fetch_object($proposals) ); exit;
 
?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Orders Pending</li>
                            
						</ul><!-- /.breadcrumb -->

						<a href="excel/orders_raised.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>
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

<div class="col-sm-2" style="width:11%">
<span>From Date</span>
<input type="text" class="form-control date-picker input-sm" id="fromDate" name="fromDate" placeholder="From Date" <?php if(isset($_GET['fromDate'])) { ?> value="<?php echo $_GET['fromDate']; ?>" <?php } ?> style="width:100%" />
</div>
<div class="col-sm-2" style="width:11%">
<span>To Date</span>
<input type="text" class="form-control date-picker input-sm" id="toDate" name="toDate" placeholder="To Date" <?php if(isset($_GET['toDate'])) { ?> value="<?php echo $_GET['toDate']; ?>" <?php } ?>  style="width:100%"/>
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
<div class="clearfix">
<span>Employee</span>
<input type="hidden" id="eid" name="eid"   />
<input type="text" id="employee" name="employee" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Employee" onkeyup="getEmployee(this.value)" <?php if(isset($_GET['employee'])) { ?> value="<?php echo $_GET['employee']; ?>" <?php } ?>  style="width:100%" />
<ul class="typeahead dropdown-menu" style="left: 10px; display: none;" id="employeesList">
</ul>

</div></div> 



<div class="col-sm-2" style="width:11%">
<span>Customer</span>

<input type="hidden" id="cid" name="cid"   />
<input type="text" id="customer" name="customer" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Customer" <?php if(isset($_GET['customer'])) { ?> value="<?php echo $_GET['customer']; ?>" <?php } ?> onkeyup="getCustomer(this.value)" style="100%" />

<ul class="typeahead dropdown-menu" style="left: 10px; display: none;" id="customersList"></ul>

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
											Orders Pending
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
                                                         <th>RC No.</th>
                                                        <th>RC Date</th>
                                                        <th>Company Name</th>
                                                        <th>Payment</th>
                                                        <th>Customer P.O. No/Date</th>
                                                        <th>Product</th>
                                                        <th>Quantity</th>
                                                         <th>Purchase P.O No/Date/Ex-Stock</th>
                                                        <th>Value</th>
                                                      
                                                        <th>Vendors Dispatch Date</th>
                                                         <th>Pro No. / date</th>
                                                         <th>Remarks</th>
                                                        <th>Action</th>
														
													</tr>
												</thead>

												<tbody>
													
														
																	

  <?php 
  // to download file in excel format we have written this code.
   $list[] = array('S. No', 'Invoice Date', 'Invoice No.', 'Executive', 'Company', 'Amount', 'Collected', 'Pending','Status');
  if(mysql_num_rows($proposals)>0) {
  $i = $start+1; 
  
  $finalTotal = 0;
  $finalPending = 0;
  $finalCollected = 0;
  
  while($proposal = mysql_fetch_array($proposals))
  {
	
	
	$totalAmount = mysql_query("select revisionId, grandTotal from daily_reports_revision where daily_reports_revision.reportId = '". $proposal['reportId'] ."' and daily_reports_revision.revision = '". $proposal['currentRevisionId'] ."'");
  
 
  if(mysql_num_rows($totalAmount)>0)
  {
	  $total = mysql_fetch_array($totalAmount);
	  $grandTotal = $total['grandTotal']; 
	  $rev = $total['revisionId'];
  }
  else
  { $grandTotal = 0; }
  
  $finalTotal = $finalTotal+$grandTotal;  
  
	// get the data from product table. 
$items = mysql_query("select products.productId, products.product, daily_reports_data.id, daily_reports_data.quantity from daily_reports_data
 left join products on daily_reports_data.productId = products.productId
 where daily_reports_data.revisionId = '". $rev ."'");
if(mysql_num_rows($items)>0)
{
	$item = mysql_fetch_array($items);
	$prd_itm = $item['product'];
	$prd_qty = $item['quantity'];
}

// to get payment type
$ptypes = mysql_query("select paymentType from collections where collections.invoiceId = '".$proposal['invoiceId']."'");
 
 if(mysql_num_rows($ptypes)>0)
{
	$ptype = mysql_fetch_array($ptypes);
	$patype = $ptype['paymentType'];
}
 
  
  // dispatched	
/*$dispatchedResults = mysql_query("select daily_reports_data.id, dispatch_items.remarks, daily_reports_data.quantity, dispatch_items.dispatchedQuantity 
from daily_reports_data
left join dispatch_items on daily_reports_data.id = dispatch_items.itemId
where daily_reports_data.reportId = '". $proposal['reportId'] ."'
order by daily_reports_data.reportId desc");
if(mysql_num_rows($dispatchedResults)>0)
{
	$dispatchedResult = mysql_fetch_array($dispatchedResults);
	$remarks = $dispatchedResult['remarks'];
}
*/

	 ?><tr>
														<td class="center">
														<?php  echo $i; ?>
														</td>
                                                       
                                                        	
                                                        	<td>
															<?php
													   
												echo $proposal['runcard'];
														 
															?>
														</td>
                                                        	<td>
															<?php
															
														
															 echo $proposal['rcDate']; 
															?>
                                
														</td>
                                                        <td>
															<?php echo ucfirst($proposal['company']);  ?>
														</td>
                                                        
                                                        <td>
															<?php 
															if($patype == 1) echo 'Proforma';
															else if($patype == 2) echo 'Through Bank';
															else if($patype == 3) echo 'Direct Payment';
															else if($patype == 4) echo 'Againast Delivery';
															else if($patype == 5) echo 'By Check/DD';
															else if($patype == 6) echo 'Bank Transfer';
															else if($patype == 7) echo 'By Cash';
															
															?>
														</td>
                                                        <td>
															<?php echo $proposal['poNo'].' / '.$proposal['podt'];  ?>
														</td>
                                                         
                                                         <td>
															<?php echo $prd_itm;  ?>
														</td>
                                                         <td>
															<?php echo $prd_qty;  ?>
														</td>
                                                         <td>
															<?php 
															if($proposal['purchasePoDrop'] == 2)
															{
															echo $proposal['purchasePoNumber'].' / '.$proposal['purpoDate'];  
                                                            }
															else
															echo 'EX-Stock';
															?>
														</td>
                                                         <td>
															<?php echo $grandTotal;  ?>
														</td>
                                                        <td>
															<?php echo $proposal['delaccDate'];  ?>
														</td>
                                                        
                                                        <td>
															<?php echo $proposal['proInvoiceNumber'].' / '.$proposal['proDate'];  ?>
														</td> 
                                                        <td>
															<?php echo $proposal['remarks'];  ?>
														</td>
                                                        
														<td>
                                                        
                                                        <p>
											


  <?PHP
  $qrey = mysql_query("select invoiceId from invoices where invoiceNumber='".$proposal['invoice']."'");
//  echo "select invoiceId from invoices where invoiceNumber=".$proposal['invoice']; exit; 
  if(mysql_num_rows($qrey)==1)
  {
	  $data = mysql_fetch_object($qrey);
	  $invoiceIde = $data->invoiceId;
  }
  
  ?>
        
                                        
											<a class="btn btn-success btn-sm" title="view" href="view_order.php?iid=<?php echo $invoiceIde; //$proposal['reportId']; ?>">
												<i class="icon-only ace-icon fa fa-align-justify"></i>
											</a>
                                            
                                            
                                            
                                          
										</p>
                                                
														</td>

													
													</tr>
<?php
// to download file in excel format we have written this code.
 $rowlist[] = $i;
$rowlist[] = $invoiceDate; 
$rowlist[] = $proposal['invoice']; 
$rowlist[] = $proposal['firstName']; 
$rowlist[] = $proposal['company']; 
$rowlist[] = $grandTotal; 
$rowlist[] = $collectedAmount;
$rowlist[] = $diff;
$rowlist[] = $proposal['leadStatus'];
	 
	 
	 
 $list[] = $rowlist;
 unset($rowlist);
 
 $i++;
  } } else {
  
  ?><tr><td colspan="14">No Data found.</td></tr>
  <?php } if($numRecords>$limit) {
   
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
$fp = fopen('excel/orders_raised.csv', 'w');


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
