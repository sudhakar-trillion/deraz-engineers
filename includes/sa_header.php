<?php ob_start(); session_start(); 

$mid = $_SESSION['id'];

  if(!(isset($_SESSION['sales'])))
  {  header("location: logout.php"); exit; }
  require("includes/db.php");
  $requestUrl = explode('/',$_SERVER['PHP_SELF']);
  $requestUrl = $requestUrl[count($requestUrl)-1]; 
  
  
   $dailyNotifications = mysql_query("select daily_report_authorization.autoId, daily_report_authorization.reportDate, daily_report_authorization.reportMonth, daily_report_authorization.createdOn, daily_report_authorization.reportType from daily_report_authorization
	
	  where daily_report_authorization.authorization = 'Yes' and daily_report_authorization.employeeId = '". $_SESSION['id'] ."' and daily_report_authorization.status = '0'");
	  
	  
	  
	  $profileImage = mysql_query("select image from employee_image where employeeId = '". $_SESSION['id'] ."'");
	  
	  if(mysql_num_rows($profileImage)>0)
	  {
		 $profileImage = mysql_fetch_array($profileImage); 
		 $profileImageSrc = 'assets/empimgs/'.$_SESSION['id'].'/'.$profileImage['image'];  
		 $profileImageSrc = 'assets/avatars/user.48.png';
	  }
	  else
	  {
	    $profileImageSrc = 'assets/avatars/user.48.png';
	  }
	  
	  // team
	  $myteam = mysql_query("select teamId from teams where teamLeaderId = '". $_SESSION['id'] ."'");
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

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="../assets/js/html5shiv.js"></script>
		<script src="../assets/js/respond.js"></script>
		<![endif]-->
	</head>

	<body class="no-skin">
		<!-- #section:basics/navbar.layout -->
		<div id="navbar" class="navbar navbar-default" style="background:#0d5685;">
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
					<a href="#" class="navbar-brand" style="padding-top:0px !important; padding-bottom:0px !important;">
						<small>
						
                
                            <img src="assets/images/derazlogo.png"  />
							
						</small>
					</a>

					<!-- /section:basics/navbar.layout.brand -->

					<!-- #section:basics/navbar.toggle -->

					<!-- /section:basics/navbar.toggle -->
				</div>

				<!-- #section:basics/navbar.dropdown -->
				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
						
                        <?php $numNotificatios =  mysql_num_rows($dailyNotifications);
							  if($numNotificatios>0) { 		     
									?>
                           <li class="purple">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="ace-icon fa fa-bell icon-animated-bell"></i>
								<span class="badge badge-important"><?php echo $numNotificatios; ?></span>
							</a>

							<ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
								<li class="dropdown-header">
									<i class="ace-icon fa fa-exclamation-triangle"></i>
									<?php  echo $numNotificatios." Notifications"; ?>
								</li>

								<li class="dropdown-content">
									<ul class="dropdown-menu dropdown-navbar navbar-pink">
                                     <?php  while($dailyNotification = mysql_fetch_array($dailyNotifications)) {
										
										
										
										if(strcmp($dailyNotification['reportType'],'daily')==0)
										 {
											 $reportDate = explode('-',$dailyNotification['reportDate']);
										     $reportDate = $reportDate[2].'-'.$reportDate[1].'-'.$reportDate[0];
											 
											 $msg = 'Authorization granted to add daily report for '.$reportDate; 
										 }
										 else if(strcmp($dailyNotification['reportType'],'monthly')==0)
										 {  
										 
										       if($dailyNotification['reportMonth']==1) {  $reportMonth = 'January'; }
										  else if($dailyNotification['reportMonth']==2) {  $reportMonth = 'Febraury'; }
										  else if($dailyNotification['reportMonth']==3) {  $reportMonth = 'March'; }
										  else if($dailyNotification['reportMonth']==4) {  $reportMonth = 'April'; }
										  else if($dailyNotification['reportMonth']==5) {  $reportMonth = 'May'; }
										  else if($dailyNotification['reportMonth']==6) {  $reportMonth = 'June'; }
										  else if($dailyNotification['reportMonth']==7) {  $reportMonth = 'July'; }
										  else if($dailyNotification['reportMonth']==8) {  $reportMonth = 'August'; }
										  else if($dailyNotification['reportMonth']==9) {  $reportMonth = 'September'; }
										  else if($dailyNotification['reportMonth']==10) {  $reportMonth = 'October'; }
										  else if($dailyNotification['reportMonth']==11) {  $reportMonth = 'November'; }
										  else if($dailyNotification['reportMonth']==12) {  $reportMonth = 'December'; }
										  
										 
											 $msg = 'Authorization granted to add daily report for  '.$reportMonth.' month.';  
										 }
										
										 ?>
                                          <li>
											<a href="authorizations.php?nid=<?php echo $dailyNotification['autoId'];  ?>">
												<div class="clearfix">
													<span class="pull-left">
														<i class="btn btn-xs btn-primary fa fa-user"></i>
														<?php echo $msg; ?>
													</span>
													
												</div>
											</a>
										</li>
                                    <?php } ?>
                                   
									</ul>
								</li>

								<li class="dropdown-footer">
									<a href="authorizations.php">
										See all notifications
										<i class="ace-icon fa fa-arrow-right"></i>
									</a>
								</li>
							</ul>
						</li>
                        
                        <?php } ?>


						<!-- #section:basics/navbar.user_menu -->
						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
                            	<img class="nav-user-photo" src="<?php echo $profileImageSrc; ?>" alt="Jason's Photo" />
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

				<!-- /.sidebar-shortcuts -->

				<ul class="nav nav-list">
					 
                      <?php
					$url[] = 'sa_dashboard.php'; $url[] = 'view_sa_dashboard.php'; 
					if (in_array($requestUrl,$url))
					{ $class = 'active'; } else { $class = ''; }
					unset($url);
					?>

					<li class="<?php echo $class; ?>">
						<a href="sa_dashboard.php">
							<i class="menu-icon fa fa-list"></i>
							<span class="menu-text"> Dashboard </span>

						</a>
						<b class="arrow"></b>
					</li>
                    
                     <?php
					$url[] = 'sa_enquiries.php';  
					if (in_array($requestUrl,$url))
					{ $class = 'active'; } else { $class = ''; }
					unset($url);
					?>

					<li class="<?php echo $class; ?>">
						<a href="sa_enquiries.php">
							<i class="menu-icon fa fa-list"></i>
							<span class="menu-text"> Enquiries </span>

						</a>
						<b class="arrow"></b>
					</li>
                    
                    <?php
					if(mysql_num_rows($myteam)>0)
					 {
					$url[] = 'sa_team.php'; 
					if (in_array($requestUrl,$url))
					{ $class = 'active'; } else { $class = ''; }
					unset($url);
					?>
<!--
					<li class="<?php echo $class; ?>">
						<a href="sa_team.php">
							<i class="menu-icon fa fa-list"></i>
							<span class="menu-text"> Team </span>

						</a>
						<b class="arrow"></b>
					</li>  
 -->                       
                    <?php }
					
					$url[] = 'sa_daily_reports.php'; $url[] = 'sa_add_daily_report.php'; $url[] = 'sa_view_daily_report.php';  $url[] = 'sa_edit_daily_report.php'; 
					
					if (in_array($requestUrl,$url))
					{ $class = 'active'; } else { $class = ''; }
					unset($url);
					?>
                        	<li class="<?php echo $class; ?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-users"></i>

							<span class="menu-text">
								Daily Reports

								<!-- #section:basics/sidebar.layout.badge -->
								<span class="badge badge-primary">2</span>

								<!-- /section:basics/sidebar.layout.badge -->
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li class="">
								<a href="sa_add_daily_report.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Add New
								</a>

								<b class="arrow"></b>
							</li>
                             <li class="">
								<a href="sa_daily_reports.php">
									<i class="menu-icon fa fa-caret-right"></i>
									View
								</a>

								<b class="arrow"></b>
							</li>
                           

						</ul>
					</li>
                    
                    
                    
                      <?php
					$url[] = 'sa_offers.php'; $url[] = 'sa_view_offer.php';  $url[] = 'add_po.php'; 
					if (in_array($requestUrl,$url))
					{ $class = 'active'; } else { $class = ''; }
					unset($url);
					?>

					<li class="<?php echo $class; ?>">
						<a href="sa_offers.php">
							<i class="menu-icon fa fa-list"></i>
							<span class="menu-text"> Offers </span>

						</a>
						<b class="arrow"></b>
					</li>
                    
                    
                    
                    <?php
					$url[] = 'sa_pos.php'; $url[] = 'sa_view_po.php'; 
					if (in_array($requestUrl,$url))
					{ $class = 'active'; } else { $class = ''; }
					unset($url);
					?>

					<li class="<?php echo $class; ?>">
						<a href="sa_pos.php">
							<i class="menu-icon fa fa-list"></i>
							<span class="menu-text"> PO's </span>

						</a>
						<b class="arrow"></b>
					</li>
                    
                    
                    <?php
					$url[] = 'sa_orders.php'; $url[] = 'sa_view_order.php'; 
					if (in_array($requestUrl,$url))
					{ $class = 'active'; } else { $class = ''; }
					unset($url);
					?>

					<li class="<?php echo $class; ?>">
						<a href="sa_orders.php">
							<i class="menu-icon fa fa-list"></i>
							<span class="menu-text"> Orders Raised </span>

						</a>
						<b class="arrow"></b>
					</li>
                    
                    
                    
                    
                    
                      <?php 
					/*$url[] = 'sa_add_actionplan.php'; $url[] = 'sa_actionplans.php'; 
					if (in_array($requestUrl,$url))
					{ $class = 'active'; } else { $class = ''; }
					unset($url);*/
					?>
                        	<!--<li class="">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Action Plans

								
								<span class="badge badge-primary">2</span>

								
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li class="">
								<a href="sa_add_actionplan.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Add New
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="sa_actionplans.php">
									<i class="menu-icon fa fa-caret-right"></i>
									View
								</a>

								<b class="arrow"></b>
							</li>

						</ul>
					</li>-->
                    
                    
                     <?php 
					 $url[] = 'sa_outstanding.php';  $url[] = 'sa_payments.php';   
					if (in_array($requestUrl,$url))
					{ $class = 'active'; } else { $class = ''; }
					unset($url);
					?>
                        	<li class="<?php echo $class; ?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Payments 
                            </span>
                            
                            <b class="arrow fa fa-angle-down"></b>
						</a>
                        
                        	<b class="arrow"></b>
                        
                        <ul class="submenu">
							<li class="">
								<a href="sa_outstanding.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Outstanding
								</a>

								<b class="arrow"></b>
							</li>
                            <li class="">
								<a href="sa_payments.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Recieved
								</a>

								<b class="arrow"></b>
							</li>

						</ul>
					</li>
                    
                    <?php 
					 $url[] = 'sa_collections.php';  $url[] = 'sa_projections.php';   $url[] = 'sa_add_projection.php';         $url[] = 'sa_sales_projection.php';   $url[] = 'sa_add_sales_projection.php';
					if (in_array($requestUrl,$url))
					{ $class = 'active'; } else { $class = ''; }
					unset($url);
					?>
                        	<li class="<?php echo $class; ?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-list"></i>

							<span class="menu-text">
								Projections
                            </span>
                            
                            <b class="arrow fa fa-angle-down"></b>
						</a>
                        
                        	<b class="arrow"></b>
                        
                        <ul class="submenu">
								<b class="arrow"></b>
							
                            
                            
                            <li class="">
								<a href="sa_add_sales_projection.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Add Sales
								</a>

								<b class="arrow"></b>
							</li>
                            
                            <li class="">
								<a href="sa_sales_projection.php">
									<i class="menu-icon fa fa-caret-right"></i>
									View Sales
								</a>

								<b class="arrow"></b>
							</li>
                            
                            <li class="">
								<a href="sa_add_projection.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Add Collections
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="sa_projections.php">
									<i class="menu-icon fa fa-caret-right"></i>
									View Collections
								</a>

								<b class="arrow"></b>
							</li>

						</ul>
					</li>
                  
                   <?php 
					$url[] = 'sa_add_customer.php'; $url[] = 'sa_customers.php'; $url[] = 'sa_view_customer.php';  $url[] = 'sa_edit_customer.php'; 
					if (in_array($requestUrl,$url))
					{ $class = 'active'; } else { $class = ''; }
					unset($url);
					?>
                        	<li class="<?php echo $class; ?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-users"></i>

							<span class="menu-text">
								Customers

								<!-- #section:basics/sidebar.layout.badge -->
								<span class="badge badge-primary">2</span>

								<!-- /section:basics/sidebar.layout.badge -->
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li class="">
								<a href="sa_add_customer.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Add New
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="sa_customers.php">
									<i class="menu-icon fa fa-caret-right"></i>
									View
								</a>

								<b class="arrow"></b>
							</li>
                            

						</ul>
					</li>
                    
                    
                    
                    
                    
                    
                     <?php 
					$url[] = 'sa_view_products.php'; $url[] = 'sa_products.php'; $url[] = 'sa_categories.php';  $url[] = 'sa_brands.php'; 
					if (in_array($requestUrl,$url))
					{ $class = 'active'; } else { $class = ''; }
					unset($url);
					?>
                        	<li class="<?php echo $class; ?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-users"></i>

							<span class="menu-text">
								Stocks

								<!-- #section:basics/sidebar.layout.badge -->
								<span class="badge badge-primary">3</span>

								<!-- /section:basics/sidebar.layout.badge -->
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li class="">
								<a href="sa_products.php">
									<i class="menu-icon fa fa-caret-right"></i>
									View
								</a>

								<b class="arrow"></b>
							</li>

							<li class="">
								<a href="sa_brands.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Brands
								</a>

								<b class="arrow"></b>
							</li>

						
					
                    <li class="">
								<a href="sa_categories.php">
									<i class="menu-icon fa fa-caret-right"></i>
									Categories
								</a>

								<b class="arrow"></b>
							</li>

						</ul>
                      
                    
                     
            <!-- /.nav-list -->

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