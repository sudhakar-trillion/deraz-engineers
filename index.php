<?php include("includes/header.php"); 



if(isset($_GET['cm']))

{

	 $cm = $_GET['cm']; 

}

else

{  

   $cm = date('m'); 

}



if( isset($_GET['fy']) && $_GET['fy']!='0')

{

	$exp  = explode("-",$_GET['fy']);

	

	$like = "'".$exp[0].'-'.$cm.'-%';

	

	$like.= "' OR dateTime like '".$exp[1].'-'.$cm.'-%'."'";

}

else

	{

		$fy = '2017';

		$like = "'".$fy.'-'.$cm.'-%'."'";

	}







	// $baseurl = "http://trillionit.in/deraz/dispatched.php";

$urlDispatch = "dispatched.php";



// enquiries recieved

#echo "select * from enquiries where dateTime LIKE $like";

$enquiries = mysql_query("select * from enquiries where dateTime LIKE $like");

$numEnquiry = mysql_num_rows($enquiries);







//dispatch date before 7 days

$dispatchExpiry = mysql_query("select * from dispatch where DATEDIFF(dispatchedOn,NOW())=7");

$numDispatchExp = mysql_num_rows($dispatchExpiry);

$itemsDispatch = mysql_fetch_array($dispatchExpiry);

$expDate = $itemsDispatch['dispatchedOn'];

$dispatchedId = $itemsDispatch['dispatchedId'];







if( isset($_GET['fy']) && $_GET['fy']!='0')

{

	$exp  = explode("-",$_GET['fy']);

	

	$like = "'".$exp[0].'-'.$cm.'-%';

	

	$like.= "' OR reportDate like '".$exp[1].'-'.$cm.'-%'."'";

}

else

	{

		$fy = '2017';

		$like = "'".$fy.'-'.$cm.'-%'."'";

	}







// offres sent

$offers = mysql_query("select reportId from daily_reports where offer > '0' and reportDate LIKE $like");

$numOffers = mysql_num_rows($offers);



if( isset($_GET['fy']) && $_GET['fy']!='0')

{

	$exp  = explode("-",$_GET['fy']);

	

	$like = "'".$exp[0].'-'.$cm.'-%';

	

	$like.= "' OR poDateTime like '".$exp[1].'-'.$cm.'-%'."'";

}

else

	{

		$fy = '2017';

		$like = "'".$fy.'-'.$cm.'-%'."'";

	}

	





// POs collected

$pos = mysql_query("select reportId from daily_reports where po = '1' and poDateTime LIKE $like");

$numPos = mysql_num_rows($pos);





if( isset($_GET['fy']) && $_GET['fy']!='0')

{

	$exp  = explode("-",$_GET['fy']);

	

	$like = "'".$exp[0].'-'.$cm.'-%';

	

	$like.= "' OR invoiceDateTime like '".$exp[1].'-'.$cm.'-%'."'";

}

else

	{

		$fy = '2017';

		$like = "'".$fy.'-'.$cm.'-%'."'";

	}







// orders raised

$ordersRaised = mysql_query("select reportId from daily_reports where inv = '2' and invoiceDateTime LIKE $like");

$numOrdersRaised = mysql_num_rows($ordersRaised); 





if( isset($_GET['fy']) && $_GET['fy']!='0')

{

	$exp  = explode("-",$_GET['fy']);

	

	$like = "'".$exp[0].'-'.$cm.'-%';

	

	$like.= "' OR lostDateTime like '".$exp[1].'-'.$cm.'-%'."'";

}

else

	{

		$fy = '2017';

		$like = "'".$fy.'-'.$cm.'-%'."'";

	}



// orders lost

$ordersLost = mysql_query("select reportId from daily_reports where leadStatus = 'Order Lost' and lostDateTime LIKE $like");

$numOrdersLost = mysql_num_rows($ordersLost);







if( isset($_GET['fy']) && $_GET['fy']!='0')

{

	$exp  = explode("-",$_GET['fy']);

	

	$like = "'".$exp[0].'-'.$cm.'-%';

	

	$like.= "' OR invoiceDateTime like '".$exp[1].'-'.$cm.'-%'."'";

}

