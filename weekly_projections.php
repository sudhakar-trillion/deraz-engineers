<?php include("includes/header.php"); 
date_default_timezone_set('Asia/Kolkata');

$where='';
$where2='';

if(isset($_GET['cm']))
 {
	 $cm = $_GET['cm']; 
 }
 else
 {  
   $cm = date('m'); 
 }

$limit = 10;

/*to get the enquiries data we will execute this query with employees table.
relation between employees and rolls are employees.roll = rolls.roll_id.
relation between branches and employees are employees.branch = branches.branchId
*/
$numRecords = mysql_query("select employees.id, employees.firstName, branches.branch, employees.email, employees.dateJoining, employees.personalMobile, rolls.roll from employees 
left join rolls on employees.roll = rolls.roll_id
left join branches on employees.branch = branches.branchId where employees.roll = '4'
order by employees.firstName desc");

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
 
 // search

 if(isset($_GET['proj_search']))
 { 
	 $where = 'where ';
 $where2 = 'where ';
 
 $whereEmp = 'where ';
 
 
 
										/* employee search based on employees.id
										branch search based on employees.branch
										*/	
 
 										 // by employee

									   
									  if(isset($_GET['eid']) && $_GET['eid']>0)
									   {  
									    
										    $whereItems[] = "employees.id =  '". $_GET['eid'] ."'";
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
												  $where = "where employees.roll = '4' ";	
												}
												

$employees = mysql_query("select employees.id, employees.firstName, branches.branch, employees.email, employees.dateJoining, employees.personalMobile, rolls.roll from employees left join rolls on employees.roll = rolls.roll_id
left join branches on employees.branch = branches.branchId 
$where  order by employees.firstName limit $start, $limit");

}
												
 else
 {
$employees = mysql_query("select employees.id, employees.firstName, branches.branch, employees.email, employees.dateJoining, employees.personalMobile, rolls.roll from employees 
left join rolls on employees.roll = rolls.roll_id
left join branches on employees.branch = branches.branchId where employees.roll = '4'
$where $where2 order by employees.firstName limit $start, $limit");
//echo "ws".mysql_num_rows($employees); exit;
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
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Projections  </li>
						</ul><!-- /.breadcrumb -->
<a href="excel/projections.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>


						<!-- #section:basics/content.searchbox -->
						<div class="nav-search" id="nav-search">
							<form class="form-search">
                            
                             
								<span class="input-icon">
									 <select class="nav-search-input" onchange="changeMonth(this.value)">
                                <option value="01" <?php if($cm=='01') { echo 'selected="selected"'; } ?>>Jan</option>
                                <option value="02" <?php if($cm=='02') { echo 'selected="selected"'; } ?>>Feb</option>
                                <option value="03" <?php if($cm=='03') { echo 'selected="selected"'; } ?>>Mar</option>
                                <option value="04" <?php if($cm=='04') { echo 'selected="selected"'; } ?>>Apr</option>
                                <option value="05" <?php if($cm=='05') { echo 'selected="selected"'; } ?>>May</option>
                                <option value="06" <?php if($cm=='06') { echo 'selected="selected"'; } ?>>Jun</option>
                                <option value="07" <?php if($cm=='07') { echo 'selected="selected"'; } ?>>Jul</option>
                                <option value="08" <?php if($cm=='08') { echo 'selected="selected"'; } ?>>Aug</option>
                                <option value="09" <?php if($cm=='09') { echo 'selected="selected"'; } ?>>Sep</option>
                                <option value="10" <?php if($cm=='10') { echo 'selected="selected"'; } ?>>Oct</option>
                                <option value="11" <?php if($cm=='11') { echo 'selected="selected"'; } ?>>Nov</option>
                                <option value="12" <?php if($cm=='12') { echo 'selected="selected"'; } ?>>Dec</option>
                                </select>
									
                                    
								</span>
                                
                           
                                <i class="ace-icon fa fa-search nav-search-icon"></i>
                               
                                
							</form>
						</div><!-- /.nav-search -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- #section:settings.box -->
						<!-- /.ace-settings-container -->

						<!-- /section:settings.box -->
					

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								

							
 <?php
	   if(isset($_GET['delete']))
{ $msg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Customer has been deleted!</div>'; }
else if(isset($_GET['error']))
{ $msg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  if(isset($msg)) {  //echo $msg; 
  }
										   ?> 
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
  
  
  <div class="form-group col-sm-2">
     <span>Branch</span>
     <select class="form-control" id="bid" name="bid">
     <option value="">Select Branch</option>
     <?php  
	 while ($branch = mysql_fetch_array($branches))
	 {  ?>
 <option <?php if(isset($_GET['bid']) && $_GET['bid']==$branch['branchId']) { ?> selected="selected" <?php } ?>  value="<?php echo $branch['branchId'] ?>"><?php echo $branch['branch'] ?></option>
                                         
                                            
                                        <?php
                                           }  ?>     </select> 
                               </div>
 
  <div class="form-group col-sm-2">
                                <span>Employee</span>
     <input type="hidden" id="eid" name="eid" value="<?php echo $_GET['eid']; ?>"  />
     <input type="text" id="employee" name="employee" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Employee" <?php if(isset($_GET['employee'])) { ?> value="<?php echo $_GET['employee']; ?>" <?php } ?> onkeyup="getEmployee(this.value)"  />
           <ul class="typeahead dropdown-menu" style="left: 10px; display: none;" id="employeesList">
                                           </ul>
                                           
                                </div>    
                                             
  <div class="form-group col-sm-2">
   <span><br></span>
    <input type="submit" class="btn btn-sm btn-success" name="proj_search">
  </div>
  
  </div>
                                
  
</form>                      
<div style="clear:both"></div>                 
                                           
								<div class="row">
									<div class="col-xs-12">
										
										<div class="table-header">
											Projections
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div>
											<table id="sample-table-1" class="table table-striped table-bordered table-hover">
												<thead>
                                                <tr>
														<th colspan="3">                                                        </th>
                                                       <th colspan="2" class="center">Collection
                                                        </th>
                                                        
                                                         <th colspan="2" class="center">Orders
                                                        </th>
														  <th colspan="8" class="center">Target VS Achievements
														</th>
                                                      
													</tr>
													<tr>
														<th class="center">S.no</th>
														<th>Name</th>
														<th>Branch</th>
                                                       
                                                        <th>Projection</th>
 														<th>Actual</th>                                                        
                                                        <th>Projection</th>
 														<th>Actual</th>                                                        
                                                        <th>Month TGT</th>
                                                        <th>Month Achieved</th>
                                                        <th>Month Downfall</th>
                                                         <th>Month % achieved</th>
                                                        <th>Annual TGT</th>
                                                        <th>Annual Achieved</th>
                                                        
                                                        <th>Annual Downfall</th>
                                                         <th>Annual % achieved</th>
													</tr>
												</thead>

												<tbody>
													

  <?php 
  if(mysql_num_rows($employees)>0)
  {
  $sno = $start+1;
  $grandExpectedSaleAmount = 0;
  $grandAchievedSaleAmount = 0;
  $grandAnnualTarget = 0;
  $grandAnnualAchieved = 0;
   // to download file in excel format we have written this code. 
  $list[] = array('S. No', 'Name', 'Projection', 'Collected', 'Sales Projections', 'Sales Achieved');
  
  
  while($employee = mysql_fetch_array($employees))
  {
   
	
	
	$currentYear = date('Y');  
	$like = $currentYear.'-'.$cm.'-%';
	
	
						
 ?><tr>
														<td class="center"><?php echo $sno; ?></td>
                                                       <td><?php echo ucfirst($employee['firstName']);  ?></td>
                                                       <td><?php echo $employee['branch'];  ?></td>
													   
                                <td>
           <?php  
		   
//	$projectionWhere =	$where." AND employees.id = '". $employee['id'] ."' AND WEEKOFYEAR(expected_collections.expectedDate)=WEEKOFYEAR(NOW())";
	
 // projected

if( (isset($_GET['fromDate']) && isset($_GET['toDate']) )  )
{
	if($_GET['fromDate']!='' && $_GET['toDate']!='')
	{
		$frmdt = $_GET['fromDate'];
		$fdt = date_create($frmdt);
$fdt = date_format($fdt,"Y-m-d");


$todt = $_GET['toDate'];
		$tdt = date_create($todt);
$tdt = date_format($tdt,"Y-m-d");
		
	// data comes from expected_collections table
	/* after execution of this query we get week data of expected_collections and search content is  expectedDate*/
			$collectionExpected = mysql_query("select daily_reports.invoice, daily_reports.invoiceDateTime, expected_collections.amount, expected_collections.expectedDate, customers.company from expected_collections
	
	left join daily_reports on expected_collections.reportId = daily_reports.reportId
	left join employees on daily_reports.addedBy = employees.id
	left join customers on daily_reports.company = customers.customerId
	$where and employees.id = '". $employee['id'] ."' AND ( expected_collections.expectedDate>='".$fdt."' AND expected_collections.expectedDate<='".$tdt."'   )  order by expected_collections.expectedId desc");
		
	}
	else
	{
		
		 if($where!='')
 	 $where= $where;
 else
 	$where ='where ';
	
		$collectionExpected = mysql_query("select daily_reports.invoice,invoices.invoiceNumber, daily_reports.invoiceDateTime, expected_collections.amount, expected_collections.expectedDate, customers.company from expected_collections left join daily_reports on expected_collections.reportId = daily_reports.reportId
	left join employees on daily_reports.addedBy = employees.id
	left join customers on daily_reports.company = customers.customerId
	left join invoices on invoices.reportId=daily_reports.reportId
	 $where AND WEEKOFYEAR(expected_collections.expectedDate)=WEEKOFYEAR(NOW())  order by expected_collections.expectedId desc");	
	 
	 

	}
	

}

else
{
 // data comes from expected_collections table
 /* after execution of this query we get week data of expected_collections and search content is  expectedDate*/
 
 if($where!='')
 	 $where= $where;
 else
 	$where ='where ';
	$collectionExpected = mysql_query("select daily_reports.invoice,invoices.invoiceNumber, daily_reports.invoiceDateTime, expected_collections.amount, expected_collections.expectedDate, customers.company from expected_collections left join daily_reports on expected_collections.reportId = daily_reports.reportId
	left join employees on daily_reports.addedBy = employees.id
	left join customers on daily_reports.company = customers.customerId
	left join invoices on invoices.reportId=daily_reports.reportId
	 $where  employees.id = '". $employee['id'] ."' AND WEEKOFYEAR(expected_collections.expectedDate)=WEEKOFYEAR(NOW())  order by expected_collections.expectedId desc");
	 
	 
	
}
	
		    ?>

            <div id="<?php echo 'modal-table88_'.$employee['id']; ?>" class="modal fade in" tabindex="-1" aria-hidden="false" style="display: none;">
              <div class="modal-backdrop fade in"></div>
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">×</span>
													</button>
													<?php echo ucfirst($employee['firstName']); echo ' - Collection Projection';  ?>
												</div>
											</div>

											<div class="modal-body no-padding">
												<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
													<thead>
														<tr>
															<th>S.no</th>
                                                           	<th>Invoice</th>
                                                            <th>Expected Date</th>
                                                            
                                                            <th>Customer</th>
                                                            <th>Expected Value</th>
                                                          </tr>
													</thead>

													<tbody>
        <?php   
	if(mysql_num_rows($collectionExpected)>0)
	{
		 $cid=1;

	   while($collectionExpect = mysql_fetch_array($collectionExpected))	
	   {
		   $expectedProjectionAmount = 0;

		  $expectedProjectionAmount = $collectionExpect['amount']+$expectedProjectionAmount;   
		  ?> <tr>
                 <td>
			<?php echo $cid; ?>
										</td>
                                        <td><?PHP echo $collectionExpect['invoiceNumber']; ?> </td>
								<td><?php $invoiceDate = explode('-',$collectionExpect['expectedDate']);
								       
										  echo $invoiceDate[2].'-'.$invoiceDate[1].'-'.$invoiceDate[0];
								 ?></td>
                              
                                <td><?php echo $collectionExpect['company']; ?></td>
                                <td><?php echo $collectionExpect['amount']; ?></td>
                               
															
														</tr> <?php
	   $cid++; 
	   }
	} 
	else
		$expectedProjectionAmount='0';
	
	$grandExpectedProjectionAmount =  $expectedProjectionAmount+$grandExpectedProjectionAmount;
	//echo $grandExpectedProjectionAmount;
	?>
													</tbody>
												</table>
											</div>

											<div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close
												</button>

												
											</div>
										</div><!-- /.modal-content -->
									</div><!-- /.modal-dialog -->
								</div>
            
<a href="<?php echo '#modal-table88_'.$employee['id']; ?>" role="button" class="green" data-toggle="modal"><?php echo $expectedProjectionAmount; ?></a>                                           
                                                      </td>
                                                      
                                                      <td>
           <?php  
		

if( (isset($_GET['fromDate']) && isset($_GET['toDate']) )  )
{
	if($_GET['fromDate']!='' && $_GET['toDate']!='')
	{
		$frmdt = $_GET['fromDate'];
		$fdt = date_create($frmdt);
$fdt = date_format($fdt,"Y-m-d");


$todt = $_GET['toDate'];
		$tdt = date_create($todt);
$tdt = date_format($tdt,"Y-m-d");
		
	// data comes from collections,daily_reports,employees,customers table.
	/* relation between invoices and collections are invoiceId.
relation between invoices and daily_reports are invoices.reportId = daily_reports.repotId.
relation between daily_reports and employees are daily_reports.addedBy = employees.id
relation between customers and daily_reports are daily_reports.company = customers.customerId
*/
/* after execution of this query we get week data of collections and search content is  paidDate*/
			$collections = mysql_query("select daily_reports.invoice,invoices.invoiceNumber,daily_reports.invoiceDateTime, collections.amount, collections.paidDate, customers.company  from collections
	
	left join invoices on collections.invoiceId = invoices.invoiceId
	left join daily_reports on invoices.reportId = daily_reports.reportId
	left join employees on daily_reports.addedBy = employees.id
	left join customers on daily_reports.company = customers.customerId
	
	$where and employees.id = '". $employee['id'] ."' AND ( collections.paidDate>='".$fdt."' AND collections.paidDate<='".$tdt."'   )  order by collections.id desc");	
	}
	
	else
{

 if($where!='')
 	 $where= $where;
 else
 	$where ='where ';
	// data comes from collections,daily_reports,employees,customers table.
	/* after execution of this query we get week data of collections and search content is  paidDate.*/
	$collections = mysql_query("select daily_reports.invoice, daily_reports.invoiceDateTime, invoices.invoiceNumber, collections.amount, collections.paidDate, customers.company  from collections
	
	left join invoices on collections.invoiceId = invoices.invoiceId
	left join daily_reports on invoices.reportId = daily_reports.reportId
	
	left join employees on daily_reports.addedBy = employees.id
	left join customers on daily_reports.company = customers.customerId
	

	$where  AND WEEKOFYEAR(collections.paidDate)=WEEKOFYEAR(NOW()) order by collections.id desc");
	
	
}


}

else
{

	// data comes from collections,daily_reports,employees,customers table.
	/* after execution of this query we get week data of collections and search content is  paidDate.*/
	
	
 if($where!='')
 	 $where= $where;
 else
 	$where ='where ';
	
	
	$collections = mysql_query("select daily_reports.invoice, daily_reports.invoiceDateTime, invoices.invoiceNumber, collections.amount, collections.paidDate, customers.company  from collections
	
	left join invoices on collections.invoiceId = invoices.invoiceId
	left join daily_reports on invoices.reportId = daily_reports.reportId
	
	left join employees on daily_reports.addedBy = employees.id
	left join customers on daily_reports.company = customers.customerId
	

	$where  employees.id = '". $employee['id'] ."'  AND WEEKOFYEAR(collections.paidDate)=WEEKOFYEAR(NOW()) order by collections.id desc");
	
	
	
	
		

}
	?>

            <div id="<?php echo 'modal-table98_'.$employee['id']; ?>" class="modal fade in" tabindex="-1" aria-hidden="false" style="display: none;">
              <div class="modal-backdrop fade in"></div>
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">×</span>
													</button>
													<?php echo ucfirst($employee['firstName']); echo ' - Actual Collection';  ?>
												</div>
											</div>

											<div class="modal-body no-padding">
												<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
													<thead>
														<tr>
															<th>S.no</th>
                                                            <th>Invoice </th>
                                                           	<th>Paid Date</th>
                                                            <th>Customer</th>
                                                            <th>Amount</th>
                                                          </tr>
													</thead>

													<tbody>
        <?php   $collectionAmount = 0;
	if(mysql_num_rows($collections)>0)
	{ $cid=1;
	   while($collectedAmount = mysql_fetch_array($collections))	
	   {
		  $collectionAmount = $collectedAmount['amount']+$collectionAmount;   
		  ?> <tr>
                 <td>
			<?php echo $cid; ?>
										</td>
                                        <td><?PHP echo $collectedAmount['invoiceNumber'];?> </td>
								<td><?php $collectionDate = explode('-',$collectedAmount['paidDate']);
								       
										  echo $collectionDate[2].'-'.$collectionDate[1].'-'.$collectionDate[0];
								 ?></td>
                              
                                <td><?php echo $collectedAmount['company']; ?></td>
                                <td><?php echo $collectedAmount['amount']; ?></td>
                               
															
														</tr> <?php
	   $cid++; }
	} 
	else
		$collectionAmount='0';
	
	
	$grandCollectionAmount =  $collectionAmount+@$grandCollectionAmount;
	?>
														

														
													</tbody>
												</table>
											</div>

											<div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close
												</button>

												
											</div>
										</div><!-- /.modal-content -->
									</div><!-- /.modal-dialog -->
								</div>
            
<a href="<?php echo '#modal-table98_'.$employee['id']; ?>" role="button" class="green" data-toggle="modal"><?php echo $collectionAmount; ?></a>                                          
                                                      </td>
                                                      
                       
                       <td>
           <?php
		   
if( (isset($_GET['fromDate']) && isset($_GET['toDate']) )  )
{
	if($_GET['fromDate']!='' && $_GET['toDate']!='')
	{
		$frmdt = $_GET['fromDate'];
		$fdt = date_create($frmdt);
$fdt = date_format($fdt,"Y-m-d");


$todt = $_GET['toDate'];
		$tdt = date_create($todt);
$tdt = date_format($tdt,"Y-m-d");
		
/*echo "select expected_sales.expectedValue, expected_sales.expectedDate, customers.company from expected_sales 
		   left join customers on expected_sales.customerId = customers.customerId 
	left join customers on daily_reports.company = customers.customerId
		   $where and expected_sales.employeeId = '". $employee['id'] ."' 
		    AND ( expected_sales.expectedDate>='".$fdt."' AND expected_sales.expectedDate<='".$tdt."'   )  order by expected_sales.expectedSaleId desc"; exit;
*/			// data comes from expected_sales table
/* after execution of this query we get week data of collections and search content is  paidDate*/
			$salesExpected123 = mysql_query("select expected_sales.expectedValue, expected_sales.expectedDate, customers.company from expected_sales 
		   left join customers on expected_sales.customerId = customers.customerId 
		   $where and expected_sales.employeeId = '". $employee['id'] ."' 
		    AND ( expected_sales.expectedDate>='".$fdt."' AND expected_sales.expectedDate<='".$tdt."'   )  order by expected_sales.expectedSaleId desc");
	}
	
	else
		   	
			{	

			// data comes from expected_sales table
	/* after execution of this query we get week data of expected_sales and search content is  expectedDate*/		
		   $salesExpected123 = mysql_query("select expected_sales.expectedValue, expected_sales.expectedDate, customers.company from expected_sales 
		   left join customers on expected_sales.customerId = customers.customerId 
		  where expected_sales.employeeId = '". $employee['id'] ."' and WEEKOFYEAR(expected_sales.expectedDate)=WEEKOFYEAR(NOW())");
		  
	
			}
}

		else
		   	
			{	

			// data comes from expected_sales table
	/* after execution of this query we get week data of expected_sales and search content is  expectedDate*/		
		   $salesExpected123 = mysql_query("select expected_sales.expectedValue, expected_sales.expectedDate, customers.company from expected_sales 
		   left join customers on expected_sales.customerId = customers.customerId 
		  where expected_sales.employeeId = '". $employee['id'] ."' and WEEKOFYEAR(expected_sales.expectedDate)=WEEKOFYEAR(NOW())");
			}
		   
		   
		    ?>
            <div id="<?php echo 'modal-table68_'.$employee['id']; ?>" class="modal fade in" tabindex="-1" aria-hidden="false" style="display: none;">
              <div class="modal-backdrop fade in"></div>
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">×</span>
													</button>
													<?php echo ucfirst($employee['firstName']); echo ' - Sales Projection';  ?>
												</div>
											</div>

											<div class="modal-body no-padding">
												<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
													<thead>
														<tr>
															<th>S.no</th>
                                                           	<th>Expected Date</th>
                                                            <th>Customer</th>
                                                            <th>Expected Value</th>
                                                          </tr>
													</thead>

													<tbody>
        <?php   $expectedSaleAmount = 0;
	if(mysql_num_rows($salesExpected123)>0)
	{ $cid=1;
	   while($salesExpect = mysql_fetch_array($salesExpected123))	
	   {
		  $expectedSaleAmount = $salesExpect['expectedValue']+$expectedSaleAmount;   
		  ?> <tr>
                 <td>
			<?php echo $cid; ?>
										</td>
								<td><?php $invoiceDate = explode('-',$salesExpect['expectedDate']);
								       
										  echo $invoiceDate[2].'-'.$invoiceDate[1].'-'.$invoiceDate[0];
								 ?></td>
                              
                                <td><?php echo $salesExpect['company']; ?></td>
                                <td><?php echo $salesExpect['expectedValue']; ?></td>
                               
															
														</tr> <?php
	   $cid++; }
	} 
	
	$grandExpectedSaleAmount =  $expectedSaleAmount+$grandExpectedSaleAmount;
	?>
														
													</tbody>
												</table>
											</div>

											<div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close
												</button>

												
											</div>
										</div><!-- /.modal-content -->
									</div><!-- /.modal-dialog -->
								</div>
<a href="<?php echo '#modal-table68_'.$employee['id']; ?>" role="button" class="green" data-toggle="modal"><?php echo $expectedSaleAmount; ?></a>                                           
                                                      
                                                      
                                                      
                                                      </td>    
                                                                              
                                                      
                                                     <td>
           <?php  
		   
		   
if( (isset($_GET['fromDate']) && isset($_GET['toDate']) )  )
{

	if($_GET['fromDate']!='' && $_GET['toDate']!='')
	{

		$frmdt = $_GET['fromDate'];
		$fdt = date_create($frmdt);
$fdt = date_format($fdt,"Y-m-d");


$todt = $_GET['toDate'];
		$tdt = date_create($todt);
$tdt = date_format($tdt,"Y-m-d");

/* after execution of this query we get week data of daily_reports and search content is  invoiceDateTime*/
			// sales achieved data comes from daily_reports table	
			$salesAchieved = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.invoice, daily_reports.invoiceDateTime, customers.company from daily_reports 
		   left join customers on daily_reports.company = customers.customerId
		   $where and daily_reports.addedBy = '". $employee['id'] ."'  AND ( daily_reports.invoiceDateTime>='".$fdt."' AND daily_reports.invoiceDateTime<='".$tdt."'   ) and daily_reports.inv > '0' and daily_reports.invoiceDateTime like '". $like ."'");
	}
	
	else
		{   
		/* after execution of this query we get week data of daily_reports and search content is  invoiceDateTime*/
		   // sales achieved data comes from daily_reports table

		   $salesAchieved = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.invoice, daily_reports.invoiceDateTime, customers.company from daily_reports 
		   left join customers on daily_reports.company = customers.customerId
		  where daily_reports.addedBy = '". $employee['id'] ."' and daily_reports.inv > '0' and daily_reports.invoiceDateTime like '". $like ."'");
		   
		   
		}
}  
		   
		else
		{   
		/* after execution of this query we get week data of daily_reports and search content is  invoiceDateTime*/
		   // sales achieved data comes from daily_reports table
		   $salesAchieved = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.invoice, daily_reports.invoiceDateTime, customers.company from daily_reports 
		   left join customers on daily_reports.company = customers.customerId
		   $where daily_reports.addedBy = '". $employee['id'] ."' and daily_reports.inv > '0' and daily_reports.invoiceDateTime like '". $like ."'");
		  
		   
		   
		}
		   
		   
		    ?>
            <div id="<?php echo 'modal-table4_'.$employee['id']; ?>" class="modal fade in" tabindex="-1" aria-hidden="false" style="display: none;">
              <div class="modal-backdrop fade in"></div>
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">×</span>
													</button>
													<?php echo ucfirst($employee['firstName']); echo ' - Sales Achieved';  ?>
												</div>
											</div>

											<div class="modal-body no-padding">
												<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
													<thead>
														<tr>
															<th>S.no</th>
                                                           	<th>Invoice Date</th>
                                                            <th>Invoice</th>
                                                             <th>Companies</th>
                                                            <th>Value</th>
                                                          </tr>
													</thead>

													<tbody>
        <?php $achievedSaleAmount = 0;
	if(mysql_num_rows($salesAchieved)>0)
	{ $cid=1;   
	   while($salesAchieve = mysql_fetch_array($salesAchieved))	
	   {
		   
		    $salesResult = mysql_query("select total from invoices where reportId = '". $salesAchieve['reportId'] ."'");

		   $invoiceAmount = 0;
		   while($salesRow = mysql_fetch_array($salesResult))
		   {
		       $achievedSaleAmount = $salesRow['total']+$achievedSaleAmount;  
			   $invoiceAmount = $salesRow['total']+$invoiceAmount;  
		   }
		  ?> <tr>
                 <td>
			<?php echo $cid; ?>
										</td>
								<td><?php $invoiceDate = explode(' ',$salesAchieve['invoiceDateTime']);
							              $invoiceDate = explode('-',$invoiceDate[0]);
								     	  echo $invoiceDate[2].'-'.$invoiceDate[1].'-'.$invoiceDate[0];
								 ?></td>
                                 <td><?php echo $salesAchieve['invoice']; ?></td>
                                <td><?php echo $salesAchieve['company']; ?></td>
                                <td><?php echo $invoiceAmount; ?></td>
                               
															
														</tr> <?php
	   $cid++; }
	   
	   $grandAchievedSaleAmount  =  $achievedSaleAmount+$grandAchievedSaleAmount;
	}
	
	
	?>
														

														
													</tbody>
												</table>
											</div>

											<div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close
												</button>

												
											</div>
										</div><!-- /.modal-content -->
									</div><!-- /.modal-dialog -->
								</div>
            
              <a href="<?php echo '#modal-table4_'.$employee['id']; ?>" role="button" class="green" data-toggle="modal"><?php echo $achievedSaleAmount; ?></a>                                            <?PHP if($expectedSaleAmount>$achievedSaleAmount) { ?> <!--<i class="fa fa-arrow-down" aria-hidden="true" style="color:#F00"></i>--> <?PHP } elseif($expectedSaleAmount<$achievedSaleAmount){?><!--<i class="fa fa-arrow-up" aria-hidden="true" style="color:#060"></i> --><?PHP }
			  $per_ach='';
			
			  
			  ?>
                                                      
                                                      
                                                      
                                                      </td>
 <td>
 

   <?php
   
$mnth = date('m');
$mnth =(int)$mnth;

if($mnth>3)   
{
	 $financialYear = date('Y')."-".(date('Y')+1);
}
else
{
	 $financialYear = (date('Y')-1)."-".(date('Y'));
}
  
     $monthTarget = mysql_query("select target from targets where employeeId = '". $employee['id'] ."' and financialMonth = '". date('m') ."' and financialYear = '". $financialYear ."'"); 
	 
   
   if(mysql_num_rows($monthTarget)>0)
   {
       $monthTarget = mysql_fetch_array($monthTarget);
	   $monthTarget = $monthTarget['target'];	   
   }
   else
   {
	   $monthTarget = 0;
   }
   echo $monthTarget;
    $Monthly_target =  $Monthly_target+$monthTarget;
   ?> 
 </td>
 <td>
 
 <?php  
		   
   
		   
		   $like = @$currentYear.'-'.date('m').'-%';
		/* after execution of this query we get week data of daily_reports and search content is  invoiceDateTime*/   
		   // salesAchievedMonth data comes from daily_reports table
		   /*
		   $salesAchievedMonth = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.invoice, daily_reports.invoiceDateTime, customers.company from daily_reports 
		   left join customers on daily_reports.company = customers.customerId
		   where daily_reports.addedBy = '". $employee['id'] ."' and daily_reports.inv > '0' and daily_reports.invoiceDateTime like '". $like ."'");
		   
		   */
		   
		   $salesAchievedMonth = mysql_query("select col.amount as amount,cus.company, dr.reportId as reportId, inv.invoiceNumber as invoiceNumber, date_format(inv.invoiceDateTime,'%Y-%m-%d') as invoiceDateTime  from employees as emp left join daily_reports as dr on dr.addedBy=emp.id left join invoices as inv on inv.reportId=dr.reportId left join collections as col on col.invoiceId=inv.invoiceId left join customers as cus on cus.customerId=dr.company where emp.id=".$employee['id']." and MONTH(paidDate)='".date('m')."'");
		
		   
		    
		    ?>
            <div id="<?php echo 'modal-tablemonthachieved_'.$employee['id']; ?>" class="modal fade in" tabindex="-1" aria-hidden="false" style="display: none;">
              <div class="modal-backdrop fade in"></div>
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">×</span>
													</button>
													<?php echo ucfirst($employee['firstName']); echo ' - Sales Achieved';  ?>
												</div>
											</div>

											<div class="modal-body no-padding">
												<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
													<thead>
														<tr>
															<th>S.no</th>
                                                           	<th>Invoice Date</th>
                                                            <th>Invoice</th>
                                                             <th>Company</th>
                                                            <th>Value</th>
                                                          </tr>
													</thead>

													<tbody>
        <?php $achievedMonthSaleAmount = 0; $achievedSaleAmount = 0;
	if(mysql_num_rows($salesAchievedMonth)>0)
	{ $cid=1;   
	   while($salesAchieve = mysql_fetch_object($salesAchievedMonth))	
	   {
		 
		  
		  ?> <tr>
                    <td>
                    <?php echo $cid; ?>
                    </td>
                    <td><?PHP echo $salesAchieve->invoiceDateTime;?> </td>
                    <td> <?PHP echo $salesAchieve->invoiceNumber;?> </td>
                    <td><?PHP echo $salesAchieve->company;?> </td>
                    <td> <?PHP echo $salesAchieve->amount;?> </td>
								
                                
															
														</tr> <?php
	   $cid++; }
	   
	   
	   $achievedSaleAmount += $invoiceAmount;
	   
	   $grandAchievedMonthSaleAmount  =  $achievedSaleAmount+$grandAchievedMonthSaleAmount;
	}
	?>
													</tbody>
												</table>
											</div>

											<div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close
												</button>

												
											</div>
										</div><!-- /.modal-content -->
									</div><!-- /.modal-dialog -->
								</div>

<?PHP

		   
$mnth = date('m');
$mnth =(int)$mnth;

if($mnth>3)   
{
 $currentYear = date('Y');
}
else
{
 $currentYear = (date('Y')-1);
} 

// data comes from these tables collections,employees,daily_reports
$qry = mysql_query("select sum(col.amount) as total_achieved from employees as emp left join daily_reports as dr on dr.addedBy=emp.id left join invoices as inv on inv.reportId=dr.reportId left join collections as col on col.invoiceId=inv.invoiceId where emp.id=".$employee['id']." and MONTH(paidDate)='".date('m')."'" );

if(mysql_num_rows($qry)>0)
{
	$achievedamnt = mysql_fetch_object($qry);
	$achAmnt = $achievedamnt->total_achieved;
}
else
	$achAmnt=0;

	$Monthlyachieved=$Monthlyachieved+$achAmnt;

?>
              <a href="<?php echo '#modal-tablemonthachieved_'.$employee['id']; ?>" role="button" class="green" data-toggle="modal"><?php if($achAmnt!=''){ echo $achAmnt; } else echo "0";?></a>                                           
</td>

   <td>
   <?PHP 
   echo ($monthTarget-$achAmnt); 
   $monthly_dwnfall = $monthly_dwnfall+($monthTarget-$achAmnt); 

   
   	//$Monthly_target,$Monthlyachieved$monthly_dwnfall,$monthly_ach_percentage 
   
   ?>
   </td>
   
    <td>
   <?PHP 
   		echo round(($achAmnt/$monthTarget)*100,3);
		$monthly_ach_percentage = $monthly_ach_percentage+round(($achAmnt/$monthTarget)*100,3);
   ?>
   %
   </td>
   
      <td>
	  
	  
	  <?php
	  $monthTargets = mysql_query("select target from targets where employeeId = '". $employee['id'] ."' and financialYear = '". $financialYear ."'"); 
   
   if(mysql_num_rows($monthTargets)>0)
   {
  
  $monthTarget = 0;
    while($row = mysql_fetch_array($monthTargets))
	{
	    $monthTarget += $row['target'];	   
	}
   }
   else
   {
	   $monthTarget = 0;
   }
   
   $grandAnnualTarget += $monthTarget;
   echo $monthTarget;
	$Annualtarget = $Annualtarget+$monthTarget
	
	

   
   ?> </td>
   
<td>
           <?php  
		   
/*
           $like1 = $financialYear1.'-%';
		   $like2 = $financialYear2.'-%';
		   
*/

$mnth = date('m');
$mnth =(int)$mnth;

if($mnth>3)   
{
 $like1 = (date('Y')).'-%';
 $like2 = (date('Y')+1).'-%';
}
else
{
  $like1 = (date('Y')-1).'-%';
  $like2 = (date('Y')).'-%';

} 
/* after execution of this query we get week data of daily_reports and search content is  invoiceDateTime*/
		 // data comes from these tables collections,customers,daily_reports  
		   $salesAchievedMonth = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.invoice, daily_reports.invoiceDateTime, customers.company from daily_reports 
		   left join customers on daily_reports.company = customers.customerId
		   where daily_reports.addedBy = '". $employee['id'] ."' and daily_reports.inv > '0' and (daily_reports.invoiceDateTime like '". $like1 ."' OR daily_reports.invoiceDateTime like '". $like2 ."')");
		   
		   
		   
		   
		    ?>
            <div id="<?php echo 'modal-tableann_ach_'.$employee['id']; ?>" class="modal fade in" tabindex="-1" aria-hidden="false" style="display: none;">
              <div class="modal-backdrop fade in"></div>
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">×</span>
													</button>
													<?php echo ucfirst($employee['firstName']); echo ' - Sales Achieved'; ?>
												</div>
											</div>

											<div class="modal-body no-padding">
												<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
													<thead>
														<tr>
															<th>S.no</th>
                                                           	<th>Invoice Date</th>
                                                            <th>Invoice</th>
                                                             <th>Company</th>
                                                            <th>Value </th>
                                                          </tr>
													</thead>

													<tbody>
        <?php $achievedMonthSaleAmount = 0; $achievedSaleAmount = 0;
	if(mysql_num_rows($salesAchievedMonth)>0)
	{ $cid=1;   
	   while($salesAchieve = mysql_fetch_array($salesAchievedMonth))	
	   {
		 /* $salesResult = mysql_query("select daily_reports_data.amount from daily_reports_revision 
		   left join daily_reports_data on daily_reports_revision.revisionId = daily_reports_data.revisionId
		   where daily_reports_revision.reportId = '". $salesAchieve['reportId'] ."' and daily_reports_revision.revision = '". $salesAchieve['currentRevisionId'] ."'");
		   
		  
		   */
		   
		    $salesResult = mysql_query("select total from invoices where reportId = '". $salesAchieve['reportId'] ."'");
		   
		   
		   
		   
		   $invoiceAmount = 0;
		   while($salesRow = mysql_fetch_array($salesResult))
		   {
		       $achievedMonthSaleAmount = $salesRow['total']+$achievedMonthSaleAmount;  
			   $invoiceAmount = $salesRow['total']+$invoiceAmount;  
		   }
		  ?> <tr>
                 <td>
			<?php echo $cid; ?>
										</td>
								<td> <?php $invoiceDate = explode(' ',$salesAchieve['invoiceDateTime']);
							              $invoiceDate = explode('-',$invoiceDate[0]);
								     	  echo $invoiceDate[2].'-'.$invoiceDate[1].'-'.$invoiceDate[0];
								 ?></td>
                                 <td><?php echo $salesAchieve['invoice']; ?></td>
                                <td><?php echo $salesAchieve['company']; ?></td>
                                <td><?php echo $invoiceAmount; ?></td>
                               
															
														</tr> <?php
	   $cid++; }
	   
	   $achievedSaleAmount += $invoiceAmount;
	   $grandAchievedMonthSaleAmount  =  $achievedSaleAmount+$grandAchievedMonthSaleAmount;
	   $grandAnnualAchieved += $achievedSaleAmount;
	   
	   
	}
	
	
	?>
													</tbody>
												</table>
											</div>

											<div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close
												</button>

												
											</div>
										</div><!-- /.modal-content -->
									</div><!-- /.modal-dialog -->
								</div>
            
<a href="<?php echo '#modal-tableann_ach_'.$employee['id']; ?>" role="button" class="green" data-toggle="modal">
<?php 

	echo $achievedSaleAmount; 
	$Annualachieved = $Annualachieved+$achievedSaleAmount;
	

?>
</a>


                                                      
                                                      </td>

<td>
<?PHP 
		echo $monthTarget-$achievedSaleAmount;
		$AnnualDownfall = $AnnualDownfall+($monthTarget-$achievedSaleAmount);
		

?>
</td>
  <td>
  <?PHP 
  		echo round(($achievedSaleAmount/$monthTarget)*100,3);
		$Annual_Per_Achi = ($Annual_Per_Achi)+(round(($achievedSaleAmount/$monthTarget)*100,3));
		

	?>%
</td>   
                                                      
                                                        
													</tr>
<?php 

$finalProjectedAmount = @$finalProjectedAmount+@$projectedAmount;
$finalCollectedAmount = @$finalCollectedAmount+@$collectedAmount;

$rowlist[] = @$i;
$rowlist[] = @$reportDate;
$rowlist[] = @$poDate;
$rowlist[] = @$proposal['poNo']; 
$rowlist[] = @$invoiceDate;
$rowlist[] = @$proposal['invoice']; 
$rowlist[] = @$proposal['firstName']; 
$rowlist[] = @$proposal['company']; 
$rowlist[] = @$grandTotal; 
$rowlist[] = @$collectedAmount;
$rowlist[] = @$diff;
	 
	 
	 
 $list[] = $rowlist;
 unset($rowlist);


$sno++;
  } 
  
//$Monthly_target,$Monthlyachieved$monthly_dwnfall,$monthly_ach_percentage 
  
  ?>     
  <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><?php echo $Monthly_target; ?></td><td><?php echo $Monthlyachieved; ?></td><td><?php echo $monthly_dwnfall; ?></td><td><?php echo $monthly_ach_percentage; ?>%</td>
  
  
  <td>
  <?PHP 		//$Annualtarget,$Annualachieved,$AnnualDownfall,$Annual_Per_Achi 
  	echo $Annualtarget;
  ?>
  </td>
  <td><?PHP echo $Annualachieved; ?></td>
  <td><?php echo $AnnualDownfall; ?></td>
  <td><?php echo $Annual_Per_Achi; ?>%</td>
  
  </tr>
  <?php } else { ?><tr><td colspan="11">No Data found.</td></tr> <?php }
//  if($numRecords>$limit &&  !(isset($_GET['proj_search']))) {
	  if($numRecords>$limit) {
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
  
  ?> <tr><td colspan="15">
  
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
  <td id="next_grid-pager" class="ui-pg-button ui-corner-all <?php echo $thirdlink; ?>" style="cursor: default;" <?php if($currentPage < $numPages) {  ?> onclick="goToPage('<?php echo $currentPage+1; ?>')" <?php } ?>> 
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
$fp = fopen('excel/projections.csv', 'w');

//foreach ($list as $fields) {
  //  fputcsv($fp, $fields);
//}

fclose($fp) ?>
               
                                            
                                            
										</div>
									</div>
								</div>

								<!-- PAGE CONTENT ENDS -->
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
        <script src="assets/js/date-time/bootstrap-datepicker.js"></script>
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
	  if(confirm("Confirm Delete"))
	  {
	  window.location = 'employees.php?did='+did;
	  }
  }
  
  // datepicker
			$('.date-picker').datepicker({
					autoclose: true,
					format: 'dd-mm-yyyy'
				})
 
		
		function changeMonth(cm)
		{
			window.location = 'projections.php?cm='+cm;
		}
		
		 function goToPage(pid)
		{
			var query_str = "<?PHP echo $_SERVER['QUERY_STRING'];?>";
			query_str = $.trim(query_str);
			
			if(query_str=='')
  			 window.location = 'weekly_projections.php?page='+pid;
			 else
			 {
			 	var page_chk="<?PHP echo @$_GET['page']; ?>";
				page_chk=$.trim(page_chk);
				
				if(page_chk=='')
				{
			 		window.location = 'weekly_projections.php?'+query_str+'&page='+pid;
				}
				else
				{
					$qrystr = query_str.split('&page=');
					//alert($qrystr[0]+"::"+$qrystr[1]);
					var other_qry_strngs = "<?PHP echo @$_GET['fromDate']; ?>";
					other_qry_strngs = $.trim(other_qry_strngs);
					if(other_qry_strngs=='')
					window.location = 'weekly_projections.php?page='+pid;
					else
					window.location = 'weekly_projections.php?'+$qrystr[0]+'&page='+pid;					
				}
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

<!--
select daily_reports.invoice,invoices.invoiceNumber, daily_reports.invoiceDateTime, expected_collections.amount, expected_collections.expectedDate, customers.company from expected_collections left join daily_reports on expected_collections.reportId = daily_reports.reportId left join employees on daily_reports.addedBy = employees.id left join customers on daily_reports.company = customers.customerId left join invoices on invoices.reportId=daily_reports.reportId where employees.id = '4' AND WEEKOFYEAR(expected_collections.expectedDate)=WEEKOFYEAR(NOW()) order by expected_collections.expectedId desc

select daily_reports.invoice,invoices.invoiceNumber, daily_reports.invoiceDateTime, expected_collections.amount, expected_collections.expectedDate, customers.company from expected_collections left join daily_reports on expected_collections.reportId = daily_reports.reportId left join employees on daily_reports.addedBy = employees.id left join customers on daily_reports.company = customers.customerId left join invoices on invoices.reportId=daily_reports.reportId where employees.id = '12' AND WEEKOFYEAR(expected_collections.expectedDate)=WEEKOFYEAR(NOW()) order by expected_collections.expectedId desc-->