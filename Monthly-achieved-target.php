<?php include("includes/header.php"); 
 
// data comes from employees table 
/* relation between employees and rolls are employees.roll = rolls.roll_id */
$employees = mysql_query("select employees.id,designation, b.branch, employees.firstName, employees.email, employees.dateJoining, employees.personalMobile, rolls.roll from employees 
 left join rolls on employees.roll = rolls.roll_id
 left join branches b on employees.branch=b.branchid
 where employees.roll = '4'
 and employees.id=".$_GET['empid']."
 order by employees.firstName");
 
 
// echo mysql_num_rows($employees); exit; 
$empl = mysql_fetch_object($employees); 

$branch = $empl->branch;
$designation = $empl->designation;
$employee = $empl->firstName;



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

$rowlist = array();

$rowlist[] = 'S. No';
$rowlist[] = 'Name';
$rowlist[] = 'Branch';
$rowlist[] = 'Designation';
$rowlist[] = 'Invoice Date';
$rowlist[] = 'Invoice Number';
$rowlist[] = 'Product Handled';
$rowlist[] = 'Company';
$rowlist[] = 'Price';
$list[] = $rowlist;

unset($rowlist);




?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Monthly achieved targets </li>
						</ul><!-- /.breadcrumb -->
                        
<a href="excel/Monthly-achieved-target.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>
                        

						
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
 


?>

								<div class="row">
									<div class="col-xs-12">
										
										<div class="table-header">
										<?php echo date('F').' achieved target by '.ucwords($employee); ?>
                                        	<div class="filters-table-header">
                                        
                                        
											
                                            
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
														
                                                        <th>Branch</th>
                                                        <th>Designation</th>
                                                        <th>Invoice Date</th>
                                                        <th>Pro-Invoice</th>
                                                        <th>Product Handled</th>
													    <th>Company</th>
                                                        <th>Price </th>
													</tr>
												</thead>
											
                                            <tbody>
                                            	
<?PHP
			$mnth=$_GET['month'];
			$empl_ide=$_GET['empid'];
			
			if($mnth>0)	
				{
				$qry =mysql_query("select inv.invoiceId,inv.invoiceNumber,inv.proInvoiceNumber, cus.company,inv.invoiceDateTime, col.amount,emp.firstName from daily_reports as dr join invoices as inv on inv.reportId=dr.reportId join collections as col on col.invoiceId=inv.invoiceId join employees as emp on emp.id=dr.addedBy join customers as cus on cus.customerId=dr.company  where dr.addedBy=".$empl_ide." and MONTH(col.paidDate)='".$mnth."'");
				}
				else
				{
				$qry =mysql_query("select inv.invoiceId,inv.invoiceNumber,cus.company,inv.proInvoiceNumber, inv.invoiceDateTime, col.amount,emp.firstName from daily_reports as dr join invoices as inv on inv.reportId=dr.reportId join collections as col on col.invoiceId=inv.invoiceId join employees as emp on emp.id=dr.addedBy join customers as cus on cus.customerId=dr.company  where dr.addedBy=".$empl_ide." and MONTH(col.paidDate)='".date('m')."'");
				}
				
				if(mysql_num_rows($qry)>0)
				{
					$slno=0;
					$empl='';
					$totalAmnt=0;
					$rows='';
					$slno=1;
					while($data=mysql_fetch_object($qry))
					{
						
						$totalAmnt=$totalAmnt+$data->amount;
						
						//get the product name
						
						//for this we need to know the relationships between the tables
						//invoice tabele is related to the dailyreorts by reportId
						//from there we can get the revisionid from the table called daily_reports_revision by using the reportId
						//by using the revisionId we can get the product id from the table called daily_reports_data
						//by using the productid we can get the product name from products table
						
						
						$qrye = "select prd.product from products as prd join  daily_reports_data as drd on drd.productId=prd.productId";
						$qrye.= " join daily_reports_revision as drv on drv.revisionId=drd.revisionId";
						$qrye.= " join daily_reports as dr on dr.reportId=drv.reportId";
						$qrye.= " join invoices as inv on inv.reportId=dr.reportId";
						$qrye.=" where inv.invoiceNumber='".$data->invoiceNumber."'";
						$exec = mysql_query($qrye);
						$prd = mysql_fetch_object($exec);
						$productname = $prd->product;
						
						
						
						

					?>
                    <tr>
                        <td> <?PHP echo $slno;//$empl->branch;?></td>
                        <td><?PHP echo $branch; ?> </td>
                        <td><?PHP echo $designation;?> </td>
                        
                        <td><?PHP echo $data->invoiceDateTime;?> </td>
                         <td><?PHP echo $data->proInvoiceNumber;?> </td>
                         <td><?PHP echo $productname;?> </td>
                        <td> <?PHP echo $data->company;?></td>
                       
                        
                        <td><?PHP echo $data->amount;?> </td>
                        
                    </tr>
         
                    
                    
                    <?PHP
					
					
					
					$rowlist[] = $slno;
					$rowlist[] = ucfirst($employee);

					$rowlist[] =  ucwords($branch);
					$rowlist[] = ucwords($designation);

					$rowlist[] = $data->invoiceDateTime;
					$rowlist[] = $data->invoiceNumber;


					$rowlist[] = $productname;
					$rowlist[] = $data->company;
					$rowlist[] = $data->amount;
					$list[] = $rowlist;
					 unset($rowlist);
						 
					$slno++;
					}
					
				}
	
			?>
            <tr><td colspan=6> </td> <td> <strong>Total</strong></td><td><b><?PHP echo $totalAmnt; ?> </b></tr>
            
               <?php                              
$fp = fopen('excel/Monthly-achieved-target.csv', 'w');

foreach ($list as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp) ?>                                  
                                            </tbody>
												
                                                
											</table>
                                                                                  
             
             
           
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