else

	{

		$fy = '2017';

		$like = "'".$fy.'-'.$cm.'-%'."'";

	}





// orders pending ( dispatch delay )

$ordersPending = mysql_query("select reportId from daily_reports where inv = '2' and leadStatus != 'Order Closed' and invoiceDateTime LIKE $like");

$numOrdersPending = mysql_num_rows($ordersPending);

 

 

 



if( isset($_GET['fy']) && $_GET['fy']!='0')

{

	$exp  = explode("-",$_GET['fy']);

	

	$like = "'".$exp[0].'-'.$cm.'-%';

	

	$like.= "' OR closedDateTime like '".$exp[1].'-'.$cm.'-%'."'";

}

else

	{

		$fy = '2017';

		$like = "'".$fy.'-'.$cm.'-%'."'";

	}





// orders closed ( total products dispatched )

$ordersClosed = mysql_query("select reportId from daily_reports where inv = '2' and leadStatus = 'Order Closed' and closedDateTime LIKE $like");





$numOrdersClosed = mysql_num_rows($ordersClosed);  

  

  

// employees

$employees = mysql_query("select id from employees where roll  > '1'");

$numEmployees = mysql_num_rows($employees);









if( isset($_GET['fy']) && $_GET['fy']!='0')

{

	$exp  = explode("-",$_GET['fy']);

	

	$like = "'".$exp[0].'-'.$cm.'-%';

	

	$like.= "' OR date like '".$exp[1].'-'.$cm.'-%'."'";

}

else

	{

		$fy = '2017';

		$like = "'".$fy.'-'.$cm.'-%'."'";

	}



// complaints recieved

$complaints = mysql_query("select serviceId from services where date LIKE $like");

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





if( isset($_GET['fy']) && $_GET['fy']!='0')

{

	$exp  = explode("-",$_GET['fy']);

	

	$like = "'".$exp[0].'-'.$cm.'-%';

	

	$like.= "' OR expectedDate like '".$exp[1].'-'.$cm.'-%'."'";

}

else

	{

		$fy = '2017';

		$like = "'".$fy.'-'.$cm.'-%'."'";

	}



// expected collection

$result = mysql_query("select amount from  expected_collections where expectedDate  like $like");





 $expectedCollection = 0;

 if(mysql_num_rows($result)>0)

 {

	 while($row = mysql_fetch_array($result))

	 {

		$expectedCollection = $row['amount']+$expectedCollection; 

	 }

	 

 }

 

 

 

if( isset($_GET['fy']) && $_GET['fy']!='0')

{

	$exp  = explode("-",$_GET['fy']);

	

	$like = "'".$exp[0].'-'.$cm.'-%';

	

	$like.= "' OR paidDate like '".$exp[1].'-'.$cm.'-%'."'";

}

else

	{

		$fy = '2017';

		$like = "'".$fy.'-'.$cm.'-%'."'";

	}



 

// achieved collection

$result = mysql_query("select amount from collections where paidDate  like $like");



 $achievedCollection = 0;

 if(mysql_num_rows($result)>0)

 {

	 while($row = mysql_fetch_array($result))

	 {

		$achievedCollection = $row['amount']+$achievedCollection; 

	 }

	 

 }

 

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





//$targets = mysql_query("select target from targets where financialMonth = '". $cm ."' and financialYear = '". $financialYear ."'");



$targets = mysql_query("select target from targets where financialYear = '". $financialYear ."'");

$grandTarget = 0;

 if(mysql_num_rows($targets)>0)

 {

	 while($target = mysql_fetch_array($targets))

	 {

		$grandTarget = $target['target']+$grandTarget; 

	 }

	 

 }







if( isset($_GET['fy']) && $_GET['fy']!='0')

{

	$exp  = explode("-",$_GET['fy']);

	

	$like = "'".$exp[0].'-'.$cm.'-%';

	

	$like.= "' OR expectedDate like '".$exp[1].'-'.$cm.'-%'."'";

}

else

	{

		$fy = '2017';

		$like = "'".$fy.'-'.$cm.'-%'."'";

	}



 

// order projection

