<?php include("includes/sa_header.php"); 

$employees = mysql_query("select employees.id, employees.firstName from employees left join rolls on employees.roll = rolls.roll_id where employees.roll = '4' and employees.id=".$_SESSION['id']." order by employees.firstName ");
 
?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Orders  </li>
						</ul><!-- /.breadcrumb -->
<a href="excel/projections.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>


						<!-- #section:basics/content.searchbox -->
						<div class="nav-search" id="nav-search">
						<!--	<form class="form-search">
                            
                             
								<span class="input-icon">
									 <select class="nav-search-input" onchange="changeMonth(this.value)">
                                <option value="01" <?php if($cm=='01') { echo 'selected="selected"'; } ?>>Jan</option>
                                <option value="02" <?php if($cm=='02') { echo 'selected="selected"'; } ?>>Feb</option>
                                <option value="03" <?php if($cm=='03') { echo 'selected="selected"'; } ?>>Mar</option>
                                <option value="04" <?php if($cm=='04') { echo 'selected="selected"'; } ?>>Apr</option>
                                <option value="05" <?php if($cm=='05') { echo 'selected="selected"'; } ?>>May</option>
                                <option value="06" <?php if($cm=='06') { echo 'selected="selected"'; } ?>>Jun</option>
                                <option value="07" <?php if($cm=='07') { echo 'selected="selected"'; } ?>>Jul</option>
                                <option value="08" <?php if($cm=='08') { echo 'selected="selected"'; } ?>>Aug</option>
                                <option value="09" <?php if($cm=='09') { echo 'selected="selected"'; } ?>>Sep</option>
                                <option value="10" <?php if($cm=='10') { echo 'selected="selected"'; } ?>>Oct</option>
                                <option value="11" <?php if($cm=='11') { echo 'selected="selected"'; } ?>>Nov</option>
                                <option value="12" <?php if($cm=='12') { echo 'selected="selected"'; } ?>>Dec</option>
                                </select>
									
                                    
								</span>
                                
                           
                                <i class="ace-icon fa fa-search nav-search-icon"></i>
                               
                                
							</form>-->
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
								

							
 <?php
	   if(isset($_GET['delete']))
{ $msg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Customer has been deleted!</div>'; }
else if(isset($_GET['error']))
{ $msg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  if(isset($msg)) {  //echo $msg; 
  }
										   ?> 


								<div class="row">
									<div class="col-xs-12">
										
										<div class="table-header">
											Orders
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
                                                        <th>Orders Projected</th>
                                                        <th>Orders Achieved</th>
                                                        <!--
                                                        <th>Orders Projected</th>
                                                        <th>Orders Achieved</th>
                                                      -->
													</tr>
												</thead>

												<tbody>
													
														
																	

  <?php 
  if(mysql_num_rows($employees)>0)
  {
  $sno = $start+1;
  $grandExpectedSaleAmount = 0;
  $grandAchievedSaleAmount = 0;
  
  $list[] = array('S. No', 'Name', 'Projection', 'Collected', 'Sales Projections', 'Sales Achieved');
  
  
  while($employee = mysql_fetch_array($employees))
  {
    // data comes from expected_sales
$Projectedqry = mysql_query("select expectedValue from expected_sales where employeeId=".$employee['id']); 

    // data comes from invoices table  
$salesAchieved = mysql_query("select inv.grandTotal as Achieved, emp.firstName from invoices as inv left join daily_reports as dr on inv.reportId=dr.reportId left join employees as emp on dr.addedBy=emp.id where dr.addedBy=".$employee['id']); 
						
						
 ?><tr>
														<td class="center"><?php echo $sno; ?></td>
                                                       <td><?php echo ucfirst($employee['firstName']);  ?></td>
                                                         
													   <td><?PHP
													   if(mysql_num_rows($Projectedqry)>0){ 
													    $Projected=mysql_fetch_object($Projectedqry);
														echo $Projected->expectedValue;
													   }
													   else
													   	echo "0";
													   ?></td>
                                                         
                                                         <td>
                                                         
                                                         <?PHP
													   if(mysql_num_rows($salesAchieved)>0){ 
													    $Achieveed=mysql_fetch_object($salesAchieved );
														echo $Achieveed->Achieved;
													   }
													   else
													   	echo "0";
													   ?>
                                                         </td>
                                                      
                                                      
                                                      
                                                     
                                                     
                                                        
													</tr>
<?php 

$finalProjectedAmount = $finalProjectedAmount+$projectedAmount;
$finalCollectedAmount = $finalCollectedAmount+$collectedAmount;

$rowlist[] = $i;
$rowlist[] = $reportDate;
$rowlist[] = $poDate;
$rowlist[] = $proposal['poNo']; 
$rowlist[] = $invoiceDate;
$rowlist[] = $proposal['invoice']; 
$rowlist[] = $proposal['firstName']; 
$rowlist[] = $proposal['company']; 
$rowlist[] = $grandTotal; 
$rowlist[] = $collectedAmount;
$rowlist[] = $diff;
	 
	 
	 
 $list[] = $rowlist;
 unset($rowlist);


$sno++;
  } } 
  
  ?>     
  
  
                                            </tbody>
											</table>
                                            
                                                                                        <?php                              
$fp = fopen('excel/projections.csv', 'w');

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
  
 
		
		function changeMonth(cm)
		{
			window.location = 'projections.php?cm='+cm;
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
