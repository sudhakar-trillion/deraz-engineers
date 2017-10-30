<?php ob_start(); session_start(); 



error_reporting(0);



if(!(isset($_SESSION['superAdmin'])) && !(isset($_SESSION['admin'])) && !(isset($_SESSION['accountsAdmin'])) && !(isset($_SESSION['ordersandExecution'])) && !(isset($_SESSION['logistics'])) && !(isset($_SESSION['bdAdmin'])) && !(isset($_SESSION['services'])))

  {  header("location: logout.php"); exit; }

     require("includes/db.php"); 

  	 $requestUrl = explode('/',$_SERVER['PHP_SELF']);

	 $requestUrl = $requestUrl[count($requestUrl)-1]; 

	 

	 // this query for pending orders

	 $pendingOrders  = mysql_query("select * from daily_reports where daily_reports.inv = '2' and daily_reports.leadStatus = 'Invoice generated' order by reportId desc");

	 $pendingOrder = mysql_num_rows($pendingOrders); 

	 

	// this is for pending payments query 

$invoiceStatus = mysql_query("SELECT * FROM `invoices` WHERE paymentStatus= 'open' and invoiceStatus = 'Invoice generated' order by invoiceId desc");

	 $invStatus = mysql_num_rows($invoiceStatus);

	 

	 // this is for dispatches before 7days 

	 $dailyNotifications = mysql_query("select * from dispatch

     left join invoices on dispatch.invoiceId = invoices.invoiceId

	  where DATEDIFF(dispatchedOn,NOW())=7");

	 

	 $base_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	 

	 // this query for pending offers

$dailyOfferNotifications = mysql_query("select *, date_format(reportDate,'%d-%m-%Y') repDate from daily_reports where leadStatus = 'Offer to be generated' and daily_reports.offer > '0' order by reportDate desc");

	 $lastIdd = mysql_num_rows($dailyOfferNotifications);

	

	 

	 ?>

<!DOCTYPE html>

<html lang="en">

	<head>

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

		<meta charset="utf-8" />

		<title>Tri Smart  Admin</title>



		<meta name="description" content="3 styles with inline editable feature" />

		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />



		<!-- bootstrap & fontawesome -->

		<link rel="stylesheet" href="assets/css/bootstrap.css" />

		<link rel="stylesheet" href="assets/css/font-awesome.css" />



		<!-- page specific plugin styles -->

		<link rel="stylesheet" href="assets/css/jquery-ui.custom.css" />

		<link rel="stylesheet" href="assets/css/jquery.gritter.css" />

		<link rel="stylesheet" href="assets/css/select2.css" />

		<link rel="stylesheet" href="assets/css/datepicker.css" />

		<link rel="stylesheet" href="assets/css/bootstrap-editable.css" />



		<!-- text fonts -->

		<link rel="stylesheet" href="assets/css/ace-fonts.css" />



		<!-- ace styles -->

		<link rel="stylesheet" href="assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />



		<!--[if lte IE 9]>

			<link rel="stylesheet" href="../assets/css/ace-part2.css" class="ace-main-stylesheet" />

		<![endif]-->



		<!--[if lte IE 9]>

		  <link rel="stylesheet" href="../assets/css/ace-ie.css" />

		<![endif]-->



		<!-- inline styles related to this page -->



		<!-- ace settings handler -->

		<script src="assets/js/ace-extra.js"></script>

        

        <link rel="stylesheet" href="assets/css/ui.jqgrid.css" />





		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->



		<!--[if lte IE 8]>

		<script src="../assets/js/html5shiv.js"></script>

		<script src="../assets/js/respond.js"></script>

		<![endif]-->

        <style>

		.err-msg

			{

				color:red;

			}

			

			.package-removal	

			{

				

				position: absolute;

				color: #fff;

				top: 1px;

				right: 0px;

				background: red;

				/* padding: 5px; */

				overflow: hidden;

				border-radius: 50%;

				width: 15px;

				height: 15px;

				line-height: 15px;

				text-align: center;

				cursor: pointer;	

			}

		</style>

	</head>



	<body class="no-skin">

		<!-- #section:basics/navbar.layout -->

		<div id="navbar" class="navbar navbar-default" style="background:#1a1c89;">

			<script type="text/javascript">

				try{ace.settings.check('navbar' , 'fixed')}catch(e){}

			</script>



			<div class="navbar-container" id="navbar-container">

				<!-- #section:basics/sidebar.mobile.toggle -->

				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">

					<span class="sr-only">Toggle sidebar</span>



					<span class="icon-bar"></span>



					<span class="icon-bar"></span>



					<span class="icon-bar"></span>

				</button>



				<!-- /section:basics/sidebar.mobile.toggle -->

				<div class="navbar-header pull-left">

					<!-- #section:basics/navbar.layout.brand -->

					<a href="#" class="navbar-brand">

						<small>

							 <img src="assets/images/derazlogo1.png"  />

						</small>

					</a>



					<!-- /section:basics/navbar.layout.brand -->



					<!-- #section:basics/navbar.toggle -->



					<!-- /section:basics/navbar.toggle -->

				</div>



				<!-- #section:basics/navbar.dropdown -->

				<div class="navbar-buttons navbar-header pull-right" role="navigation">

					<ul class="nav ace-nav">

                    <li class="green dropdown-modal">

							<a href="DERAZ ENGINEERS ADMIN DOCUMENTATION.pdf" target="_new" title="Documentation">

								<i class="ace-icon fa fa-download"></i>

								

							</a>

</li>

						

						<?php $numNotificatios =  mysql_num_rows($dailyNotifications);

									if($numNotificatios>0) { 		     

									?>

                                    <li class="purple">

							<a data-toggle="dropdown" class="dropdown-toggle" href="#">

								<i class="ace-icon fa fa-money icon-animated-bell"></i>

								<span class="badge badge-important"><?php echo $numNotificatios; ?></span>

							</a>



							<ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">

								<li class="dropdown-header">

									<i class="ace-icon fa fa-exclamation-triangle"></i>

									<?php  echo $numNotificatios." Dispatched Items"; ?>

								</li>



								<li class="dropdown-content">

									<ul class="dropdown-menu dropdown-navbar navbar-pink">

                                    <?php  while($dailyNotification = mysql_fetch_array($dailyNotifications)) { 

									          $expDate = $dailyNotification['dispatchedOn'];

											  $dispatchedId = $dailyNotification['dispatchedId'];

										

										 ?>

                                    

                                    <li>

                     <a href="<?php echo "dispatched.php?dispatchedId=$dispatchedId"; ?>">

											<!--<a href="authorize_notifications.php?nid=<?php #echo $dailyNotification['autoId'];  ?>">-->

												<div class="clearfix">

													<span class="pull-left">

														<i class="btn btn-xs btn-primary fa fa-user"></i>

								<?php echo '<strong>'.$dailyNotification['invoiceNumber'].'</strong>'. ' '.  'is going to dispatch in 2 days.' ; ?>

													</span>

													

												</div>

											</a>

										</li>

                                    <?php } 

									

									?>

										



										



										

										

									</ul>

								</li>



								<li class="dropdown-footer">

									<a href="<?PHP echo "dispatched.php?expDate=$expDate"; ?>">

										See all notifications

										<i class="ace-icon fa fa-arrow-right"></i>

									</a>

								</li>

							</ul>

                            

                            

                            

                            

						</li>

                         <?php } ?>

                         						<?php

									if($lastIdd>0) { 		     

									?>

                                    <li class="purple">

							<a data-toggle="dropdown" class="dropdown-toggle" href="#">

								<i class="ace-icon fa fa-envelope icon-animated-bell"></i>

								<span class="badge badge-important"><?php echo $lastIdd; ?></span>

							</a>





							<ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">

								<li class="dropdown-header">

									<i class="ace-icon fa fa-exclamation-triangle"></i>

									<?php  echo $lastIdd." Offers"; ?>

								</li>



								<li class="dropdown-content">

									<ul class="dropdown-menu dropdown-navbar navbar-pink">

                                    <?php  while($dailyOfferNotification = mysql_fetch_array($dailyOfferNotifications)) { 

/*									          $expDate = $dailyNotification['dispatchedOn'];

											  $dispatchedId = $dailyNotification['dispatchedId']; */

											  $repDate = $dailyOfferNotification['repDate'];

											  $repId = $dailyOfferNotification['reportId']

										

										 ?>

                                    

                                    <li>

                     <a href="<?php echo "offers.php?repId=$repId"; ?>">

											<!--<a href="authorize_notifications.php?nid=<?php #echo $dailyNotification['autoId'];  ?>">-->

												<div class="clearfix">

													<span class="pull-left">

														<i class="btn btn-xs btn-primary fa fa-user"></i>

								<?php echo '<strong>'.$repDate.'</strong>'. ' '.  'have to give the offer number.' ; ?>

													</span>

													

												</div>

											</a>

										</li>

                                    <?php } 

									

									?>

										

									</ul>

								</li>



								<li class="dropdown-footer">

									<a href="<?PHP echo "offers.php?repDate=$lastIdd"; ?>">

										See all notifications

										<i class="ace-icon fa fa-arrow-right"></i>

									</a>

								</li>

							</ul>

                            

                            

						</li>

                         <?php } ?>



                                                  						<?php

									if($invStatus>0) { 		     

									?>

                                    <li class="purple">

							<a data-toggle="dropdown" class="dropdown-toggle" href="#">

								<i class="ace-icon fa fa-money icon-animated-bell"></i>

								<span class="badge badge-important"><?php echo $invStatus; ?></span>

							</a>



							<ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">

								<li class="dropdown-header">

									<i class="ace-icon fa fa-exclamation-triangle"></i>

									<?php  echo $invStatus." Pending Payments"; ?>

								</li>



								<li class="dropdown-content">

									<ul class="dropdown-menu dropdown-navbar navbar-pink">

                                    <?php  while($invoices = mysql_fetch_array($invoiceStatus)) { 

/*									          $expDate = $dailyNotification['dispatchedOn'];

											  $dispatchedId = $dailyNotification['dispatchedId']; */

											  $invId = $invoices['invoiceId'];

											  $invNumber = $invoices['invoiceNumber']

										

										 ?>

                                    

                                    <li>

                     <a href="<?php echo "orders.php?invId=$invId"; ?>">

											<!--<a href="authorize_notifications.php?nid=<?php #echo $dailyNotification['autoId'];  ?>">-->

												<div class="clearfix">

													<span class="pull-left">

														<i class="btn btn-xs btn-primary fa fa-user"></i>

								<?php echo '<strong>'.$invNumber.'</strong>'. ' '.  'has outstanding amount.' ; ?>

													</span>

													

												</div>

											</a>

										</li>

                                    <?php } 

									

									?>

										

									</ul>

								</li>



								<li class="dropdown-footer">

									<a href="<?PHP echo "orders.php?invs=$invStatus"; ?>">

										See all notifications

										<i class="ace-icon fa fa-arrow-right"></i>

									</a>

								</li>

							</ul>

                            

                            

						</li>

                         <?php } ?>

                         

                                                                           						<?php

									if($pendingOrder>0) { 		     

									?>

                                    <li class="purple">

							<a data-toggle="dropdown" class="dropdown-toggle" href="#">

								<i class="ace-icon fa fa-database icon-animated-bell"></i>

								<span class="badge badge-important"><?php echo $pendingOrder; ?></span>

							</a>



							<ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">

								<li class="dropdown-header">

									<i class="ace-icon fa fa-exclamation-triangle"></i>

									<?php  echo $pendingOrder." Pending Orders"; ?>

								</li>



								<li class="dropdown-content">

									<ul class="dropdown-menu dropdown-navbar navbar-pink">

                                    <?php  while($pendings = mysql_fetch_array($pendingOrders)) { 

/*									          $expDate = $dailyNotification['dispatchedOn'];

											  $dispatchedId = $dailyNotification['dispatchedId']; */

											  $pendingId = $pendings['reportId'];

											  $pendingInv = $pendings['invoice']

										

										 ?>

                                    

                                    <li>

                     <a href="<?php echo "orders_pending.php?pendingId=$pendingId"; ?>">

											<!--<a href="authorize_notifications.php?nid=<?php #echo $dailyNotification['autoId'];  ?>">-->

												<div class="clearfix">

													<span class="pull-left">

														<i class="btn btn-xs btn-primary fa fa-user"></i>

								<?php echo '<strong>'.$pendingInv.'</strong>'. ' '.  'is in pending status.' ; ?>

													</span>

													

												</div>

											</a>

										</li>

                                    <?php } 

									

									?>

										

									</ul>

								</li>



								<li class="dropdown-footer">

									<a href="<?PHP echo "orders_pending.php"; ?>">

										See all notifications

										<i class="ace-icon fa fa-arrow-right"></i>

									</a>

								</li>

							</ul>

                            

                            

                            

                            

						</li>

                        

                        			

                         <?php } ?>



<!-- view the complaints notifications -->

                                    

                                    <?PHP

									

									//$qry = mysql_query("select count(*) as unviewed from services where viewed ='Not View'");

									$qry = mysql_query("select * from service_status where status ='pending'");

									$totalrows = mysql_fetch_object($qry);

									

									?>

                                    

                                    

                                    

                                    

                                    <li class="purple">

							<a  href="services.php">

								<i class="ace-icon fa fa-database icon-animated-bell"></i>

								<span class="badge badge-important"><?php echo mysql_num_rows($qry);//$totalrows->unviewed; ?></span>

							</a>



							

                            

                            

                            

                            

                            

						</li>

                        

                        			<!-- view the complaints notifications ends here-->



                         

                         

                         <!-- #section:basics/navbar.user_menu -->

						<li class="light-blue">

							<a data-toggle="dropdown" href="#" class="dropdown-toggle">

								<img class="nav-user-photo" src="assets/avatars/user.48.png" alt="Jason's Photo" />

								<span class="user-info">

									<small>Welcome,</small>

									<?php echo $_SESSION['firstName']; ?>

								</span>



								<i class="ace-icon fa fa-caret-down"></i>

							</a>



							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">

								<!--<li>

									<a href="#">

										<i class="ace-icon fa fa-cog"></i>

										Settings

									</a>

								</li>



								<li>

									<a href="profile.html">

										<i class="ace-icon fa fa-user"></i>

										Profile

									</a>

								</li>



								<li class="divider"></li> -->



								<li>

									<a href="logout.php">

										<i class="ace-icon fa fa-power-off"></i>

										Logout

									</a>

								</li>

							</ul>

						</li>



						<!-- /section:basics/navbar.user_menu -->

					</ul>

				</div>



				<!-- /section:basics/navbar.dropdown -->

			</div><!-- /.navbar-container -->

		</div>



		<!-- /section:basics/navbar.layout -->

		<div class="main-container" id="main-container">

			<script type="text/javascript">

				try{ace.settings.check('main-container' , 'fixed')}catch(e){}

			</script>



			<!-- #section:basics/sidebar -->

			<div id="sidebar" class="sidebar                  responsive">

				<script type="text/javascript">

					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}

				</script>



				



				<ul class="nav nav-list">

					

                    

                     <?php 

					  if(isset($_SESSION['superAdmin']) || isset($_SESSION['admin']))

	{

					$url[] = 'index.php'; 

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>



					<li class="<?php echo $class; ?>">

						<a href="index.php">

							<i class="menu-icon fa fa-tachometer"></i>

							<span class="menu-text"> Dashboard </span>



						</a>

						<b class="arrow"></b>

					</li>

                    <?php } ?>

                    

                    <?php

					  if(isset($_SESSION['superAdmin']) || isset($_SESSION['admin']) || isset($_SESSION['bdAdmin']))

	{

					$url[] = 'add_enquiry.php'; $url[] = 'view_enquiry.php'; $url[] = 'enquiries.php'; 

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>



					<li class="<?php  echo $class; ?>">

							<a href="#" class="dropdown-toggle">

							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text"> Enquiries <span class="badge badge-primary">2</span></span>

                           <b class="arrow fa fa-angle-down"></b>

						</a>

						<b class="arrow"></b>

                        

                         <ul class="submenu">

							

							



							

							<li class="">

								<a href="enquiries.php">

									<i class="menu-icon fa fa-caret-right"></i>

									View Enquiries

								</a>



								<b class="arrow"></b>

							</li>



							<li class="">

								<a href="add_enquiry.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Add Enquiry

								</a>



								<b class="arrow"></b>

							</li>



						</ul>

					</li>

                    <?php } ?>

                    

                   <?php 

					  if(isset($_SESSION['ordersandExecution']))

	{

					$url[] = 'offers.php'; 

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>



					<li class="<?php echo $class; ?>">

						<a href="offers.php">

							<i class="menu-icon fa fa-tachometer"></i>

							<span class="menu-text"> Offers </span>



						</a>

						<b class="arrow"></b>

					</li>

                    <?php } ?>

                    

                     <?php 

					  if(isset($_SESSION['bdAdmin']))

	{

					$url[] = 'offers.php';  $url[] = 'generate_offer.php';

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>



					<li class="<?php echo $class; ?>">

						<a href="offers.php">

							<i class="menu-icon fa fa-tachometer"></i>

							<span class="menu-text"> Offers </span>



						</a>

						<b class="arrow"></b>

					</li>

                    <?php } ?>

                     

                    

                     <?php 

					  if(isset($_SESSION['accountsAdmin']))

	{

					$url[] = 'pos.php';  $url[] = 'generate_proinvoice.php';

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>



					<li class="<?php echo $class; ?>">

						<a href="pos.php">

							<i class="menu-icon fa fa-tachometer"></i>

							<span class="menu-text"> PO'S </span>



						</a>

						<b class="arrow"></b>

					</li>

                    <?php } ?>

                    

                     <?php 

					  if(isset($_SESSION['accountsAdmin']))

	{

					$url[] = 'orders.php';  $url[] = 'generate_invoice.php';

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>



					<li class="<?php echo $class; ?>">

						<a href="orders.php">

							<i class="menu-icon fa fa-tachometer"></i>

							<span class="menu-text"> Orders </span>



						</a>

						<b class="arrow"></b>

					</li>

                    <?php } ?>

                     

                    

                    

                     <?php

					   if(isset($_SESSION['superAdmin']) || isset($_SESSION['admin']))

	{

					$url[] = 'reports.php'; $url[] = 'view_report.php'; 

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>



					<li class="<?php echo $class; ?>">

						<a href="reports.php">

							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text"> Daily Reports </span>



						</a>

						<b class="arrow"></b>

					</li>

                    <?php }?>

                    

                     <?php

					  

					   if(isset($_SESSION['superAdmin']) || isset($_SESSION['admin']))

	{

   

					$url[] = 'offers.php'; $url[] = 'view_offer.php'; $url[] = 'generate_offer.php'; 

					$url[] = 'pos.php'; $url[] = 'generate_proinvoice.php';  $url[] = 'view_po.php';  

					$url[] = 'orders.php'; $url[] = 'view_order.php'; $url[] = 'generate_invoice.php';

					

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>



					<li class="<?php echo $class; ?>">

						<a href="#" class="dropdown-toggle">

							<i class="menu-icon fa fa-tachometer"></i>

							<span class="menu-text"> DSQ Register  <span class="badge badge-primary">3</span> </span>

                              <b class="arrow fa fa-angle-down"></b>

						</a>



						<b class="arrow"></b>

                        <ul class="submenu">

                        

						  <li class="">

								<a href="offers.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Offers

								</a>



								<b class="arrow"></b>

							</li>

							

							<li class="">

								<a href="pos.php">

									<i class="menu-icon fa fa-caret-right"></i>

									PO'S

								</a>



								<b class="arrow"></b>

							</li>



							<li class="">

								<a href="orders.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Orders

								</a>



								<b class="arrow"></b>

							</li>



						</ul>

					</li>

                    

                    <?php  }  ?>

                    

                    <?php

					  

					   if(isset($_SESSION['superAdmin']) || isset($_SESSION['admin']) || isset($_SESSION['accountsAdmin']))

	{

   

					$url[] = 'outstandings.php'; $url[] = 'payments.php'; $url[] = 'add_payment.php'; 

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>



					<li class="<?php echo $class; ?>">

						<a href="#" class="dropdown-toggle">

							<i class="menu-icon fa fa-tachometer"></i>

							<span class="menu-text"> Collections  <span class="badge badge-primary">3</span> </span>

                              <b class="arrow fa fa-angle-down"></b>

						</a>



						<b class="arrow"></b>

                        <ul class="submenu">

						  <li class="">

								<a href="outstandings.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Outstandings

								</a>



								<b class="arrow"></b>

							</li>

							

							<li class="">

								<a href="payments.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Recieved

								</a>



								<b class="arrow"></b>

							</li>



							<li class="">

								<a href="add_payment.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Add Payment

								</a>



								<b class="arrow"></b>

							</li>



						</ul>

					</li>

                    

                    <?php  }  ?>

                    

                    <?php

					  if(isset($_SESSION['superAdmin']) || isset($_SESSION['admin']) || isset($_SESSION['logistics']))

	{

   

					 

					 

					$url[] = 'dispatched.php'; $url[] = 'add_dispatch.php'; 

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>



					<li class="<?php echo $class; ?>">

							<a href="#" class="dropdown-toggle">

							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text"> Dispatches </span>



						</a>

						<b class="arrow"></b>

                        

                         <ul class="submenu">

							



							

							<li class="">

								<a href="dispatched.php">

									<i class="menu-icon fa fa-caret-right"></i>

									View Dispatched

								</a>



								<b class="arrow"></b>

							</li>



							<li class="">

                            

								<!-- <a href="add_dispatch.php"><i class="menu-icon fa fa-caret-right"></i>Add New</a> -->



								<b class="arrow"></b>

                             <a href="ready_dispatch.php"><i class="menu-icon fa fa-caret-right"></i>Ready to dispatch</a>  

							</li>



						</ul>

					</li>

                    

                    <?php }  ?>

   <?php

					   if(isset($_SESSION['logistics']))

	{

					$url[] = 'orders_pending.php'; 

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>



					<li class="<?php echo $class; ?>">

						<a href="orders_pending.php">

							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text"> Pending Orders </span>



						</a>

						<b class="arrow"></b>

					</li>

                    <?php }?>



                    

                      <?php

					  

					 if(isset($_SESSION['ordersandExecution']))

	{

   

					$url[] = 'pos.php'; $url[] = 'pending_pro_invoices.php'; 

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>



					<li class="<?php echo $class; ?>">

						<a href="#" class="dropdown-toggle">

							<i class="menu-icon fa fa-tachometer"></i>

							<span class="menu-text"> PO'S  <span class="badge badge-primary">3</span> </span>

                              <b class="arrow fa fa-angle-down"></b>

						</a>



						<b class="arrow"></b>

                        <ul class="submenu">

						  <li class="">

								<a href="pos.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Runcards

								</a>



								<b class="arrow"></b>

							</li>

							

							<li class="">

								<a href="pending_pro_invoices.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Pending Proforma Invoices

								</a>



								<b class="arrow"></b>

							</li>

                            <li class="">

								<a href="orders_pending.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Pending Orders

								</a>



								<b class="arrow"></b>

							</li>



						</ul>

					</li>

                    

                    <?php  }  ?>

                    

                     <?php

					  

					 if(isset($_SESSION['ordersandExecution']))

	{

   $url[] = 'view_products.php'; $url[] = 'products.php'; $url[] = 'categories.php';  $url[] = 'brands.php'; 

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>

                        	<li class="<?php echo $class; ?>">

						<a href="#" class="dropdown-toggle">

							<i class="menu-icon fa fa-users"></i>



							<span class="menu-text">

								Products



								<!-- #section:basics/sidebar.layout.badge -->

								<span class="badge badge-primary">3</span>



								<!-- /section:basics/sidebar.layout.badge -->

							</span>



							<b class="arrow fa fa-angle-down"></b>

						</a>



						<b class="arrow"></b>



						<ul class="submenu">

							<li class="">

								<a href="products.php">

									<i class="menu-icon fa fa-caret-right"></i>

									View

								</a>



								<b class="arrow"></b>

							</li>



							<li class="">

								<a href="brands.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Brands

								</a>



								<b class="arrow"></b>

							</li>



						

					

                    <li class="">

								<a href="categories.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Categories

								</a>



								<b class="arrow"></b>

							</li>

                            </ul>

					</li>

                    <?php  }  ?>

                    

                    

                    

                    

  

                    

                      <?php

					    if(isset($_SESSION['superAdmin']) || isset($_SESSION['admin']))

	{

					$url[] = 'targets.php'; $url[] = 'view_target.php'; $url[] = 'year_target.php'; 

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>



					<li class="<?php echo $class; ?>">

						<a href="targets.php">

							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text"> Targets </span>



						</a>

						<b class="arrow"></b>

					</li>

                    <?php } ?>

                    

                     <?php

					  

					   if(isset($_SESSION['superAdmin']) || isset($_SESSION['admin']))

	{

   

					$url[] = 'weekly_projections.php'; $url[] = 'weekly_sales_reports.php';  

					

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>



					<li class="<?php echo $class; ?>">

						<a href="#" class="dropdown-toggle">

							<i class="menu-icon fa fa-tachometer"></i>

							<span class="menu-text"> Projections  <span class="badge badge-primary">2</span> </span>

                              <b class="arrow fa fa-angle-down"></b>

						</a>



						<b class="arrow"></b>

                        <ul class="submenu">

                        

						  <li class="">

								<a href="weekly_projections.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Weekly Projections

								</a>



								<b class="arrow"></b>

							</li>

							

							<li class="">

								<a href="weekly_sales_reports.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Weekly Sales Reports

								</a>



								<b class="arrow"></b>

							</li>



						</ul>

					</li>

                    

                    <?php  }  ?>

  

  

                    

                 <?php

					 

					 if(isset($_SESSION['superAdmin']) || isset($_SESSION['admin']) || isset($_SESSION['bdAdmin']))

	{

    

					$url[] = 'c_form.php'; $url[] = 'view_c_form.php';  $url[] = 'add_cform.php';

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>

                    <li class="<?php echo $class; ?>">

							<!--<a href="#" class="dropdown-toggle">

							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text"> C Forms </span><span class="badge badge-primary">2</span>

                            <b class="arrow fa fa-angle-down"></b>



						</a>-->

						<b class="arrow"></b>

                        

                         <ul class="submenu">

							



							

							<li class="">

								<a href="c_form.php">

									<i class="menu-icon fa fa-caret-right"></i>

									View

								</a>



								<b class="arrow"></b>

							</li>



							<li class="">

								<a href="add_cform.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Add New

								</a>



								<b class="arrow"></b>

							</li>



						</ul>

					</li>



					

                    

                    <?php } ?>   

                    

                     

                     <?php