/*
$result = mysql_query("select expectedValue from expected_sales where expectedDate  like $like");



 $orderProjection = 0;

 if(mysql_num_rows($result)>0)

 {

	 while($row = mysql_fetch_array($result))

	 {

		$orderProjection = $row['expectedValue']+$orderProjection; 

	 }

	 

 }  

 */



$qry = mysql_query("select sum(inv.total) as Achieved from invoices as inv inner join daily_reports as dr on inv.reportId=dr.reportId inner join employees as emp on dr.addedBy=emp.id where dr.reportDate like $like"); 

$achievedtotal = mysql_fetch_object($qry);

$orderProjection = $achievedtotal->Achieved;

if( isset($_GET['fy']) && $_GET['fy']!='0')

{

	$exp  = explode("-",$_GET['fy']);

	

	$like = "'".$exp[0].'-'.$cm.'-%';

	

	$like.= "' OR invoiceDateTime like '".$exp[1].'-'.$cm.'-%'."'";

}

else

	{

		$fy = '2017';

		$like = "'".$fy.'-'.$cm.'-%'."'";

	}



// order achieved



$result = mysql_query("select reportId, currentRevisionId from daily_reports where inv = '2' and invoiceDateTime  like $like");

 $totalOrderAchieved = 0;

 if(mysql_num_rows($result)>0)

 {

	 while($row = mysql_fetch_array($result))

	 {

	

		

		 $amountResult = mysql_query("select grandTotal from daily_reports_revision where reportId = '". $row['reportId'] ."' and revision = '". $row['currentRevisionId'] ."'");

		 

		 if(mysql_num_rows($amountResult)>0)

		 {

			 while($amountRow = mysql_fetch_array($amountResult))

			 {

				 

				   $totalOrderAchieved = $amountRow['grandTotal']+$totalOrderAchieved; 

				

			

			 }

		 }

		

	 }

	 

 }   





 

if( isset($_GET['fy']) && $_GET['fy']!='0')

{

	$exp  = explode("-",$_GET['fy']);

	

	$like = "'".$exp[0].'-'.$cm.'-%';

	

	$like.= "' OR invoiceDateTime like '".$exp[1].'-'.$cm.'-%'."'";

}

else

	{

		$fy = '2017';

		$like = "'".$fy.'-'.$cm.'-%'."'";

	}

	 



// outstanding

$grandOutstanding  = 0;

$result = mysql_query("select reportId, currentRevisionId, invoice from daily_reports where inv > '1' and paymentStatus = 'open' and invoiceDateTime  like $like");



 $totalInvoicesAmount = 0;

 $totalInvoicesCollected = 0;

 if(mysql_num_rows($result)>0)

 {

   while($row = mysql_fetch_array($result))

   {

	  // total amount;

$invoicesAmount = mysql_query("select grandTotal from daily_reports_revision  where daily_reports_revision.reportId = '". $row['reportId'] ."' and daily_reports_revision.revision = '". $row['currentRevisionId'] ."'");

 

 

 if(mysql_num_rows($invoicesAmount)>0)

  {

	  

	 while($invoiceAmount = mysql_fetch_array($invoicesAmount))

	 {

		$totalInvoicesAmount = $invoiceAmount['grandTotal']+$totalInvoicesAmount; 

		

		

	 }

	   

  }



  // collected amount

 

  

  $collectedAmount = mysql_query("select invoiceId, amount from collections  where collections.invoiceId = '". $row['reportId'] ."' order by collections.id");	

						

 

 if(mysql_num_rows($collectedAmount)>0)

  {

	  

	 while($total = mysql_fetch_array($collectedAmount))

	 {

		$totalInvoicesCollected = $total['amount']+$totalInvoicesCollected; 

	

	

	 }

	   

  }

  

 

   

   }

 }







if( isset($_GET['fy']) && $_GET['fy']!='0')

{

	$exp  = explode("-",$_GET['fy']);

	

	$like = "'".$exp[0].'-'.$cm.'-%';

	

	$like.= "' OR disdate like '".$exp[1].'-'.$cm.'-%'."'";

}

else

	{

		$fy = '2017';

		$like = "'".$fy.'-'.$cm.'-%'."'";

	}



