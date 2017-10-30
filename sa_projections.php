<?php include("includes/sa_header.php"); 
 
// pagination
 $limit = 10;
 
 // data comes from expected_collections table
 $numRecords = mysql_query("select * from expected_collections 
 left join daily_reports on expected_collections.reportId = daily_reports.reportId
 where daily_reports.addedBy = '". $_SESSION['id'] ."' order by expected_collections.expectedId desc");
 
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


// search
   $where = 'where ';
  $whereItems[] = "daily_reports.addedBy = '". $_SESSION['id'] ."'";
  
if(isset($_GET['projectionSearch']))
{

    // by num days
									   if(isset($_GET['numDays']) && $_GET['numDays']>0)
									   {  
									   
									   
		 							    $numDays = '-'.$_GET['numDays'];
									    $fromDate = strtotime($numDays." days", time() + (86400));
									    $fromDate = date("Y-m-d",$fromDate);
										$whereItems[] = " expected_collections.expectedDate > '". $fromDate ."'";
									   }
									   
									    // by customer
									  if(isset($_GET['customer']) && $_GET['customer']!='')
										{
									   if(isset($_GET['cid']) && $_GET['cid']>0)
									   {  
									  
										    $whereItems[] = "daily_reports.company =  '". $_GET['cid'] ."'";
									   }
										}
										else 
										$_GET['customer']='';
									   
									
										
									
									
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

// data comes from expected_collections table												
												
												$collections = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.contactPerson, daily_reports.invoice, expected_collections.expectedId, expected_collections.amount, expected_collections.expectedDate  from expected_collections 
 left join daily_reports on expected_collections.reportId = daily_reports.reportId
 $where  order by expected_collections.expectedId");	
											
}
else
{

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
			
// data comes from expected_collections table		
			$collections = mysql_query("select daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.contactPerson, daily_reports.invoice, expected_collections.expectedId, expected_collections.amount, expected_collections.expectedDate  from expected_collections 
 left join daily_reports on expected_collections.reportId = daily_reports.reportId
 $where  order by expected_collections.expectedId desc limit $start, $limit");									
 

}


?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Projections  </li>
						</ul><!-- /.breadcrumb -->
                        
           <a href="excel/sa_outstanding.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>


						<!-- #section:basics/content.searchbox -->
						<div class="nav-search" id="nav-search" style="display:none;">
                        
                       
							<form class="form-search">
                            
                             
								<span class="input-icon">
								<select class="nav-search-input" onchange="changeMonth(this.value)">
                                <option value="01" <?php if($cm=='01') { echo 'selected="selected"'; } ?>>January</option>
                                <option value="02" <?php if($cm=='02') { echo 'selected="selected"'; } ?>>Febraury</option>
                                <option value="03" <?php if($cm=='03') { echo 'selected="selected"'; } ?>>March</option>
                                <option value="04" <?php if($cm=='04') { echo 'selected="selected"'; } ?>>April</option>
                                <option value="05" <?php if($cm=='05') { echo 'selected="selected"'; } ?>>May</option>
                                <option value="06" <?php if($cm=='06') { echo 'selected="selected"'; } ?>>June</option>
                                <option value="07" <?php if($cm=='07') { echo 'selected="selected"'; } ?>>July</option>
                                <option value="08" <?php if($cm=='08') { echo 'selected="selected"'; } ?>>August</option>
                                <option value="09" <?php if($cm=='09') { echo 'selected="selected"'; } ?>>September</option>
                                <option value="10" <?php if($cm=='10') { echo 'selected="selected"'; } ?>>October</option>
                                <option value="11" <?php if($cm=='11') { echo 'selected="selected"'; } ?>>November</option>
                                <option value="12" <?php if($cm=='12') { echo 'selected="selected"'; } ?>>December</option>
                                </select>
								</span>
                                <i class="ace-icon fa fa-search nav-search-icon"></i>
                               
                                
							</form>
						</div><!-- /.nav-search -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content" id="outclick">
						<!-- #section:settings.box -->
						<!-- /.ace-settings-container -->

						<!-- /section:settings.box -->
					

						<div class="row">
                        <div class="col-xs-12">
                                    
                                    
                                                                        
                                    <form class="form-inline" method="get" action="" autocomplete="off">
                                  
                      
  <div class="row">              
                                  
  <div class="form-group col-sm-2">
   <span>No. Days<br></span>
    <select class="form-control date-picker input-sm" id="numDays" name="numDays">
    <option value="0">Select Days</option>
    <option value="30" <?php if(isset($_GET['numDays']) && $_GET['numDays']==30) { ?> selected="selected" <?php } ?>>30 Days</option>
    <option value="60" <?php if(isset($_GET['numDays']) && $_GET['numDays']==60) { ?> selected="selected" <?php } ?>>60 Days</option>
    <option value="90" <?php if(isset($_GET['numDays']) && $_GET['numDays']==90) { ?> selected="selected" <?php } ?>>90 Days</option>
    <option value="120" <?php if(isset($_GET['numDays']) && $_GET['numDays']==120) { ?> selected="selected" <?php } ?>>120 Days</option>
    </select>
  </div>
  
  <div class="col-sm-2">
                                <span>Customer</span>
                                <input type="hidden" id="cid" name="cid" value="<?PHP echo $_GET['cid']; ?>" />

<input type="text" id="customer" name="customer" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Customer" <?php if(isset($_GET['customer'])) { ?> value="<?php echo $_GET['customer']; ?>" <?php } ?>  />
     
      <ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="customersList"></ul>
                               </div>
 
  <div class="form-group col-sm-2">
   <br>
    <input type="submit" class="btn btn-sm btn-success" name="projectionSearch" value="Search">
  </div>
  
  </div>
  
</form>
<div class="space"></div>
</div>
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								
                                    <div class="row">
									        <div class="col-xs-12">
                                            
                                    
										<div class="table-header">
											Collections Projection
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
														<th>Expected Date</th>
                                                        <th>Invoice</th>
                                                        <th>Customer</th>
                                                        <th>Total</th>
                                                        <th>Paid</th>
                                                        <th>Pending</th>
                                                        <th>Expected</th>
														</tr>
												</thead>

												<tbody>
<?php 
  
  
$finalTotal = 0;
$finalPaid = 0;
$finalPending = 0;
$finalExpected = 0;
     $list[] = array('S.no', 'Expected Date', 'Invoice', 'Customer', 'Total', 'Paid', 'Pending', 'Expected' );

  if(mysql_num_rows($collections)>0)
  {   $i = $start+1;
  
  while($collection = mysql_fetch_array($collections))
  {
	
	
	 // total amount;
	   $totalAmount = mysql_query("select daily_reports_data.amount from daily_reports_revision
  left join daily_reports_data on daily_reports_revision.revisionId = daily_reports_data.revisionId
  where daily_reports_revision.reportId = '". $collection['reportId'] ."' and daily_reports_revision.revision = '". $collection['currentRevisionId'] ."'");
  
//  $totalAmount = mysql_query("select amount from daily_reports_data where reportId = '". $collection['reportId'] ."'");
  
  $grandTotal = 0;
  if(mysql_num_rows($totalAmount)>0)
  {
	  
	 while($total = mysql_fetch_array($totalAmount))
	 {
		$grandTotal = $total['amount']+$grandTotal; 
	 }
	   
  }
  
  // paid amount;
  $paidAmount = mysql_query("select amount from collections where invoiceId = '". $collection['reportId'] ."'");
  
  $paidTotal = 0;
  if(mysql_num_rows($paidAmount)>0)
  {
	  
	 while($total = mysql_fetch_array($paidAmount))
	 {
		$paidTotal = $total['amount']+$paidTotal; 
	 }
	   
  }
	  
	 ?><tr>
														<td class="center">
															<?php echo $i; ?>
														</td>
                                                        <td>
															<?php 
															      $date = explode('-',$collection['expectedDate']);
																  echo $date = $date[2].'-'.$date[1].'-'.$date[0];
																       ?>
														</td>
														<td>
															<?php echo $collection['invoice']; ?>
														</td>
                                                        	<td>
															<?php echo ucfirst($collection['contactPerson']); ?>
														</td>
                                                       
                                                        <td>
															<?php echo $grandTotal; ?>
														</td>
                                                        <td>
															<?php echo $paidTotal; ?>
														</td>
                                                        <td>
															<?php echo $diff = $grandTotal-$paidTotal; ?>
														</td>
                                                        <td>
															<?php echo $collection['amount']; ?>
														</td>
                                                        
                                                        
														

													</tr>
<?php

$finalTotal = $grandTotal+$finalTotal;
$finalPaid = $paidTotal+$finalPaid;
$finalPending = $diff+$finalPending;
$finalExpected = $collection['amount']+$finalExpected;


 $rowlist[] = $i;
 $rowlist[] = $date;
 $rowlist[] = $collection['invoice'];
 $rowlist[] = ucfirst($collection['contactPerson']);  
 $rowlist[] = $grandTotal;
 $rowlist[] = $paidTotal;
 $rowlist[] = $diff;
 $rowlist[] = $collection['amount'];
 $list[] = $rowlist;
  unset($rowlist);

 $i++; }
  $rowlist[] = '';
  $rowlist[] = '';
  $rowlist[] = '';
  $rowlist[] = '';
  $rowlist[] = '';
  $rowlist[] = '';
  $rowlist[] = '';

$rowlist[] = $finalExpected;
$list[] = $rowlist;
unset($rowlist);

 
 ?> 
 <tr><td colspan="4">  </td><td><?php // echo $finalTotal; ?></td><td><?php // echo $finalPaid; ?></td><td><?php // echo $finalPending; ?></td><td><?php echo $finalExpected; ?></td><td></td></tr>
 
 <?php
 
  } else { ?> <tr><td colspan="9"> No Data found. </td></tr> <?php }
  
  ?>      
    <?php 
	 if($numRecords>$limit &&  !(isset($_GET['projectionSearch']))) 
 {
   
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
	  else
	  {
		 
	  }
	  
	  
  }
  
  ?> 
  <tr><td colspan="12">
  
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
             Page <input class="ui-pg-input" type="text" onkeyup="goToPage(this.value)" size="2" maxlength="7" value="<?php echo $currentPage; ?>" role="textbox"> of <span id="sp_1_grid-pager"><?php echo $numPages; ?></span>
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
  
  
  
  
  
  </td></tr>
  <?php } ?>   
  
                                           </tbody>
											</table>
                                            
                                             <?php                             
$fp = fopen('excel/sa_projections.csv', 'w');

foreach ($list as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp); ?>
                         
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
													Results for "Latest Registered Domains
												</div>
											</div>

											<div class="modal-body no-padding">
												<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
													<thead>
														<tr>
															<th>Domain</th>
															<th>Price</th>
															<th>Clicks</th>

															<th>
																<i class="ace-icon fa fa-clock-o bigger-110"></i>
																Update
															</th>
														</tr>
													</thead>

													<tbody>
														<tr>
															<td>
																<a href="#">ace.com</a>
															</td>
															<td>$45</td>
															<td>3,330</td>
															<td>Feb 12</td>
														</tr>

														<tr>
															<td>
																<a href="#">base.com</a>
															</td>
															<td>$35</td>
															<td>2,595</td>
															<td>Feb 18</td>
														</tr>

														<tr>
															<td>
																<a href="#">max.com</a>
															</td>
															<td>$60</td>
															<td>4,400</td>
															<td>Mar 11</td>
														</tr>

														<tr>
															<td>
																<a href="#">best.com</a>
															</td>
															<td>$75</td>
															<td>6,500</td>
															<td>Apr 03</td>
														</tr>

														<tr>
															<td>
																<a href="#">pro.com</a>
															</td>
															<td>$55</td>
															<td>4,250</td>
															<td>Jan 21</td>
														</tr>
													</tbody>
												</table>
											</div>

											<div class="modal-footer no-margin-top">
												<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close
												</button>

												<ul class="pagination pull-right no-margin">
													<li class="prev disabled">
														<a href="#">
															<i class="ace-icon fa fa-angle-double-left"></i>
														</a>
													</li>

													<li class="active">
														<a href="#">1</a>
													</li>

													<li>
														<a href="#">2</a>
													</li>

													<li>
														<a href="#">3</a>
													</li>

													<li class="next">
														<a href="#">
															<i class="ace-icon fa fa-angle-double-right"></i>
														</a>
													</li>
												</ul>
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
		$(document).on('click','#outclick,#breadcrumbs',function(){
			$('#customersList').css('display','none');
			});
	

$(document).on('keyup','#customer',function(){
			var customer = $(this).val();
			
			$.ajax({
				   url: 'ajax/getCustomerList.php',
				   type: 'POST',
				   data: {'customer':customer},
				   success:function(data){ 
				   $("#customersList").html(data);
				$("#customersList").css('display','block');
				   }
				
				
				});
			
			
			});


	
		
		function selectCustomer(id,firstName)
		{
			document.getElementById("customersList").style.display = 'none';
			document.getElementById("cid").value = id;
			document.getElementById("customer").value = firstName;
	
		}	
		
		 function goToPage(pid)
{
   window.location = 'sa_projections.php?page='+pid;	
}
		
		function changeMonth(cm)
		{
			window.location = 'sa_projections.php?cm='+cm;
		}
		
		 function confirmDelete(did)
  {
	  if(confirm("Confirm Delete"))
	  {
	  window.location = 'sa_projections.php?did='+did;
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
