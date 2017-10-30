<?php 
include("includes/header.php"); 

// if we want to delete the report we need to delete the report from 3 tables they are daily_reports, daily_reports_revision, daily_reports_revision
 // delete report from daily_reports
 if(isset($_GET['did']))
 {
	mysql_query("delete from daily_reports where reportId = '". $_GET['did'] ."'");
	
	$revisions = mysql_query("select revisionId from daily_reports_revision where reportId = '". $_GET['did'] ."'");
	while($revision = mysql_fetch_array($revisions))
	{
	   	mysql_query("delete from daily_reports_data where revisionId = '". $revision['revisionId'] ."'");
	}
	
	mysql_query("delete from daily_reports_revision where reportId = '". $_GET['did'] ."'");
	header("location: offers.php?delete=1");
 }
 

  if(isset($_POST['po_submit']))
  {
	  
	  mysql_query("update daily_reports set poNo = '". $_POST['po_number'] ."' where reportId = '". $_POST['reportId'] ."'");  
	  header("location: offers.php");
  }
  
// pagination
 $limit = 10;
 
// get the data from daily_reports,customers,employees
 /*to get the enquiries data we will execute this query with employees table.
relation between enquiries and enquiry_assign is enquiryId.
relation between daily_reports and enquiries is enquiryNumber.
relation between daily_reports and customers are daily_reports.company = customers.customerId.
relation between daily_reports and employees are daily_reports.addedBy = employees.id
relation between branches and employees are employees.branch = branches.branchId
*/

/*$numRecords = mysql_query("select daily_reports.reportId, daily_reports.reportDate, daily_reports.enquiryNumber, date_format(daily_reports.offerDate,'%d-%m-%Y') offerDate, daily_reports.leadType, daily_reports.leadStatus, daily_reports.poNo, daily_reports.poDateTime, daily_reports.inv, daily_reports.invoice, daily_reports.invoiceDateTime, customers.company, employees.firstName from daily_reports
INNER join customers on daily_reports.company = customers.customerId
INNER join employees on daily_reports.addedBy = employees.id
where daily_reports.offer > '0' GROUP BY reportId desc");*/

$where = "where daily_reports.offer>0";

$fromDate = '';
$toDate = '';
//check whether user searches with from and todate

if( isset($_GET['proposalSearch']))
{
		if( isset($_GET['fromDate']) && $_GET['fromDate']!='' )
		{
			$fromDate = $_GET['fromDate'];
			$toDate = $_GET['toDate'];
			
			$fromDate = date_create($fromDate);
			$fromDate = date_format($fromDate,"Y-m-d");
			
			$toDate = date_create($toDate);
			$toDate = date_format($toDate,"Y-m-d");
			
			$where.=" and ( daily_reports.reportDate >='".$fromDate."' and daily_reports.reportDate<='".$toDate."') ";	
		}
		
		if( isset($_GET['bid']) && $_GET['bid']>0 )
			$where.="  and employees.branch=".$_GET['bid'];
		
		if( isset( $_GET['catid']) && $_GET['catid']>0 )
			$where.="  and cat.id=  '". $_GET['catid'] ."'";
		
		if(isset($_GET['eid']) && $_GET['eid']>0)
			$where.=" and employees.id =  '".$_GET['eid']."'";

		 if(isset($_GET['cid']) && $_GET['cid']>0)
			$where.=" and customers.customerId =  '". $_GET['cid'] ."'";

}
else
{
	
	if( isset($_GET['fromDate']) && $_GET['fromDate']!='' )
	{
			$fromDate = $_GET['fromDate'];
			$toDate = $_GET['toDate'];
			$fromDate = date_create($fromDate);
			$fromDate = date_format($fromDate,"Y-m-d");
			
			$toDate = date_create($toDate);
			$toDate = date_format($toDate,"Y-m-d");
			$where.=" and ( daily_reports.reportDate >='".$fromDate."' and 'daily_reports.reportDate<='".$toDate."') ";
	}
	
	
	//for branch
	
	if( isset($_GET['bid']) && $_GET['bid']>0 )
		$where.="  and employees.branch=".$_GET['bid'];


// for category

if( isset( $_GET['catid']) && $_GET['catid']>0 )
	$where.="  and cat.id=  '". $_GET['catid'] ."'";

//for employee


 if(isset($_GET['eid']) && $_GET['eid']>0)
	$where.=" and employees.id =  '".$_GET['eid']."'";


//for customer

 if(isset($_GET['cid']) && $_GET['cid']>0)
	$where.=" and customers.customerId =  '". $_GET['cid'] ."'";

//$whereItems[] = "customers.customerId =  '". $_GET['cid'] ."'";
}

