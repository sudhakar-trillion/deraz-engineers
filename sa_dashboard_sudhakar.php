<?php include("includes/sa_header.php"); 

if(isset($_GET['cm']))
 {
	 $cm = $_GET['cm']; 
 }
 else
 {  
   $cm = date('m'); 
 }

$currentYear = date('Y');
$like = $currentYear.'-'.$cm.'-%';



// team
  // team
	  $myteam = mysql_query("select teamId from teams where teamLeaderId = '". $_SESSION['id'] ."'");
	  
	
	 
	  if(mysql_num_rows($myteam)>0)
	  {
		    $myteam = mysql_fetch_array($myteam);
	    $teamMembers = mysql_query("select employees.id, employees.firstName  from team_members 
								  left join employees on team_members.memberId = employees.id
								  where team_members.teamId = '". $myteam['teamId'] ."'
								  order by team_members.teamMemberId desc");
								  
								  if(mysql_num_rows($teamMembers)>0)
								  {
								  while($teamMember = mysql_fetch_array($teamMembers))
								  {
									
									 $team_members[] = $teamMember['id'];
								  }
								  }
	  }

// No. of new calls
//$newCalls = mysql_query("select * from customers where addedBy = '". $_SESSION['id'] ."' and dateTime like '$like'");
//$numNewCalls = mysql_num_rows($newCalls);




// offres sent
    $where = 'where ';
    $whereItems[] = "daily_reports.offer = '1'";
    $whereItems[] = "daily_reports.reportDate like '$like'";
  if(isset($_GET['tmid']))
						{  
									
						if($_GET['tmid']>0)
						{
	                        $whereItems[] = "daily_reports.addedBy = '". $_GET['tmid'] ."'";				
							
						}/*
						else 
						{ 
						   // all	
						$in_team_members = implode(', ',$team_members);
						$in_team_members = '('.$in_team_members.')';
						   
						$whereItems[] = "daily_reports.addedBy in ".$in_team_members;		
						   
						}
						*/
									
					 } else
							{   // logged in user
								$whereItems[] = "daily_reports.addedBy = '". $_SESSION['id'] ."'";		
								
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
												
												
$offers = mysql_query("select reportId from daily_reports $where");
//echo "select reportId from daily_reports $where"; exit; 
$numOffers = mysql_num_rows($offers);

if(isset($_GET['tmid']) && $_GET['tmid']>0)
{
	//get the employee name
	$qry = mysql_query("select firstName from employees where id=".$_GET['tmid']);
	if(mysql_num_rows($qry)>0)
	{
		$empl = mysql_fetch_object($qry);
		$Emply_ide = $_GET['tmid'];
		$EmployeeName = $empl->firstName;

	}
	else
	{
		$Emply_ide = $_SESSION['id'];
		$EmployeeName = $_SESSION['firstName'];
	}
	
	$enquiries = mysql_query("select * from enquiries
	left join enquiry_assign on enquiries.enquiryId= enquiry_assign.enquiryId join employees as empl on empl.id='".$Emply_ide."'
	where enquiry_assign.assignedTo = '".$EmployeeName."' order by enquiries.enquiryId desc");
	$enq_link="$Emply_ide";
	
}
else
{
	$enquiries = mysql_query("select * from enquiries
	left join enquiry_assign on enquiries.enquiryId= enquiry_assign.enquiryId join employees as empl on empl.id='". $_SESSION['id'] ."'
	where enquiry_assign.assignedTo = '". $_SESSION['firstName'] ."' order by enquiries.enquiryId desc");
	$enq_link="";
}

$numEnquiries = mysql_num_rows($enquiries);


if($numOffers>0)
{
	while($row = mysql_fetch_array($offers))
	
	{
		$reportIds[] = $row['reportId'];
	}
}
unset($whereItems);



// Po Collected
$where = 'where ';
    $whereItems[] = "daily_reports.po = '1'";
    $whereItems[] = "daily_reports.poDateTime like '$like'";
  if(isset($_GET['tmid']))
									{  
									
						if($_GET['tmid']>0)
						{
	                        $whereItems[] = "daily_reports.addedBy = '". $_GET['tmid'] ."'";				
							
						}
						else 
						{ 
						   // all	
						$in_team_members = implode(', ',$team_members);
						$in_team_members = '('.$in_team_members.')';
						   
						$whereItems[] = "daily_reports.addedBy in ".$in_team_members;		
						   
						}
									
					 } else
							{   // logged in user   
								$whereItems[] = "daily_reports.addedBy = '". $_SESSION['id'] ."'";		
								
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
												
$pos = mysql_query("select reportId from daily_reports $where");
$numPos = mysql_num_rows($pos);
unset($whereItems);

// Orders Raised
$where = 'where ';
  //  $whereItems[] = "daily_reports.inv = '2'";
    $whereItems[] = "invoices.invoiceDateTime like '$like'";
  if(isset($_GET['tmid']))
									{  
									
						if($_GET['tmid']>0)
						{
	                        $whereItems[] = "daily_reports.addedBy = '". $_GET['tmid'] ."'";				
							
						}
						else 
						{ 
						   // all	
						$in_team_members = implode(', ',$team_members);
						$in_team_members = '('.$in_team_members.')';
						   
						$whereItems[] = "daily_reports.addedBy in ".$in_team_members;		
						   
						}
									
					 } else
							{   // logged in user   
								$whereItems[] = "daily_reports.addedBy = '". $_SESSION['id'] ."'";		
								
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
							
$ordersRaised = mysql_query("select invoices.invoiceId from invoices left join daily_reports on invoices.reportId = daily_reports.reportId  $where");
$numOrdersRaised = mysql_num_rows($ordersRaised);
unset($whereItems);

// orders lost
$where = 'where ';
    $whereItems[] = "invoices.invoiceStatus = 'Order Lost'";
    $whereItems[] = "invoices.lostDateTime like '$like'";
  if(isset($_GET['tmid']))
									{  
									
						if($_GET['tmid']>0)
						{
	                        $whereItems[] = "daily_reports.addedBy = '". $_GET['tmid'] ."'";				
							
						}
						else 
						{ 
						   // all	
						$in_team_members = implode(', ',$team_members);
						$in_team_members = '('.$in_team_members.')';
						   
						$whereItems[] = "daily_reports.addedBy in ".$in_team_members;		
						   
						}
									
					 } else
							{   // logged in user   
								$whereItems[] = "daily_reports.addedBy = '". $_SESSION['id'] ."'";		
								
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

//echo "select count(reportId) from daily_reports where daily_reports.addedBy = '". $_SESSION['id'] ."' and leadStatus='Order Lost'"; exit; 
$ordersLost = mysql_query("select count(reportId) orderLost from daily_reports where daily_reports.addedBy = '".$Emply_ide."' and leadStatus='Order Lost'");

if(mysql_num_rows($ordersLost)>0)
{
	$lost = mysql_fetch_object($ordersLost);
	$numOrdersLost = $lost->orderLost;
}
else
$numOrdersLost = "0";

unset($whereItems);

// orders Pending
// inv = '2' and leadStatus != 'Order Closed' and invoiceDateTime LIKE '$like' and addedBy = '". $_SESSION['id'] ."'
$where = 'where ';
    $whereItems[] = "invoices.invoiceStatus != 'Order Closed'";
    $whereItems[] = "invoices.invoiceDateTime like '$like'";
  if(isset($_GET['tmid']))
									{  
									
						if($_GET['tmid']>0)
						{
	                        $whereItems[] = "daily_reports.addedBy = '". $_GET['tmid'] ."'";				
							
						}
						else 
						{ 
						   // all	
						$in_team_members = implode(', ',$team_members);
						$in_team_members = '('.$in_team_members.')';
						   
						$whereItems[] = "daily_reports.addedBy in ".$in_team_members;		
						   
						}
									
					 } else
							{   // logged in user   
								$whereItems[] = "daily_reports.addedBy = '". $_SESSION['id'] ."'";		
								
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

$ordersPending = mysql_query("select invoices.invoiceId from invoices left join daily_reports on invoices.reportId = daily_reports.reportId  $where");
$numOrdersPending = mysql_num_rows($ordersPending);
unset($whereItems);

// orders Closed
// leadStatus = 'Order Closed' and closedDateTime LIKE '$like' and addedBy = '". $_SESSION['id'] ."'
$where = 'where ';
  
	$whereItems[] = "invoices.invoiceStatus = 'Order Closed'";
    $whereItems[] = "invoices.closedDateTime like '$like'";
  if(isset($_GET['tmid']))
									{  
									
						if($_GET['tmid']>0)
						{
	                        $whereItems[] = "daily_reports.addedBy = '". $_GET['tmid'] ."'";				
							
						}
						else 
						{ 
						   // all	
						$in_team_members = implode(', ',$team_members);
						$in_team_members = '('.$in_team_members.')';
						   
						$whereItems[] = "daily_reports.addedBy in ".$in_team_members;		
						   
						}
									
					 } else
							{   // logged in user   
								$whereItems[] = "daily_reports.addedBy = '". $_SESSION['id'] ."'";		
								
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
  
$ordersClosed = mysql_query("select invoices.invoiceId from invoices left join daily_reports on invoices.reportId = daily_reports.reportId  $where");
$numOrdersClosed= mysql_num_rows($ordersClosed);
unset($whereItems);

// employees
$employees = mysql_query("select id from employees where roll  > '1'");
$numEmployees = mysql_num_rows($employees);


// complaints recieved
$complaints = mysql_query("select serviceId from services where date LIKE '$like'");
$numComplaints = mysql_num_rows($complaints);

 $complaintsClosed = 0;
 $complaintsPending = 0;
 
 while($complaint = mysql_fetch_array($complaints))
 {
	
	$result = mysql_query("select * from service_status where status = 'closed' and serviceId = '". $complaint['serviceId'] ."'");
	
	if(mysql_num_rows($result)>0)
	{
		 $complaintsClosed++;
	}
	else
	{
		 $complaintsPending++;
	}
	
	 
 }

// expected collection
if(count($reportIds)>0)
{

$reportIds = implode(',',$reportIds);	
$reportIds = '('.$reportIds.')';



$result = mysql_query("select amount from  expected_collections where reportId IN $reportIds and expectedDate  like '$like'");

 $expectedCollection = 0;
 if(mysql_num_rows($result)>0)
 {
	 while($row = mysql_fetch_array($result))
	 {
		$expectedCollection = $row['amount']+$expectedCollection; 
	 }
	 
 }
 
// achieved collection

$result = mysql_query("select amount from collections where invoiceId IN $reportIds and paidDate  like '$like'");

 $achievedCollection = 0;
 if(mysql_num_rows($result)>0)
 {
	 while($row = mysql_fetch_array($result))
	 {
		$achievedCollection = $row['amount']+$achievedCollection; 
	 }
	 
 }
}
else
{
	 $achievedCollection = 0;
	  $expectedCollection = 0;
}
 
// order projection
$result = mysql_query("select expectedValue from expected_sales where  expectedDate  like '$like' and employeeId = '". $_SESSION['id'] ."'");

 $orderProjection = 0;
 if(mysql_num_rows($result)>0)
 {
	 while($row = mysql_fetch_array($result))
	 {
		$orderProjection = $row['expectedValue']+$orderProjection; 
	 }
	 
 }  
 

// order achieved
// inv > '0' and reportDate like '$like' and addedBy = '". $_SESSION['id'] ."'
$where = 'where ';
  
	$whereItems[] = "daily_reports.inv = '2'";
    $whereItems[] = "daily_reports.reportDate like '$like'";
  if(isset($_GET['tmid']))
									{  
									
						if($_GET['tmid']>0)
						{
	                        $whereItems[] = "daily_reports.addedBy = '". $_GET['tmid'] ."'";				
							
						}
						else 
						{ 
						   // all	
						$in_team_members = implode(', ',$team_members);
						$in_team_members = '('.$in_team_members.')';
						   
						$whereItems[] = "daily_reports.addedBy in ".$in_team_members;		
						   
						}
									
					 } else
							{   // logged in user   
								$whereItems[] = "daily_reports.addedBy = '". $_SESSION['id'] ."'";		
								
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

  
$result = mysql_query("select reportId, currentRevisionId from daily_reports $where");
 $subTotalOrderAchieved = 0;
 if(mysql_num_rows($result)>0)
 {
	 while($row = mysql_fetch_array($result))
	 {
		
		 $amountResult = mysql_query("select grandTotal from daily_reports_revision  where daily_reports_revision.reportId = '". $row['reportId'] ."' and daily_reports_revision.revision = '". $row['currentRevisionId'] ."'");
		 
		 if(mysql_num_rows($amountResult)>0)
		 {
			 while($amountRow = mysql_fetch_array($amountResult))
			 {
				  $subTotalOrderAchieved = $amountRow['grandTotal']+$subTotalOrderAchieved; 
				
			
			 }
		 }
		
	 }
	 
 }   
 unset($whereItems);


//Target
// targets

 $currentYear = date('Y');
 $previousYear = $currentYear-1;
 $nextYear = $currentYear+1;
 if($cm>3)
 {
	$financialYear = $currentYear.'-'.$nextYear;
 }
 else
 {
	 $financialYear = $previousYear.'-'.$currentYear; 
 }


// employeeId = '". $_SESSION['id'] ."'  and  financialMonth = '". $cm ."' and financialYear = '". $financialYear ."'
$where = 'where ';
  
	$whereItems[] = "targets.financialMonth = '". $cm ."'";
    $whereItems[] = "targets.financialYear = '". $financialYear ."'";
  if(isset($_GET['tmid']))
									{  
									
						if($_GET['tmid']>0)
						{
	                        $whereItems[] = "targets.employeeId = '". $_GET['tmid'] ."'";				
							
						}
						else 
						{ 
						   // all	
						$in_team_members = implode(', ',$team_members);
						$in_team_members = '('.$in_team_members.')';
						   
						$whereItems[] = "targets.employeeId in ".$in_team_members;		
						   
						}
									
					 } else
							{   // logged in user   
								$whereItems[] = "targets.employeeId = '". $_SESSION['id'] ."'";		
								
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
   
$targets = mysql_query("select target from targets $where");
$grandTarget = 0;
 if(mysql_num_rows($targets)>0)
 {
	 while($target = mysql_fetch_array($targets))
	 {
		$grandTarget = $target['target']+$grandTarget; 
	 }
	 
 }
unset($whereItems);
 
	 

// outstanding
/*$grandOutstanding  = 0;


$result = mysql_query("select reportId, currentRevisionId from daily_reports where inv > '0' and paymentStatus = 'open' and reportDate  like '$like'");

 if(mysql_num_rows($result)>0)
 {
   while($row = mysql_fetch_array($result))
   {
	  // total amount;
	 
	  $totalAmount = mysql_query("select daily_reports_data.amount from daily_reports_revision
  left join daily_reports_data on daily_reports_revision.revisionId = daily_reports_data.revisionId
  where daily_reports_revision.reportId = '". $row['reportId'] ."' and daily_reports_revision.revision = '". $row['currentRevisionId'] ."'");
 
  $grandTotal = 0;
 if(mysql_num_rows($totalAmount)>0)
  {
	  
	 while($total = mysql_fetch_array($totalAmount))
	 {
		$grandTotal = $total['amount']+$grandTotal; 
	 }
	   
  }

  // pending amount
  $collectedAmount = mysql_query("select collections.amount from collections                     
                        left join daily_reports on collections.invoiceId = daily_reports.reportId
						where collections.invoiceId = '". $row['reportId'] ."'
						order by collections.id");	
						
						 $grandCollected = 0;
 if(mysql_num_rows($collectedAmount)>0)
  {
	  
	 while($total = mysql_fetch_array($collectedAmount))
	 {
		$grandCollected = $total['amount']+$grandCollected; 
	 }
	   
  }
  
  $pending = $grandTotal-$grandCollected;
  
   $grandOutstanding =  $grandOutstanding+$pending;
   
   }
 }*/
 
 // inv = '2' and paymentStatus = 'open' and reportDate  like '$like' and addedBy = '". $_SESSION['id'] ."'
 $grandOutstanding  = 0;
 $grandCollectionRecieved = 0;

$where = 'where ';
    $whereItems[] = "invoices.paymentStatus = 'open'";
	$whereItems[] = "invoices.invoiceDateTime like '$like'";
  if(isset($_GET['tmid']))
									{  
									
						if($_GET['tmid']>0)
						{
	                        $whereItems[] = "daily_reports.addedBy = '". $_GET['tmid'] ."'";				
							
						}
						else 
						{ 
						   // all	
						$in_team_members = implode(', ',$team_members);
						$in_team_members = '('.$in_team_members.')';
						   
						$whereItems[] = "daily_reports.addedBy in ".$in_team_members;		
						   
						}
									
					 } else
							{   // logged in user   
								$whereItems[] = "daily_reports.addedBy = '". $_SESSION['id'] ."'";		
								
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
										
$result = mysql_query("select invoices.invoiceId, invoices.grandTotal from invoices left join daily_reports on invoices.reportId = daily_reports.reportId $where");

unset($whereItems);

 if(mysql_num_rows($result)>0)
 {
   while($row = mysql_fetch_array($result))
   {
	 

  $invoiceAmount = $row['grandTotal']+$invoiceAmount; 
  // pending amount
  $collectedAmount = mysql_query("select collections.amount from collections                     
                      	where collections.invoiceId = '". $row['invoiceId'] ."'
						order by collections.id");	
						
						 $invoiceCollected = 0;
 if(mysql_num_rows($collectedAmount)>0)
  {
	  
	 while($total = mysql_fetch_array($collectedAmount))
	 {
		$invoiceCollected = $total['amount']+$invoiceCollected; 
	 }
	   
  }
  
 // echo $invoiceAmount; 
  //  echo $invoiceCollected; 
  
   
   $grandCollectionRecieved = $grandCollectionRecieved+$invoiceCollected;

   $pending = $invoiceAmount-$invoiceCollected;
  
   $grandOutstanding =  $grandOutstanding+$pending;
   
   
   
   }
 }



// collection of the Month

$monthCollectionRecieved = 0;
    $where = 'where ';
    $whereItems[] = "collections.paidDate like  '". $like ."'";
	if(isset($_GET['tmid']))
									{  
									
						if($_GET['tmid']>0)
						{
	                        $whereItems[] = "daily_reports.addedBy = '". $_GET['tmid'] ."'";				
							
						}
						else 
						{ 
						   // all	
						$in_team_members = implode(', ',$team_members);
						$in_team_members = '('.$in_team_members.')';
						   
						$whereItems[] = "daily_reports.addedBy in ".$in_team_members;		
						   
						}
									
					 } else
							{   // logged in user   
								$whereItems[] = "daily_reports.addedBy = '". $_SESSION['id'] ."'";		
								
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
										
$collectedAmount = mysql_query("select collections.amount from collections      
             left join invoices on collections.invoiceId = invoices.invoiceId
			 left join daily_reports on invoices.reportId = daily_reports.reportId               
                      	$where 
						order by collections.id");	
						
						if(mysql_num_rows($collectedAmount)>0)
						{
						   while($collected_amount = mysql_fetch_array($collectedAmount))
						   {
							   $monthCollectionRecieved = $collected_amount['amount']+$monthCollectionRecieved; 
						   }
						}
unset($whereItems);



?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Dashboard </li>
						</ul><!-- /.breadcrumb -->

						<!-- #section:basics/content.searchbox -->
						<div class="nav-search" id="nav-search">
                        <form class="form-search">
                        
      <?php                       
                               // team
	  $myteam = mysql_query("select teamId from teams where teamLeaderId = '". $_SESSION['id'] ."'");
	  /* commented by sudhakar please uncomment this section once you decided to implement the team concept
	  if(mysql_num_rows($myteam)>0) {
	  $myteam = mysql_fetch_array($myteam);
	  $teamMembers = mysql_query("select employees.id, employees.firstName  from team_members 
                          left join employees on team_members.memberId = employees.id
						  where team_members.teamId = '". $myteam['teamId'] ."'
						  order by team_members.teamMemberId desc");
      ?>                    <span class="input-icon">
									 <select class="nav-search-input" onchange="changeMonth('<?php echo $cm; ?>',this.value)" id="tmid">
<!--<option value="<?php echo $_SESSION['id']; ?>" <?php if(isset($_GET['tmid']) && $_SESSION['id']==$_GET['tmid']) { ?> selected="selected" <?php } ?>><?php echo $_SESSION['firstName']; ?></option>-->
<option value="" <?php if(isset($_GET['tmid']) && $_GET['tmid']==0) { ?> selected="selected" <?php } ?>>Select Team Member</option>
                                    <?php while($teamMember = mysql_fetch_array($teamMembers))
								{ ?>
<option value="<?php echo $teamMember['id']; ?>" <?php if(isset($_GET['tmid']) && $teamMember['id']==$_GET['tmid']) { ?> selected="selected" <?php } ?>><?php echo $teamMember['firstName']; ?></option>
          
                                      <?php } ?>
                                    </select> 
                              </span>
                              <?php } */ ?>
                                     
								<span class="input-icon">
									 <select class="nav-search-input" onchange="changeMonth(this.value,'<?php echo $_GET['tmid']; ?>')" id="month">
                                <option value="01" <?php if($cm=='01') { echo 'selected="selected"'; } ?>>January</option>
                                <option value="02" <?php if($cm=='02') { echo 'selected="selected"'; } ?>>Febraury</option>
                                <option value="03" <?php if($cm=='03') { echo 'selected="selected"'; } ?>>March</option>
                                <option value="04" <?php if($cm=='04') { echo 'selected="selected"'; } ?>>April</option>
                                <option value="05" <?php if($cm=='05') { echo 'selected="selected"'; } ?>>May</option>
                                <option value="06" <?php if($cm=='06') { echo 'selected="selected"'; } ?>>June</option>
                                <option value="07" <?php if($cm=='07') { echo 'selected="selected"'; } ?>>July</option>
                                <option value="08" <?php if($cm=='08') { echo 'selected="selected"'; } ?>>August</option>
                                <option value="09" <?php if($cm=='09') { echo 'selected="selected"'; } ?>>September</option>
                                <option value="10" <?php if($cm=='10') { echo 'selected="selected"'; } ?>>October</option>
                                <option value="11" <?php if($cm=='11') { echo 'selected="selected"'; } ?>>November</option>
                                <option value="12" <?php if($cm=='12') { echo 'selected="selected"'; } ?>>December</option>
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
								
                            
                            

								<div class="row">
									<div class="col-xs-12">
                                    
                                    
                                    <div class="col-sm-10 infobox-container">
										<!-- #section:pages/dashboard.infobox -->
                                        
                                    
                                    
                                    <div class="infobox infobox-green" style="float:left;">
											
                                            

											<div class="infobox-data" style="padding-left:15px;">
												<span class="infobox-data-number"><?php echo $numEnquiries; ?></span>
												<?PHP 
												if($Emply_ide!='')
												{ 
												?>
                                                <a href="sa_enquiries.php?tmid=<?PHP echo $Emply_ide; ?>"><div class="infobox-content">Enquiries</div></a>
                                                <?PHP
												} else{?>
                                                <a href="sa_enquiries.php"><div class="infobox-content">Enquiries</div></a>
                                                <?PHP } ?>
											</div>

											<!-- #section:pages/dashboard.infobox.stat -->
											

											<!-- /section:pages/dashboard.infobox.stat -->
										</div>    
                                        
                                        
										<div class="infobox infobox-green" style="float:left;">
											
                                            

											<div class="infobox-data" style="padding-left:15px;">
												<span class="infobox-data-number"><?php echo $numOffers; ?></span>
												<?PHP if(isset($_GET['tmid']) && $_GET['tmid']>0){ ?>
                                                <a href="sa_offers.php?tmid=<?PHP echo trim($_GET['tmid']);?>"><div class="infobox-content">Offers Sent</div></a>
                                                <?PHP }else{?>
                                                <a href="sa_offers.php"><div class="infobox-content">Offers Sent</div></a>
                                                <?PHP } ?>
											</div>

											<!-- #section:pages/dashboard.infobox.stat -->
											

											<!-- /section:pages/dashboard.infobox.stat -->
										</div>

										<div class="infobox infobox-blue" style="float:left;">
											

											<div class="infobox-data" style="padding-left:15px;">
												<span class="infobox-data-number"><?php echo $numPos; ?></span>
												<?PHP if(isset($_GET['tmid']) && $_GET['tmid']>0){ ?>
                                                <a href="sa_pos.php?tmid=<?PHP echo $_GET['tmid']; ?>"><div class="infobox-content">PO's Collected</div></a>
                                                <?PHP } else { ?>
                                                <a href="sa_pos.php"><div class="infobox-content">PO's Collected</div></a>
                                                <?PHP } ?>
											</div>

											
										</div>

										<div class="infobox infobox-pink" style="float:left;">
										

											<div class="infobox-data" style="padding-left:15px;">
												<span class="infobox-data-number"><?php echo $numOrdersRaised; ?></span>
                                                <?PHP if(isset($_GET['tmid']) && $_GET['tmid']>0){ ?>
												<a href="sa_orders.php?tmid=<?PHP echo $_GET['tmid']; ?>"><div class="infobox-content">Orders Raised</div></a>
                                                <?PHP } else {?>
												<a href="sa_orders.php"><div class="infobox-content">Orders Raised</div></a>
                                                <?PHP } ?>
											</div>
											
										</div>


										<div class="infobox infobox-pink" style="float:left;">
											

											<div class="infobox-data" style="padding-left:15px;">
												<span class="infobox-data-number"><?php echo $numOrdersLost; ?></span>
												<?PHP if(isset($_GET['tmid']) && $_GET['tmid']>0 ){ ?>
                                                <a href="sa_orders_lost.php?tmid=<?PHP echo trim($_GET['tmid']); ?>"><div class="infobox-content">Orders Lost</div></a>
												<?PHP } else { ?>
                                                <a href="sa_orders_lost.php"><div class="infobox-content">Orders Lost</div></a>
                                                <?PHP } ?>
											</div>
											
										</div>
                                        
                                        
                                        <div class="infobox infobox-blue" style="float:left;">
											

											<div class="infobox-data" style="padding-left:15px;">
												<span class="infobox-data-number"><?php echo $numOrdersClosed; ?></span>
                                                <?PHP if(isset($_GET['tmid']) && $_GET['tmid']>0 ){ ?>
                                   <a href="sa_orders_closed.php?tmid=<?PHP echo trim($_GET['tmid']);?>"><div class="infobox-content">Orders Closed</div></a>
												<?PHP }else { ?>
												<a href="sa_orders_closed.php"><div class="infobox-content">Orders Closed</div></a>
                                                <?PHP } ?>
											</div>

											
										</div>



										<div class="infobox infobox-pink" style="float:left;">
											

											<div class="infobox-data" style="padding-left:15px;">
												<span class="infobox-data-number"><?php echo $numOrdersPending; ?></span>
                                                <?PHP if(isset($_GET['tmid']) && $_GET['tmid']>0 ){ ?>
                                                
					<a href="sa_orders.php?status=pending&tmid=<?PHP echo $_GET['tmid']; ?>"><div class="infobox-content">Orders Pending</div></a>
                                                <?PHP } else {?>
                                                <a href="sa_orders.php?status=pending"><div class="infobox-content">Orders Pending</div></a>
                                                <?PHP } ?>
											</div>
											
										</div>
                                        
                                        
                                        
                                        
										

                                 
                                      <!--  <div class="infobox infobox-red">
											<div class="infobox-icon">
												<i class="ace-icon fa fa-users"></i>
											</div>

											<div class="infobox-data">
												<span class="infobox-data-number"><?php // echo $numEmployees; ?></span>
												<div class="infobox-content">No. of Employees</div>
											</div>
										</div>
-->
										

										

										<!-- /section:pages/dashboard.infobox -->
										<div class="space-6"></div>

										<!-- #section:pages/dashboard.infobox.dark -->
										

										

										

										<!-- /section:pages/dashboard.infobox.dark -->
									</div>
										
                
                <!--sales-->
                
                <div class="col-sm-10 infobox-container">
                <h1 style="text-align:left; font-size:18px;">Projections</h1>
										<!-- #section:pages/dashboard.infobox -->
										<div class="infobox infobox-green" style="float:left;">
											

											<div class="infobox-data" style="padding-left:15px;">
												<span class="infobox-data-number"><?php echo $expectedCollection;  ?></span>
                                     <?PHP if(isset($_GET['tmid']) && $_GET['tmid']>0 ){ ?>
									<a href="sales-collection-projections.php?tmid=<?PHP echo $_GET['tmid']; ?>"><div class="infobox-content">Collections </div></a>
                                    <?php } else { ?>
                                    <a href="sales-collection-projections.php"><div class="infobox-content">Collections </div></a>
                                    <?php } ?>
											</div>

											<!-- #section:pages/dashboard.infobox.stat -->
											

											<!-- /section:pages/dashboard.infobox.stat -->
										</div>

										<!--<div class="infobox infobox-blue" style="float:left;">
											

											<div class="infobox-data" style="padding-left:15px;">
												<span class="infobox-data-number"><?php echo $achievedCollection;  ?></span>
					<a href="sa_payments.php"><div class="infobox-content">Collections Achieved</div></a>
											</div>

											
										</div>-->

										
                                        
                                        <div class="infobox infobox-pink" style="float:left;">
											

											<div class="infobox-data" style="padding-left:15px;">
												<span class="infobox-data-number"><?php echo $orderProjection; ?></span>
												<!--<a href="sa_sales_projection.php?cm=<?php echo $cm; ?>"><div class="infobox-content">Orders </div></a>-->
                                                <a href="sales-orders-projections.php"><div class="infobox-content">Orders </div></a>
											</div>
											
										</div>
                                        
                                        <!--<div class="infobox infobox-pink" style="float:left;">
											

											<div class="infobox-data" style="padding-left:15px;">
												<span class="infobox-data-number"><?php 
												   echo $subTotalOrderAchieved;
									    ?></span>
												<a href="sa_orders.php"><div class="infobox-content">Orders Achieved</div></a>
											</div>
											
										</div>-->
<!-- /section:pages/dashboard.infobox -->
										<div class="space-6"></div>

										<!-- #section:pages/dashboard.infobox.dark -->
										<!-- /section:pages/dashboard.infobox.dark -->
									</div>
                                    
                                    
                                    
                                    
                                    
                                    <div class="col-sm-10 infobox-container">
                <h1 style="text-align:left; font-size:18px;">Sales</h1>
										<!-- #section:pages/dashboard.infobox -->
										<div class="infobox infobox-green" style="float:left;">
											

											<div class="infobox-data" style="padding-left:15px;">
												<span class="infobox-data-number"><?php echo $grandTarget; ?></span>
												<a href="sa_orders.php"><div class="infobox-content">Target</div></a>
											</div>

											<!-- #section:pages/dashboard.infobox.stat -->
											

											<!-- /section:pages/dashboard.infobox.stat -->
										</div>

										<div class="infobox infobox-blue" style="float:left;">
											

											<div class="infobox-data" style="padding-left:15px;">
												<span class="infobox-data-number"><?php echo $subTotalOrderAchieved; ?></span>
												<a href="sa_orders.php"><div class="infobox-content">Target Achieved</div></a>
											</div>

											
										</div>

                                        
                                        
                                        
<!-- /section:pages/dashboard.infobox -->
										<div class="space-6"></div>

										<!-- #section:pages/dashboard.infobox.dark -->
										<!-- /section:pages/dashboard.infobox.dark -->
									</div>
                                    
                                    
                                    
                                     <!-- payments -->
                                     <div class="col-sm-10 infobox-container">
                <h1 style="text-align:left; font-size:18px;">Payments</h1>
										<!-- #section:pages/dashboard.infobox -->
										<div class="infobox infobox-green" style="float:left;">
											

											<div class="infobox-data" style="padding-left:15px;">
												<span class="infobox-data-number"><?php echo $grandOutstanding; ?></span>
												<a href="sa_outstanding.php"><div class="infobox-content">Oustandings Receivable</div></a>
											</div>

											<!-- #section:pages/dashboard.infobox.stat -->
											

											<!-- /section:pages/dashboard.infobox.stat -->
										</div>

										<div class="infobox infobox-blue" style="float:left;">
											

											<div class="infobox-data" style="padding-left:15px;">
												<span class="infobox-data-number"><?php echo $monthCollectionRecieved; ?></span>
												<a href="sa_payments.php"><div class="infobox-content">Collection Recieved</div></a>
											</div>

											
										</div>

                                        
                                        
                                        
<!-- /section:pages/dashboard.infobox -->
										<div class="space-6"></div>

										<!-- #section:pages/dashboard.infobox.dark -->
										<!-- /section:pages/dashboard.infobox.dark -->
									</div>
                                    <!-- payments -->
                                    <!--services-->	
                                    
                                    <!--services-->	
                                    
                                    
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
		
		function changeMonth(cm,tmid)
		{
			//tmid
			if(tmid!='')
			{
				window.location = 'sa_dashboard.php?cm='+cm+'&tmid='+tmid;
			}
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
