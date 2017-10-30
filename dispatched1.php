<?php include("includes/header.php"); 


 // delet report
 if(isset($_GET['did']))
 {
	
	mysql_query("delete from orders where id = '". $_GET['did'] ."'");
	header("location: orders.php?delete=1"); 
 }
 
// update status
if(isset($_POST['submitStatus']))
{
  
 mysql_query("insert into order_status (`orderId`, `status`, `remarks`, `dateTime`) values('". $_POST['orderId'] ."', '". $_POST['status'] ."', '". addslashes($_POST['remarks']) ."', NOW())");	
  
  $lastId = mysql_insert_id();
  
  if($lastId>0)
  {
	 header("location: orders.php?update=1");  
  }
  else
  {
	  header("location: orders.php?error=1");  
  }
 
}

// pagination
$where = '';
$limit = 5;
 
 $numRecords = mysql_query("select daily_reports.invoice, daily_reports_data.quantity, dispatch.dispatchedId, dispatch.reportId, dispatch.dispatchedOn, dispatch_items.remarks, dispatch_items.dispatchedQuantity, products.product, products.code, customers.company from dispatch

left join daily_reports on dispatch.reportId = daily_reports.reportId
left join customers on daily_reports.company = customers.customerId
left join dispatch_items on dispatch.dispatchedId = dispatch_items.dispatchId
left join daily_reports_data on dispatch_items.itemId = daily_reports_data.id
left join products on daily_reports_data.productId = products.productId
$where
order by dispatch.dispatchedId desc");
 
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


// search options
  if(isset($_GET['dispatchSearch']))
									{
										
										$where = 'where ';
										
									if(isset($_GET['fromDate']) && strlen($_GET['fromDate']>8) && isset($_GET['toDate']) && strlen($_GET['toDate']>8))
									{
										
									
										$fromDate = explode('-',$_GET['fromDate']);
										$fromDate = $fromDate[2].'-'.$fromDate[1].'-'.$fromDate[0];
										
										$toDate = explode('-',$_GET['toDate']);
										$toDate = $toDate[2].'-'.$toDate[1].'-'.$toDate[0];
									 
									 
									 $whereItems[] = "dispatch.dispatchedOn >=  '". $fromDate ."'";
									 
									 $whereItems[] = "dispatch.dispatchedOn <= '". $toDate ."'";
									}
									
									
									   // by invoice
									   if(isset($_GET['invid']) && $_GET['invid']>0)
									   {  
									    
										    $whereItems[] = "daily_reports.reportId =  '". $_GET['invid'] ."'";
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
												
												
												
								 $query = "select daily_reports.invoice, daily_reports.invoiceDateTime, daily_reports_data.quantity, dispatch.dispatchedId, dispatch.reportId, dispatch.dispatchedOn, dispatch_items.remarks, dispatch_items.dispatchedQuantity, products.product, products.code, customers.company from dispatch

left join daily_reports on dispatch.reportId = daily_reports.reportId
left join customers on daily_reports.company = customers.customerId
left join dispatch_items on dispatch.dispatchedId = dispatch_items.dispatchId
left join daily_reports_data on dispatch_items.itemId = daily_reports_data.id
left join products on daily_reports_data.productId = products.productId
$where
order by dispatch.dispatchedId desc";				
												
									}
else								
{
  $where = '';	
/*  $query = "select daily_reports.invoice, daily_reports.invoiceDateTime,  daily_reports_data.quantity, dispatch.dispatchedId, dispatch.reportId, dispatch.dispatchedOn, dispatch_items.remarks, dispatch_items.dispatchedQuantity, products.product, products.code, customers.company from dispatch

left join daily_reports on dispatch.reportId = daily_reports.reportId
left join customers on daily_reports.company = customers.customerId
left join dispatch_items on dispatch.dispatchedId = dispatch_items.dispatchId
left join daily_reports_data on dispatch_items.itemId = daily_reports_data.id
left join products on daily_reports_data.productId = products.productId
$where
order by dispatch.dispatchedId desc limit $start, $limit";*/


  echo $query = "select daily_reports.invoice, daily_reports.invoiceDateTime,  dispatch.dispatchedId, dispatch.reportId, dispatch.dispatchedOn,  customers.company from dispatch
left join daily_reports on dispatch.reportId = daily_reports.reportId
left join customers on daily_reports.company = customers.customerId
$where
order by dispatch.dispatchedId desc limit $start, $limit";


}




$result = mysql_query($query);

?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Dispatched </li>
                            
                            
						</ul><!-- /.breadcrumb -->
                        
               <a href="excel/dispatched.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>

						
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
												<option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
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
                                    
                                    
                                                                        
                                    <form class="form-inline" method="get" action="" autocomplete="off">
                                  
                      
  <div class="row">              
                                  
  <div class="form-group col-sm-2">
   <span>From Date</span>
    <input type="text" class="form-control date-picker input-sm" id="fromDate" name="fromDate" placeholder="From Date" <?php if(isset($_GET['fromDate'])) { ?> value="<?php echo $_GET['fromDate']; ?>" <?php } ?> />
  </div>
  <div class="form-group col-sm-2">
   <span>To Date</span>
   <input type="text" class="form-control date-picker input-sm" id="toDate" name="toDate" placeholder="To Date" <?php if(isset($_GET['toDate'])) { ?> value="<?php echo $_GET['toDate']; ?>" <?php } ?> />
  </div>
   
                                               
                               
                                <div class="col-sm-2">
                                <span>Invoice</span>
 <input type="hidden" id="invid" name="invid"   />
 <input type="text" id="invoice" name="invoice" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Invoice" onkeyup="getInvoice(this.value)"  <?php if(isset($_GET['invoice'])) { ?> value="<?php echo $_GET['invoice']; ?>" <?php } ?>  />
           <ul class="typeahead dropdown-menu" style="left: 10px; display: none;" id="invoicesList">
                                           </ul>
                                           
                                </div> 
                                
                                
                               
                                
                                       
  <div class="form-group col-sm-2">
   <span><br></span>
    <input type="submit" class="btn btn-sm btn-success" name="dispatchSearch">
  </div>
  
  </div>
                                
                              
                                 
                               
  
</form>

</div>
									<div class="col-xs-12">
										
										<div class="table-header">
											Dispatched
										</div>


    <?php
										   
	   if(isset($_GET['delete']))
{ $alertMsg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Order has been deleted!</div>'; }
else if(isset($_GET['update']))
{ $alertMsg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Status has been updated!</div>'; }
else if(isset($_GET['error']))
{ $alertMsg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  if(isset($alertMsg)) { echo $alertMsg; }
										   ?> 
										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">
											<table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                            
												<thead>
													<tr>
														<th class="center">
															S.no
														</th>
														<th>Dispatch Date</th>
                                                        <th>Invoice Date</th>
                                                        <th>Invoice</th>
                                                        <th>Company</th>
                                                        <th>Product</th>
                                                        <th>Model No</th>
                                                        <th>Ordered</th>
                                                        <th>Dispatched</th>
                                                        <th>Pending</th>
                                                        <th>Remarks</th>
                                                     
														
													</tr>
												</thead>

												<tbody>
													
														
																	

  <?php   $list[] = array('S. No', 'Dispatch Date', 'Invoice Date', 'Invoice', 'Company', 'Product', 'Model No', 'Ordered', 'Dispatched');
  if(mysql_num_rows($result)>0) {
   $i = $start+1;
		
   
   
  while($row = mysql_fetch_array($result))
  {
	
	
///	$currentStatus = mysql_query("select `status`, `remarks`, `dateTime` from order_status where orderId = '". $proposal['id'] ."' order by statusId desc");
	
	
	  
	 ?><tr>
														<td class="center">
															<?php echo $i; ?>
														</td>
<td>
                                                         <?php
														
													 	 $dispatchedOn = explode('-',$row['dispatchedOn']); 
														 $dispatchedOn = $dispatchedOn[2].'-'.$dispatchedOn[1].'-'.$dispatchedOn[0];
												
												if(strcmp('00-00-0000',$dispatchedOn)!=0)
												{
													echo $dispatchedOn;
												}
                                                           
 													  ?>
                                                      </td>
                                                      
														<td>
                                                         <?php
														
													 	 $invoiceDate = explode(' ',$row['invoiceDateTime']); 
														 $invoiceDate = explode('-',$invoiceDate[0]); 
														 $invoiceDate = $invoiceDate[2].'-'.$invoiceDate[1].'-'.$invoiceDate[0];
												
												if(strcmp('00-00-0000',$invoiceDate)!=0)
												{
													echo $invoiceDate;
												}
                                                           
 													  ?>
                                                      </td>
                                                      
                                                      <td>
															<?php
															
														
															 echo $row['invoice']; 
															?>
                                
														</td>
                                                         <td>
															<?php
															
														
															 echo $row['company']; 
															?>
                                
														</td>
                                                        	<td>
															<?php
															 echo $row['product']; 
															?>
                                
														</td>

                                                        	<td>
															<?php
															
														
															  echo $row['code']; 
															?>
                                
														</td>
                                                        <td>
															<?php echo $row['quantity']; ?>
														</td>
                                                         <td>
															<?php echo $row['dispatchedQuantity']; ?>
														</td>
                                                        <td>
  <a href="<?php echo '#modal-form'.$row['dispatchedId']; ?>" role="button" class="label label-warning" data-toggle="modal"> Remarks </a>
                                                              <!--Modal Box-->
                                            <div id="<?php echo 'modal-form'.$row['dispatchedId']; ?>" class="modal in" tabindex="-1" aria-hidden="false" style="display: none;"><div class="modal-backdrop  in"></div>
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">×</button>
												<h4 class="blue bigger"><?php  echo $row['invoice'];  ?></h4>
											</div>

											<div class="modal-body">
												<div class="row">
													<div class="col-xs-12 col-sm-12">
														
                                             <?php echo $row['remarks'];   ?>

													</div>
												</div>
											</div>

											<div class="modal-footer">
												<button class="btn btn-sm" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close
												</button>

												
											</div>
										</div>
									</div>
								</div>
                                            <!--Modal Box-->
                                            
                                         
                    
														</td>
                                                        
                                                        
                                                       
                                                        
                                                      
														

													
													</tr>
<?php



$rowlist[] = $i;
$rowlist[] = $dispatchedOn;
$rowlist[] = $invoiceDate;
$rowlist[] = $row['invoice']; 
$rowlist[] = $row['company'];
$rowlist[] = $row['product']; 
$rowlist[] = $row['code']; 
$rowlist[] = $row['quantity'];
$rowlist[] = $row['dispatchedQuantity'];
$list[] = $rowlist;
unset($rowlist);


 $i++;
  } } else {
  
  ?>        
  
  <tr><td colspan="9">No Data found.</td></tr>
  <?php } 
  
  if($numRecords>$limit &&  !(isset($_GET['dispatchSearch'])))
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
  <tr><td colspan="10">
  
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
$fp = fopen('excel/dispatched.csv', 'w');

foreach ($list as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp) ?>
                                     
                                            
                                           
                                           
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
 <script src="assets/js/date-time/bootstrap-datepicker.js"></script>	
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

  function goToPage(pid)
{
   window.location = 'dispatched.php?page='+pid;	
}
 
 		
		function getInvoice(val)
		{
			
			document.getElementById("invoicesList").style.display = 'block';
				$.ajax({url: "ajax/getInvoicesList.php?val="+val, success: function(result){
		$("#invoicesList").html(result);
    }});	
			
		}
		
		
		function selectInvoice(invid,invoice)
		{
			document.getElementById("invoicesList").style.display = 'none';
			document.getElementById("invid").value = invid;
			document.getElementById("invoice").value = invoice;
	
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