// amount payable:

$stocks = mysql_query("select stockId, invoiceAmount from stock where disdate LIKE $like");



$totalStockInvoicesAmount = 0;

$totalStockInvoicesPaidAmount = 0;

if(mysql_num_rows($stocks)>0)

{

   while($stock = mysql_fetch_array($stocks))

   {

	   $totalStockInvoicesAmount = $stock['invoiceAmount']+$totalStockInvoicesAmount;   

	   

	   // paid amount

	   

	   //$amountPayable = $totalStockInvoicesAmount-$totalStockInvoicesPaidAmount;

	   

	   $vendorsPayments = mysql_query("select amount from vendor_payment where invoiceId = '". $stock['stockId'] ."'");

	   if(mysql_num_rows($vendorsPayments)>0)

	   {

		 while($vendorPayments = mysql_fetch_array($vendorsPayments))

		 {

			 $totalStockInvoicesPaidAmount = $vendorPayments['amount']+$totalStockInvoicesPaidAmount;

		 }

		   

	   }

	   

   }

}









/*$orders = mysql_query("select reportId from orders where dateTime LIKE '$like'");

$numOrders = mysql_num_rows($orders);



$dispatched = mysql_query("select orderId from order_status where status = 'Dispatched' and dateTime LIKE '$like'");

$numDispatched = mysql_num_rows($dispatched);*/



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

						<div class="nav-search" id="nav-search" style="display:none;">

                        

                        <form class="form-search">

                            

                             

								<span class="input-icon">

									 <select class="nav-search-input" onchange="changeMonth(this.value)">

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

                                    

                                           <form class="form-inline">

  <!--<div class="form-group">

   

    <select class="form-control" id="exampleInputName2" name="searchBy">

    <option value="1">By Week</option>

    <option value="2">By Month</option>

    <option value="3">By Year</option>

    </select>

  </div>-->

  <div class="form-group">

   

    <select class="form-control" name="cm">

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

  </div>

  

    <!--<div class="form-group">

   

     <select name="year" id="year" class="form-control">

                                                    	<option value="0">Select Financial Year</option>

                                                        <?PHP

														for($i=2010;$i<=(date('Y'));$i++)

														{

															?>

                                                            <option value="<?PHP echo "$i-".($i+1); ?>" <?PHP if($filtered_year=="$i-".($i+1)){ echo "selected";}?> > <?PHP echo "$i-".($i+1); ?></option>

                                                            <?PHP	

														}

														?>

   </select>

   

  </div>-->



<?PHP

	if(isset($_GET['fy']))	{ $fy =	$_GET['fy'];	}

	else	$fy ='0';

		

?>

    <select  class="form-control" name="fy" style="margin-left:7px">

    <option value="0" <?PHP if($fy=='0') { echo 'selected="selected"';}?> >Financial year </option>

    <option value="2017-2018"  <?PHP if($fy=='2017-2018') { echo 'selected="selected"';}?>> 2017-2018 </option>

    <option value="2017-2018"  <?PHP if($fy=='2018-2019') { echo 'selected="selected"';}?>> 2018-2019 </option>

    <option value="2019-2020"  <?PHP if($fy=='2019-2020') { echo 'selected="selected"';}?>> 2019-2020 </option>

    

        </select>	

  

  <button type="submit" class="btn btn-default">Search</button>