$qry= mysql_query("select daily_reports.reportId,date_format(enquiries.enqDate,'%d-%m-%Y') as enqDate, daily_reports.offer, daily_reports.offerNo, date_format(daily_reports.offerDate,'%d-%m-%Y') offerDate, daily_reports.enquiryNumber, daily_reports.offerToBeSubmitted,  daily_reports.currentRevisionId, daily_reports.reportDate, daily_reports.leadType, daily_reports.leadStatus, daily_reports.po, daily_reports.poNo, daily_reports.poDateTime, daily_reports.inv, daily_reports.invoice, daily_reports.invoiceDateTime, daily_reports.proformaInvoice, daily_reports.proformaInvoiceDateTime, customers.company, employees.firstName, branches.branch, cat.category from daily_reports
INNER join customers on daily_reports.company = customers.customerId
INNER join employees on daily_reports.addedBy = employees.id
INNER join branches on employees.branch = branches.branchId
INNER join enquiries on enquiries.enquiryNumber=daily_reports.enquiryNumber
INNER join daily_reports_revision as drv on drv.reportId=daily_reports.reportId
INNER join daily_reports_data as drd on drd.revisionId=drv.revisionId
INNER join categories as cat on cat.id=drd.categoryId
$where GROUP BY daily_reports.reportId order by  daily_reports.reportId DESC");
 
 $numRecords = mysql_num_rows($qry);  
 

 
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
  
  
  /*
  
// search options  where daily_reports.po = '0'

    $where = " where daily_reports.offer >0";
  if(isset($_GET['proposalSearch']))
			{
				
				#if(isset($_GET['fromDate']) && strlen($_GET['fromDate']>8) && isset($_GET['toDate']) && strlen($_GET['toDate']>8))
				if( isset($_GET['fromDate']) && isset($_GET['toDate']) )
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
			
			   // by employee
			   if(isset($_GET['eid']) && $_GET['eid']>0)
					$whereItems[] = "employees.id =  '". $_GET['eid'] ."'";
				// by customer
			   if(isset($_GET['cid']) && $_GET['cid']>0)
					$whereItems[] = "customers.customerId =  '". $_GET['cid'] ."'";
			   // by Branch
			   if(isset($_GET['bid']) && $_GET['bid']>0)
					$whereItems[] = "employees.branch =  '". $_GET['bid'] ."'";
			   
				if( isset($_GET['catid']) && $_GET['catid']>0)
					$whereItems[] = "cat.id=  '". $_GET['catid'] ."'";
				
				if( isset($_GET['prdid']) && $_GET['prdid']>0)
					$whereItems[] = "drd.productId=  '". $_GET['prdid'] ."'";
	
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
				  $where = '';	
		
	// this query used for search content				
$proposals = mysql_query("select daily_reports.reportId,date_format(enquiries.enqDate,'%d-%m-%Y') as enqDate, daily_reports.offer, date_format(daily_reports.offerDate,'%d-%m-%Y') offerDate, daily_reports.enquiryNumber, daily_reports.offerNo, daily_reports.offerToBeSubmitted,  daily_reports.currentRevisionId, daily_reports.reportDate, daily_reports.leadType, daily_reports.leadStatus, daily_reports.po, daily_reports.poNo, daily_reports.poDateTime, daily_reports.inv, daily_reports.invoice, daily_reports.invoiceDateTime, daily_reports.proformaInvoice, daily_reports.proformaInvoiceDateTime, customers.company, employees.firstName, branches.branch from daily_reports

inner join customers on daily_reports.company = customers.customerId
inner join employees on daily_reports.addedBy = employees.id
inner join branches on employees.branch = branches.branchId
inner join enquiries on enquiries.enquiryNumber=daily_reports.enquiryNumber
inner join daily_reports_revision as drv on drv.reportId=daily_reports.reportId
inner join daily_reports_data as drd on drd.revisionId=drv.revisionId
inner join categories as cat on cat.id=drd.categoryId

$where
GROUP BY reportId desc limit $start, $limit");					

}
									
else if(isset($_GET['repDate']))
{     
//  this query written for To get all the pending offers data . this count displayed in header file 
$proposals = mysql_query("select daily_reports.reportId,date_format(enquiries.enqDate,'%d-%m-%Y') as enqDate, daily_reports.offer, daily_reports.offerNo, date_format(daily_reports.offerDate,'%d-%m-%Y') offerDate, daily_reports.enquiryNumber, daily_reports.offerToBeSubmitted,  daily_reports.currentRevisionId, daily_reports.reportDate, daily_reports.leadType, daily_reports.leadStatus, daily_reports.po, daily_reports.poNo, daily_reports.poDateTime, daily_reports.inv, daily_reports.invoice, daily_reports.invoiceDateTime, daily_reports.proformaInvoice, daily_reports.proformaInvoiceDateTime, customers.company, employees.firstName, branches.branch from daily_reports
inner join customers on daily_reports.company = customers.customerId
inner join employees on daily_reports.addedBy = employees.id
inner join branches on employees.branch = branches.branchId
inner join enquiries on enquiries.enquiryNumber=daily_reports.enquiryNumber
inner join daily_reports_revision as drv on drv.reportId=daily_reports.reportId
inner join daily_reports_data as drd on drd.revisionId=drv.revisionId
inner join categories as cat on cat.id=drd.categoryId

where daily_reports.leadStatus = 'Offer to be generated' and daily_reports.offer > '0' GROUP BY reportId desc limit $start, $limit");

}

else if(isset($_GET['repId']))
{
//  this query written to get the pending offer of particular report only(clicked on)
$proposals = mysql_query("select daily_reports.reportId,date_format(enquiries.enqDate,'%d-%m-%Y') as enqDate, daily_reports.offer, daily_reports.offerNo, date_format(daily_reports.offerDate,'%d-%m-%Y') offerDate, daily_reports.enquiryNumber, daily_reports.offerToBeSubmitted,  daily_reports.currentRevisionId, daily_reports.reportDate, daily_reports.leadType, daily_reports.leadStatus, daily_reports.po, daily_reports.poNo, daily_reports.poDateTime, daily_reports.inv, daily_reports.invoice, daily_reports.invoiceDateTime, daily_reports.proformaInvoice, daily_reports.proformaInvoiceDateTime, customers.company, employees.firstName, branches.branch from daily_reports
inner join customers on daily_reports.company = customers.customerId
inner join employees on daily_reports.addedBy = employees.id
inner join branches on employees.branch = branches.branchId
inner join enquiries on enquiries.enquiryNumber=daily_reports.enquiryNumber
inner join daily_reports_revision as drv on drv.reportId=daily_reports.reportId
inner join daily_reports_data as drd on drd.revisionId=drv.revisionId
inner join categories as cat on cat.id=drd.categoryId

where daily_reports.reportId = '". $_GET['repId'] ."' and daily_reports.leadStatus = 'Offer to be generated' and daily_reports.offer > '0' GROUP BY reportId desc limit $start, $limit");

}

else								
{
  
// this query to get the offers data from daily_reports table  
$proposals = mysql_query("select daily_reports.reportId,date_format(enquiries.enqDate,'%d-%m-%Y') as enqDate, daily_reports.offer, daily_reports.offerNo, date_format(daily_reports.offerDate,'%d-%m-%Y') offerDate, daily_reports.enquiryNumber, daily_reports.offerToBeSubmitted,  daily_reports.currentRevisionId, daily_reports.reportDate, daily_reports.leadType, daily_reports.leadStatus, daily_reports.po, daily_reports.poNo, daily_reports.poDateTime, daily_reports.inv, daily_reports.invoice, daily_reports.invoiceDateTime, daily_reports.proformaInvoice, daily_reports.proformaInvoiceDateTime, customers.company, employees.firstName, branches.branch, cat.category from daily_reports
INNER join customers on daily_reports.company = customers.customerId
INNER join employees on daily_reports.addedBy = employees.id
INNER join branches on employees.branch = branches.branchId
INNER join enquiries on enquiries.enquiryNumber=daily_reports.enquiryNumber
INNER join daily_reports_revision as drv on drv.reportId=daily_reports.reportId
INNER join daily_reports_data as drd on drd.revisionId=drv.revisionId
INNER join categories as cat on cat.id=drd.categoryId
where daily_reports.offer > '0' GROUP BY reportId desc limit $start, $limit");
  
}
*/


$proposals = mysql_query("select daily_reports.reportId,date_format(enquiries.enqDate,'%d-%m-%Y') as enqDate, daily_reports.offer, daily_reports.offerNo, date_format(daily_reports.offerDate,'%d-%m-%Y') offerDate, daily_reports.enquiryNumber, daily_reports.offerToBeSubmitted,  daily_reports.currentRevisionId, daily_reports.reportDate, daily_reports.leadType, daily_reports.leadStatus, daily_reports.po, daily_reports.poNo, daily_reports.poDateTime, daily_reports.inv, daily_reports.invoice, daily_reports.invoiceDateTime, daily_reports.proformaInvoice, daily_reports.proformaInvoiceDateTime, customers.company, employees.firstName, branches.branch, cat.category from daily_reports
INNER join customers on daily_reports.company = customers.customerId
INNER join employees on daily_reports.addedBy = employees.id
INNER join branches on employees.branch = branches.branchId
INNER join enquiries on enquiries.enquiryNumber=daily_reports.enquiryNumber
INNER join daily_reports_revision as drv on drv.reportId=daily_reports.reportId
INNER join daily_reports_data as drd on drd.revisionId=drv.revisionId
INNER join categories as cat on cat.id=drd.categoryId
$where GROUP BY daily_reports.reportId order by daily_reports.reportId DESC  limit $start, $limit");

 $branches = mysql_query("select * from branches order by branch");

/*
 echo "select daily_reports.reportId,date_format(enquiries.enqDate,'%d-%m-%Y') as enqDate, daily_reports.offer, daily_reports.offerNo, date_format(daily_reports.offerDate,'%d-%m-%Y') offerDate, daily_reports.enquiryNumber, daily_reports.offerToBeSubmitted,  daily_reports.currentRevisionId, daily_reports.reportDate, daily_reports.leadType, daily_reports.leadStatus, daily_reports.po, daily_reports.poNo, daily_reports.poDateTime, daily_reports.inv, daily_reports.invoice, daily_reports.invoiceDateTime, daily_reports.proformaInvoice, daily_reports.proformaInvoiceDateTime, customers.company, employees.firstName, branches.branch, cat.category from daily_reports
INNER join customers on daily_reports.company = customers.customerId
INNER join employees on daily_reports.addedBy = employees.id
INNER join branches on employees.branch = branches.branchId
INNER join enquiries on enquiries.enquiryNumber=daily_reports.enquiryNumber
INNER join daily_reports_revision as drv on drv.reportId=daily_reports.reportId
INNER join daily_reports_data as drd on drd.revisionId=drv.revisionId
INNER join categories as cat on cat.id=drd.categoryId
$where GROUP BY daily_reports.reportId order by daily_reports.reportId DESC limit $start, $limit "; exit; */


?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Offers</li>
                            
						</ul><!-- /.breadcrumb -->

						 <a href="excel/offers.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>

						<!-- /section:basics/content.searchbox -->
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
<input type="text" class="form-control date-picker input-sm" id="fromDate" name="fromDate" placeholder="From Date" <?php if(isset($_GET['fromDate'])) { ?> value="<?php echo $_GET['fromDate']; ?>" <?php } ?> style="width:100%"  />
</div>
<div class="col-sm-2" style="width:13%">
<span>To Date</span>
<input type="text" class="form-control date-picker input-sm" id="toDate" name="toDate" placeholder="To Date" <?php if(isset($_GET['toDate'])) { ?> value="<?php echo $_GET['toDate']; ?>" <?php } ?> style="width:100%" />
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
<input type="text" id="employee" name="employee" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Employee" <?php if(isset($_GET['employee'])) { ?> value="<?php echo $_GET['employee']; ?>" <?php } ?> onKeyUp="getEmployee(this.value)" style="width:100%"  />
<ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="employeesList">
</ul>

</div> 




<div class="col-sm-2" style="width:11%">
<span>Customer</span>
<input type="hidden" id="cid" name="cid" value="<?PHP echo $_GET['cid']; ?>"  />

<input type="text" id="customer" name="customer" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Customer" <?php if(isset($_GET['customer'])) { ?> value="<?php echo $_GET['customer']; ?>" <?php } ?> style="width:100%"  />

<ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="customersList"></ul>
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
											Offers
										</div>


    <?php
										   
	   if(isset($_GET['delete']))
{ $alertMsg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Offer has been deleted!</div>'; }
else if(isset($_GET['error']))
{ $alertMsg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  if(isset($alertMsg)) { echo $alertMsg; }
										   ?> 
										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">
											<table class="table table-striped table-bordered table-hover">
                                            
												<thead>
													<tr>
														<th class="center">
															S.no
														</th>
														<th>Report Date</th>
                                                      <!--  <th>Category</th>-->
                                                        <th>Offer Date</th>
                                                        <th>Offer No</th>
                                                        <th>Enquiry No</th>
                                                        <th>Enquiry Date</th>
                                                        <th>Value</th>
                                                        <th>Branch</th>
                                                        <th>Executive</th>
                                                        <th>Company Name</th>
                                                    	<th>Status</th>
                                                       
                                                        <th>Action</th>
														
													</tr>
												</thead>

												<tbody>
													
														
																	
 
  <?php
  // to download file in excel format we have written this code.
    $list[] = array('S. No', 'Report Date', 'Offer No', 'Value', 'Branch', 'Executive', 'Company', 'Status' ); 
	

	
  if(mysql_num_rows($proposals)>0)
  {
  $i = $start+1; 
  
  $finalTotal = 0;
  while($proposal = mysql_fetch_array($proposals))
  {
	 
  
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
                                                        
                                                        <td><?php
															 $reportDate = explode(' ',$proposal['reportDate']); 
														     $reportDate = explode('-',$reportDate[0]); 
														     $reportDate = $reportDate[2].'-'.$reportDate[1].'-'.$reportDate[0];
														
														 if(strcmp('00-00-0000',$reportDate)!=0)
															{
																echo $reportDate;
															}
															?>
                                                        </td>
                                                      <!--  <td><?PHP echo $proposal['category']; ?></td>-->
                                                        <td> <?PHP echo $proposal['offerDate']; ?></td>
                                                        
                                                        
														
                                                         
                                                        <td>
                                                        
															<?php
															
														
															 echo $proposal['offerNo']; 
															?>
                                
														</td>
                                                         <td>
                                                         
													 <?php 
													 if($proposal['enquiryNumber']!='')
													 {
													  echo $proposal['enquiryNumber']; }
													  else
													  {
														  echo '--';
													  }
													  
													  ?>
												  </td>
                                                  
                                                  <td>
                                                  		<?PHP
															if($proposal['enqDate']!='')
															{
																echo $proposal['enqDate']; }
															else
															{
																echo '--';
															}
													  
														?>
                                                  </td>
                                
														
                                                        <td>
                                                        
															<?php
															
														
															 echo $grandTotal; 
															?>
                                
														</td>
                                                        <td>
															<?php echo ucfirst($proposal['branch']); ?>
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
                               <button class="btn btn-danger btn-sm" title="delete" onClick="confirmDelete('<?php echo $proposal['reportId']; ?>')">
												<i class="ace-icon fa fa-trash icon-only"></i>
										   </button>
     <a href="view_offer.php?pid=<?php echo $proposal['reportId']; ?>" class="btn btn-pink btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                           
                                                        <?php 
												//	echo $proposal['offerToBeSubmitted']."-".$proposal['po']."-".$proposal['inv']; 
													$addoffer[] = 'Offer to be resubmitted'; $addoffer[] = 'Offer to be generated';
											
if(in_array($proposal['leadStatus'],$addoffer) && $proposal['po']==0){
 if(isset($_SESSION['superAdmin']) || isset($_SESSION['bdAdmin']))
 {
														{ ?>
     <a class="btn btn-primary btn-sm" href="generate_offer.php?pid=<?php echo $proposal['reportId']; ?>">Add Offer</a>
                                             <?php } } }
											 
																						  ?>
                                             <?PHP
//if(  $proposal['leadStatus'] == "PO collected"){
//if(isset($_SESSION['superAdmin']) && isset($_SESSION['ordersandExecution']) && $proposal['leadStatus'] == "PO collected" && $proposal['po']<0 )
if( isset($_SESSION['superAdmin']) && $proposal['leadStatus'] == "PO collected" && $proposal['po']<1  || isset($_SESSION['ordersandExecution']) && $proposal['leadStatus'] == "PO collected" && $proposal['po']<1 ) 
											 {
												 ?>
<a href="generate_po.php?order_id=<?PHP echo $proposal['reportId']; ?>" title="Add Po Number" class="btn btn-sm"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                               <?PHP  } ?>         

                                        		</td>

													
													</tr>
<?php 

// to download file in excel format we have written this code.
$rowlist[] = $i;
$rowlist[] = $reportDate;
$rowlist[] = $proposal['offerNo'];
$rowlist[] = $grandTotal; 
$rowlist[] = $proposal['branch']; 

$rowlist[] = ucfirst($proposal['firstName']);
$rowlist[] =ucfirst($proposal['company']);
$rowlist[] =$proposal['leadStatus']; 

 $list[] = $rowlist;
 unset($rowlist);

$i++;
  }
  
 $rowlist[] = '';
 $rowlist[] = '';
 $rowlist[] = '';
  
 $rowlist[] = $finalTotal;
 $list[] = $rowlist;
 unset($rowlist);
 
 
  ?><tr><td colspan="6"></td><td><?php echo $finalTotal; ?></td><td colspan="7"></td></tr><?php
  
  
   } else { ?> <tr><td colspan="9">No Data found.</td></tr> <?php }
  
  //if($numRecords>$limit &&  !(isset($_GET['proposalSearch'])) &&  !(isset($_GET['repDate'])) && !(isset($_GET['repId']))) 
   if($numRecords>$limit ) 
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
  <td id="first_grid-pager" class="ui-pg-button ui-corner-all <?php echo $firstlink; ?>" onClick="goToPage('1')">
                 <span class="ui-icon ace-icon fa fa-angle-double-left bigger-140"></span>
  </td>
  <td id="prev_grid-pager" class="ui-pg-button ui-corner-all <?php echo $secondlink; ?>" onClick="goToPage('<?php echo $currentPage-1; ?>')">
               <span class="ui-icon ace-icon fa fa-angle-left bigger-140"></span>
  </td>
  <td class="ui-pg-button ui-state-disabled" style="width:4px;">
       <span class="ui-separator"></span>
  </td>
  <td dir="ltr">
             Page <input class="ui-pg-input" type="text" onKeyUp="goToPage(this.value)" size="2" maxlength="7" value="<?php echo $currentPage; ?>" role="textbox"> of <span id="sp_1_grid-pager"><?php echo $numPages; ?></span>
  </td>
  <td class="ui-pg-button ui-state-disabled" style="width: 4px; cursor: default;">
         <span class="ui-separator"></span>
  </td>
  <td id="next_grid-pager" class="ui-pg-button ui-corner-all <?php echo $thirdlink; ?>" style="cursor: default;" onClick="goToPage('<?php echo $currentPage+1; ?>')">
                <span class="ui-icon ace-icon fa fa-angle-right bigger-140">></span>
  </td>
  <td id="last_grid-pager" class="ui-pg-button ui-corner-all <?php echo $fourthlink; ?>" onClick="goToPage('<?php echo $numPages; ?>')">
                <span class="ui-icon ace-icon fa fa-angle-double-right bigger-140"></span>
  </td>
  </tr></tbody></table>
  
  
  
  
  
  </td></tr>
  <?php } ?>                                              </tbody>
											</table>
                                            
                                            
                                            
                                             <?php                              
$fp = fopen('excel/offers.csv', 'w');

foreach ($list as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp) ?>
                                 
										</div>
                                        
									</div>
                                    
                                    <div style="clear:both"></div>
                                    
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


$(".ui-icon, .ui-widget-content .ui-icon").css({'background':'none'});
$(".ui-pg-button").css({'cursor':'pointer'});
	
	
			//$("#employee").blur(function(){
				$(document).on('click','#outclick,#breadcrumbs',function(){
				$("#employeesList,#customersList").css('display','none'); 
			});
		});



 function confirmDelete(did)
 {
	if(confirm('Do you want to delete the report')) 
	{
	 window.location = 'offers.php?did='+did; 
	}
 }

function goToPage(pid)
{
		var url =  window.location.href;
		
		var pid = parseInt(pid);

		if(url.indexOf('&page') !== -1 )
			{
				var requrl = url.split('&page');
				window.location = requrl[0]+'&page='+pid;
			}
		else
		{
			if(url.indexOf('?fromDate') !== -1 )
			{
				var requrl = url.split('&page');
				window.location = requrl[0]+'&page='+pid;
			}
			else
				window.location = 'offers.php?page='+pid;	
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
			$('.date-picker').datepicker({
					autoclose: true,
					todayHighlight: true,
					format: 'dd-mm-yyyy'
				})
				
				
			})
		</script>

		<!-- the following scripts are used in demo only for onpage help and you don't need them -->

		
        <!--<link rel="stylesheet" href="docs/assets/js/themes/sunburst.css" />-->

		<script type="text/javascript"> ace.vars['base'] = '..'; </script>
		<script src="assets/js/ace/elements.onpage-help.js"></script>
		<script src="assets/js/ace/ace.onpage-help.js"></script>
		
		<!--<script src="docs/assets/js/rainbow.js"></script>
		<script src="docs/assets/js/language/generic.js"></script>
		<script src="docs/assets/js/language/html.js"></script>
		<script src="docs/assets/js/language/css.js"></script>
		<script src="docs/assets/js/language/javascript.js"></script>
        -->
	</body>
</html>
