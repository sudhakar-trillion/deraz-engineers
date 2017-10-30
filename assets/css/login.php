<?php ob_start(); session_start();  include("includes/db.php");


 // check user is logged in or not
   if(isset($_SESSION['superAdmin']))
	 {
		 header("location: index.php"); 
	 }
	 else if(isset($_SESSION['admin']))
	 {
		  header("location: index.php"); 
	 }
	 else if(isset($_SESSION['accountsAdmin']))
	 {
		  header("location: services.php"); 
	 }
	 else if(isset($_SESSION['sales']))
	 {
		 header("location: sa_dashboard.php"); 
	 }
	 else if(isset($_SESSION['bdAdmin']))
	 {
    	 header("location: enquiries.php"); 
	 }
	  else if(isset($_SESSION['ordersandExecution']))
	 {
    	 header("location: pos.php"); 
	 }
	  else if(isset($_SESSION['logistics']))
	 {
    	 header("location: stock.php"); 
	 }
	  else if(isset($_SESSION['services']))
	 {
    	 header("location: services.php"); 
	 }
	
	 
	 
  
  // forgot pwd
  if(isset($_POST['forgotPassword']))
  {
	
	  $result = mysql_query("select * from employees where email = '". $_POST['email'] ."'");
  
  if(mysql_num_rows($result)>0)
  {
	  $code = rand(1000,100000);
	
	  mysql_query("insert into forgotpwd (`email`, `code`) values('". $_POST['email'] ."', '$code')");
	  
	  // mail for retrieve link:
	  
$headers = "From: " . strip_tags('no-reply@derazengineers.com') . "\r\n";
$headers .= "Reply-To: ". strip_tags('no-reply@derazengineers.com') . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$message = 'Hi <br />
            <a href="http://trillionit.in/derazerprevised/retrieve_pasword.php?code='.$code.'">Click here</a> to retieve your password. <br /><br />
			regards<br />
			Deraz Team';


mail($_POST['email'],'Retieve Password',$message,$headers);

	  
	  header("location: login.php?retrievePasword=success"); exit;  
  }
  else
  {
 	  header("location: login.php?error=2"); exit;  
  }
  
  }
  
  
  // login
  if(isset($_POST['login']))
  {
 
 
  if(isset($_POST['remember']))
  {
	
	   mysql_query("update employees set remember = 'no' where ip = '". $_SERVER['REMOTE_ADDR'] ."'");
	  // remmember query
	   mysql_query("update employees set ip = '". $_SERVER['REMOTE_ADDR'] ."', remember = 'yes' where userName = '". $_POST['userName'] ."' and password = '". $_POST['password'] ."'");
  } else {
	 
	   mysql_query("update employees set ip = '". $_SERVER['REMOTE_ADDR'] ."', remember = 'no' where userName = '". $_POST['userName'] ."' and password = '". $_POST['password'] ."'");
  }
  $result = mysql_query("select * from employees where userName = '". $_POST['userName'] ."' and password = '". $_POST['password'] ."' and status = '1'");
  
  if(mysql_num_rows($result)>0)
  {
	
	 $row = mysql_fetch_array($result);
	 
	 $_SESSION['id'] = $row['id'];
	 $_SESSION['employeeId'] = $row['employeeId']; 
	 $_SESSION['firstName'] = $row['firstName']; 
	 
	 
	 if($row['roll']==1)
	 {
		 $_SESSION['superAdmin'] = 1;
		 $url = 'index.php';
	 }
	 else if($row['roll']==2)
	 {
		 $_SESSION['admin'] = 1;
		 $url = 'index.php';
	 }
	 else if($row['roll']==3)
	 {
		 $_SESSION['accountsAdmin'] = 1;
		 $url = 'services.php';
	 }
	 else if($row['roll']==4)
	 {
		 $_SESSION['sales'] = 1;
		 $url = 'sa_dashboard.php';
	 }
	 else if($row['roll']==5)
	 {
		 $_SESSION['bdAdmin'] = 1;
		 $url = 'dispatched.php';
	 }
	  else if($row['roll']==6)
	 {
		 $_SESSION['ordersandExecution'] = 1;
		 $url = 'pos.php';
	 }
	  else if($row['roll']==7)
	 {
		 $_SESSION['logistics'] = 1;
		 $url = 'stock.php';
	 }
	 else if($row['roll']==8)
	 {
		 $_SESSION['services'] = 1;
		 $url = 'services.php';
	 }
	 
	 
	 header("location: ".$url); exit;
  }
  else
  {
	 header("location: login.php?error=1"); exit; 
  }
  }
  
  $result = mysql_query("select userName, password from employees where ip = '". $_SERVER['REMOTE_ADDR'] ."' and remember = 'yes'");
  
  if(mysql_num_rows($result)>0)
  {
	$result = mysql_fetch_array($result);
	
	$userName = $result['userName'];  
	$password = $result['password'];  
	$checked = 1;
	
  }
  else
  {
	 $userName = '';  
	 $password = '';  
	 $checked = 0; 
  }
  