</form>

                                    

                                    </div>

                                    </div>

                                    </div>							







								<div class="row">

									<div class="col-xs-12">

                                    

                                  

                                    <div class="col-sm-10 infobox-container">

										<!-- #section:pages/dashboard.infobox -->

                                        <h1 style="text-align:left; font-size:18px;">DSQ / PO Register</h1>

                                        <div class="infobox infobox-green" style="float:left;">

											



											<div class="infobox-data" style="padding-left:15px;">

												<span class="infobox-data-number"><?php echo $numEnquiry; ?></span>

												<a href="enquiries.php"><div class="infobox-content">Enquiries Recieved</div></a>

											</div>



											<!-- #section:pages/dashboard.infobox.stat -->

											



											<!-- /section:pages/dashboard.infobox.stat -->

										</div>

                                        

                                        <div class="infobox infobox-green" style="float:left;">

											



											<div class="infobox-data" style="padding-left:15px;">

												<span class="infobox-data-number"><?php echo $numOffers; ?></span>

												<a href="offers.php"><div class="infobox-content">Offers Sent</div></a>

											</div>



											<!-- #section:pages/dashboard.infobox.stat -->

											



											<!-- /section:pages/dashboard.infobox.stat -->

										</div>

										



										<div class="infobox infobox-blue" style="float:left;">

											



											<div class="infobox-data" style="padding-left:15px;">

												<span class="infobox-data-number"><?php echo $numPos; ?></span>

												<a href="pos.php"><div class="infobox-content">PO's Collected</div></a>

											</div>



											

										</div>

                                        

                                        <div class="infobox infobox-pink" style="float:left;">

											



											<div class="infobox-data" style="padding-left:15px;">

												<span class="infobox-data-number"><?php echo $numOrdersLost; ?></span>

												<a href="orders_lost.php"><div class="infobox-content">Orders Lost</div></a>

											</div>

											

										</div>

                                        

                                        

                                        



										<div class="infobox infobox-pink" style="float:left;">

											



											<div class="infobox-data" style="padding-left:15px;">

												<span class="infobox-data-number"><?php echo $numOrdersRaised; ?></span>

												<a href="orders_raised.php"><div class="infobox-content">Orders Raised</div></a>

											</div>

											

										</div>

                                        

                                        

                                        

                                        <div class="infobox infobox-pink" style="float:left;">

											



											<div class="infobox-data" style="padding-left:15px;">

												<span class="infobox-data-number"><?php echo $numOrdersPending; ?></span>

												<a href="orders_pending.php"><div class="infobox-content">Pending Orders</div></a>

											</div>

											

										</div>

                                        

                                        

                                          <div class="infobox infobox-pink" style="float:left;">

											



											<div class="infobox-data" style="padding-left:15px;">

												<span class="infobox-data-number"><?php echo $numOrdersClosed; ?></span>

												<a href="orders_closed.php"><div class="infobox-content">Orders Closed</div></a>

											</div>

											

										</div>

                                        

                                        <div class="infobox infobox-pink" style="float:left;">

											



											<div class="infobox-data" style="padding-left:15px;">

												<span class="infobox-data-number"><?php echo $numDispatchExp; ?></span>

<a href="<?php echo $urlDispatch."?dispatchedId=$dispatchedId&expDate=$expDate"; ?>"><div class="infobox-content">7 more days to dispatch</div></a>

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

                <h1 style="text-align:left; font-size:18px;">Sales / Collections / Targets</h1>

										<!-- #section:pages/dashboard.infobox -->

                                        

                <div class="infobox infobox-pink" style="float:left;">

											



											<div class="infobox-data" style="padding-left:15px;">

<?PHP                                            

                                            

$year_target = 0;

$employees = mysql_query("select employees.id, employees.firstName from employees left join rolls on employees.roll = rolls.roll_id where employees.roll = '4' order by employees.firstName");



$mnt = date('m');

$mnt = (int)$mnt;



if($mnt>3)

{

	$trlike = date('Y')."-".(date('Y')+1);	

}

else

	$trlike = (date('Y')-1)."-".date('Y');	



if( isset($_GET['cm']) && $_GET['cm']>0 )
{
	$financialMonth = $_GET['cm'];
}
else
	$financialMonth=date('m');


	$yrTarget =  mysql_query("select sum(target) as Target from targets where  financialMonth='".$financialMonth."' and financialYear = '". $trlike ."'");
	
#	echo "select sum(target) as Target from targets where  financialMonth='".$financialMonth."' and financialYear = '". $trlike ."'"; exit; 
	


	if(mysql_num_rows($yrTarget)>0)
		{
			$yearTarget = mysql_fetch_array($yrTarget);
			if($yearTarget['Target']>0)
				$year_target =  $yearTarget['Target'];
			else
				$year_target ='0';
		}	
	
		
	
