<?php include("includes/header.php"); 

// delete 
if(isset($_GET['did']))
{
  if(mysql_query("delete from employees where id = '". $_GET['did'] ."'"))
  {
	header("location: employees.php?delete=success");  
  }
  else
  {
	header("location: employees.php?error=fail");    
  }
}


// update Target
if(isset($_POST['updateTarget']))
{      
	// updating the target for employees from target table
	mysql_query("update targets set target = '". $_POST['updateTargetValue'] ."' where targetId = '". $_POST['tid'] ."'");
    header("location: employees.php?update=success");    
}


// add Target to employees by using targets table
if(isset($_POST['addTarget']))
{
	
mysql_query("insert into targets (`employeeId`, `financialMonth`, `financialYear`, `target`) values ('". $_POST['employeeId'] ."', '". $_POST['month'] ."', '". $_POST['financialYear'] ."', '". $_POST['target'] ."')");	
  
  $lastId = mysql_insert_id();


}
 $limit = 10;
/*to get the enquiries data we will execute this query with employees table.
relation between employees and rolls are employees.roll = rolls.roll_id.
relation between branches and employees are employees.branch = branches.branchId
roll (3,4,5,6,7,8) these are the rolls we can show here.
3-Accounts Admin, 4-Sales & Marketing, 5-BD Admin, 6-Orders and Execution, 7-Logistics,8-services
*/

 $numRecords = mysql_query("select employees.id, employees.firstName, employees.email, employees.dateJoining, employees.personalMobile, rolls.roll from employees 
 left join rolls on employees.roll = rolls.roll_id where employees.roll In (3,4,5,6,7,8,9,10)
 order by employees.firstName asc");
 
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
 
 // employee search branchwise and employeewise
 /* employee search beased on employees.id
 branch search based on employees.branch */ 
 
 if(isset($_GET['empSearch']))
 {
	$where = '';
	
	if(isset($_GET['eid']) && $_GET['eid']>0) 
	 {
		 $whereItems[] = "employees.id = '".$_GET['eid']."'";
	 }
	 if(isset($_GET['bid']) && $_GET['bid']>0)
	 {
		 $whereItems[] = "employees.branch = '".$_GET['bid']."'";
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
												
/*to get the enquiries data we will execute this query with employees table.
relation between employees and rolls are employees.roll = rolls.roll_id.
relation between branches and employees are employees.branch = branches.branchId
roll (3,4,5,6,7,8) these are the rolls we can show here.
3-Accounts Admin, 4-Sales & Marketing, 5-BD Admin, 6-Orders and Execution, 7-Logistics,8-services
*/

												
$employees = mysql_query("select employees.id, employees.firstName, employees.email, employees.dateJoining, employees.emergencyContact, employees.emergencyName, employees.emergencyName, employees.personalMobile, rolls.roll, branches.branch from employees 
 left join rolls on employees.roll = rolls.roll_id
 left join branches on employees.branch = branches.branchId where employees.roll In (3,4,5,6,7,8,9,10) and
 $where
 order by employees.firstName asc");	 
	 
 }
 

 else {
	 
	
 
$employees = mysql_query("select employees.id, employees.firstName, employees.email, employees.dateJoining, employees.emergencyContact, employees.emergencyName, employees.emergencyName, employees.personalMobile, rolls.roll, branches.branch from employees 
 left join rolls on employees.roll = rolls.roll_id
 left join branches on employees.branch = branches.branchId where employees.roll In (3,4,5,6,7,8,9,10) 
 $where
 order by employees.firstName asc limit $start, $limit");
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
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Employees </li>
                            
						</ul><!-- /.breadcrumb -->
                           <a href="excel/employees.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>

						<!-- #section:basics/content.searchbox -->
						<!-- /.nav-search -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- #section:settings.box -->
						<!-- /.ace-settings-container -->

						<!-- /section:settings.box -->
					

						<div class="row">
                         <?PHP 
											if($lastId>0)
											{
												echo '<div class="alert alert-success col-sm-12">Target has been added successfully.</div>';
											}
											
											
											 ?>
                        
                        <div class="col-xs-12">
                                    
                                    
                                                                        
                                    <form class="form-inline" method="get" action="" autocomplete="off">
                                  
                      
  <div class="row">              
                                  
  
  <div class="col-sm-2">
     <span>Branch</span><br />
     <select id="bid" name="bid">
     <option value="">Select Branch</option>
     <?php  
	 while ($branch = mysql_fetch_array($branches))
	 {  ?>
 <option <?php if(isset($_GET['bid']) && $_GET['bid']==$branch['branchId']) { ?> selected="selected" <?php } ?> value="<?php echo $branch['branchId'] ?>"><?php echo $branch['branch'] ?></option>
                                          
                                            
                                        <?php
                                           }  ?>     </select> 
                               </div>
  
                                <div class="col-sm-2">
                                <span>Employee</span>
     <input type="hidden" id="eid" name="eid" value="<?php echo $_GET['eid']; ?>"  />
<input type="text" id="employee" name="employee" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Employee" <?php if(isset($_GET['employee'])) { ?> value="<?php echo $_GET['employee']; ?>" <?php } ?> onkeyup="getEmployee(this.value)"  />
           <ul class="typeahead dropdown-menu" style="left: 10px; display: none;" id="employeesList">
                                           </ul>
                                           
                                </div> 
                     
                                       
  <div class="form-group col-sm-2">
   <br/>
    <input type="submit" class="btn btn-sm btn-success" name="empSearch" value="Search" />
  </div>
  
  </div>
  
</form>
<div class="space"></div>
</div>
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								

							
 <?php
	   if(isset($_GET['delete']))
{ echo '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Employee has been deleted!</div>'; }
else if(isset($_GET['error']))
{ echo '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }
else if(isset($_GET['targetset']))
{  echo '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Financial year target has been set..</div>'; }

 
										   ?> 


								<div class="row">
									<div class="col-xs-12">
										
										<div class="table-header">
											Employees
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">
											<table id="sample-table-1" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th class="center">
															S.no
														</th>
														<th>Name</th>
                                                        <th>Branch</th>
                                                        <th>Designation</th>
                                                        <th>Email</th>
                                                        <th>Mobile</th>
                                                        <th>Date</th>
                                                        <th>Action</th>
														
													</tr>
												</thead>

												<tbody>
													
														
																	

  <?php 
  // to get the all data in excel sheet we write this code
     $list[] = array('S. No', 'Name', 'Designation', 'Email', 'Mobile', 'Date' ); 

  if(mysql_num_rows($employees)>0)
  {
   $sno = $start+1; 
   
    while($employee = mysql_fetch_array($employees))
  {
	
	
	$currentDate = date('Y-m-d');
		  
$prices = mysql_query("select `price` from product_price where productId = '". $product['productId'] ."' and fromDate <= '$currentDate' order by fromDate desc limit 1");
$price = mysql_fetch_array($prices);
	  
	 ?><tr>
														<td class="center">
															<?php echo $sno; ?>
														</td>

														<td>
															<?php echo ucfirst($employee['firstName']); ?>
														</td>
                                                        <td>
															<?php echo $employee['branch']; ?>
														</td>
                                                        <td>
															<?php echo $employee['roll']; ?>
														</td>
                                                         <td>
															<?php echo $employee['email']; ?>
														</td>
                                                        <td>
															<?php echo $employee['personalMobile']; ?>
														</td>
                                                        <td>
															<?php $doj = explode('-',$employee['dateJoining']);
															  echo $doj =$doj[2].'-'.$doj[1].'-'.$doj[0];
															 ?>
														</td>
                                                        
                                                        
														<td>
                                                        
                                                      
                                                        
                                            <a class="btn btn-success btn-sm" href="view_employee.php?eid=<?php echo $employee['id'] ?>">
												<i class="ace-icon fa fa-eye"></i>
											</a>
                                              
                                            
                                            <a class="btn btn-primary btn-sm" href="edit_employee.php?eid=<?php echo $employee['id'] ?>">
												<i class="ace-icon fa fa-edit icon-only"></i>
											</a>
                                            
                                            <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?php echo $employee['id'] ?>')">
												<i class="ace-icon fa fa-trash icon-only"></i>
											</button>
                                                      
                                    <a href="<?php echo '#modal-form'.$employee['id']; ?>" role="button" class="blue" data-toggle="modal"> Target </a>              
                                                  
                                                  <div id="<?php echo 'modal-form'.$employee['id']; ?>" class="modal fade" tabindex="-1">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">&times;</span>
													</button>
													<?php echo ucfirst($employee['firstName']); ?> Target 
												</div>
											</div>

											<div class="modal-body no-padding">
                                            
                                            
                                            <?php
											$currentYear = date('Y');
											$previousYear =	$currentYear-1;
										    $nextYear =	$currentYear+1;
											
											$currentMonth = date('m');
											
											
											if($currentMonth>3)
											{
											  $financialYear = 	$currentYear.'-'.$nextYear;
											}
											else
											{
											  $financialYear = 	$previousYear.'-'.$currentYear;
											}
											
	
	$targetMonths = mysql_query("select financialMonth, target from targets where employeeId = '". $employee['id'] ."' and financialYear = '". $financialYear ."'");				
	
if(mysql_num_rows($targetMonths)>0)
	{
		while($targetMonth = mysql_fetch_array($targetMonths))
		{
		  	$monthData[] = $targetMonth['financialMonth'];
			$targetData[] = $targetMonth['target'];
		}
		
	}
	else
	{
	  $monthData[] ='';	 $targetData[] = '';
	}
	
											
											?>
                                            
                                            <div id="<?php echo 'financialTarget'.$employee['id']; ?>">
                                           
                                    <form class="form-horizontal" method="post" action="">         
                                            
                                            <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Financial Year </label>
                                        
                                        

										<div class="col-sm-9">
		<select  id="financialYear" name="financialYear" class="col-xs-10 col-sm-5" onchange="getFinancialTargetDetails('<?php echo $employee['id'] ?>',this.value)">
                                        <?php for($i=2016;$i<=2020;$i++)
											{  
											
											$n = $i+1
											 ?><option value="<?php echo $i.'-'.$n; ?>"><?php echo $i.' to '.$n; ?></option>	
											<?php } ?>
                                            </select>
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="month"> Month </label>

										<div class="col-sm-9">
                                        
											<input type="hidden" name="employeeId"  value="<?php echo $employee['id'] ?>" />
                                            <select id="month"  name="month" class="col-xs-10 col-sm-5">
                                            <option value="">Select Month</option>
 <?php if(!(in_array('04',$monthData))) { ?><option value="04">April</option><?php } ?>
 <?php if(!(in_array('05',$monthData))) { ?><option value="05">May</option><?php } ?>
 <?php if(!(in_array('06',$monthData))) { ?><option value="06">June</option><?php } ?>
 <?php if(!(in_array('07',$monthData))) { ?> <option value="07">July</option><?php } ?>
 <?php if(!(in_array('08',$monthData))) { ?> <option value="08">August</option><?php } ?>
 <?php if(!(in_array('09',$monthData))) { ?>  <option value="09">November</option><?php } ?>
 <?php if(!(in_array('10',$monthData))) { ?> <option value="10">October</option><?php } ?>
 <?php if(!(in_array('11',$monthData))) { ?> <option value="11">November</option><?php } ?>
 <?php if(!(in_array('12',$monthData))) { ?> <option value="12">December</option><?php } ?>
 <?php if(!(in_array('01',$monthData))) { ?> <option value="01">January</option><?php } ?>
 <?php if(!(in_array('02',$monthData))) { ?>  <option value="02">February</option><?php } ?>
 <?php if(!(in_array('03',$monthData))) { ?>     <option value="03">March</option><?php } ?>
                                            </select>
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="target"> Target </label>

										<div class="col-sm-9">
                                            <input type="text" id="target"  name="target" class="col-xs-10 col-sm-5" value="">
                                         
										</div>
									</div>
                                    
                                  
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">  </label>

										<div class="col-sm-9">
											<button type="submit" name="addTarget" class="col-xs-10 col-sm-5 btn btn-sm btn-info">
                                            Submit
                                            </button>
										</div>
									</div>
                                    </form>
                                    
                                    <?php
									
	$targets = mysql_query("select targetId, financialMonth, financialYear, target from targets where employeeId = '". $employee['id'] ."' and financialYear = '". $financialYear ."'");	
	
	
	    $monthName[1] = 'January';
		$monthName[2] = 'February';
		$monthName[3] = 'March';
		$monthName[4] = 'April';
		$monthName[5] = 'May';
		$monthName[6] = 'June';
		$monthName[7] = 'July';
		$monthName[8] = 'August';
		$monthName[9] = 'September';
		$monthName[10] = 'October';
		$monthName[11] = 'November';
		$monthName[12] = 'December';
		
		
		
		//	$targetMonths = mysql_query("select financialMonth, target from targets where employeeId = '". $employee['id'] ."' and financialYear = '". $financialYear ."'");							
									
									?>
                                    
												<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
													<thead>
														<tr>
															<th>S.no</th>
															<th>Financialy year</th>
                                                            <th>Month</th>
															<th>Target</th>
                                                            
														</tr>
													</thead>

													<tbody>
														

								<?php 
								
								
								
								if(mysql_num_rows($targets)>0) { $num = 1;
								while($target = mysql_fetch_array($targets))
								{ ?>						

														<tr>
															<td>
															<?php echo $num; ?>
															</td>
															<td><?php echo $target['financialYear']; ?></td>
                                                            <td><?php 
															
															if($target['financialMonth']<10)
															{
															
															 $monthId = $target['financialMonth'][1];
															echo $monthName[$monthId]; 	
															}
															else
															{
															
															echo $monthName[$target['financialMonth']]; 
															}
															
															
															 ?></td>
															<td>
	<span id="<?php echo 'updateTargetDiv_'.$target['targetId']; ?>">
    <a href="javascript:void()" onclick="updateTarget('<?php echo $target['targetId']; ?>','<?php echo $target['target']; ?>')">
															<?php echo $target['target']; ?>
                                                            </a>
                                                            </span>
                                                            </td>
															
														</tr>
                                                        <?php $num++; } }  else { ?> <tr><td colspan="3">No Data found.</td></tr> <?php } ?>
													</tbody>
												</table>
                                                
                                                
                                                
                                                <?php unset($monthData); unset($targetData);  ?>
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
                                                          
														</td>
</tr>
													
													
<?php
// to get the all data in excel sheet we write this code
$rowlist[] = $sno;
 $rowlist[] = ucfirst($employee['firstName']);
 $rowlist[] = $employee['roll'];
 $rowlist[] = $employee['email'];
 $rowlist[] = $employee['personalMobile'];
 $rowlist[] = $doj;
 $list[] = $rowlist;
 unset($rowlist);


 $sno++;
  } } else { ?> <tr><td colspan="8">No Data found.</td></tr> <?php }
  
  
  if($numRecords>$limit && !isset($_GET['empSearch'])) {
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
  
  ?> <tr><td colspan="8">
  
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
  <td id="next_grid-pager" class="ui-pg-button ui-corner-all <?php echo $thirdlink; ?>" style="cursor: default;" onclick="goToPage('<?php echo $currentPage+1; ?>')">
                <span class="ui-icon ace-icon fa fa-angle-right bigger-140">></span>
  </td>
  <td id="last_grid-pager" class="ui-pg-button ui-corner-all <?php echo $fourthlink; ?>" onclick="goToPage('<?php echo $numPages; ?>')">
                <span class="ui-icon ace-icon fa fa-angle-double-right bigger-140"></span>
  </td>
  </tr></tbody></table>
  
  
  
  
  
  </td></tr>    <?php } ?></tbody>
											</table>
                                            
                                            
                                            <?php                              
$fp = fopen('excel/employees.csv', 'w');

foreach ($list as $fields) {
    fputcsv($fp, $fields);
}

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
  
  
  function updateTarget(tid,target)
  {
	  $.ajax({url: "ajax/getUpdateTarget.php?tid="+tid+"&target="+target, success: function(result){
		$("#updateTargetDiv_"+tid).html(result);
    }});	
	  
	  $("#updateTargetDiv_"+tid).focus();
  }
  
		function confirmDelete(did)
		{
		  if(confirm("Confirm Delete"))
		  {
		  window.location = 'employees.php?did='+did;
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
  
  
  function goToPage(pid)
{
   window.location = 'employees.php?page='+pid;	
}

function getFinancialTargetDetails(eid,finYear)
{
	//document.getElementById("employeesList").style.display = 'block';
				$.ajax({url: "ajax/getFinancialTargetDetails.php?eid="+eid+"&finYear="+finYear, success: function(result){
		$("#financialTarget"+eid).html(result);
    }});	
	
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
