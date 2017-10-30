<?php include("includes/header.php"); 


		if(isset($_POST['submit']))
		{
			
			$employeeId = mysql_query("select employeeId from employees order by employeeId desc limit 1");
			$employeeId = mysql_fetch_array($employeeId);
			$employeeId = $employeeId['employeeId']+1;

             $dob = explode('-',$_POST['dateBirth']);
			 $dob = $dob[2].'-'.$dob[1].'-'.$dob[0];
		
			 $doj = explode('-',$_POST['dateJoining']);
		 	 $doj = $doj[2].'-'.$doj[1].'-'.$doj[0];


		   mysql_query("insert into employees (`employeeId`, `roll`, `firstName`, `fatherName`, `dob`, `bloodGroup`, `userName`, `password`, `email`, `officialEmail`, `gender`, `designation`, `dateJoining`, `emergencyContact`, `emergencyName`, `emergencyRelation`, `personalMobile`, `cHno`, `cStreet`, `cLandmark`, `cCity`, `cArea`, `cPincode`, `pHno`, `pStreet`, `pLandmark`, `pCity`, `pArea`, `pPincode`,  `status`, `dateTime`) values ('". $employeeId ."', '". $_POST['roll'] ."', '". $_POST['firstName'] ."', '". $_POST['fatherName'] ."', '". $dob ."', '". $_POST['bloodGroup'] ."', '". $_POST['username2'] ."', '". $_POST['password3'] ."', '". $_POST['email'] ."', '". $_POST['officialEmail'] ."', '". $_POST['gender'] ."', '". $_POST['designation'] ."', '". $doj ."', '". $_POST['emergencyContact'] ."', '". $_POST['emergencyName'] ."', '". $_POST['relation'] ."', '". $_POST['mobile'] ."', '". $_POST['c_hno'] ."', '". $_POST['c_street'] ."', '". $_POST['c_landmark'] ."', '". $_POST['c_city'] ."', '". $_POST['c_area'] ."', '". $_POST['c_pincode'] ."', '". $_POST['p_hno'] ."', '". $_POST['p_street'] ."', '". $_POST['p_landmark'] ."', '". $_POST['p_city'] ."', '". $_POST['p_area'] ."', '". $_POST['p_pincode'] ."', '1', NOW())");			
			
			$lastId = mysql_insert_id();
			
			if($lastId>0)
			{
				
		    // mail code
			$subject = 'Employee registration at DERAZ';
			
			$headers = "From: " . strip_tags('no-reply@trillionit.in') . "\r\n";
			$headers .= "Reply-To: ". strip_tags('no-reply@trillionit.in') . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			$message = 'Hi '.$_POST['firstName'].',
			
			Login : http://www.derazengineers.com/trismart/login.php
			<table>
			<tr><td>Username:</td><td>'.$_POST["username2"].'</td></tr>
			<tr><td>Password:</td><td>'.$_POST["password3"].'</td></tr>
			
			</table>';
			
			mail($_POST['email'],$subject,$message,$headers);
				
				
				
			// 	
		      if(mkdir("assets/empimgs/".$lastId))
			  {
				 if(strlen($_FILES['image']['name'])>3)
				 {
			    	if(move_uploaded_file($_FILES['image']['tmp_name'],"assets/empimgs/".$lastId."/".$_FILES['image']['name']))
					{
					  
					   mysql_query("insert into employee_image (`employeeId`, `image`) values ('$lastId', '". $_FILES['image']['name'] ."')");
					}
					
				 }
				   
			  }
		
			  header("location: add_employee.php?add=1");	
			}
			else
			{
			  header("location: add_employee.php?error=1");	
			}
			
		}

 $roles = mysql_query("select * from rolls where roll_Id > '2' order by roll");
 $branches = mysql_query("select branchId, branch from branches order by branch");
  

