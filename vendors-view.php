<?php include("includes/header.php"); 

// get the vendors data from vendor table
$vendors = mysql_query("select employees.firstName, vendors.vendorId, vendors.company, vendors.contactPerson, vendors.category, vendors.brand, vendors.product, vendors.modelNo, vendors.designation, vendors.email, vendors.designation, vendors.phone, vendors.address from vendors left join employees on vendors.addedBy = employees.id  where vendors.vendorId='".$_GET['vid']."' order by vendors.vendorId desc ");

//echo mysql_num_rows($vendors); exit;


?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i>Vendors-View</li>
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



								<div class="row">
                                
                                <div class="col-xs-12">
                                    
                                    
                                                                        
                                    <form class="form-inline" method="get" action="">
                                  
                             
  
</form>

</div>
									<div class="col-xs-12">
										
										<div class="table-header">
											Vendors-View
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div>
											<table  class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														
                                                        <th>S.No</th>
														<th>Vendor Name</th>
                                                        <th>ContactPerson</th>
                                                    	<th>Designation</th>
                                                        <th>Email</th>
                                                        <th>Mobile</th>
                                                        <th>Address</th>
                                                         <th>Category</th>
                                                        <th>Brand</th>
                                                        <th>Product</th>
                                                        <th>Model No.</th>
                                                       
												</thead>

												<tbody>

  <?php 
  $i=1;
  
 if(mysql_num_rows($vendors)>0)
  {
  
  $i = $start+1; 
 $list[] = array('S. No', 'Company', 'ContactPerson', 'Designation', 'Email', 'Mobile', 'Address', 'Cstegory', 'Brand', 'Product', 'Model No.', 'Added By' );
 
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
                                                        <td>
															<?php echo $vendor['email']; ?>
														</td>
                                                        <td>
															<?php echo $vendor['phone']; ?>
														</td>
                                                        
                                                         <td>
															<?php echo $vendor['address']; ?>
														</td>
                                                         <td>
															<?php echo $vendor['category']; ?>
														</td>
                                                         <td>
															<?php echo $vendor['brand']; ?>
														</td>
                                                         <td>
															<?php echo $vendor['product']; ?>
														</td>
                                                         <td>
															<?php echo $vendor['modelNo']; ?>
														</td>
                                                       
													</tr>
<?php
 $rowlist[] = $i;
 $rowlist[] = $vendor['company'];
 $rowlist[] = ucfirst($vendor['contactPerson']); 
 $rowlist[] = $vendor['designation']; 
 $rowlist[] = $vendor['email'];
 $rowlist[] = $vendor['phone'];
 $rowlist[] = $vendor['address'];
 $rowlist[] = $vendor['firstName'];
$list[] = $rowlist;
 unset($rowlist);




 $i++; } }

                                                    ?>
  
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