?>



<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Login Page - Tri Smart</title>

		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="assets/css/bootstrap.css" />
		<link rel="stylesheet" href="assets/css/font-awesome.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="assets/css/ace.css" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="../assets/css/ace-part2.css" />
		<![endif]-->
		<link rel="stylesheet" href="assets/css/ace-rtl.css" />

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="../assets/css/ace-ie.css" />
		<![endif]-->

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!--[if lt IE 9]>
		<script src="../assets/js/html5shiv.js"></script>
		<script src="../assets/js/respond.js"></script>
		<![endif]-->
	</head>
<!--style="background:url(assets/images/deraz1.jpg);"-->
	<body class="login-layout" style="background:#fff;" >
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container" style="margin-top:20px;">
							<div class="center">
                            							<!--<div class="space-20"></div>
-->
								<h1 style="margin-top:60px;">
									<img src="assets/images/logo_deraz.png" style="width:140px;" />
                                    
									
								</h1>
								<!--<h4 class="blue" id="id-company-text">&copy; Company Name</h4>-->
							</div>

							<!--<div class="space-6"></div>-->
                            							


							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger" style="font-size: 16px;font-weight: bold;
text-align: center;letter-spacing: 0.5px; border-bottom-color: #fff; color:#4a4a4a;">
												<i class="ace-icon fa fa-coffee green" style="color:#4a4a4a;"></i>
												Please Enter Your Information
											</h4>

											<div class="space-6"></div>

											<form method="post" action="">
												<fieldset>
													<label class="block clearfix" for="userName">
														<span class="block input-icon input-icon-right">
						<input type="text" class="form-control" placeholder="Username" name="userName" id="userName" value="<?php echo $userName; ?>" />
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix" for="password">
														<span class="block input-icon input-icon-right">
		  	<input type="password" class="form-control" placeholder="Password" name="password" id="password" value="<?php echo $password; ?>" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>

													<div class="space"></div>

													<div class="clearfix">
														<label class="inline">
			<input type="checkbox" class="ace" name="remember" value="1" <?php if($checked==1) { ?> checked <?php } ?> />
															<span class="lbl"> Remember Me</span>
														</label>

														<button type="submit" style="width:77px !important;" name="login" class="width-35 pull-right btn btn-sm btn-primary">
															<i class="ace-icon fa fa-key"></i>
															<span class="bigger-110">Login</span>
														</button>
													</div>

													<div class="space-4"></div>
												</fieldset>
											</form>

										
										</div><!-- /.widget-main -->

										<div class="toolbar clearfix">
											<div>
												<a href="#" data-target="#forgot-box" class="forgot-password-link">
													<i class="ace-icon fa fa-arrow-left"></i>
													I forgot my password
												</a>
											</div>

											<div>
												
											</div>
										</div>
									</div><!-- /.widget-body -->
                                    	
								</div><!-- /.login-box -->
                                
                                <!--<div class="space-20"></div>
                                <div class="space-20"></div>
                                <div class="space-20"></div>
                                <div class="space-20"></div>
                                <div class="space-20"></div>
                                <div class="space-20"></div>-->
                               <!-- <div class="space-20"></div>-->
                                
								<div id="forgot-box" class="forgot-box widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header red lighter bigger">
												<i class="ace-icon fa fa-key"></i>
												Retrieve Password
											</h4>

											<div class="space-6"></div>
											<p>
												Enter your email and to receive instructions
											</p>

											<form action="" method="post">
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="email" name="email" class="form-control" placeholder="Email" />
															<i class="ace-icon fa fa-envelope"></i>
														</span>
													</label>

													<div class="clearfix">
														<button type="submit" name="forgotPassword" class="width-35 pull-right btn btn-sm btn-danger">
															<i class="ace-icon fa fa-lightbulb-o"></i>
															<span class="bigger-110">Send Me!</span>
														</button>
													</div>
												</fieldset>
											</form>
										</div><!-- /.widget-main -->

										<div class="toolbar center">
											<a href="#" data-target="#login-box" class="back-to-login-link">
												Back to login
												<i class="ace-icon fa fa-arrow-right"></i>
											</a>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.forgot-box -->

								<div id="signup-box" class="signup-box widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header green lighter bigger">
												<i class="ace-icon fa fa-users blue"></i>
												New User Registration
											</h4>

											<div class="space-6"></div>
											<p> Enter your details to begin: </p>

											<form>
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="email" class="form-control" placeholder="Email" />
															<i class="ace-icon fa fa-envelope"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control" placeholder="Username" />
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" placeholder="Password" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" placeholder="Repeat password" />
															<i class="ace-icon fa fa-retweet"></i>
														</span>
													</label>

													<label class="block">
														<input type="checkbox" class="ace" />
														<span class="lbl">
															I accept the
															<a href="#">User Agreement</a>
														</span>
													</label>

													<div class="space-24"></div>

													<div class="clearfix">
														<button type="reset" class="width-30 pull-left btn btn-sm">
															<i class="ace-icon fa fa-refresh"></i>
															<span class="bigger-110">Reset</span>
														</button>

														<button type="button" class="width-65 pull-right btn btn-sm btn-success">
															<span class="bigger-110">Register</span>

															<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
														</button>
													</div>
												</fieldset>
											</form>
										</div>

										<div class="toolbar center">
											<a href="#" data-target="#login-box" class="back-to-login-link">
												<i class="ace-icon fa fa-arrow-left"></i>
												Back to login
											</a>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.signup-box -->
							</div><!-- /.position-relative -->

							<!--<div class="navbar-fixed-top align-right">
								<br />
								&nbsp;
								<a id="btn-login-dark" href="#">Dark</a>
								&nbsp;
								<span class="blue">/</span>
								&nbsp;
								<a id="btn-login-blur" href="#">Blur</a>
								&nbsp;
								<span class="blue">/</span>
								&nbsp;
								<a id="btn-login-light" href="#">Light</a>
								&nbsp; &nbsp; &nbsp;
							</div>-->
						</div>
                        <p align="center" style="font-size:14px; padding-top:15px; padding-bottom:15px;">
                                
                                <span class="">
							<!--<span class="orange bolder">Tri</span>
<span class="blue bolder">SMART</span>
<span class="green bolder"> ERP </span>--><img src="assets/images/logo_smart.png" style="width:90px;" /> by <a href="http://www.trillionit.com/" target="_blank">Trillion IT</a> © 2017
						</span>

</p>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
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

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
			 $(document).on('click', '.toolbar a[data-target]', function(e) {
				e.preventDefault();
				var target = $(this).data('target');
				$('.widget-box.visible').removeClass('visible');//hide others
				$(target).addClass('visible');//show target
			 });
			});
			
			
			
			//you don't need this, just used for changing background
			jQuery(function($) {
			 $('#btn-login-dark').on('click', function(e) {
				$('body').attr('class', 'login-layout');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'blue');
				
				e.preventDefault();
			 });
			 $('#btn-login-light').on('click', function(e) {
				$('body').attr('class', 'login-layout light-login');
				$('#id-text2').attr('class', 'grey');
				$('#id-company-text').attr('class', 'blue');
				
				e.preventDefault();
			 });
			 $('#btn-login-blur').on('click', function(e) {
				$('body').attr('class', 'login-layout blur-login');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'light-blue');
				
				e.preventDefault();
			 });
			 
			});
		</script>
	</body>
</html>