?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="employees.php">Employees</a>
							</li>

							<li class="active">Add Employee</li>
						</ul><!-- /.breadcrumb -->

						<!-- #section:basics/content.searchbox -->
						<div class="nav-search" id="nav-search">
							
						</div><!-- /.nav-search -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- #section:settings.box -->
						<div class="ace-settings-container" id="ace-settings-container">
							<div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
								<i class="ace-icon fa fa-cog bigger-130"></i>
							</div>

							<div class="ace-settings-box clearfix" id="ace-settings-box">
								<div class="pull-left width-50">
									<!-- #section:settings.skins -->
									<div class="ace-settings-item">
										<div class="pull-left">
											<select id="skin-colorpicker" class="hide">
												<option data-skin="no-skin" value="#438EB9">#438EB9</option>
												<option data-skin="skin-1" value="#222A2D">#222A2D</option>
												<option data-skin="skin-2" value="#C6487E">#C6487E</option>
												<option data-skin="skin-3" value="#D0D0D0" selected="selected">#D0D0D0</option>
											</select>
										</div>
										<span>&nbsp; Choose Skin</span>
									</div>

									<!-- /section:settings.skins -->

									<!-- #section:settings.navbar -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
										<label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
									</div>

									<!-- /section:settings.navbar -->

									<!-- #section:settings.sidebar -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
										<label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
									</div>

									<!-- /section:settings.sidebar -->

									<!-- #section:settings.breadcrumbs -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
										<label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
									</div>

									<!-- /section:settings.breadcrumbs -->

									<!-- #section:settings.rtl -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
										<label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
									</div>

									<!-- /section:settings.rtl -->

									<!-- #section:settings.container -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container" />
										<label class="lbl" for="ace-settings-add-container">
											Inside
											<b>.container</b>
										</label>
									</div>

									<!-- /section:settings.container -->
								</div><!-- /.pull-left -->

								<div class="pull-left width-50">
									<!-- #section:basics/sidebar.options -->
									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-hover" />
										<label class="lbl" for="ace-settings-hover"> Submenu on Hover</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-compact" />
										<label class="lbl" for="ace-settings-compact"> Compact Sidebar</label>
									</div>

									<div class="ace-settings-item">
										<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-highlight" />
										<label class="lbl" for="ace-settings-highlight"> Alt. Active Item</label>
									</div>

									<!-- /section:basics/sidebar.options -->
								</div><!-- /.pull-left -->
							</div><!-- /.ace-settings-box -->
						</div><!-- /.ace-settings-container -->

						<!-- /section:settings.box -->
					

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								

							



								<div class="row">
									<div class="col-xs-12">
										
										<div class="table-header">
											Add Employee
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div>
											
                                           <?php
	   if(isset($_GET['add']))
{ echo '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Employee has been added!</div>'; }
else if(isset($_GET['error']))
{ echo '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  
										   ?> 
                                            
                                           
                                            
                               
                                            
                                    <form class="form-horizontal row-border" id="validation-form" action="" method="post" enctype="multipart/form-data" autocomplete="off">
                                
                                   <div class="form-group">
										<label for="firstName" class="col-md-2 control-label">Full Name<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="firstName" id="firstName" class="form-control required" >
                                            </div>
										</div>
                                        <label for="fatherName" class="col-md-2 control-label">Father's Name<span class="required">*</span></label>
                                        <div class="col-md-4"> 
                                         <div class="clearfix">
											<input type="text" name="fatherName" id="fatherName" class="form-control" >
                                            </div>
										</div>
									</div>
                                    
                                    <div class="form-group">
                                   
										<label class="col-md-2 control-label">Gender<span class="required">*</span> </label>
										<div class="col-md-4">
                                         <div class="clearfix">
											<label class="radio-inline" for="male">
												<input type="radio" class="uniform" name="gender" id="male" value="Male">
												Male
											</label>
                                            
											<label class="radio-inline" for="female">
												<input type="radio" class="uniform" name="gender" id="female" value="Female">
												Female
											</label>
                                            </div>
											
										</div>
                                        
                                        <label class="col-md-2 control-label" id="dateBirth">Date of Birth<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="dateBirth" id="dateBirth" class="form-control date-picker1" >
                                            </div>
										</div>
                                       
									</div>
                                    
                                    
                                    
                                    <div class="form-group">
                                     
										<label class="col-md-2 control-label" for="bloodGroup">Blood Group<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<select name="bloodGroup" id="bloodGroup" class="form-control">
                                            <option value="">Select Blood Group</option>
                                            <option value="A1 +ve">A1 +ve</option>
                                            <option value="A1 -ve">A1 -ve</option>
                                            <option value="B +ve">B +ve</option>
                                            <option value="B -ve">B -ve</option>
                                            <option value="B1 +ve">B1 +ve</option>
                                            <option value="A2 +ve">A2 +ve</option>
                                            <option value="A2 −ve">A2 −ve</option>
                                            <option value="A1B +ve">A1B +ve</option>
                                            <option value="A1B −ve">A1B −ve</option>
                                            <option value="A2B +ve">A2B +ve</option>
                                            <option value="A2B −ve">A2B −ve</option>
                                            <option value="O +ve">O +ve</option>
                                            <option value="O −ve">O −ve</option>
                                            </select>
                                            </div>
										</div>
                                        
                                        
                                        <label class="col-md-2 control-label" for="mobile">Personal Mobile<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="mobile" id="mobile" class="form-control required" >
                                            </div>
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-md-2 control-label" for="email">Personal Email<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="email" id="email" class="form-control required email" >
                                            </div>
										</div>
                                        <label class="col-md-2 control-label" for="officialEmail">Official Email<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="officialEmail" id="officialEmail" class="form-control required email" >
                                            </div>
										</div>
									</div>
                                    
                                    
                                    
                                    <div class="form-group">
										
                                        <label class="col-md-2 control-label">Emergency Contact<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="emergencyContact" id="emergencyContact" class="form-control" >
										</div>
                                        </div>
                                        
                                        <label class="col-md-2 control-label" for="emergencyName">Name<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="emergencyName" id="emergencyName" class="form-control" >
										</div>
                                        </div>
                                        
                                        
									</div>
                                    
                                     <div class="form-group">
										
                                        <label class="col-md-2 control-label" for="relation">Relation to the Employee<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="relation" id="relation" class="form-control required" >
                                            </div>
										</div>
                                        
                                        
									</div>
                                                                                                            
                                    
                                     <div class="well well-sm">
									<h4 class="lighter no-margin-bottom purple">
										Current Address
                                     </h4>
								</div>
                                
                                    <div class="form-group">
										<label class="col-md-2 control-label">H.No<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="c_hno" class="form-control required" id="c_hno" >
                                            </div>
										</div>
                                        <label class="col-md-2 control-label">Street / Colony<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="c_street" class="form-control required" id="c_street">
                                            </div>
										</div>
									</div>
                                    
                                    
                                    
                                    <div class="form-group">
										<label class="col-md-2 control-label">Landmark<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="c_landmark" class="form-control required" id="c_landmark" >
                                            </div>
										</div>
                                        <label class="col-md-2 control-label">City<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="c_city" class="form-control required" id="c_city" >
                                            </div>
										</div>
									</div>
                                    
                                    
                                        <div class="form-group">
										<label class="col-md-2 control-label">Area<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="c_area" class="form-control required" id="c_area" >
                                            </div>
										</div>
                                        <label class="col-md-2 control-label">pincode<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="c_pincode" class="form-control required" id="c_pincode" >
                                            </div>
										</div>
									</div>
                                    
                                    
                                    <div class="well well-sm">
									<h4 class="lighter no-margin-bottom purple">
										Perminent Address
                                        
                                        <input type="checkbox" class="uniform" name="check"  value="1" id="check" onclick="setAddress()" >Same as Current Address
									</h4>
								</div>
                                
                                    <div class="form-group">
										<label class="col-md-2 control-label">H.No<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="p_hno" class="form-control required" id="p_hno" >
                                            </div>
										</div>
                                        <label class="col-md-2 control-label">Street / Colony<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="p_street" class="form-control required" id="p_street">
                                            </div>
										</div>
									</div>
                                    
                                    
                                    
                                    <div class="form-group">
										<label class="col-md-2 control-label">Landmark<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="p_landmark" class="form-control required" id="p_landmark" >
                                            </div>
										</div>
                                        <label class="col-md-2 control-label">City<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="p_city" class="form-control required" id="p_city">
                                            </div>
										</div>
									</div>
                                    
                                    
                                      <div class="form-group">
										<label class="col-md-2 control-label">Area<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="p_area" class="form-control required" id="p_area" >
                                            </div>
										</div>
                                        <label class="col-md-2 control-label">pincode<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="p_pincode" class="form-control required" id="p_pincode" >
                                            </div>
										</div>
									</div>
                                    
                                   
                                   <div class="well well-sm">
									<h4 class="lighter no-margin-bottom purple">
										Official Details
									</h4>
								</div>
                                 
                                    
                                   
                                   <div class="form-group">
                                              <label class="col-md-2 control-label" for="roll">Department<span class="required">*</span></label>
                                              <div class="col-md-4">
                                               <div class="clearfix">
                                                  <select class="form-control" name="roll" id="roll">
                                                  <option value="">Select</option> 
                                                  <?php while($role = mysql_fetch_array($roles)) { ?>
                                                  <option value="<?php echo $role['roll_Id']; ?>"><?php echo $role['roll']; ?></option> 
												  <?php } ?>
                                                  </select>
                                                  </div>
                                                    
									            </div>
										<label class="col-md-2 control-label" for="designation">Designation<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="designation" id="designation" class="form-control required" >
                                            </div>
										</div>
									</div>
                                    <div class="form-group">
										<label class="col-md-2 control-label" for="username2">Portal User Name<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="username2" id="username2" class="form-control required" onChange="checkUsername(this.value)" value="" >
                                            <span id="div1"></span>
                                            </div>
										</div>
								
										<label class="col-md-2 control-label" for="password3">Portal Password<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="password" name="password3" id="password3" class="form-control required" value="" >
                                            </div>
										</div>
									</div>
                                    
                                    
                                    <div class="form-group">
										<label class="col-md-2 control-label" for="branch">Branch<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<select name="branch" id="branch" class="form-control">
                                            <option value="">Select Branch</option>
                                            <?php while($branch = mysql_fetch_array($branches)) { ?>
                                             <option value="<?php echo $branch['branchId']; ?>"><?php echo $branch['branch']; ?></option>
                                            <?php } ?>
                                            </select>
                                            </div>
										</div>
                                        
                                        <label class="col-md-2 control-label" for="dateJoining">Date of Joining<span class="required">*</span></label>
                                        <div class="col-md-4">
                                        <div class="clearfix">
											<input type="text" name="dateJoining" id="dateJoining" class="form-control date-picker" >
                                            </div>
										</div>
                                        
									</div>
                                    
                                    
                                     <div class="form-group">
										
                                        <label class="col-md-2 control-label">Image<span class="required">*</span></label>
           								 <div class="col-md-4">
                                         <div class="clearfix">
             							 <input type="file" name="image"  accept="image/*" data-style="fileinput"data-inputsize="medium" multiple="multiple">
             								 <p class="help-block">Images only (image/*)</p>
              							<label for="file1" class="has-error help-block" generated="true" style="display:none;"></label>
                                        </div>
            							</div>
									</div>
                                    
                                 <div class="clearfix form-actions">
										<div class="col-md-offset-9 col-md-9">
											<button class="btn btn-info" type="submit" name="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>

											&nbsp; &nbsp; &nbsp;
											<button class="btn" type="reset">
												<i class="ace-icon fa fa-undo bigger-110"></i>
												Reset
											</button>
										</div>
									</div>

                                    
                                    </form>
                                    
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
        
        
         <script src="assets/js/jquery.validate.js"></script>

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
        
        
        
        <script src="assets/js/date-time/bootstrap-datepicker.js"></script>
		<script src="assets/js/date-time/bootstrap-timepicker.js"></script>
		<script src="assets/js/date-time/moment.js"></script>
		<script src="assets/js/date-time/daterangepicker.js"></script>
		<script src="assets/js/date-time/bootstrap-datetimepicker.js"></script>
        
        <script>
		
		function checkUsername(val)
		{
		$.ajax({url: "ajax/checkusername.php?user="+val, success: function(result){
        $("#div1").html(result);
    }});
		}
		
		function setAddress()
		{
		 
		 if(document.getElementById('check').checked)
		 {
			document.getElementById('p_hno').value = document.getElementById('c_hno').value; 
			document.getElementById('p_street').value = document.getElementById('c_street').value; 
			document.getElementById('p_landmark').value = document.getElementById('c_landmark').value; 
			document.getElementById('p_city').value = document.getElementById('c_city').value; 
			document.getElementById('p_area').value = document.getElementById('c_area').value; 
			document.getElementById('p_pincode').value = document.getElementById('c_pincode').value; 
		 }
		 else
		 {
			 document.getElementById('p_hno').value = ''; 
			document.getElementById('p_street').value = '';
			document.getElementById('p_landmark').value = '';
			document.getElementById('p_city').value = '';
			document.getElementById('p_area').value = '';
			document.getElementById('p_pincode').value = '';
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
			
			
			
			// datepicker
			$('.date-picker').datepicker({
					autoclose: true,
					todayHighlight: true,
					 format: 'dd-mm-yyyy'
				})
				
				
				$('.date-picker1').datepicker({
					//autoclose: true,
					//format: 'dd-mm-yyyy'
					format: "dd-mm-yyyy",
             startView: "year", 
    minViewMode: "date"
   // minViewMode: "date"
				})
				
				
			
			// validateion
				
				$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					ignore: "",
					rules: {
						firstName: {
							required: true,
							
						},
						fatherName: {
							required: true,
							
						},
						dateBirth: {
							required: true,
							
						},						
						bloodGroup: {
							required: true,
							
						},
						mobile: {
							required: true,
							
						},
						email: {
							required: true,
							
						},
						officialEmail: {
							required: true,
							
						},
						emergencyContact: {
							required: true,
							
						},
						emergencyName: {
							required: true,
							
						},
						relation: {
							required: true,
							
						},
						c_hno: {
							required: true,
							
						},
						c_street: {
							required: true,
							
						},
						c_landmark: {
							required: true,
							
						},
						c_city: {
							required: true,
							
						},
						c_area: {
							required: true,
							
						},
						c_pincode: {
							required: true,
							
						},
						p_hno: {
							required: true,
							
						},
						p_street: {
							required: true,
							
						},
						p_landmark: {
							required: true,
							
						},
						p_city: {
							required: true,
							
						},
						p_area: {
							required: true,
							
						},
						p_pincode: {
							required: true,
							
						},
						roll: {
							required: true,
							
						},
						designation: {
							required: true,
							
						},
						username2: {
							required: true,
							
						},
						password3: {
							required: true,
							
						},
						branch: {
							required: true,
							
						},
						dateJoining: {
							required: true,
							
						}
					},
			    	highlight: function (e) {
						$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
					},
			
					success: function (e) {   
						$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
						$(e).remove();
					}
			
				
				});
				// validation
			
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

								