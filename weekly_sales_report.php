<?php include("includes/header.php"); 
error_reporting(0);
$employeeDetails = mysql_query("select firstName, designation from employees where id = '". $_GET['eid'] ."'");
$employee = mysql_fetch_array($employeeDetails);
	$weekstart='';
	$weekend='';
	$nextweekstart='';
	$nextweekend='';

 
   // search options 
   $where = 'where ';
   $whereCollection = 'where ';
   $whereExpectedCollection = 'where ';
   $whereItems[] = "employees.id =  '". $_GET['eid'] ."'";

   $whereCollectionItems[] = "employees.id =  '". $_GET['eid'] ."'";
   $whereExpectedCollectionItems[] = "employees.id =  '". $_GET['eid'] ."'";
   
   if(isset($_GET['ordersSearch']))
									{
							 /* from date to todate search based on "daily_reports.dateTime".
								employee search based on employees.id
								customer search based on customers.customerId.  
								branch search based on employees.branch
								*/	
										
									if(isset($_GET['fromDate']) && isset($_GET['toDate']))
									{
										
									
										$fromDate = explode('-',$_GET['fromDate']);
										$fromDate = $fromDate[2].'-'.$fromDate[1].'-'.$fromDate[0];
										
										$toDate = explode('-',$_GET['toDate']);
										$toDate = $toDate[2].'-'.$toDate[1].'-'.$toDate[0];
									 
									 
									 $whereItems[] = "daily_reports.reportDate >=  '". $fromDate ."'";
									 $whereItems[] = "daily_reports.reportDate <=  '". $toDate ."'";
									 
									 
									 $whereCollectionItems[] = "collections.paidDate >=  '". $fromDate ."'";
									 $whereCollectionItems[] = "collections.paidDate <=  '". $toDate ."'";
									 
									 $whereExpectedCollectionItems[] = "expected_collections.expectedDate >  '". $toDate ."'";
									 
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
															
}
 

                                             if(count($whereExpectedCollectionItems)>1)
												{ 
												$whereCondition = implode(' and ',$whereExpectedCollectionItems);
												$whereExpectedCollection = $whereExpectedCollection.$whereCondition;
												}
												else if(count($whereExpectedCollectionItems)==1)
												{   
													$whereCondition = $whereExpectedCollectionItems[0];
													$whereExpectedCollection = $whereExpectedCollection.$whereCondition;
												}
												else
												{  
												  $whereExpectedCollection = '';	
												} 


                                             if(count($whereCollectionItems)>1)
												{ 
												$whereCondition = implode(' and ',$whereCollectionItems);
												$whereCollection = $whereCollection.$whereCondition;
												}
												else if(count($whereCollectionItems)==1)
												{   
													$whereCondition = $whereCollectionItems[0];
													$whereCollection = $whereCollection.$whereCondition;
												}
												else
												{  
												  $whereCollection = '';	
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
 

/*$proposals = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.reportDate,  daily_reports.poNo, daily_reports.poDateTime, daily_reports.inv, customers.company, employees.firstName, branches.branch, invoices.invoiceId, invoices.proInvoiceNumber, invoices.proInvoiceDateTime, invoices.invoiceNumber, invoices.invoiceDateTime,  invoices.grandTotal, invoices.invoiceStatus, products.code, daily_reports_data.quantity, daily_reports_data.amount 
from invoices
left join daily_reports on invoices.reportId = daily_reports.reportId
left join customers on daily_reports.company = customers.customerId
left join employees on daily_reports.addedBy = employees.id
left join branches on employees.branch = branches.branchId
left join daily_reports_data on invoices.invoiceId = daily_reports_data.invoice_id
left join products on daily_reports_data.productId = products.productId
$where order by daily_reports.reportId desc");
*/

// orders lost
/*
relation between enquiries and enquiry_assign is enquiryId.
relation between daily_reports_revision and daily_reports is reportId.
relation between daily_reports and customers are daily_reports.company = customers.customerId.
relation between daily_reports and employees are daily_reports.addedBy = employees.id
relation between branches and employees are employees.branch = branches.branchId
relation between invoices and daily_reports are daily_reports.reportId=invoices.reportId
*/

$whereRecieved = $where." and daily_reports.addedBy=".$_GET['eid']." AND daily_reports.leadStatus = 'Invoice generated'";
//$lostProposals = mysql_query("select reportId from daily_reports left join employees on daily_reports.addedBy = employees.id $where");
$proposals = mysql_query("select *,invoices.invoiceNumber from daily_reports 
							left join employees on daily_reports.addedBy = employees.id 
							left join customers on daily_reports.company = customers.customerId
							left join invoices on invoices.reportId=daily_reports.reportId
							left join daily_reports_revision on daily_reports.reportId = daily_reports_revision.reportId
							left join daily_reports_data on daily_reports_data.revisionId = daily_reports_data.revisionId
							left join products on daily_reports_data.productId = products.productId

$whereRecieved group by daily_reports.reportId order by daily_reports.reportId desc");





 if(isset($_GET['ordersSearch']))
 {
	 if(isset($_GET['fromDate']) && isset($_GET['toDate']))
	 {
			if( $_GET['fromDate']!='' && $_GET['toDate']!='')
			{
				$frmdate = date_create(trim($_GET['fromDate']));
				$frmdate = date_format($frmdate,'Y-m-d');
				
				$todate = date_create(trim($_GET['toDate']));
				$todate = date_format($todate,'Y-m-d');
				
				
				
				$other_filters = "and ( dr.reportDate>='".$frmdate."' and dr.reportDate<='".$todate."')";	
			}
	 }
 }
 else
 {
	 $other_filters='';
 }
// orders lost
$whereLost = " where dr.addedBy='". $_GET['eid'] ."' AND dr.leadStatus =  'Order Lost' $other_filters";
//$lostProposals = mysql_query("select reportId from daily_reports left join employees on daily_reports.addedBy = employees.id $where");
$lostProposals =  mysql_query("select date_format(dr.offerDate,'%d-%m-%Y') as offerDate, dr.reportId,cus.company from daily_reports as dr left join customers as cus on cus.customerId = dr.company $whereLost  order by dr.reportId desc");

$whereExpected = " where dr.addedBy='". $_GET['eid'] ."' AND dr.leadStatus = 'Offer generated' $other_filters ";
//$lostProposals = mysql_query("select reportId from daily_reports left join employees on daily_reports.addedBy = employees.id $where");
$collection_sales  = mysql_query("select date_format(dr.offerDate,'%d-%m-%Y') as offerDate, dr.reportId,cus.company from daily_reports as dr left join customers as cus on cus.customerId = dr.company $whereExpected  order by dr.reportId desc");





/*

$collection_sales = mysql_query("select * from expected_sales left join customers on expected_sales.customerId = customers.customerId  where expected_sales.employeeId = '". $_GET['eid'] ."' order by expected_sales.expectedSaleId desc");	

*/ 
 
?>
			<div class="main-content">
            
            
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Orders
                            
                            <?php  // echo "";	?>
                            </li>
                            
						</ul><!-- /.breadcrumb -->

						<a href="excel/orders.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>
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
                
                
                
                <div class="col-sm-2"><div class="clearfix">
                <span>Employee</span>
                <input type="hidden" id="eid" name="eid" <?php if(isset($_GET['eid'])) {  ?> value="<?php echo $_GET['eid'];  ?>" <?php } ?>   />
                <input type="text" id="employee" name="employee" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Employee" onkeyup="getEmployee(this.value)" <?php if(isset($_GET['employee'])) { ?> value="<?php echo $_GET['employee']; ?>" <?php } ?>  />
                <ul class="typeahead dropdown-menu" style="left: 10px; display: none;" id="employeesList">
                </ul>
                
                </div></div> 
                
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
                                             WEEKLY SALES REPORT
										</div>
                                        
                                        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                            
												<tbody>
													<tr>
														<td width="150">Name</td>
                                                        <td><?php echo $employee['firstName']; ?></td>
                                                        <td width="150">FROM</td>
                                                        <td></td>
														
													</tr>
                                                    <tr>
														<td width="150">Designation</td>
                                                        <td><?php echo $employee['designation']; ?></td>
                                                        <td width="150">TO</td>
                                                        <td></td>
														
													</tr>
												</tbody>

</table>

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
                                        
                                        <h6>ORDERS RECEIVED</h6>
											<table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                            
												<thead>
													<tr>
														<th class="center">
														S.no
														</th>
													    <th>Client Name</th>
                                                        <th>Invoice </th>
                                                        <th>Product</th>
                                                        <th>Quantity</th>
                                                        <th>Value</th>
                                                        
														
													</tr>
												</thead>

												<tbody>
													
														
																	

  <?php 
   $list[] = array('S. No', 'Proforma Date', 'Proforma Invoice', 'Invoice Date', 'Invoice', 'Invoice Amount', 'Executive', 'Company', 'Collected', 'Pending');
  if(mysql_num_rows($proposals)>0) {
  $i = $start+1; 
  
  $finalTotal = 0;
  $finalPending = 0;
  $finalCollected = 0;
  $totalValue = 0;
  while($proposal = mysql_fetch_array($proposals))
  {

 ?><tr>
														<td class="center">
														<?php  echo $i; ?>
														</td>
                                                       
                                                        	<td>
															<?php
															
														
															 echo $proposal['company']; 
															?>
                                
														</td>
                                                        <td>
                                                        	<?PHP echo $proposal['invoiceNumber']; ?>
                                                        </td>
                                                        <td>
															<?php
															$getproduct = mysql_query("select prd.product as product,drd.quantity as quantity, drd.price as price from products as prd left join daily_reports_data as drd on drd.productId = prd.productId left join daily_reports_revision as drv on drv.revisionId=drd.revisionId left join daily_reports as dr on dr.reportId=drv.reportId where  dr.reportId=".$proposal['reportId']);
															
														#	echo "select prd.product,drd.quantity from products as prd left join daily_reports_data as drd on drd.productId = prd.productId left join daily_reports_revision as drv on drv.revisionId=revisionId left join daily_reports as dr on dr.reportId=drv.reportId where  dr.reportId=".$proposal['reportId']; 
															$pr="<ol>";
															$pr_quant = "<ol>";
															$pr_price="<ol>";
															if( mysql_num_rows($getproduct)>0)
															{
																while($prdct = mysql_fetch_object($getproduct))	
																{
																	$pr.="<li>".$prdct->product."</li>";
																	$pr_quant.="<li>".$prdct->quantity."</li>";			
																	$pr_price.="<li>".$prdct->price."</li>";	
																	$totalValue	= $totalValue+$prdct->price;
																	
																}
																$pr.="</ol>";
																$pr_quant.= "</ol>";
																$pr_price.="</ol>";
																
																
															
															}
															else
															{
																$pr = "No products";
																$pr_quant='0';
																$pr_price='0';
															}	
															echo $pr;
															?>
                                
														</td>
                                                         <td>
															<?php
																	
																	echo $pr_quant;
														 
															?>
                                
														</td>
                                                         <td>
															<?php
															
														
															 echo $pr_price; 
															// $totalValue += $proposal['amount'];
															?>
                                
														</td>
                                                        </tr>
<?php
$rowlist[] = $i;
$rowlist[] = $proInvoiceDate;
$rowlist[] = $poDate;
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
 
  } ?><tr><td colspan="4"></td><td></td><td><?php echo $totalValue; ?></td></tr> <?php } else {
  
  ?><tr><td colspan="5">No Data found.</td></tr>
  <?php }  ?>                                                       </tbody>
											</table>
                                            
                                            
                                            
                                              <h6>ORDERS LOST</h6>
                                            
                                            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                            
												<thead>
													<tr>
														<th class="center">
														S.no
														</th>
													    <th>Client Name</th>
                                                        <th>Product</th>
                                                        <th>Quantity</th>
                                                        <th>Value</th>
                                                        
														
													</tr>
												</thead>

												<tbody>
													
														
																	

  <?php 
   $list[] = array('S. No', 'Proforma Date', 'Proforma Invoice', 'Invoice Date', 'Invoice', 'Invoice Amount', 'Executive', 'Company', 'Collected', 'Pending');
  if(mysql_num_rows($lostProposals)>0) {
  $i = $start+1; 
  
  $finalTotal = 0;
  $finalPending = 0;
  $finalCollected = 0;
  $totalValue = 0;
  while($proposal = mysql_fetch_array($lostProposals))
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
 ?><tr>
														<td class="center">
														<?php  echo $i; ?>
														</td>
                                                       
                                                        	<td>
															<?php
															
														
															 echo $proposal['company']; 
															?>
                                
														</td>
                                                        <td>
															<?php
															
															$getproduct = mysql_query("select prd.product as product,drd.quantity as quantity, drd.price as price from products as prd left join daily_reports_data as drd on drd.productId = prd.productId left join daily_reports_revision as drv on drv.revisionId=drd.revisionId left join daily_reports as dr on dr.reportId=drv.reportId where  dr.reportId=".$proposal['reportId']);
															
														#	echo "select prd.product,drd.quantity from products as prd left join daily_reports_data as drd on drd.productId = prd.productId left join daily_reports_revision as drv on drv.revisionId=revisionId left join daily_reports as dr on dr.reportId=drv.reportId where  dr.reportId=".$proposal['reportId']; 
															$pr="<ol>";
															$pr_quant = "<ol>";
															$pr_price="<ol>";
															if( mysql_num_rows($getproduct)>0)
															{
																while($prdct = mysql_fetch_object($getproduct))	
																{
																	$pr.="<li>".$prdct->product."</li>";
																	$pr_quant.="<li>".$prdct->quantity."</li>";			
																	$pr_price.="<li>".$prdct->price."</li>";	
																	$totalValue	= $totalValue+$prdct->price;
																	
																}
																$pr.="</ol>";
																$pr_quant.= "</ol>";
																$pr_price.="</ol>";
																
																
															
															}
															else
															{
																$pr = "No products";
																$pr_quant='0';
																$pr_price='0';
															}	
															echo $pr;
															?>
                                
														</td>
                                                         <td>
															<?php
															
														
															 echo $pr_quant; 
															?>
                                
														</td>
                                                         <td>
															<?php
															
														
															 echo $pr_price;
															
															?>
                                
														</td>
                                                        </tr>
<?php
$rowlist[] = $i;
$rowlist[] = $proInvoiceDate;
$rowlist[] = $poDate;
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
 
  } ?><tr><td colspan="3"></td><td>Total</td><td><?php echo $totalValue; ?></td></tr> <?php } else {
  
  ?><tr><td colspan="5">No Data found.</td></tr>
  <?php }  ?>                                                       </tbody>
											</table>
                                            
                                              <h6>Expected Orders</h6>
                                            
                                            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                            
												<thead>
													<tr>
														<th class="center">
														S.no
														</th>
													    <th>Date</th>
                                                        <th>Company</th>
                                                        <th>Product</th>
                                                        <th>Quantity</th>
                                                        
                                                        <th>Expected Value</th>
                                                        
														
													</tr>
												</thead>

												<tbody>
													
														
																	

  <?php 
$list[] = array('S. No', 'Proforma Date', 'Proforma Invoice', 'Invoice Date', 'Invoice', 'Invoice Amount', 'Executive', 'Company', 'Collected', 'Pending');
  if(mysql_num_rows($collection_sales)>0)
  {  
   $i = $start+1;
 $totalValue=0;
 
  while($collection_sale = mysql_fetch_array($collection_sales))
  {
	
 ?><tr>
														<td class="center">
														<?php  echo $i; ?>
														</td>
                                                       
                                                        	<td>
															<?php 
															     
																  echo $collection_sale['offerDate']
																       ?>
														</td>
                                                        <td>
															<?php
															
														
															echo $collection_sale['company'];
															?>
                                
														</td>
                                                       <td>
															<?php
															$getproduct = mysql_query("select prd.product as product,drd.quantity as quantity, drd.price as price from products as prd left join daily_reports_data as drd on drd.productId = prd.productId left join daily_reports_revision as drv on drv.revisionId=drd.revisionId left join daily_reports as dr on dr.reportId=drv.reportId where  dr.reportId=".$collection_sale['reportId']);
															
															
														#	echo "select prd.product,drd.quantity from products as prd left join daily_reports_data as drd on drd.productId = prd.productId left join daily_reports_revision as drv on drv.revisionId=revisionId left join daily_reports as dr on dr.reportId=drv.reportId where  dr.reportId=".$proposal['reportId']; 
															$pr="<ol>";
															$pr_quant = "<ol>";
															$pr_price="<ol>";
															if( mysql_num_rows($getproduct)>0)
															{
																while($prdct = mysql_fetch_object($getproduct))	
																{
																	$pr.="<li>".$prdct->product."</li>";
																	$pr_quant.="<li>".$prdct->quantity."</li>";			
																	$pr_price.="<li>".$prdct->price."</li>";	
																	$totalValue	= $totalValue+$prdct->price;
																	
																}
																$pr.="</ol>";
																$pr_quant.= "</ol>";
																$pr_price.="</ol>";
																
																
															
															}
															else
															{
																$pr = "No products";
																$pr_quant='0';
																$pr_price='0';
															}	
															echo $pr;
															?>
                                
														</td>
                                                        
                                                        <td>
															<?php
															
														
															echo $pr_quant;
															?>
                                
														</td> 
                                                        
                                                         <td>
															<?php
															
														
															 echo $pr_price; 

															?>
                                
														</td>
                                                         
                                                        </tr>
<?php
$rowlist[] = $i;
$rowlist[] = $proInvoiceDate;
$rowlist[] = $poDate;
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
 
  } ?><tr><td colspan="4"></td><td>Total</td><td><?php echo $totalValue; ?></td></tr> <?php } else {
  
  ?><tr><td colspan="5">No Data found.</td></tr>
  <?php }  ?>                                                       </tbody>
											</table>
                                            
                                            
   <?php
   
// relation between collections and invoices is invoiceId 
// collections of this week and it comes from collections and collection week considered with paidDate 
$collections = mysql_query("select collections.amount, adddate(curdate(), INTERVAL 1-dayofweek(curdate()) DAY) WEEKSTART,  adddate(curdate(), INTERVAL 7-dayofweek(curdate()) DAY) WEEKEND from daily_reports 
							left join employees on daily_reports.addedBy = employees.id 
							
							left join invoices on daily_reports.reportId = invoices.reportId
							left join collections on invoices.invoiceId = collections.invoiceId 
$whereCollection and WEEKOFYEAR( collections.paidDate ) = WEEKOFYEAR( NOW() ) order by collections.id desc");



  $weekCollection = 0;
  $expectedCollections=0;
  if(mysql_num_rows($collections)>0)
  {
	 
	 while($collection = mysql_fetch_array($collections))
	 { 
		 
		 $weekCollection += $collection['amount']; 
		 $weekstart1 = $collection['WEEKSTART'];
		 $weekstart = date_create($weekstart1);
		 $weekstart = date_format($weekstart,'d-m-Y');
		 
		 		 $weekend1 = $collection['WEEKEND'];
				 $weekend = date_create($weekend1);
				 $weekend = date_format($weekend,'d-m-Y');

	 }
  }
	else
	{
		$qry = mysql_query("select date_format(adddate(curdate(), INTERVAL 1-dayofweek(curdate()) DAY),'%d-%m-%Y') as WEEKSTART,  date_format(adddate(curdate(), INTERVAL 7-dayofweek(curdate()) DAY),'%d-%m-%Y') as WEEKEND ");
		
		$collection = mysql_fetch_array($qry);
		
		$weekstart = $collection['WEEKSTART'];
		$weekend = $collection['WEEKEND'];
	}


// expected payment of next week and data comes from expected_sales table and date considered as expectedDate
$result = mysql_query("select ec.expectedDate,offerNo,Amount, DATE_ADD(curdate(), INTERVAL (8 - DAYOFWEEK(curdate())) DAY) AS nextweek_startday, DATE_ADD(DATE_ADD(curdate(), INTERVAL (8 - DAYOFWEEK(curdate())) DAY), INTERVAL 6 DAY) as next_lastday from daily_reports as dr join expected_collections as ec on ec.reportId=dr.reportId where weekofyear(ec.expectedDate)=(weekofyear(CURRENT_DATE)+1)");



  if(mysql_num_rows($result)>0)
  {
	 
	 while($row = mysql_fetch_array($result))
	 { 
		 $expectedCollections = $row['Amount']; 
		 $nextweekstart1 = $row['nextweek_startday'];
		 $nextweekstart = date_create($nextweekstart1);
		 $nextweekstart = date_format($nextweekstart,'d-m-Y');
		 
		 $nextweekend1 = $row['next_lastday'];
		  $nextweekend = date_create($nextweekend1);
		 $nextweekend = date_format($nextweekend,'d-m-Y');
		 
	 }
  }
  else
  {
		$result = mysql_query("select date_format(DATE_ADD(curdate(), INTERVAL (8 - DAYOFWEEK(curdate())) DAY),'%d-%m-%Y') AS nextweek_starts, date_format(DATE_ADD(DATE_ADD(curdate(), INTERVAL (8 - DAYOFWEEK(curdate())) DAY), INTERVAL 6 DAY),'%d-%m-%Y') as next_lastday");
		
		$row = mysql_fetch_array($result);
		
		$nextweekstart = $row['nextweek_starts'];
		
		 $nextweekend = $row['next_lastday'];
  }
	//exit;
	
   ?>
   
   
                                            
   <h6>PAYMENT COLLECTION</h6>
   <table id="sample-table-1" class="table table-striped table-bordered table-hover">
   <tbody>
   <tr><td class="left">Payment Collected this week</td><td><strong>Week Start:</strong>&nbsp;&nbsp;<?PHP echo $weekstart; ?></td><td><strong>Week End:</strong>&nbsp;&nbsp;<?PHP echo $weekend; ?></td><td><?php echo $weekCollection; ?></td></tr>
   <tr><td class="left">Expected Payment next week</td><td><strong>Next Week Start:</strong>&nbsp;&nbsp;<?PHP echo $nextweekstart; ?></td><td><strong>Next Week End:</strong>&nbsp;&nbsp;<?PHP echo $nextweekend; ?></td><td><?php  echo $expectedCollections; ?></td></tr>
   </tbody>
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
