<?php include("includes/header.php"); 


// delete 
if(isset($_GET['did']))
{
  if(mysql_query("delete from customers where customerId = '". $_GET['did'] ."' and addedBy = '". $_SESSION['id'] ."'"))
  {
	header("location: sa_customers.php?delete=success");  
  }
  else
  {
	header("location: sa_customers.php?error=fail");    
  }
}


// pagination
 $limit = 10; 
 
 $numRecords = mysql_query("select enquiries.enquiryId, enquiries.dateTime, enquiries.enquiryNumber, categories.category, enquiries.enquiryId, enquiries.name,  enquiries.company, enquiries.phone, enquiries.email, enquiries.source, employees.firstName from enquiries 
left join employees on enquiries.addedBy = employees.id 
left join categories on enquiries.category = categories.id order by enquiries.enquiryId");
 
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

$enquiries = mysql_query("select enquiries.enquiryId, enquiries.dateTime, enquiries.enquiryNumber, categories.category, enquiries.enquiryId, enquiries.name,  enquiries.company, enquiries.phone, enquiries.email, enquiries.source, employees.firstName from enquiries 
left join employees on enquiries.addedBy = employees.id
left join categories on enquiries.category = categories.id order by enquiries.enquiryId
 desc limit $start, $limit ");

  
  
  

?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Enquiries </li>
						</ul><!-- /.breadcrumb -->
             <a href="excel/enquiries.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>


						<!-- #section:basics/content.searchbox -->
						<!-- /.nav-search -->

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
								

							
 <?php
	   if(isset($_GET['delete']))
{ echo $msg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Customer has been deleted!</div>'; }
else if(isset($_GET['error']))
{ echo $msg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }


										   ?> 


								<div class="row">
									<div class="col-xs-12">
										
										<div class="table-header">
											Enquiries
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
																				<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">

											<table id="sample-table-1" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th class="center">
															S.No
														</th>
                                                        <th>Date</th>
                                                        <th>Enquiry Number</th>
                                                        <th>Category</th>
                                                         <th>Products</th>
														<th>Name</th>
                                                        <th>Company</th>
                                                      	<th>Email</th>													
                                                    	<th>Phone</th>
                                                        <th>Source</th>
                                                       
                                                        <th>Added By</th>
                                                        <th>Action</th>
													</tr>
												</thead>

												<tbody>
													
														
																	

  <?php 
  
   		 $list[] = array('S. No', 'Date', 'EnquiryNumber', 'category', 'Products', 'Name', 'Company', 'Email', 'Phone', 'Source',  'Added By');
 
  if(mysql_num_rows($enquiries)>0)
  {
    
  $i=$start+1;
  

  while($enquiry = mysql_fetch_array($enquiries))
  {
	  
	
	
	$products= mysql_query("select * from enquiry_products  
	left join products on enquiry_products.productId = products.productId where enquiry_products.enquiryId = '". $enquiry['enquiryId'] ."'")
	
	  

	  
	 ?><tr>
														<td class="center">
                                                      	<?php echo $i; ?>

														</td>
                                                        
                                                        <td>
															<?php $date = explode(' ',$enquiry['dateTime']);
															      $date = explode('-',$date[0]);
																    $date = $date[2].'-'.$date[1].'-'.$date[0];
																      echo $date;
																	   ?>
														</td>
                                                        <td>
															<?php echo $enquiry['enquiryNumber']; ?>
														</td>
                                                        <td>
															<?php echo $enquiry['category']; ?>
														</td>
                                                        <td>
                                                        
                                      <div id="<?php echo 'modal-form'.$i; ?>" class="modal fade in" tabindex="-1" aria-hidden="false" style="display: none;"><div class="modal-backdrop fade in"></div>
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header no-padding">
												<div class="table-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
														<span class="white">×</span>
													</button>
													<?php echo $enquiry['name']; ?>
												</div>
											</div>

											<div class="modal-body no-padding">
                                                                                       
                                            <div id="financialTarget38">
                                            <table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
													<thead>
														<tr>
															<th>S.no</th>
															<th>Product</th>
														</tr>
													</thead>

													<tbody>
														

                                                                        
												<?php 
															
															$numProducts = mysql_num_rows($products);
															
															if($numProducts >0)
															{
									                      	$sno=1;
															 while($product = mysql_fetch_array($products))
															{
															
												$productsList[] = $product['product'];			
															
													?><tr><td><?php echo $sno; ?></td><td><?php  echo $productName = $product['product']; ?></td></tr><?php 
														$sno++;	}
															 
															 
															} 
														
															
													?></tbody></table>
                                                
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
                                                          
														</div>
															
                                                    
            <?php   if($numProducts>1)
              {     ?>                               
               <a href="<?php echo '#modal-form'.$i; ?>" role="button" class="blue" data-toggle="modal"> <?php		echo $productName;  ?> </a>
                       <?php  } else
					   {		echo $productName;  	 } ?>
															
															
														</td>

														<td>
															<?php echo $enquiry['name']; ?>
														</td>
                                                        <td>
															<?php echo $enquiry['company'];  ?>
														</td>
                                                       
                                                       
                                                        <td>
															<?php echo $enquiry['email']; ?>
														</td>
                                                        
                                                         <td>
															<?php echo $enquiry['phone']; ?>
														</td>
                                                        <td>
															<?php echo $enquiry['source']; ?>
														</td>
                                                       
                                                       
                                                        
                                                       
                                                        <td>
															<?php echo $enquiry['firstName']; ?>
														</td>
                                                        
                                                         <td>
													
                                        
                                 <button type="button" data-toggle="modal" data-target="#myModal<?php echo $i; ?>" class="btn btn-primary btn-xs">
												<i class="ace-icon fa fa-asterisk  bigger-110 icon-only"></i>
											</button>
                                            
                                            <!-- Trigger the modal with a button -->
<!-- Modal -->
<div id="myModal<?php echo $i; ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Assign to executive <?php echo $enquiry['enquiryNumber']; ?></h4>
      </div>
      <div class="modal-body">
      
      
      <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
      
      <form>
        <div class="form-group">
           <label class="col-sm-3 control-label no-padding-right" for="category"> Category</label>
                <div class="col-sm-6">
                   <input type="hidden" id="employeeId<?php echo $i; ?>"  value="">
                   
<input type="text" id="employee<?php echo $i; ?>" placeholder="Employee" class="col-xs-10 col-sm-12 valid" onkeyup="getEmployeeList(this.value,'<?php echo $i; ?>')" aria-required="true" aria-invalid="false" aria-describedby="invoice-error">
<ul class="typeahead dropdown-menu" style="top: 28px; left: 0px; display: none;" id="employeeList<?php echo $i; ?>"></ul>
   			   </div>
        </div>
        <div class="form-group">
           <div class="col-sm-6 col-sm-offset-3">
                <button type="button" class="btn btn-sm btn-primary" onclick="assignExecutive('<?php echo $enquiry['enquiryId']; ?>','<?php echo $i; ?>')">Assign</button>
			</div>
       </div>
       </form>
       </div>
       </div>
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
                                            
                                            
                                            
                                            
														</td>
                                                        
                                                        
														</tr>
                                          
                                                    
<?php

$rowlist[] = $i;
$rowlist[] = $date;
$rowlist[] = $enquiry['EnquiryNumber'];
$rowlist[] = $enquiry['category'];
$rowlist[] = $enquiry['name'];
$rowlist[] = $enquiry['company'];
$rowlist[] = $enquiry['email'];
$rowlist[] = $enquiry['phone'];
$rowlist[] = $enquiry['source'];

if(count($productsList)>0)
{
$selectedProducts = implode(' , ',$productsList);
unset($productsList);
} else 
{ $selectedProducts = ''; }
$rowlist[] = $selectedProducts;
$rowlist[] = $enquiry['firstName'];


 $list[] = $rowlist;
 unset($rowlist);


 $i++ ; }
  
  }
   else 
  {
	  ?><tr><td colspan="11">No Data Found</td></tr><?php
  }  ?>   
                                    


                                    
    <?php 
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
  <tr><td colspan="11">
  
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
$fp = fopen('excel/enquiries.csv', 'w');

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
   window.location = 'enquiries.php?page='+pid;	
}
  
  function confirmDelete(did)
  {
	  if(confirm("Confirm Delete"))
	  {
	  window.location = 'sa_customers.php?did='+did;
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
<script>

function assignExecutive(enquiryId,row)
{
	
	
	var employeeId = document.getElementById("employeeId"+row).value;
	$.ajax({url: "ajax/assignEnquiry.php?enquiryId="+enquiryId+"&row="+row+"&employeeId="+employeeId, success: function(result){
		
		obj = JSON.parse(result);
		
		
		alert(obj.success);
		alert(obj.message);
		alert(obj.date);
		alert(obj.name);
		
        //$("#employeeList"+row).html(result);
    }});	
	
	
}

function getEmployeeList(val,row)
		{
			document.getElementById("employeeList"+row).style.display = 'block';
			$.ajax({url: "ajax/getSalesEmployeeList.php?val="+val+"&row="+row, success: function(result){
		
		
        $("#employeeList"+row).html(result);
		
		
		
    }});	
			
		}
		
		
		
		
		function selectEmployee(id,val,row)
		{
			
			
			document.getElementById("employeeList"+row).style.display = 'none';
			document.getElementById("employeeId"+row).value = id;
			document.getElementById("employee"+row).value = val;
			
	
		
		}
</script>
