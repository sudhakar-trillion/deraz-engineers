<?php include("includes/header.php"); 

if(isset($_GET['cm']))
 {
	 $cm = $_GET['cm']; 
 }
 else
 {  
   $cm = date('m'); 
 }

$limit = 10;

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
 
 
 if(isset($_GET['search']))
 {
	 $where = 'where ';
 $where2 = 'where ';
 
 $whereEmp = 'where ';
	 
	 if(isset($_GET['fromDate']) && strlen($_GET['fromDate'])>5)
	 {
		 $fromDate = explode('-',$_GET['fromDate']);
		 
		 
		 		
 $currentYear = $fromDate[2];
 $financialMonth = $fromDate[1];
 $previousYear = $currentYear-1;
 $nextYear = $currentYear+1;
 				
							
 if($financialMonth>3)
 {
	$financialYear = $currentYear.'-'.$nextYear;
	$financialYear1 = $currentYear;
	$financialYear2 = $nextYear;
 }
 else
 {
	 $financialYear = $previousYear.'-'.$currentYear; 
  	 $financialYear1 = $previousYear;
	 $financialYear2 = $currentYear;

 }
 
 
 
		 $fromDate = $fromDate[2].'-'.$fromDate[1].'-'.$fromDate[0];
		 $whereItems[] = "expected_collections.expectedDate > '". $fromDate ."'";
		 
		 $whereItems2[] = "collections.paidDate > '". $fromDate ."'";
	 }
	 
	 if(isset($_GET['toDate']) && strlen($_GET['toDate'])>5)
	 {
		 $toDate = explode('-',$_GET['toDate']);
		 $toDate = $toDate[2].'-'.$toDate[1].'-'.$toDate[0];
		 $whereItems[] = "expected_collections.expectedDate < '". $toDate ."'";
		 
		 $whereItems2[] = "collections.paidDate < '". $toDate ."'";
	 }
	 
	 
	 if(isset($_GET['bid']) && $_GET['bid']>0)
	 {
		
		 $whereEmpItems[] = "employees.branch = '". $_GET['bid'] ."'";
		
	 }
	 
// by employee
if(isset($_GET['eid']) && $_GET['eid']>0)
{  

	$whereItems[] = "employees.id =  '". $_GET['eid'] ."'";
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
												
												
												 if(count($whereItems2)>1)
												{ 
													$whereCondition2 = implode(' and ',$whereItems2);
													$where2 = $where2.$whereCondition2;
												}
												else if(count($whereItems2)==1)
												{   
													$whereCondition2 = $whereItems2[0];
													$where2 = $where2.$whereCondition2;
												}
												else
												{  
												  $where2 = '';	
												}
	 
 
 if(count($whereEmpItems)>1)
												{
													$whereConditionEmp = implode(' and ',$whereEmpItems);
													$whereEmp .= $whereConditionEmp;
												}
												else if(count($whereEmpItems)==1)
												{
												
													$whereConditionEmp = $whereEmpItems[0];
													$whereEmp .= $whereConditionEmp;
												
												 
												}
												else
												{  
											
												echo  $whereEmp = '';	
												
												}

$employees = mysql_query("select employees.id, employees.firstName, branches.branch, employees.email, employees.dateJoining, employees.personalMobile, rolls.roll from employees 
left join rolls on employees.roll = rolls.roll_id
left join branches on employees.branch = branches.branchId 
$where and employees.roll = '4' order by employees.firstName desc");
}
												
 else
 {

$employees = mysql_query("select employees.id, employees.firstName, branches.branch, employees.email, employees.dateJoining, employees.personalMobile, rolls.roll from employees 
left join rolls on employees.roll = rolls.roll_id
left join branches on employees.branch = branches.branchId where employees.roll = '4'
$where $where2 order by employees.firstName limit $start, $limit");
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
    <input type="submit" class="btn btn-sm btn-success" name="search">
  </div>
  
  </div>
                                
                              
                                 
                               
  
</form>                                       
                                           
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
                                                       <th colspan="3" class="center">Collection
                                                        </th>
                                                        
                                                         <th colspan="2" class="center">Orders
                                                        </th>
														  <th colspan="5" class="center">Target VS Achievements
														</th>
                                                      
													</tr>
													<tr>
														<th class="center">S.no</th>
														<th>Name</th>
														<th>Branch</th>
                                                        <th>Actual</th>
                                                        <th>Projection</th>
                                                        <th>Actual</th>
                                                        <th>Month TGT</th>
                                                        <th>Month Achieved</th>
                                                        <th>Annual TGT</th>
                                                        <th>Annual Achieved</th>
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
  
  $list[] = array('S. No', 'Name', 'Projection', 'Collected', 'Sales Projections', 'Sales Achieved');
  
  
  while($employee = mysql_fetch_array($employees))
  {
   
	
	
	$currentYear = date('Y');  
	$like = $currentYear.'-'.$cm.'-%';
	
	$projectionWhere =	$where." AND employees.id = '". $employee['id'] ."'";
	
 // projected
 	$projections = mysql_query("select daily_reports.invoice, daily_reports.invoiceDateTime, expected_collections.amount, expected_collections.expectedDate, customers.company from expected_collections
	
	left join daily_reports on expected_collections.reportId = daily_reports.reportId
	left join employees on daily_reports.addedBy = employees.id
	left join customers on daily_reports.company = customers.customerId
	$projectionWhere  order by expected_collections.expectedId desc");
	
	
	
	
  // collected
    $collectionWhere =	$where2." AND employees.id = '". $employee['id'] ."'";
	$collections = mysql_query("select daily_reports.invoice, daily_reports.invoiceDateTime, collections.amount, collections.paidDate, customers.company  from collections
	
	left join invoices on collections.invoiceId = invoices.invoiceId
	left join daily_reports on invoices.reportId = daily_reports.reportId
	left join employees on daily_reports.addedBy = employees.id
	left join customers on daily_reports.company = customers.customerId
	$collectionWhere order by collections.id desc");
	
		
	
						
						
						
						
						
 ?><tr>
														<td class="center"><?php echo $sno; ?></td>
                                                       <td><?php echo ucfirst($employee['firstName']);  ?></td>
                                                       <td><?php echo $employee['branch'];  ?></td>
													   <td>
                                                       
                                                       
													   
													   <div id="<?php echo 'modal-table1_'.$employee['id']; ?>" class="modal fade in" tabindex="-1" aria-hidden="false" style="display: none;">
              <div class="modal-backdrop fade in"></div>
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">×</span>
													</button>
													<?php echo ucfirst($employee['firstName']);
													 echo ' - Projection';  ?>
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
                                                            <th>Amount</th>
															<th>Expected Date</th>
														</tr>
													</thead>

													<tbody>
                                                    
        <?php                                            $projectedAmount = 0;
	if(mysql_num_rows($projections)>0)
	{  $pid=1;
	   while($projected = mysql_fetch_array($projections))	
	   {
		  $projectedAmount = $projected['amount']+$projectedAmount;   
	  
 
		 
		  ?> <tr>
                 <td>
			<?php echo $pid; ?>
										</td>
								<td><?php $invoiceDate = explode(' ',$projected['invoiceDateTime']);
								          $invoiceDate = explode('-',$invoiceDate[0]);
										  echo $invoiceDate[2].'-'.$invoiceDate[1].'-'.$invoiceDate[0];
								 ?></td>
                                <td><?php echo $projected['invoice']; ?></td>
                                <td><?php echo $projected['company']; ?></td>
                                <td><?php echo $projected['amount']; ?></td>
                                <td><?php $date = explode('-',$projected['expectedDate']);
								echo $date[2].'-'.$date[1].'-'.$date[0];
								 ?></td>
															
														</tr> <?php
	   $pid++; }
	} ?>
														

														
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
								
                              <a href="<?php echo '#modal-table1_'.$employee['id']; ?>" role="button" class="green" data-toggle="modal"><?php echo $projectedAmount; ?></a>

                                
                                </td>
                                <td>
                                                         
                                                        
                                                         
              <div id="<?php echo 'modal-table2_'.$employee['id']; ?>" class="modal fade in" tabindex="-1" aria-hidden="false" style="display: none;">
              <div class="modal-backdrop fade in"></div>
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">×</span>
													</button>
													<?php echo ucfirst($employee['firstName']); echo ' - Collection';  ?>
												</div>
											</div>

											<div class="modal-body no-padding">
												<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
													<thead>
														<tr>
															<th>S.no</th>
                                                            <th>Invoice Date</th>
                                                            <th>Invoice</th>
                                                            <th>Customer</th>
															<th>Amount</th>
															<th>Paid Date</th>
														</tr>
													</thead>

													<tbody>
        <?php                                            $collectedAmount = 0;
	if(mysql_num_rows($collections)>0)
	{ $cid=1;
	   while($collected = mysql_fetch_array($collections))	
	   {
		  $collectedAmount = $collected['amount']+$collectedAmount;   
		  ?> <tr>
                 <td>
			<?php echo $cid; ?>
										</td>
								<td><?php $invoiceDate = explode(' ',$collected['invoiceDateTime']);
								          $invoiceDate = explode('-',$invoiceDate[0]);
										  echo $invoiceDate[2].'-'.$invoiceDate[1].'-'.$invoiceDate[0];
								 ?></td>
                                <td><?php echo $collected['invoice']; ?></td>
                                <td><?php echo $collected['company']; ?></td>
                                <td><?php echo $collected['amount']; ?></td>
                                <td><?php $date = explode('-',$collected['paidDate']);
								echo $date[2].'-'.$date[1].'-'.$date[0];
								 ?></td>
															
														</tr> <?php
	   $cid++; }
	} ?>
														

														
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
                                         
                                      
                                         
      <a href="<?php echo '#modal-table2_'.$employee['id']; ?>" role="button" class="green" data-toggle="modal"><?php echo $collectedAmount; ?></a>
                                                          </td>
                                                      
                                                      <td>
           <?php  
		
		   $salesExpected = mysql_query("select expected_sales.expectedValue, expected_sales.expectedDate, customers.company from expected_sales 
		   left join customers on expected_sales.customerId = customers.customerId
		   where expected_sales.employeeId = '". $employee['id'] ."' and expected_sales.expectedDate like '". $like ."'");
		   
		   
		    ?>
            <div id="<?php echo 'modal-table3_'.$employee['id']; ?>" class="modal fade in" tabindex="-1" aria-hidden="false" style="display: none;">
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
	if(mysql_num_rows($salesExpected)>0)
	{ $cid=1;
	   while($salesExpect = mysql_fetch_array($salesExpected))	
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
            
              <a href="<?php echo '#modal-table3_'.$employee['id']; ?>" role="button" class="green" data-toggle="modal"><?php echo $expectedSaleAmount; ?></a>                                           
                                                      
                                                      
                                                      
                                                      </td>
                                                      
                                                     <td>
           <?php  
		   $salesAchieved = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.invoice, daily_reports.invoiceDateTime, customers.company from daily_reports 
		   left join customers on daily_reports.company = customers.customerId
		   where daily_reports.addedBy = '". $employee['id'] ."' and daily_reports.inv > '0' and daily_reports.invoiceDateTime like '". $like ."'");
		   
		   
		   
		   
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
                                                             <th>Company</th>
                                                            <th>Value</th>
                                                          </tr>
													</thead>

													<tbody>
        <?php $achievedSaleAmount = 0;
	if(mysql_num_rows($salesAchieved)>0)
	{ $cid=1;   
	   while($salesAchieve = mysql_fetch_array($salesAchieved))	
	   {
		 /* $salesResult = mysql_query("select daily_reports_data.amount from daily_reports_revision 
		   left join daily_reports_data on daily_reports_revision.revisionId = daily_reports_data.revisionId
		   where daily_reports_revision.reportId = '". $salesAchieve['reportId'] ."' and daily_reports_revision.revision = '". $salesAchieve['currentRevisionId'] ."'");
		   
		  
		   */
		   
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
            
              <a href="<?php echo '#modal-table4_'.$employee['id']; ?>" role="button" class="green" data-toggle="modal"><?php echo $achievedSaleAmount; ?></a>                                           
                                                      
                                                      
                                                      
                                                      </td>
 <td>
 

   <?php
   
   
  
     $monthTarget = mysql_query("select target from targets where employeeId = '". $employee['id'] ."' and financialMonth = '". $financialMonth ."' and financialYear = '". $financialYear ."'"); 
   
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
   ?> 
 </td>
 <td>
 
 <?php  
		   
           $like = $currentYear.'-'.$financialMonth.'-%';
		   $salesAchievedMonth = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.invoice, daily_reports.invoiceDateTime, customers.company from daily_reports 
		   left join customers on daily_reports.company = customers.customerId
		   where daily_reports.addedBy = '". $employee['id'] ."' and daily_reports.inv > '0' and daily_reports.invoiceDateTime like '". $like ."'");
		   
		   
		   
		   
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
                                                             <th>Company</th>
                                                            <th>Value</th>
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
								<td><?php $invoiceDate = explode(' ',$salesAchieve['invoiceDateTime']);
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
            
              <a href="<?php echo '#modal-table4_'.$employee['id']; ?>" role="button" class="green" data-toggle="modal"><?php echo $achievedSaleAmount; ?></a>                                           
                                                      
                                                      
                                                      
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
   ?> </td>
                                                      <td>
           <?php  
		   
           $like1 = $financialYear1.'-%';
		   $like2 = $financialYear2.'-%';
		   $salesAchievedMonth = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.invoice, daily_reports.invoiceDateTime, customers.company from daily_reports 
		   left join customers on daily_reports.company = customers.customerId
		   where daily_reports.addedBy = '". $employee['id'] ."' and daily_reports.inv > '0' and (daily_reports.invoiceDateTime like '". $like1 ."' OR daily_reports.invoiceDateTime like '". $like2 ."')");
		   
		   
		   
		   
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
                                                             <th>Company</th>
                                                            <th>Value</th>
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
								<td><?php $invoiceDate = explode(' ',$salesAchieve['invoiceDateTime']);
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
            
              <a href="<?php echo '#modal-table4_'.$employee['id']; ?>" role="button" class="green" data-toggle="modal"><?php echo $achievedSaleAmount; ?></a>                                           
                                                      
                                                      
                                                      
                                                      </td>
                                                      
                                                        
													</tr>
<?php 

$finalProjectedAmount = $finalProjectedAmount+$projectedAmount;
$finalCollectedAmount = $finalCollectedAmount+$collectedAmount;

$rowlist[] = $i;
$rowlist[] = $reportDate;
$rowlist[] = $poDate;
$rowlist[] = $proposal['poNo']; 
$rowlist[] = $invoiceDate;
$rowlist[] = $proposal['invoice']; 
$rowlist[] = $proposal['firstName']; 
$rowlist[] = $proposal['company']; 
$rowlist[] = $grandTotal; 
$rowlist[] = $collectedAmount;
$rowlist[] = $diff;
	 
	 
	 
 $list[] = $rowlist;
 unset($rowlist);


$sno++;
  } 
  
  ?>     
  <tr><td></td><td></td><td></td><td><?php echo $finalProjectedAmount; ?></td><td><?php echo $finalCollectedAmount; ?></td><td><?php echo $grandExpectedSaleAmount; ?></td>
  <td><?php echo $grandAchievedSaleAmount; ?></td>
  
  
  <td></td>
  <td></td>
  <td><?php echo $grandAnnualTarget; ?></td>
  <td><?php echo $grandAnnualAchieved; ?></td>
  </tr>
  <?php } else { ?><tr><td colspan="10">No Data found.</td></tr> <?php }
  if($numRecords>$limit &&  !(isset($_GET['search']))) {
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
  
  ?> <tr><td colspan="10">
  
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
   window.location = 'weekly_projections.php?page='+pid;	
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