if(isset($_SESSION['superAdmin']) || isset($_SESSION['admin']) || isset($_SESSION['bdAdmin']))

	{

$url[] = 'products.php'; $url[] = 'add_product.php'; $url[] = 'edit_product.php';  $url[] = 'manage_stock.php';  $url[] = 'manage_price.php'; 

					$url[] = 'brands.php';  $url[] = 'categories.php'; 

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>

 					<li class="<?php echo $class; ?>">

						<a href="#" class="dropdown-toggle">

							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text"> Stocks  <span class="badge badge-primary">4</span></span>

                            <b class="arrow fa fa-angle-down"></b>

						</a>

						<b class="arrow"></b>

                        

                        <ul class="submenu">

							

                            <li class="">

								<a href="products.php">

									<i class="menu-icon fa fa-caret-right"></i>

									View

								</a>



								<b class="arrow"></b>

							</li>

                            

                            

                            <li class="">

								<a href="add_product.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Add New

								</a>



								<b class="arrow"></b>

							</li>

                            

                            <li class="">

								<a href="brands.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Brands

								</a>



								<b class="arrow"></b>

							</li>

                            

                            <li class="">

								<a href="categories.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Categories

								</a>



								<b class="arrow"></b>

							</li>

                            </ul>

                            </li>

                            <?php } ?>

                            

                            

                            <?php

	if(isset($_SESSION['superAdmin']) || isset($_SESSION['admin'])|| isset($_SESSION['logistics']) || isset($_SESSION['ordersandExecution']))

	{

   

					$url[] = 'stock.php'; $url[] = 'add_stock.php';  $url[] = 'view_stock.php'; 

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>



					<li class="<?php echo $class; ?>">

						<a href="#" class="dropdown-toggle">

							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text"> Stores 

                                <span class="badge badge-primary">2</span>

                            </span>

                            

                           

                             <b class="arrow fa fa-angle-down"></b>

                             

                            

						</a>

						<b class="arrow"></b>

                        

                        <ul class="submenu">

							<li class="">

								<a href="stock.php">

									<i class="menu-icon fa fa-caret-right"></i>

									View

								</a>



								<b class="arrow"></b>

							</li>

                            <li class="">

								<a href="add_stock.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Add New

								</a>



								<b class="arrow"></b>

							</li>

                            

                            

                            

                            

						</ul>

					</li>

                    

                    

                    

                    

                    

                    

                    <li class="<?php echo $class; ?>">

						<a href="#" class="dropdown-toggle">

							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text"> MOCS 

                                <span class="badge badge-primary">2</span>

                            </span>

                            

                           

                             <b class="arrow fa fa-angle-down"></b>

                             

                            

						</a>

						<b class="arrow"></b>

                        

                        <ul class="submenu">

							

                            <li class="">

								<a href="add-mocs.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Add New

								</a>



								<b class="arrow"></b>

							</li>

                            

                            

                            

                            

						</ul>

					</li>

                    

                    

                    <?php } ?>

                     

                   

                   

                   

      <?php  

   

    if(isset($_SESSION['superAdmin']) || isset($_SESSION['admin']) || isset($_SESSION['services'])|| isset($_SESSION['logistics']))

	{

   

					$url[] = 'services.php'; $url[] = 'add_service.php'; $url[] = 'view_service.php';  $url[] = 'edit_service.php';  $url[] = 'status_service.php';  

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>

                  

                       <li class="<?php echo $class; ?>">

						<a href="#" class="dropdown-toggle">

							<i class="menu-icon fa fa-users"></i>

							<span class="menu-text">

								Services  <span class="badge badge-primary">2</span>

							</span>



							<b class="arrow fa fa-angle-down"></b>

						</a>



						<b class="arrow"></b>



						<ul class="submenu">

							



							

							<li class="">

								<a href="services.php">

									<i class="menu-icon fa fa-caret-right"></i>

									View

								</a>



								<b class="arrow"></b>

							</li>



							<li class="">

								<a href="add_service.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Add New

								</a>



								<b class="arrow"></b>

							</li>



						</ul>

					</li>

					<?php } ?>

                    

                     <?php

					 if(isset($_SESSION['superAdmin']) || isset($_SESSION['admin']) || isset($_SESSION['bdAdmin']))

	{

    

					$url = array(0=>'station_services.php', 1 => 'add_station_service.php', 2 => 'view_station_service.php', 3 => 'edit_station_service.php', 4 => 'status_station_service.php');  

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>



					<li class="<?php echo $class; ?>">

						<!--<a href="#" class="dropdown-toggle">

							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text"> Services At Station </span>



						</a>-->

						<b class="arrow"></b>

                        

                        <ul class="submenu">

							



							

							<li class="">

								<a href="station_services.php">

									<i class="menu-icon fa fa-caret-right"></i>

									View

								</a>



								<b class="arrow"></b>

							</li>



							<li class="">

								<a href="add_station_service.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Add New

								</a>



								<b class="arrow"></b>

							</li>



						</ul>

					</li>

                    

                    <?php } ?>



					

   <?php   if(isset($_SESSION['superAdmin']) || isset($_SESSION['admin']))

	{

					$url[] = 'customers.php'; $url[] = 'add_customer.php'; $url[] = 'view_customer.php';  $url[] = 'edit_customer.php'; 

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>

                  

                       <li class="<?php echo $class; ?>">

						<a href="#" class="dropdown-toggle">

							<i class="menu-icon fa fa-users"></i>

							<span class="menu-text">

								Customers  <span class="badge badge-primary">2</span>

							</span>



							<b class="arrow fa fa-angle-down"></b>

						</a>



						<b class="arrow"></b>



						<ul class="submenu">

							



							

							<li class="">

								<a href="customers.php">

									<i class="menu-icon fa fa-caret-right"></i>

									View

								</a>



								<b class="arrow"></b>

							</li>



							<li class="">

								<a href="add_customer.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Add New

								</a>



								<b class="arrow"></b>

							</li>



						</ul>

					</li>

                    <?php } ?>

                    

                    <?php

					  if(isset($_SESSION['superAdmin']) || isset($_SESSION['admin']) || isset($_SESSION['logistics']))

	{

					$url[] = 'vendors.php'; $url[] = 'add_vendor.php'; $url[] = 'add_vendor_payment.php';  $url[] = 'vendor_payments.php';     

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>

                  

                       <li class="<?php echo $class; ?>">

						<a href="#" class="dropdown-toggle">

							<i class="menu-icon fa fa-users"></i>

							<span class="menu-text">

								Vendors  <span class="badge badge-primary">4</span>

							</span>



							<b class="arrow fa fa-angle-down"></b>

						</a>



						<b class="arrow"></b>



						<ul class="submenu">

							



							

							<li class="">

								<a href="vendors.php">

									<i class="menu-icon fa fa-caret-right"></i>

									View

								</a>



								<b class="arrow"></b>

							</li>



							<li class="">

								<a href="add_vendor.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Add New

								</a>



								<b class="arrow"></b>

							</li>

                            

<!--   <li class="">

								<a href="invoices.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Invoices

								</a>



								<b class="arrow"></b>

							</li>

                            

                            <li class="">

								<a href="add_invoice.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Add Invoice

								</a>



								<b class="arrow"></b>

							</li>

-->



                        <li class="">

								<a href="vendor_payments.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Payments Made

								</a>



								<b class="arrow"></b>

							</li>

                            

                            <li class="">

								<a href="add_vendor_payment.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Add Payment

								</a>



								<b class="arrow"></b>

							</li>



						</ul>

					</li>

                    <?php } ?>

                    

                    

                     <?php

					   if(isset($_SESSION['superAdmin']) || isset($_SESSION['admin']))

	{

					$url[] = 'employees.php'; $url[] = 'add_employee.php'; $url[] = 'view_employee.php';  $url[] = 'edit_employee.php'; 

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>

                  

                       <li class="<?php echo $class; ?>">

						<a href="#" class="dropdown-toggle">

							<i class="menu-icon fa fa-users"></i>

							<span class="menu-text">

								Employees  <span class="badge badge-primary">2</span>

							</span>



							<b class="arrow fa fa-angle-down"></b>

						</a>



						<b class="arrow"></b>



						<ul class="submenu">

							



							

							<li class="">

								<a href="employees.php">

									<i class="menu-icon fa fa-caret-right"></i>

									View

								</a>



								<b class="arrow"></b>

							</li>



							<li class="">

								<a href="add_employee.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Add New

								</a>



								<b class="arrow"></b>

							</li>



						</ul>

					</li>

                    <?php } ?>



                    

                      <?php

					    if(isset($_SESSION['superAdmin']) || isset($_SESSION['admin']))

	{

					$url[] = 'teams.php'; $url[] = 'add_team.php'; 

					if (in_array($requestUrl,$url))

					{ $class = 'active'; } else { $class = ''; }

					unset($url);

					?>

                  

                       <!--<li class="<?php #echo $class; ?>">

						<a href="#" class="dropdown-toggle">

							<i class="menu-icon fa fa-users"></i>

							<span class="menu-text">

								Teams  <span class="badge badge-primary">2</span>

							</span>



							<b class="arrow fa fa-angle-down"></b>

						</a>



						<b class="arrow"></b>



						<ul class="submenu">

							



							

							<li class="">

								<a href="teams.php">

									<i class="menu-icon fa fa-caret-right"></i>

									View

								</a>



								<b class="arrow"></b>

							</li>



							<li class="">

								<a href="add_team.php">

									<i class="menu-icon fa fa-caret-right"></i>

									Add New

								</a>



								<b class="arrow"></b>

							</li>



						</ul>

					</li>-->                      

					<?php } ?>

                    



            </ul><!-- /.nav-list -->



				<!-- #section:basics/sidebar.layout.minimize -->

				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">

					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>

				</div>



				<!-- /section:basics/sidebar.layout.minimize -->

				<script type="text/javascript">

					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}

				</script>

			</div>



			<!-- /section:basics/sidebar -->