/*

while($employee = mysql_fetch_array($employees))
{

	//$yrTarget =  mysql_query("select target from targets where employeeId = '". $employee['id'] ."' and financialYear = '". $trlike ."'");
	
	$yrTarget =  mysql_query("select target from targets where employeeId = '". $employee['id'] ."' and financialMonth='".$financialMonth."' and financialYear = '". $trlike ."'");
	
	echo "select target from targets where employeeId = '". $employee['id'] ."' and financialMonth='".$financialMonth."' and financialYear = '". $trlike ."'"; exit; 

	if(mysql_num_rows($yrTarget)>0)

	{

		while($yearTarget = mysql_fetch_array($yrTarget))

		{

			$year_target = $year_target+$yearTarget['target'];

		}

	}

}
*/
?>

    

    											<span class="infobox-data-number"><?php echo $year_target; //$grandTarget; ?></span>

												<a href="targets.php"><div class="infobox-content">Target</div></a>

											</div>

											

										</div>                        

                                        

                                        

                                        

										<div class="infobox infobox-green" style="float:left;">

											



											<div class="infobox-data" style="padding-left:15px;">

                                            <?PHP

											

											$employees = mysql_query("select employees.id, employees.firstName from employees left join rolls on employees.roll = rolls.roll_id where employees.roll = '4' order by employees.firstName");

											

											$inv_proj=0;

											$cnt_emp=0;

										while($employee = mysql_fetch_array($employees))

										{

										$Projectedqry = mysql_query("select exp_col.amount, emp.firstName from expected_collections as exp_col join daily_reports as dr on exp_col.reportId=dr.reportId join employees as emp on dr.addedBy=emp.id where emp.id=".$employee['id']." and dr.reportDate like $like "); 

										if(mysql_num_rows($Projectedqry)>0)

										{

											$cnt_emp++;

											while($Prjctd = mysql_fetch_object($Projectedqry))

											{

												$inv_proj =$inv_proj+$Prjctd->amount;

											}

											//echo mysql_num_rows($Projectedqry).":".$Prjctd->amount."<br>";

										}

										

									}

                                            ?>

                                            

												<span class="infobox-data-number"><?php echo $inv_proj; //$expectedCollection;  ?></span>

												<a href="collection-projections.php"><div class="infobox-content">Collections</div></a>

											</div>



											<!-- #section:pages/dashboard.infobox.stat -->

											



											<!-- /section:pages/dashboard.infobox.stat -->

										</div>



										<!--<div class="infobox infobox-blue" style="float:left;">

											



											<div class="infobox-data" style="padding-left:15px;">

												<span class="infobox-data-number"><?php echo $achievedCollection;  ?></span>

												<a href="projections.php"><div class="infobox-content">Collection Achieved</div></a>

											</div>



											

										</div>-->



										

                                        

                                        <div class="infobox infobox-pink" style="float:left;">

											



											<div class="infobox-data" style="padding-left:15px;">

<?PHP                                            

       $employees = mysql_query("select employees.id, employees.firstName from employees left join rolls on employees.roll = rolls.roll_id where employees.roll = '4' order by employees.firstName");


