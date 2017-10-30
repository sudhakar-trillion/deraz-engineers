<?php include("includes/header.php"); 

 
// delete 
if(isset($_POST['updateTarget']))
{
	
	if($_POST['targetId']>0)
	{
		mysql_query("update targets set target = '". $_POST['target'] ."' where targetId = '". $_POST['targetId'] ."'");	
		$lastId = 1;
		
	}
	else
	{
		mysql_query("insert into  targets (`employeeId`, `financialMonth`, `financialYear`, `target`) values('". $_GET['eid'] ."', '". $_POST['month'] ."', '". $_POST['year'] ."', '". $_POST['target'] ."')");
		$lastId = mysql_insert_id();
		
	}
	
  if($lastId>0)
  {
	header("location: year_target.php?eid=".$_GET['eid']."&update=success");  
  }
  else
  {
	header("location: year_target.php?eid=".$_GET['eid']."&error=fail");    
  }
  

}

// year targets are given to only sales executives and the below data comes from employees table.
$employees = mysql_query("select employees.id, employees.firstName, employees.email, employees.dateJoining, employees.personalMobile, rolls.roll from employees 
left join rolls on employees.roll = rolls.roll_id
where employees.roll = '4' and employees.id = '". $_GET['eid'] ."'
order by employees.firstName");

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
								

							
 <?php
 
  $employee = mysql_fetch_array($employees);
  
	   if(isset($_GET['update']))
{ echo  '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Target has been updated!</div>'; }
else if(isset($_GET['error']))
{ echo '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }


			
			
 $currentYear = date('Y');
 $currentMonth = date('m');
 $previousYear = $currentYear-1;
 $nextYear = $currentYear+1;
 				
							
 if($currentMonth>3)
 {
	$financialYear = $currentYear.'-'.$nextYear;
 }
 else
 {
	 $financialYear = $previousYear.'-'.$currentYear; 
 }
 
  
 $total_mnth_achieved=0;
$rowlist[] = $employee['firstName'].' '.$financialYear.' Sales Targets';
$list[] = $rowlist;
 unset($rowlist);
 
  $list[] = array('S. No', 'Month', 'Target', 'Achieved');
	   ?> 


								<div class="row">
									<div class="col-xs-12">
										
										<div class="table-header">
										<?php echo $employee['firstName'].' '.$financialYear; ?>	Sales Targets
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
														<th>Month</th>
                                                        <th>Target</th>
													    <th>Achieved</th>
													</tr>
												</thead>

												<tbody>
													
														
																	

  <?php 
 
   
  $sno = $start+1; 
  
 
  

 
 
 
 $sno = 1;
 $yearAchieved = 0;
 $yearTarget = 0;
   for($i=4;$i<=12;$i++)
   {
	  
	   

 
 $m = $i;
 if(strlen($m)==1) { $m = '0'.$m; } else { $m = $m; }
 

 
 if($m>3)
 {
	   $like = $currentYear.'-'.$m.'-'.'%';
 }
 else
 {
	   $like = $nextYear.'-'.$m.'-'.'%'; 
 }
 
 // month target
 $monthTarget = mysql_query("select targetId, target from targets where employeeId = '". $_GET['eid'] ."' and financialMonth = '". $m ."' and financialYear = '". $financialYear ."'");
 
 
 $monthTarget = mysql_fetch_array($monthTarget);
 $yearTarget = $monthTarget['target']+$yearTarget;

 
  // month achieved
 $invoices = mysql_query("select reportId, currentRevisionId from daily_reports where addedBy = '". $_GET['eid'] ."' and inv = '2' and invoiceDateTime like '$like' order by reportId desc");

 $monthAchieved = 0;    
	if(mysql_num_rows($invoices)>0)
	{

 while($invoice = mysql_fetch_array($invoices))
 {
 // invoices amount:	 
 $invoicesAmount = mysql_query("select grandTotal from daily_reports_revision where reportId = '". $invoice['reportId'] ."' and revision = '". $invoice['currentRevisionId'] ."'");
 $invoiceAmount = mysql_fetch_array($invoicesAmount);
 
 $monthAchieved = $invoiceAmount['grandTotal']+$monthAchieved; 
 $yearAchieved = $monthAchieved+$yearAchieved;


 
  // month target
/* $monthTarget = mysql_query("select target from targets where employeeId = '". $employee['id'] ."' and financialMonth = '". $m ."'");
 $monthTarget = mysql_fetch_array($monthTarget);
 $currentMonthAmount[$m] = $monthAchieved;*/
 }
 

 
 
	}
	else
	{
		$monthAchieved = 0;    
	}
 
// $currentMonthAmount[$m] = $monthAchieved;
	   
	?><tr><td class="center"><?php echo $sno; ?></td>
          <td>
          <?php 
		  
		  $monthNum  = 10;
echo $monthName = date('F', mktime(0, 0, 0, $i, 10)); // March
		  ?>
          </td>
          <td>
          
         
		  <a href="<?php echo '#modal-form'.$i; ?>" role="button" class="blue" data-toggle="modal"><?php
		  
		
		   if(strlen($monthTarget['target'])>0) { echo $monthTarget['target']; } else { echo 0; } ?></a>
          <div id="<?php echo 'modal-form'.$i; ?>" class="modal in" tabindex="-1" aria-hidden="false" style="display: none;"><div class="modal-backdrop  in"></div>
									
                                    <form action="" method="post">
                                    <div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">×</button>
												<h4 class="blue bigger">Please fill the following form fields</h4>
											</div>

											<div class="modal-body">
												<div class="row">
													<div class="col-xs-12 col-sm-12">
														<div class="space"></div>

														
                                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="<?php echo 'form-field-1-'.$i; ?>"><?php echo $monthName.' Target'; ?> </label>

										<div class="col-sm-9">
		<input type="text" id="<?php echo 'form-field-1-'.$i; ?>" placeholder="Target" name="target" class="col-xs-10 col-sm-5" value="<?php echo $monthTarget['target']; ?>" />
        <input type="hidden" name="targetId" value="<?php echo $monthTarget['targetId']; ?>">
         <input type="hidden" name="month" value="<?php echo $m; ?>">
          <input type="hidden" name="year" value="<?php echo $financialYear; ?>">
										</div>
									</div>
                                                   <!--     
                                                        <div class="form-group">
															<label for="form-field-username">Username</label>

															<div>
																<input type="text" id="form-field-username" placeholder="Username" value="alexdoe">
															</div>
														</div>
                                                        
                                                        -->
													</div>

													
												</div>
											</div>

											<div class="modal-footer">
												<button class="btn btn-sm" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Cancel
												</button>

												<button class="btn btn-sm btn-primary" type="submit" name="updateTarget">
													<i class="ace-icon fa fa-check"></i>
													Update
												</button>
											</div>
										</div>
									</div></form>
								</div>
                                </td>
          <td>
		  
		                                  
<?PHP

$monthly_achieved_amnt=0;

$monthly_achieved_qry = mysql_query("select sum(col.amount) MonthlyAchieved from daily_reports dr join employees emp on emp.id=dr.addedBy join invoices inv on inv.reportId=dr.reportId join collections col on col.invoiceId=inv.invoiceId where dr.addedBy=".$_GET['eid']."  and MONTH(col.paidDate)='".$m."'");

if(mysql_num_rows($monthly_achieved_qry)>0)
{
	$achievmnth_Amnt = mysql_fetch_object($monthly_achieved_qry);
	$monthly_achieved_amnt=$achievmnth_Amnt->MonthlyAchieved;
	$total_mnth_achieved=$total_mnth_achieved+$achievmnth_Amnt->MonthlyAchieved;
}
 
?>                                

		  
		  
		  <?php if($monthly_achieved_amnt!=''){ echo $monthly_achieved_amnt;} else echo "0";//$monthAchieved; ?></td>
          </tr>
	  
	  
	  
	  <?php 
	  
	    $rowlist[] = $sno;
//$rowlist[] = ucfirst($employee['firstName']);
$rowlist[] = $monthName;
$rowlist[] = $monthTarget['target'];
$rowlist[] = $monthAchieved;
$list[] = $rowlist;
 unset($rowlist);
	  
	  $sno++;
	  if($i==12) { $i=0; }
	  else if($i==3) { $i=14; } 
	  
	
	  
	  //  
   }  
   ?>
   
   <tr><td colspan="2"></td><td><?php echo $yearTarget; ?>
   
   
   
   </td><td><?php echo $total_mnth_achieved; ?></td></tr>
   <?php

$rowlist[] = '';
$rowlist[] = '';
$rowlist[] = $yearTarget;
$rowlist[] = $yearAchieved;
$list[] = $rowlist;

unset($rowlist);
unset($currentMonthAmount);
unset($monthTarget);
unset($year_target);
unset($yearAchieved);




 
  
  ?>                                               </tbody>
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
	</body>
</html>
