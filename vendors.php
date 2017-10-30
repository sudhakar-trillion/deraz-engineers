<?php include("includes/header.php"); 
//error_reporting(0);

// delete the vendor
if(isset($_GET['did']))
{
	
  if(mysql_query("delete from vendors where vendorId = '". $_GET['did'] ."'"))
  {
	header("location: vendors.php?delete=success");  
  }
  else
  {
	header("location: vendors.php?error=fail");    
  }
}

// pagination
 $limit = 4;
 
// get the vendor data from vendors table and the relation between vendors and employees are vendors.addedBy = employees.id 
// $numRecords = mysql_query("select employees.firstName, vendors.vendorId, vendors.company, vendors.contactPerson, vendors.designation, vendors.category, vendors.brand, vendors.product, vendors.modelNo, vendors.email, vendors.designation, vendors.phone, vendors.address from vendors left join employees on vendors.addedBy = employees.id order by vendors.vendorId");

 $numRecords = mysql_query("select employees.firstName, vendors.vendorId, vendors.company, vendors.contactPerson, vendors.designation,vendors.brand, vendors.product,  vendors.designation, vendors.phone, vendors.address from vendors left join employees on vendors.addedBy = employees.id order by vendors.vendorId");
 
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
 

// from date and to date and vendorwise search options
  if(isset($_GET['stockSearch']))
									{
										
									/* from date to todate search based on "vendors.dateTime".
								vendor search based on vendors.vendorId
								*/		
										
										$where = 'where ';
										
									if(isset($_GET['fromDate']) && strlen($_GET['fromDate']>8) && isset($_GET['toDate']) && strlen($_GET['toDate']>8))
									{
										
									
										$fromDate = explode('/',$_GET['fromDate']);
										$fromDate = $fromDate[2].'-'.$fromDate[0].'-'.$fromDate[1];
										
										$toDate = explode('/',$_GET['toDate']);
										$toDate = $toDate[2].'-'.$toDate[0].'-'.$toDate[1];
									 
									 
									 $whereItems[] = "vendors.dateTime >  '". $fromDate ."'";
									 
									 $whereItems[] = "vendors.dateTime <  '". $toDate ."'";
									}
									
									
									// by vendor
									 if(isset($_GET['vid']) && $_GET['vid']>0)
									   {  
										  $whereItems[] = "vendors.vendorId =  '". $_GET['vid'] ."'";
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
 $vendors = mysql_query("select employees.firstName, vendors.vendorId, vendors.company, vendors.contactPerson, vendors.designation,vendors.brand, vendors.product,  vendors.designation, vendors.phone, vendors.address from vendors left join employees on vendors.addedBy = employees.id $where order by vendors.vendorId desc");
}
else
{
$vendors = mysql_query("select employees.firstName, vendors.vendorId, vendors.company, vendors.contactPerson, vendors.brand, vendors.product,  vendors.designation, vendors.phone, vendors.address from vendors left join employees on vendors.addedBy = employees.id order by vendors.vendorId desc limit $start, $limit");
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
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i>Vendors</li>
						</ul><!-- /.breadcrumb -->
   <a href="excel/vendors.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>


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
                                    
                                 
                                    
                                    <form class="form-inline" method="get" action="" autocomplete= "off">
                                  
                      
  <div class="row">              
                                
  
   <div class="form-group col-sm-2">
   <span>Vendor</span>
    <input type="hidden" id="vid" name="vid" value="<?PHP echo $_GET['vid']; ?>"  />
     <input type="text" id="vendor" name="vendor" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Vendor" onkeyup="getVendor(this.value)" <?php if(isset($_GET['vendor'])) { ?> value="<?php echo $_GET['vendor']; ?>" <?php } ?>  />
           <ul class="typeahead dropdown-menu" style="left: 10px; display: none;" id="vendorsList">
                                           </ul>
  </div>
  
  <div class="form-group col-sm-2">
   <span><br /></span>
    <input type="submit" class="btn btn-sm btn-success"  name="stockSearch" value="Search" />
  </div>
  
  

  </div>  <div class="space"></div>
 
         
  
</form>

</div>
									<div class="col-xs-12">
										
										<div class="table-header">
											Vendors
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div>
											<table class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														
                                                        <th>S.No</th>
														<th>Vendor Name</th>
                                                        <th>ContactPerson</th>
                                                    	<th>Designation</th>
                                                      <!--  <th>Email</th>-->
                                                        <th>Mobile</th>
                                                        <th>Address</th>
                                                       <!-- <th>Category</th>-->
                                                        <th>Brand</th>
                                                        <th>Product</th>
                                                     <!--   <th>Model No.</th>-->
                                                        <th>Action</th>
                                                        <!--<th class="hidden-480">Action</th>
														
													</tr>-->
												</thead>

												<tbody>

  <?php 
  //to get the data in excel sheet we write this code
  $i=1;
  
 if(mysql_num_rows($vendors)>0)
  {
  
  $i = $start+1; 
// $list[] = array('S. No', 'Company', 'ContactPerson', 'Designation', 'Email', 'Mobile', 'Address', 'Cstegory', 'Brand', 'Product', 'Model No.', 'Added By' );
 $list[] = array('S. No', 'Company', 'ContactPerson', 'Designation', 'Mobile', 'Address',  'Brand', 'Product', 'Added By' );
 
  while($vendor = mysql_fetch_array($vendors))
  {
	
	?><tr>
	
														<td>
															<?php echo $i; ?>
														</td>

														<td>
															<?php echo $vendor['company']; ?>
														</td>
                                                       
                                                        <td>
															<?php echo ucfirst($vendor['contactPerson']); ?>
														</td>
                                                        <td>
															<?php echo $vendor['designation']; ?>
														</td>
                                                       <!-- <td>
															<?php echo $vendor['email']; ?>
														</td>-->
                                                        <td>
															<?php echo $vendor['phone']; ?>
														</td>
                                                        
                                                         <td>
															<?php echo $vendor['address']; ?>
														</td>
                                                        <!-- <td>
															<?php #echo $vendor['category']; ?>
														</td>-->
                                                         <td>
															<?php echo $vendor['brand']; ?>
														</td>
                                                         <td>
															<?php echo $vendor['product']; ?>
														</td>
                                                         <!--<td>
															<?php echo $vendor['modelNo']; ?>
														</td>-->
                                                        
														<td>
                                                         <a class="btn btn-primary btn-sm" href="edit_vendor.php?eid=<?php echo $vendor['vendorId']; ?>">
												<i class="ace-icon fa fa-edit icon-only"></i>
											</a>
                                                            <a class="btn btn-warning btn-sm" href="vendors-view.php?vid=<?php echo $vendor['vendorId']; ?>"><i class="ace-icon fa fa-eye icon-only"></i></a>
                                            
                                            <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?php echo $vendor['vendorId']; ?>')">
												<i class="ace-icon fa fa-trash icon-only"></i>
											</button>
                                                        </td>
													</tr>
<?php
  //to get the data in excel sheet we write this code
 $rowlist[] = $i;
 $rowlist[] = $vendor['company'];
 $rowlist[] = ucfirst($vendor['contactPerson']); 
 $rowlist[] = $vendor['designation']; 
// $rowlist[] = $vendor['email'];
 $rowlist[] = $vendor['phone'];
 $rowlist[] = $vendor['address'];
 $rowlist[] = $vendor['firstName'];
$list[] = $rowlist;
 unset($rowlist);




 $i++; } } else { ?> <tr><td colspan="10">No Data found.</td></tr> <?php }
 
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
  
  if($numRecords>$limit && !isset($_GET['stockSearch']))
{ ?>   
   
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
             Page <input class="ui-pg-input" type="text" size="2" maxlength="7" value="<?php echo $currentPage; ?>" role="textbox"> of <span id="sp_1_grid-pager"><?php echo $numPages; ?></span>
  </td>
  <td class="ui-pg-button ui-state-disabled" style="width: 4px; cursor: default;">
         <span class="ui-separator"></span>
  </td>
  <td id="next_grid-pager" class="ui-pg-button ui-corner-all <?php echo $thirdlink; ?>" style="cursor: default;" <?PHP if($currentPage < $numPages) { ?> onclick="goToPage('<?php echo $currentPage+1; ?>')" <?PHP } ?> >
                <span class="ui-icon ace-icon fa fa-angle-right bigger-140">></span>
  </td>
  <td id="last_grid-pager" class="ui-pg-button ui-corner-all <?php echo $fourthlink; ?>" onclick="goToPage('<?php echo $numPages; ?>')">
                <span class="ui-icon ace-icon fa fa-angle-double-right bigger-140">></span>
  </td>
  </tr></tbody></table>
  
  
  </td></tr>
 <?PHP } ?>
  
                                    </tbody>
											</table>
                                            
                                            
                                            <?php                              
$fp = fopen('excel/vendors.csv', 'w');

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
 <script>
        function goToPage(pid)
{
   window.location = 'vendors.php?page='+pid;	
}
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
  
  function getVendor(val)
		{
			
			document.getElementById("vendorsList").style.display = 'block';
				$.ajax({url: "ajax/getVendorsList.php?val="+val, success: function(result){
		$("#vendorsList").html(result);
    }});	
			
		}
		
		
		function selectVendor(vid,company)
		{
			document.getElementById("vendorsList").style.display = 'none';
			document.getElementById("vid").value = vid;
			document.getElementById("vendor").value = company;
	
		}
  
  function confirmDelete(did)
  {

	  if(confirm("Confirm Delete"))
	  { 
	  	window.location = 'vendors.php?did='+did;
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
