<?php include("includes/header.php"); 
 
		if(isset($_POST['update']))
		{
			$date = $_POST['dateBirth'];
			$dob = date('Y-m-d', strtotime(str_replace('.', '/', $date)));
			
			$dateJoining = $_POST['dateJoining'];
			$doj = date('Y-m-d', strtotime(str_replace('.', '/', $dateJoining)));
			
			// edit and update employee using employees table
if(mysql_query("update employees set `roll` = '". $_POST['roll'] ."', `firstName` = '". $_POST['firstName'] ."', `fatherName` = '". $_POST['fatherName'] ."', `dob` = '". $dob ."', `bloodGroup` = '". $_POST['bloodGroup'] ."', `userName` = '". $_POST['userName'] ."', `password` = '". $_POST['password'] ."', `email` = '". $_POST['email'] ."', `officialEmail` = '". $_POST['officialEmail'] ."', `gender` = '". $_POST['gender'] ."', `designation` = '". $_POST['designation'] ."', `dateJoining` = '". $doj ."', `emergencyContact` = '". $_POST['emergencyContact'] ."', `emergencyName` = '". $_POST['emergencyName'] ."', `emergencyRelation` = '". $_POST['relation'] ."', `personalMobile` = '". $_POST['mobile'] ."', `cHno` = '". $_POST['c_hno'] ."', `cStreet` = '". $_POST['c_Street'] ."', `cLandmark` = '". $_POST['c_Landmark'] ."', `cCity` = '". $_POST['c_City'] ."', `cArea` = '". $_POST['c_Area'] ."', `cPincode` = '". $_POST['c_Pincode'] ."', `status` = '1', `role` = '". $_POST['type'] ."' where id = '". $_GET['eid'] ."'"))
			{
			  header("location: edit_employee.php?eid=".$_GET['eid']."&update=1");	
			}
			else
			{
			  header("location: edit_employee.php?eid=".$_GET['eid']."&error=1");	
			}
			
		}
		
 $branches = mysql_query("select branchId, branch from branches order by branch");
		
		
		
		
		
		
	$customer = mysql_query("select * from employees where id = '". $_GET['eid'] ."'");
	 //$customer = mysql_fetch_array($customer);
	
	
	
	if(mysql_num_rows($customer)<1)
	{  header("location: employees.php"); exit; 	
	}
	$customer = mysql_fetch_array($customer);
	$dob = explode('-',$customer['dob']);
			 $dob = $dob[2].'-'.$dob[1].'-'.$dob[0];
		
			 $doj = explode('-',$customer['dateJoining']);
		 	 $doj = $doj[2].'-'.$doj[1].'-'.$doj[0];
	
  
 $roles = mysql_query("select * from rolls where roll_Id > '1'");
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

							<li class="active">Edit Employee</li>
						</ul><!-- /.breadcrumb -->

						<!-- #section:basics/content.searchbox -->
						<!--<div class="nav-search" id="nav-search">
							<form class="form-search">
								<span class="input-icon">
									<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
									<i class="ace-icon fa fa-search nav-search-icon"></i>
								</span>
							</form>
						</div>--><!-- /.nav-search -->

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
										
										<div class="table-header">
											Edit Employee
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div>
											
                                           <?php
	   if(isset($_GET['update']))
{ $alertMsg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Employee has been updated!</div>'; }
else if(isset($_GET['error']))
{ $alertMsg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  if(isset($msg)) { echo $alertMsg; }
										   ?> 
                                            
                                           
                                            
                               
                                            
                                           
<form class="form-horizontal row-border" id="validate-3" action="" method="post" enctype="multipart/form-data">



<div class="form-group">
<label for="firstName" class="col-md-2 control-label">Full Name<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="firstName" id="firstName" class="form-control required" value="<?php echo $customer['firstName']; ?>" />
</div>
<label for="fatherName" class="col-md-2 control-label">Father's Name<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="fatherName" id="fatherName" class="form-control required" value="<?php echo $customer['fatherName']; ?>" />
</div>
</div>

<div class="form-group">

<label class="col-md-2 control-label">Gender<span class="required">*</span> </label>
<div class="col-md-4">
<label class="radio-inline" for="male">
<input type="radio" class="uniform" name="gender" id="male" value="Male" required <?php if(strcmp($customer['gender'],'Male')==0) { ?> checked <?php } ?> />
Male
</label>
<label class="radio-inline" for="female">
<input type="radio" class="uniform" name="gender" id="female" value="Female" <?php if(strcmp($customer['gender'],'Female')==0) { ?> checked <?php } ?> />
Female
</label>

</div>

<label class="col-md-2 control-label" id="dateBirth">Date of Birth<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="dateBirth" id="dateBirth" class="form-control date-picker1" value="<?php echo $dob; ?>" />
<!--<span class="help-block">(DD-MM-YYYY)</span>-->
</div>

</div>



<div class="form-group">

<label class="col-md-2 control-label" for="bloodGroup">Blood Group<span class="required">*</span></label>
<div class="col-md-4">
<select name="bloodGroup" id="bloodGroup" class="form-control required">
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


<label class="col-md-2 control-label" for="mobile">Personal Mobile<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="mobile" id="mobile" class="form-control required" value="<?php echo $customer['personalMobile']; ?>" >
</div>
</div>

<div class="form-group">
<label class="col-md-2 control-label" for="email">Personal Email<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="email" id="email" class="form-control required email" value="<?php echo $customer['email']; ?>" >
</div>
<label class="col-md-2 control-label" for="officialEmail">Official Email<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="officialEmail" id="officialEmail" class="form-control required email" value="<?php echo $customer['officialEmail']; ?>" >
</div>
</div>



<div class="form-group">

<label class="col-md-2 control-label">Emergency Contact<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="emergencyContact" id="emergencyContact" class="form-control required" value="<?php echo $customer['emergencyContact']; ?>" >
</div>


<label class="col-md-2 control-label" for="emergencyName">Name<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="emergencyName" id="emergencyName" class="form-control required" value="<?php echo $customer['emergencyName']; ?>"  >
</div>


</div>

<div class="form-group">

<label class="col-md-2 control-label" for="relation">Relation to the Employee<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="relation" id="relation" class="form-control required" value="<?php echo $customer['emergencyRelation']; ?>" >
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
<input type="text" name="c_hno" class="form-control required" id="c_hno" value="<?php echo $customer['cHno']; ?>" >
</div>
<label class="col-md-2 control-label">Street / Colony<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="c_Street" class="form-control required" id="c_Street" value="<?php echo $customer['cStreet']; ?>">
</div>
</div>



<div class="form-group">
<label class="col-md-2 control-label">Landmark<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="c_landmark" class="form-control required" id="c_landmark" value="<?php echo $customer['cLandmark']; ?>" >
</div>
<label class="col-md-2 control-label">City<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="c_City" class="form-control required" id="c_city" value="<?php echo $customer['cCity']; ?>" >
</div>
</div>


<div class="form-group">
<label class="col-md-2 control-label">Area<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="c_Area" class="form-control required" id="c_area" value="<?php echo $customer['cArea']; ?>" >
</div>
<label class="col-md-2 control-label">pincode<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="c_Pincode" class="form-control required" id="c_Pincode" value="<?php echo $customer['cPincode']; ?>" >
</div>
</div>


<div class="well well-sm">
<h4 class="lighter no-margin-bottom purple">
Perminent Address

<input type="checkbox" class="uniform" name="check" value="1" id="address" >Same as Current Address
</h4>
</div>

<div class="form-group">
<label class="col-md-2 control-label">H.No<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="p_hno" class="form-control required" id="p_hno" value="<?php echo $customer['pHno']; ?>" >
</div>
<label class="col-md-2 control-label">Street / Colony<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="p_street" class="form-control required" id="p_street" value="<?php echo $customer['pStreet']; ?>" >
</div>
</div>



<div class="form-group">
<label class="col-md-2 control-label">Landmark<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="c_Landmark" class="form-control required" id="c_Landmark" value="<?php echo $customer['pLandmark']; ?>" >
</div>
<label class="col-md-2 control-label">City<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="p_city" class="form-control required" id="p_city" value="<?php echo $customer['pCity']; ?>">
</div>
</div>


<div class="form-group">
<label class="col-md-2 control-label">Area<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="p_area" class="form-control required" id="p_area" value="<?php echo $customer['pArea']; ?>" >
</div>
<label class="col-md-2 control-label">pincode<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="p_pincode" class="form-control required" id="p_pincode" value="<?php echo $customer['pPincode']; ?>" >
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
<select class="form-control" name="roll" id="roll">
<option value="">Select</option> 
<?php while($role = mysql_fetch_array($roles)) { ?>
<option value="<?php echo $role['roll_Id']; ?>" <?php if($customer['roll']==$role['roll_Id']) { ?> selected <?php } ?>><?php echo $role['roll']; ?></option> 
<?php } ?>
</select>

</div>
<label class="col-md-2 control-label" for="designation">Designation<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="designation" id="designation" class="form-control required" value="<?php echo $customer['designation']; ?>" >
</div>
</div>

<div class="form-group">
<label class="col-md-2 control-label" for="userName">Portal Username<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="userName" id="userName" class="form-control required" value="<?php echo $customer['userName']; ?>" >

</div>
<label class="col-md-2 control-label" for="designation">Portal Password<span class="required">*</span></label>
<div class="col-md-4">
<input type="text" name="password" id="password" class="form-control required" value="<?php echo $customer['password']; ?>" >
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
<input type="text" name="dateJoining" id="dateJoining" class="form-control date-picker" value="<?php echo $doj; ?>" />
</div>

</div>


<div class="form-group">

<label class="col-md-2 control-label" for="roll">Type<span class="required">*</span></label>
<div class="col-md-4">
<select class="form-control" name="type" id="type">
<?php
$role = $customer['role'];

?>
<option value="1" <?PHP if($role == '1'){ echo 'selected="selected"'; } ?> >Teamleader</option>
<option value="2" <?PHP if($role == '2'){ echo 'selected="selected"'; } ?> >Member</option>

</select>

</div>       

<label class="col-md-2 control-label">Image<span class="required">*</span></label>
<div class="col-md-4">
<input type="file" name="image" class="required" accept="image/*" data-style="fileinput"data-inputsize="medium" multiple="multiple">
<p class="help-block">Images only (image/*)</p>
<label for="file1" class="has-error help-block" generated="true" style="display:none;"></label>
</div>

</div>
</div>

<div class="clearfix form-actions">
<div class="col-md-offset-9 col-md-9">
<button class="btn btn-info" type="submit" name="update">
<i class="ace-icon fa fa-check bigger-110"></i>
Update
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
						<span class="bigger-120">
							<span class="blue bolder">Tri</span>
			SMART  Application &copy; 2014-2015
						</span>

						&nbsp; &nbsp;
						<span class="action-buttons">
							<a href="#">
								<i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
							</a>

							<a href="#">
								<i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
							</a>

							<a href="#">
								<i class="ace-icon fa fa-rss-square orange bigger-150"></i>
							</a>
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
        
        
        
        <script src="assets/js/date-time/bootstrap-datepicker.js"></script>
		<script src="assets/js/date-time/bootstrap-timepicker.js"></script>
		<script src="assets/js/date-time/moment.js"></script>
		<script src="assets/js/date-time/daterangepicker.js"></script>
		<script src="assets/js/date-time/bootstrap-datetimepicker.js"></script>

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