/*

  while($employee = mysql_fetch_array($employees))

  {

   

	$Projectedqry = mysql_query("select expectedValue from expected_sales where employeeId=".$employee['id']); 

	if(mysql_num_rows($Projectedqry)>0)

	{ 

		   $prj_exp=0;

			while($Projected=mysql_fetch_object($Projectedqry))

			{

				$prj_exp = $Projected->expectedValue+$prj_exp;

			}

			$orderProjection = $prj_exp;

		   }

		   

  }
*/
	                                        

                                            

     ?>                                       

												<span class="infobox-data-number"><?php echo round($orderProjection,5); ?></span>

												<a href="orders-projections.php"><div class="infobox-content">Orders</div></a>

											</div>

											

										</div>

                                        

                                        <!--<div class="infobox infobox-pink" style="float:left;">

											



											<div class="infobox-data" style="padding-left:15px;">

												<span class="infobox-data-number"><?php 

												   

												   echo $totalOrderAchieved;

									    ?></span>

												<a href="projections.php"><div class="infobox-content">Order Achieved</div></a>

											</div>

											

										</div>-->

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

												<span class="infobox-data-number">

												<?php

												

												

							$whereItems[] = "where invoices.proInvoiceDateTime like '%-".date('m')."-%'";

							

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

							

							

							

							$employees = mysql_query("select DISTINCT employees.id, employees.firstName, customers.company, branches.branch from invoices 

							left join daily_reports on invoices.reportId = daily_reports.reportId

							left join employees on employees.id = daily_reports.addedBy 

							left join branches on employees.branch=branches.branchid

							left join customers on daily_reports.company = customers.customerId $where");

							

							

							

  while($employee = mysql_fetch_array($employees))

  {							

							

							

							  $outstandings = mysql_query("select invoices.invoiceId, invoices.invoiceNumber, invoices.invoiceDateTime, invoices.grandTotal       

                        from invoices

						left join daily_reports on invoices.reportId  = daily_reports.reportId

                        where daily_reports.addedBy = '". $employee['id'] ."' and daily_reports.inv > '0' and invoices.paymentStatus = 'open' 

					    order by daily_reports.reportId desc");



						$totalPending = 0;

						$invoiceTotal = 	0;

						

						while($outstanding = mysql_fetch_array($outstandings))

							{

								$outstan = $outstanding['invoiceId'];

								$collections = mysql_query("select amount from collections where invoiceId = '". $outstanding['invoiceId'] ."'");

								$invCollected = 0;

								while($collection = mysql_fetch_array($collections))

								{

									$invCollected = $collection['amount']+$invCollected;

								}

								

								// $pending = $invoiceTotal-$invCollected; 

								$pending = $outstanding['grandTotal']-$invCollected;

								

								$totalPending = $pending+$totalPending;

								

							}//outstanding 

							$grandTotalPending =  $totalPending+$grandTotalPending;

												

  }

  echo $grandTotalPending; 

												 ?></span>

												<a href="outstandings.php"><div class="infobox-content">Outstanding <!--Recieveable--></div></a>

											</div>



											<!-- #section:pages/dashboard.infobox.stat -->

											



											<!-- /section:pages/dashboard.infobox.stat -->

										</div>



										<div class="infobox infobox-blue" style="float:left;">

											



											<div class="infobox-data" style="padding-left:15px;">

												<span class="infobox-data-number"><?php //echo $achievedCollection; 

												

											echo $amountPayable = $totalStockInvoicesAmount-$totalStockInvoicesPaidAmount;

 ?></span>

												<a href="vendor_payments.php"><div class="infobox-content">Amount Payable</div></a>

											</div>



											

										</div>



										

										<div class="space-6"></div>



										<!-- #section:pages/dashboard.infobox.dark -->

										<!-- /section:pages/dashboard.infobox.dark -->

									</div>

                                    

                                    <!-- payments -->

                                    <!--services-->	

                                

                               			 <div class="col-sm-10 infobox-container">

                <h1 style="text-align:left; font-size:18px;">Services</h1>

										<!-- #section:pages/dashboard.infobox -->

										<div class="infobox infobox-green" style="float:left;">

											



											<div class="infobox-data" style="padding-left:15px;">

                                            

                                            <?PHP

											$services_open = mysql_query("select services.serialNumber, services.typeOfService, services.category, services.serviceId, services.customerName, services.product, services.modelNo, services.date, services.inwardDetails, services.engineer, services.quantity from services join service_status as sts on sts.serviceId=services.serviceId where sts.status='open'

order by serviceId desc");



											?>

                                            

                                            

												<span class="infobox-data-number"><?PHP echo mysql_num_rows($services_open);?></span>

												<a href="complaints-raised.php"><div class="infobox-content">Complaints Raised</div></a>

											</div>



											<!-- #section:pages/dashboard.infobox.stat -->

											



											<!-- /section:pages/dashboard.infobox.stat -->

										</div>



										<div class="infobox infobox-blue" style="float:left;">

											 <?PHP

											$services_closed = mysql_query("select services.serialNumber, services.typeOfService, services.category, services.serviceId, services.customerName, services.product, services.modelNo, services.date, services.inwardDetails, services.engineer, services.quantity from services join service_status as sts on sts.serviceId=services.serviceId where sts.status='closed'

order by serviceId desc");



											?>

                                            



											<div class="infobox-data" style="padding-left:15px;">

												<span class="infobox-data-number"><?PHP echo mysql_num_rows($services_closed);?></span>

												<a href="complaints-closed.php"><div class="infobox-content">Complaints Closed</div></a>

											</div>



											

										</div>



										<div class="infobox infobox-pink" style="float:left;">

											 <?PHP

											$services_pending = mysql_query("select services.serialNumber, services.typeOfService, services.category, services.serviceId, services.customerName, services.product, services.modelNo, services.date, services.inwardDetails, services.engineer, services.quantity from services join service_status as sts on sts.serviceId=services.serviceId where sts.status='pending'

order by serviceId desc");



											?>

                                            



											<div class="infobox-data" style="padding-left:15px;">

												<span class="infobox-data-number"><?PHP echo mysql_num_rows($services_pending);?></span>

												<a href="complaints-pending.php"><div class="infobox-content">Complaints Pending</div></a>

											</div>

											

										</div>

<!-- /section:pages/dashboard.infobox -->

										<div class="space-6"></div>



										<!-- #section:pages/dashboard.infobox.dark -->

										<!-- /section:pages/dashboard.infobox.dark -->

									</div>

                                    

                                  <!--services-->	

                                  

                                  <div class="col-sm-10 infobox-container">

                <h1 style="text-align:left; font-size:18px;">Customers, Vendors, Employees, Stores</h1>

										<!-- #section:pages/dashboard.infobox -->

										<div class="infobox infobox-green" style="float:left;">

											



											<div class="infobox-data" style="padding-left:15px;">

                                            

                                            <?PHP  $total_customers = mysql_query("SELECT * FROM `customers`"); ?>

                                            

												<span class="infobox-data-number"><?PHP echo mysql_num_rows($total_customers);?></span>

												<a href="customers.php"><div class="infobox-content">Customers</div></a>

											</div>



											<!-- #section:pages/dashboard.infobox.stat -->

											



											<!-- /section:pages/dashboard.infobox.stat -->

										</div>



										<div class="infobox infobox-blue" style="float:left;">

											<?PHP  $total_vendors = mysql_query("SELECT * FROM `vendors`"); ?>

                                            



											<div class="infobox-data" style="padding-left:15px;">

												<span class="infobox-data-number"><?PHP echo mysql_num_rows($total_vendors);?></span>

											<a href="vendors.php"><div class="infobox-content">Vendors</div></a>

                                            </div>



											

										</div>



										<div class="infobox infobox-pink" style="float:left;">

											 <?PHP  $total_employees = mysql_query("SELECT * FROM `employees`"); ?>

                                            



											<div class="infobox-data" style="padding-left:15px;">

												<span class="infobox-data-number"><?PHP echo mysql_num_rows($total_employees);?></span>

												<a href="employees.php"><div class="infobox-content">Employees</div></a>

											</div>

											

										</div>

                                        

                                        

                                        <!--<div class="infobox infobox-pink" style="float:left;">

											 <?PHP

											$services_pending = mysql_query("select services.serialNumber, services.typeOfService, services.category, services.serviceId, services.customerName, services.product, services.modelNo, services.date, services.inwardDetails, services.engineer, services.quantity from services join service_status as sts on sts.serviceId=services.serviceId where sts.status='pending'

order by serviceId desc");



											?>

                                            



											<div class="infobox-data" style="padding-left:15px;">

												<span class="infobox-data-number"><?PHP echo mysql_num_rows($services_pending);?></span>

												<a href="complaints-pending.php"><div class="infobox-content">Complaints Pending</div></a>

											</div>

											

										</div>-->

                                        

                                        

<!-- /section:pages/dashboard.infobox -->

										<div class="space-6"></div>



										<!-- #section:pages/dashboard.infobox.dark -->

										<!-- /section:pages/dashboard.infobox.dark -->

									</div>

                                    

                                    

									</div>

								</div>

                                

							</div><!-- /.col -->

						</div><!-- /.row -->

					</div><!-- /.page-content -->

				</div>

			</div><!-- /.main-content -->



			<?php include("includes/footer.php");  ?>

