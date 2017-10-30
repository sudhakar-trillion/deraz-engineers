<?php include("includes/header.php"); 

  // delete
  if(isset($_GET['did']))
  {
	  mysql_query("delete from services_station where serviceId = '". $_GET['did'] ."'");
	  mysql_query("delete from service_station_status where serviceId = '". $_GET['did'] ."'");
	  header("location: station_services.php?delete=success");
  }
  
    // search options 
    if(isset($_GET['search']))
								{
										$where = 'where ';
										
									// by date
									   if(isset($_GET['date']) && strlen($_GET['date'])>5)
									   {  
									        $date = explode('-',$_GET['date']);
											$date = $date[2].'-'.$date[1].'-'.$date[0];
										    $whereItems[] = "services_station.date =  '". $date ."'";
									   }
									
									
									   // by category
									   if(isset($_GET['cstegoryId']) && $_GET['categoryId']>0)
									   {  
									    
										    $whereItems[] = "categories.id =  '". $_GET['categoryId'] ."'";
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
								}
  // pagination
$limit = 10;

$numRecords = mysql_query("select services_station.serialNumber, services_station.serviceId, services_station.customerName, services_station.product, services_station.modelNo, services_station.date, services_station.equipmentNo, services_station.engineer, services_station.quantity, categories.category from services_station 
left join categories on services_station.category = categories.category $where
order by serviceId desc");
 
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
 


$services = mysql_query("select services_station.serialNumber, services_station.serviceId, services_station.customerName, services_station.product, services_station.modelNo, services_station.date, services_station.equipmentNo, services_station.engineer, services_station.quantity, categories.category from services_station 
left join categories on services_station.category = categories.id $where
order by serviceId desc limit $start, $limit");

									

?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> services   </li>
                            
						</ul><!-- /.breadcrumb -->
                        
                         <a href="excel/services.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>

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
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
      <form method="get" action="" class="form-inline" autocomplete="off">
 <div class="col-sm-2"><div class="clearfix">
     <span>Category</span>
     <input type="hidden" id="categoryId" name="categoryId"   />
     <input type="text" id="category" name="category" class="form-control col-xs-4 col-sm-12 input-sm"
      placeholder="Category" onkeyup="getCategory(this.value)" <?php if(isset($_GET['category'])) { ?> value="<?php echo $_GET['category']; ?>" <?php } ?>  />
           <ul class="typeahead dropdown-menu" style="left: 10px; display: none;" id="categoryList">
                                           </ul>
                                           
                                </div></div>
                                
     <div class="form-group col-sm-2">
   <span>Date</span>
    <input type="text" class="form-control date-picker input-sm" id="date" name="date"
     placeholder="Date" <?php if(isset($_GET['date'])) { ?> value="<?php echo $_GET['date']; ?>" <?php } ?> required />
  </div>         
  <div class="form-group col-sm-2">
   <span><br></span>
    <input type="submit" class="btn btn-sm btn-success" name="search">
  </div>  </form>                                                
                                
								
<div style="clear:both;"></div>
							
 <?php
	   if(isset($_GET['delete']))
{ $alertMsg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Service has been deleted!</div>'; }


  if(isset($alertMsg)) {  echo $alertMsg; 
  }
										   ?> 
                                            


								<div class="row">
									<div class="col-xs-12">
										
										<div class="table-header">
											services
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
                                                        <th>Date</th>		
                                                        <th>Customer</th>				
                                                        <th>Sr. no</th>	
                                                        <th>Category</th>			
														<th>Product</th>
                                                      	<th>Model No</th>
                                                        <th>Equipment No</th>
                                                        <th>Quantity</th>
                                                        <th>Engineer</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
													</tr>
												</thead>

												<tbody>
													
														
																	

  <?php 
   $list[] = array('S. No', 'Date', 'Customer', 'Sr. no', 'Category', 'Product', 'Model No', 'Equipment No', 'Quantity', 'Engineer', 'Status','Remarks' );
  if(mysql_num_rows($services)>0) {
	
	  $i = $start+1; 
    
	  
  while($service = mysql_fetch_array($services))
  {
	
	$status = mysql_query("select status, remarks from service_status where serviceId = '". $service['serviceId'] ."' order by statusId desc limit 1");
    $status  = mysql_fetch_array($status);

	  
	 ?><tr>
														<td class="center">
															<?php echo $i; ?>
														</td>
  <td>
															<?php $date = explode('-',$service['date']); echo $date[2].'-'.$date[1].'-'.$date[0]; ?>
														</td>
														<td>
															<?php echo ucfirst($service['customerName']); ?>
														</td>
                                                        <td>
															<?php echo $service['serialNumber']; ?>
														</td>
                                                        
                                                        <td>
															<?php echo $service['category']; ?>
														</td>
                                                        <td>
															<?php echo $service['product']; ?>
														</td>
                                                        
                                                        
                                                      
                                                        <td>
															<?php echo $service['modelNo']; ?>
														</td>
                                                        <td>
															<?php echo $service['equipmentNo']; ?>
														</td>
                                                        <td>
															<?php echo $service['quantity']; ?>
														</td>
                                                         <td>
															<?php echo ucfirst($service['engineer']); ?>
														</td>
                                                        <td class="hidden-480">
                                                        
                <?php if(strcmp($status['status'],'open')==0) { $serviceStatus = 'open' ;  ?>
              <span class="label label-sm label-success"><?php echo $serviceStatus;   ?></span>
                <?php  } else if(strcmp($status['status'],'closed')==0) {  $serviceStatus = 'closed' ;  ?>
            <span class="label label-sm label-warning"><?php echo $serviceStatus;   ?></span>
                <?php  }  else if(strcmp($status['status'],'pending')==0) {  $serviceStatus = 'pending' ;  ?>
         <span class="label label-sm label-danger"><?php echo $serviceStatus;   ?></span>
                                                           <?php  } ?>
															
														</td>
                                                       <td>
                                                       
                                                       
                                                        
                                           
                                           <a class="btn btn-primary btn-sm" href="status_station_service.php?sid=<?php echo $service['serviceId']; ?>" title="Update Status">
												<i class="ace-icon fa fa-edit icon-only"></i>
											</a>
                                            
                                            <a class="btn btn-success btn-sm" href="view_station_service.php?sid=<?php echo $service['serviceId']; ?>" title="View Service">
												<i class="ace-icon fa fa-eye icon-only"></i>
											</a>
                                             
                                            <a class="btn btn-primary btn-sm" href="edit_station_service.php?sid=<?php echo $service['serviceId']; ?>" title="Edit Service">
												<i class="ace-icon fa fa-edit icon-only"></i>
											</a>
                                            
                                            <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?php echo $service['serviceId']; ?>')" title="Delete">
												<i class="ace-icon fa fa-trash icon-only"></i>
											</button>
                                                      
                                                      
                                                    
                                                  
                                                          
														</td>

													
													</tr>
<?php
$rowlist[] = $i;
$rowlist[] = $date[2].'-'.$date[1].'-'.$date[0];
$rowlist[] = ucfirst($service['customerName']);
$rowlist[] = $service['serialNumber'];
$rowlist[] = $service['category'];
$rowlist[] = $service['product']; 
$rowlist[] = $service['modelNo'];
$rowlist[] = $service['equipmentNo'];
$rowlist[] = $service['quantity']; 
$rowlist[] = ucfirst($service['engineer']);

$rowlist[] = $serviceStatus;
$rowlist[] = $status['remarks']; 

 $list[] = $rowlist;
 unset($rowlist);



$i++;   } } else { ?> <tr><td colspan="12">No Data found.</td></tr> <?php } 


if($numRecords>$limit) {
   
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
  echo $start;
   
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
$fp = fopen('excel/services.csv', 'w');

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
          <script src="assets/js/date-time/bootstrap-datepicker.js"></script>
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
		
		
		function getCategory(val)
		{
				document.getElementById("categoryList").style.display = 'block';
				$.ajax({url: "ajax/getCategoryList.php?val="+val, success: function(result){
		$("#categoryList").html(result);
    }});	
			
		}
		
		
		function selectCategory(id,category)
		{
			document.getElementById("categoryList").style.display = 'none';
			document.getElementById("categoryId").value = id;
			document.getElementById("category").value = category;
	
		}
		
		function confirmDelete(did)
		{
			if(confirm("Do you want to delete the service."))
			{
				window.location = 'station_services.php?did='+did;
				
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

		
	</body>
</html>

<script>

   function goToPage(pid)
{
   window.location = 'services_station.php?page='+pid;	
}


		jQuery(function($) {
			$('.date-picker').datepicker({
					autoclose: true,
					format: 'dd-mm-yyyy'
				})
			
			});
		
		
</script>
