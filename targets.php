<?php include("includes/header.php"); 
 
// data comes from employees table 
/* relation between employees and rolls are employees.roll = rolls.roll_id */
$employees = mysql_query("select employees.id,designation, b.branch, employees.firstName, employees.email, employees.dateJoining, employees.personalMobile, rolls.roll from employees 
 left join rolls on employees.roll = rolls.roll_id
 left join branches b on employees.branch=b.branchid
 where employees.roll = '4'
 order by employees.firstName");
 
 
$total_month_amnt=0;
$total_year_targert=0;
$total_year_target_achieved=0;

$start=0;

$currentMonthAmount=0;
$yearAchieved=0;
$grandMonthTarget=0;

$grandMonthAchieved=0;
$grandYearTarget=0;
$grandYearAchieved=0;

$currentMonthAmount=0;
$yearAchieved=0;




?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Sales Targets </li>
						</ul><!-- /.breadcrumb -->
                        
<a href="excel/targets.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>
                        

						
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- #section:settings.box -->
						
                        <!-- /.ace-settings-container -->

						<!-- /section:settings.box -->
					

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								
<?PHP	

$currentYear = date('Y');
$currentMonth = date('m');
$previousYear = $currentYear-1;
$nextYear = $currentYear+1;
 				
							
 if($currentMonth>3)
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
 
$rowlist[] = $financialYear.' Sales Targets';
$list[] = $rowlist;

unset($rowlist);

$rowlist[] = 'S. No';
$rowlist[] = 'Name';
$rowlist[] = date('M Y').' Target';
$rowlist[] = date('M Y').' Achieved';
$rowlist[] = 'Yr Target';
$rowlist[] = 'Yr Achieved';
$list[] = $rowlist;

unset($rowlist);

?>

								<div class="row">
									<div class="col-xs-12">
										
										<div class="table-header">
										<?php echo $financialYear.' Sales Targets'; ?>
                                        	<div class="filters-table-header">
                                        	<form method="post" action="" method="post">
                                            	<div class="form-group">
                                                
                                                <?PHP
														if(isset($_POST['filter']))
														{
															
															$filtered_mnth=$_POST['month'];
															$filtered_year=$_POST['year'];
															
														}
														else
														{
															$filtered_mnth=0;
															$filtered_year=0;	
														}
												?>
                                                	<select name="month" id="month" class="form-control">
                                                    	<option value="0" <?PHP if($filtered_mnth=="0"){ echo 'selected';} ?> >Select Month</option>
                                                        <option value="01" <?PHP if($filtered_mnth=="01"){ echo 'selected';} ?>>January</option>
                                                        <option value="02" <?PHP if($filtered_mnth=="02"){ echo 'selected';} ?>>February</option>
                                                        <option value="03" <?PHP if($filtered_mnth=="03"){ echo 'selected';} ?>>March</option>
                                                        <option value="04" <?PHP if($filtered_mnth=="04"){ echo 'selected';} ?>>April</option>
                                                        <option value="05" <?PHP if($filtered_mnth=="05"){ echo 'selected';} ?>>May</option>
                                                        <option value="06" <?PHP if($filtered_mnth=="06"){ echo 'selected';} ?>>June</option>
                                                        
                                                        <option value="07" <?PHP if($filtered_mnth=="07"){ echo 'selected';} ?>>July</option>
                                                        <option value="08" <?PHP if($filtered_mnth=="08"){ echo 'selected';} ?>>August</option>
                                                        <option value="09" <?PHP if($filtered_mnth=="09"){ echo 'selected';} ?>>September</option>
                                                        <option value="10" <?PHP if($filtered_mnth=="10"){ echo 'selected';} ?>>October</option>
                                                        <option value="11" <?PHP if($filtered_mnth=="11"){ echo 'selected';} ?>>November</option>
                                                        <option value="12" <?PHP if($filtered_mnth=="12"){ echo 'selected';} ?>>December</option>
                                                    </select>
                                                </div>
                                                <?PHP
                                                	if(isset($_POST))
													{
														extract($_POST);	
													}
                                                ?>
                                               	<div class="form-group">
                                                	<select name="year" id="year" class="form-control">
                                                    <option value="0">Select Financial Year</option>
                                                    <option value="0" <?PHP if($year=='0') { echo 'selected="selected"';}?> >Financial year </option>
                                                    <option value="2017-2018"  <?PHP if($year=='2017-2018') { echo 'selected="selected"';}?>> 2017-2018 </option>
                                                    
	                              <option value="2018-2019"  <?PHP if($year=='2018-2019') { echo 'selected="selected"';}?>> 2018-2019 </option>                                                    <option value="2019-2020"  <?PHP if($year=='2019-2020') { echo 'selected="selected"';}?>> 2019-2020 </option>

                                                    </select>
                                                </div>
                                                
                                                <div class="form-group">
                                                	<button type="submit" class="btn btn-warning" name="filter">Filter</button>
                                                </div>
                                            </form>
                                            </div>
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div>
											<table id="sample-table-1" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th class="center">
															S.no
														</th>
														<th>Name</th>
                                                        <th>Branch</th>
                                                        <th>Designation</th>
                                                        <th><?php #echo date('M Y'); ?> Month Target</th>
                                                        <th><?php #echo date('M Y'); ?> Month Achieved</th>
                                                        <th>Yr Target</th>
													    <th>Yr Achieved</th>
													</tr>
												</thead>

												<tbody>
													
														
																	

  <?php 
  if(mysql_num_rows($employees)>0)
  {
   
  $sno = $start+1; 
   
  while($employee = mysql_fetch_array($employees))
  {

if(!isset($_POST['filter']))
{
	
	$currentYear = date('Y');  
	$mnth = (int)date('m'); 

	if($mnth<3)
	{
		$prevyear=date('Y')-1;
		$trlike = $prevyear.'-'.$currentYear;
	}
	else
	{
		$nextyear=date('Y')+1;
		$trlike = $currentYear."-".$nextyear;	
	}

}
else
{
	$mnth = $_POST['month']; 
	$trlike = $_POST['year'];
	
}

// year target
	$year_target = 0;
	$yrTarget =  mysql_query("select target from targets where employeeId = '". $employee['id'] ."' and financialYear = '". $trlike ."'");


	while($yearTarget = mysql_fetch_array($yrTarget))
	{
		$year_target = $year_target+$yearTarget['target'];
	}
	// month data
	 $m = $currentMonth = date('m');
	 if(strlen($m)==1) { $m = '0'.$m; } else { $m = $m; }
	 
	 
	
  // month target
  
  
  if($mnth<3)
	{
		$prevyear=date('Y')-1;
		$fin_yr = $prevyear.'-'.$currentYear;
	}
	else
	{
		$nextyear=date('Y')+1;
		$fin_yr = $currentYear."-".$nextyear;	
	}




if(!isset($_POST['filter']))
{
	
	 if($mnth<3)
	{
		$prevyear=date('Y')-1;
		$fin_yr = $prevyear.'-'.$currentYear;
	}
	else
	{
		$nextyear=date('Y')+1;
		$fin_yr = $currentYear."-".$nextyear;	
	}
	
	}
else
{
	//$mnth = $_POST['month']; 
	$m=$_POST['month'];
	$fin_yr  = $_POST['year'];
	
}

#echo $fin_yr; exit; 

 $monthTarget = mysql_query("select target from targets where employeeId = '". $employee['id'] ."' and financialMonth = '". $m ."' and financialYear='".$fin_yr."' ");

 $monthTarget = mysql_fetch_array($monthTarget); 


$yrtarget =  mysql_query("select sum(target) as target from targets where employeeId = '". $employee['id'] ."' and financialYear='".$fin_yr."' ");
if(mysql_num_rows($yrtarget)>0)
{
$yrtargetamnt =  mysql_fetch_array($yrtarget); 
$yearTarget_amnt=$yrtargetamnt['target'];
}
else
	$yearTarget_amnt="0";
 // month achieved
	
	 
	 
	// $like = $currentYear.'-'.$m.'-'.'%';
	 $like = '%'.'-'.$m.'-'.'%';
	
 $invoices = mysql_query("select reportId, currentRevisionId from daily_reports where addedBy = '". $employee['id'] ."' and inv > '0' and proformaInvoiceDateTime like '$like' order by reportId desc");
    
	if(mysql_num_rows($invoices)>0)
	{
 
 $monthAchieved = 0;
 while($invoice = mysql_fetch_array($invoices))
 {
 // invoices amount:	 
 $invoicesAmount = mysql_query("select grandTotal from daily_reports_revision where reportId = '". $invoice['reportId'] ."' and revision = '". $invoice['currentRevisionId'] ."'");
 $invoiceAmount = mysql_fetch_array($invoicesAmount);
 
 $monthAchieved = $invoiceAmount['grandTotal']; 


 $currentMonthAmount[$m] = $monthAchieved;
 }
 

 
 
	}
	
	
	// year achieved
	 $m = $currentMonth = date('m');
	 if(strlen($m)==1) { $m = '0'.$m; } else { $m = $m; }
	 $like = $currentYear.'-'.$m.'-'.'%';
	
 $invoices = mysql_query("select reportId, currentRevisionId from daily_reports where addedBy = '". $employee['id'] ."' and inv > '0' and proformaInvoiceDateTime like '$like' order by reportId desc");
    
	if(mysql_num_rows($invoices)>0)
	{
 
 $yearAchieved = 0;
 
 
 while($invoice = mysql_fetch_array($invoices))
 {
 // invoices amount:	 
 $invoicesAmount = mysql_query("select grandTotal from daily_reports_revision where reportId = '". $invoice['reportId'] ."' and revision = '". $invoice['currentRevisionId'] ."'");
 $invoiceAmount = mysql_fetch_array($invoicesAmount);
 
 $yearAchieved = $invoiceAmount['grandTotal']; 
 

 }
 

 
 
	}
			
	
	
	  
	 ?><tr>
														<td class="center">
															<?php echo $sno; ?>
														</td>

														<td>
															<?php 
															echo ucfirst($employee['firstName']); ?>
														</td>
                                                        <td>
                                                        <?PHP echo ucwords($employee['branch']);?>
                                                        </td>
                                                        <td>
                                                        	<?PHP echo ucwords($employee['designation']); ?>
                                                        </td>
                                                        <td><?php if($monthTarget['target']!=''){ echo $monthTarget['target']; } else echo "0"; ?></td>
                                                        <td>
														
														<?php
														


														
														
			// this data comes from daily_reports,customers											
		     $salesAchieved = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.invoice, daily_reports.invoiceDateTime, customers.company from daily_reports 
		   left join customers on daily_reports.company = customers.customerId
		   where daily_reports.addedBy = '". $employee['id'] ."' and daily_reports.inv > '0' and daily_reports.invoiceDateTime like '". $like ."'");
		   
		   
		   
		   ?>
           
                                
<?PHP


if(isset($_POST['filter']))
{
	$currentMonth = $_POST['month'];
	$currentMonth = (int)$currentMonth;
	
	$yrs = explode("-",$_POST['year']);
	if($yrs[0]!='0')
	{
		$prevyear=$yrs[1];
		$curtyr=$yrs[0];
		
		
		$yr1=date('Y');
		$yr2=date('Y')-1;
	}
	else
	{
		
		if($currentMonth>=3)
		{
			$yr1=date('Y');
			$yr2=date('Y')+1;
		}
		else
		{
			$yr1=date('Y')-1;
			$yr2=date('Y');
		}
	}	
	

	
	
	
}

else
{
	$currentMonth = date('m');

	$currentMonth=(int)$currentMonth;
	//echo $mnth;
		$yr1=date('Y');
		$yr2=date('Y')-1;	
}

$monthly_achieved_amnt=0;

// achieved amount comes from collections and daily_reports table
$monthly_achieved_qry = mysql_query("select sum(col.amount) MonthlyAchieved from daily_reports dr join employees emp on emp.id=dr.addedBy join invoices inv on inv.reportId=dr.reportId join collections col on col.invoiceId=inv.invoiceId where dr.addedBy=".$employee['id']."  and MONTH(col.paidDate)='".$currentMonth."' AND ( YEAR(col.paidDate)='".$yr1."' or  YEAR(col.paidDate)='".$yr2."')");

if(mysql_num_rows($monthly_achieved_qry)>0)
{
	$achievmnth_Amnt = mysql_fetch_object($monthly_achieved_qry);
	$monthly_achieved_amnt=$achievmnth_Amnt->MonthlyAchieved;
}
 
?>                                
            
             <!-- <a href="#modal-table" role="button" class="green get_modal" id="<?php echo $employee['id']; ?>" userdefined="monthlyachieved" data-toggle="modal">-->
             <?PHP
			 if($monthly_achieved_amnt>0)
			 {
			 ?>
             <a href="Monthly-achieved-target.php?empid=<?php echo $employee['id']; ?>&month=<?PHP echo $currentMonth;?>" >
			  	<?php if($monthly_achieved_amnt!=0){ echo $monthly_achieved_amnt; } else echo "0"; ?>
              </a>
               <?php 
			 }
			 else
			 {
				?>
                <a >0</a>
                <?PHP 
			 }
					$total_month_amnt=($total_month_amnt)+($monthly_achieved_amnt);
				?>
                                        
                                        </td>
                                                        <td><a href="year_target.php?eid=<?php echo $employee['id']; ?>"><?php if($yearTarget_amnt!=''){ echo $yearTarget_amnt; } else echo "0"; ?></a></td>
                                                        <?PHP $total_year_targert = ($total_year_targert)+($yearTarget_amnt); ?>
                                                        <td>
														
														<?php
														
														  $like1 = $financialYear1.'-%';
		   $like2 = $financialYear2.'-%';
		   
		     $salesAchieved = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.invoice, daily_reports.invoiceDateTime, customers.company from daily_reports 
		   left join customers on daily_reports.company = customers.customerId
		   where daily_reports.addedBy = '". $employee['id'] ."' and daily_reports.inv > '0' and (daily_reports.invoiceDateTime like '". $like1 ."' OR daily_reports.invoiceDateTime like '". $like2 ."')");
		   
		   
		   
		   ?>
           
           
            
            <?PHP
			
			//get the total amount achieved by the employee in the finanacial year
			
            
			//salesResult
			
			$qry = "select dr.reportId from daily_reports dr join employees emp on emp.id=dr.addedBy where dr.addedBy=".$employee['id'];
			//echo $qry; exit; 
			
			
            ?>
            
  <?PHP

$year_achieved_amnt=0;

if(!isset($_POST['filter']))
{
$mnth = (int)date('m'); 

	if($mnth<=3)
	{
		$prevyear=date('Y')-1;
		$curtyr=date('Y');
	}
	else
	{
		$prevyear=date('Y')+1;
		$curtyr=date('Y');
	}
}
else
{
	$yrs = explode("-",$_POST['year']);
	if($yrs[0]!='0')
	{
		$prevyear=$yrs[1];
		$curtyr=$yrs[0];
	}
	else
	{
		$curmnth = date('m');
		$curmnth = (int)$curmnth;
		
		if($curmnth>=3)
		{
			$prevyear=date('Y');
			$curtyr=date('Y')+1;
		}
		else
		{
			$prevyear=date('Y')-1;
			$curtyr=date('Y');
		}
	}	
	
}

$year_achieved_qry = mysql_query("select sum(col.amount) YearAchieved from daily_reports dr join employees emp on emp.id=dr.addedBy join invoices inv on inv.reportId=dr.reportId join collections col on col.invoiceId=inv.invoiceId where dr.addedBy=".$employee['id']."  and ( YEAR(col.paidDate)='".$curtyr."' OR YEAR(col.paidDate)='".$prevyear."')");


if(mysql_num_rows($year_achieved_qry)>0)
{
	$achievmnth_Amnt = mysql_fetch_object($year_achieved_qry);
	$year_achieved_amnt=$achievmnth_Amnt->YearAchieved;
}
 
 
 if(isset($_POST['month']))
 {
	 $mnth=(int)$_POST['month'];
 }else
 {
	$mnth=0; 
 }
 if(isset($_POST['year']))
 {
	 $yr=$_POST['year'];
 }else
 $yr=0;
?>           
            
<!--  <a href="#modal-table" role="button" class="green get_modal" data-toggle="modal" id="<?php echo $employee['id']; ?>" userdefined="yearachieved" >-->
<?PHP
if($year_achieved_amnt>0)
{
?>
<a href="Yearly-achieved-target.php?empid=<?php echo $employee['id']; ?>&month=<?PHP echo $mnth;?>&yr=<?PHP echo $yr;?>">
<?php if($year_achieved_amnt!=''){ echo $year_achieved_amnt; } else echo "0"; ?>
</a>
<?PHP
}
else
{
?>
<a>0</a>
<?PHP	
}
?>			  

														<?PHP $total_year_target_achieved = ($total_year_target_achieved)+($year_achieved_amnt) ?>
														<?php //echo  $currentMonthAmount[$m];
														
														
														 ?></td>
                                                                                                                
                                                        
													</tr>
<?php 



$rowlist[] = $sno;
$rowlist[] = ucfirst($employee['firstName']);
$rowlist[] =  $monthTarget['target'];
$rowlist[] = @$currentMonthAmount[$m];
$rowlist[] = @$year_target;
$rowlist[] = @$yearAchieved;
	 
	 
 $list[] = $rowlist;
 unset($rowlist);

$grandMonthTarget = $monthTarget['target']+$grandMonthTarget;
$grandMonthAchieved = @$currentMonthAmount[$m]+$grandMonthAchieved;
$grandYearTarget = @$year_target+@$grandYearTarget;
$grandYearAchieved = @$yearAchieved+@$grandYearAchieved;


$rowlist[] = '';
$rowlist[] = '';
$rowlist[] =  $grandMonthTarget;
$rowlist[] = $grandMonthAchieved;
$rowlist[] = $grandYearTarget;
$rowlist[] = $grandYearAchieved;
	 
	 
 $list[] = $rowlist;
 unset($rowlist);

unset($currentMonthAmount);
unset($monthTarget);
unset($year_target);
unset($yearAchieved);



$sno++;
  } } 
  
  ?>    
  <tr><td colspan="4"></td>
   <td><?php echo $grandMonthTarget; ?></td>
   <td><?php echo $total_month_amnt; //$grandMonthAchieved; ?></td>
   <td><?php echo $total_year_targert; //$grandYearTarget; ?></td>
   <td><?php echo $total_year_target_achieved//$grandYearAchieved; ?></td></tr>                                          </tbody>
											</table>
                                                                                  
              <?php                              
$fp = fopen('excel/targets.csv', 'w');

foreach ($list as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp) ?>
           
										</div>
									</div>
								</div>

								<div id="modal-table" class="modal fade" tabindex="-1">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">&times;</span>
													</button>
													<span id="username"></span>
												</div>
											</div>

											<div class="modal-body no-padding body_content">
												
											</div>

											<div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close
												</button>

												
											</div>
										</div><!-- /.modal-content -->
									</div><!-- /.modal-dialog -->
                                    
                                    
								</div><!-- PAGE CONTENT ENDS -->
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
  
  function confirmDelete(did)
  {
	  if(confirm("Confirm Delete"))
	  {
	  window.location = 'employees.php?did='+did;
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

        <script>
			$(document).ready(function() 
			{ 
			
				$(".get_modal").on('click',function() 
				{
					var mnth_yearly	=	$(this).attr('userdefined');
					var empl_ide	=	$(this).attr('id');
					var mnth=$("#month").val();
					var yr=$("#year").val();
					
					
					$.ajax({ 
								'url':'ajax/get_pop_data_targets.php',
								'type':"POST",
								'data':{"mnth_yearly":mnth_yearly,"empl_ide":empl_ide,'mnth':mnth,'yr':yr},
								success:function(resp_data) {
													
													var respdata = $.parseJSON(resp_data);
												if(	respdata.nodata=="no")
												{
													$(".body_content").html(respdata.data);
													$("#username").html(respdata.Employee);
												}
												else
												{
													$(".body_content").html('No records found');
												}
									 }
								
								
							})
				});
				
			});
		</script>
        
        
        
	</body>
</html>
