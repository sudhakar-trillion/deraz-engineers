<?php include("includes/header.php"); 
/* in this we assign the brand to category. for this we use the category_brands table. */

  if(isset($_POST['submit'])) 
  {
	$count = count($_POST['duallistbox_demo1']);
	
	for($i=0;$i<$count;$i++)
	{
		// assigning the brand to category in category_brands.
	   mysql_query("insert into category_brands (`categoryId`, `brandId`) values ('". $_GET['cid'] ."', '". $_POST['duallistbox_demo1'][$i] ."')");  	
		
	}
	
	 header("location: manage_category.php?cid=".$_GET['cid']."&assigned=1");
  }

  // add
  if(isset($_POST['addCategory']))
  {
	 mysql_query("insert into categories (`category`, `status`, `addedBy`, `dateTime`) values ('". $_POST['category'] ."', '1', '". $_SESSION['id'] ."', NOW())");  
	 $lastId = mysql_insert_id();
	 
	 if($lastId>0)
	 {	 
	    header("location: categories.php?add=success");
	 }
	 else
	 {
		 header("location: categories.php?error=1");
	 }
  }
  
  // delete  the brand which is assigned. 
  if(isset($_GET['did']))
  {
	 mysql_query("delete from category_brands where autoId = '". $_GET['did'] ."'");  
	 header("location: manage_category.php?cid=".$_GET['cid']."&delete=success");
  }


$brands = mysql_query("select id, brand from brands");


$categories = mysql_query("select * from categories where id = '". $_GET['cid'] ."'");

if(mysql_num_rows($categories)<1)
{
  header("location: categories.php");	
}

$category = mysql_fetch_array($categories);


$categoryBrands =  mysql_query("select category_brands.autoId, brands.id, brands.brand, brands.status from category_brands 
left join brands on category_brands.brandId = brands.id
 where category_brands.categoryId = '". $_GET['cid'] ."'");
 

?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Manage Category </li>
						</ul><!-- /.breadcrumb -->

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
								

						



								<div class="row">
									<div class="col-xs-6">
						
                        
                        <?php
				if(isset($_GET['delete']))
				{
		$alert = '<div class="alert alert-success">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											
											Brand has been removeed.
										</div>'; 
				}
				else if(isset($_GET['assigned']))
				{
		$alert = '<div class="alert alert-success">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											
											Brand has been assigned to the category.
										</div>'; 
				}
				else if(isset($_GET['error']))
				{
		$alert = '<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											
											Error occured try again.
										</div>'; 
				}
					
					if(isset($alert)) { echo $alert; }	
						?>
                        				
										<div class="table-header">
											<?php echo $category['category']; ?>
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
														<th>Brand</th>
                                                      	<th>Status</th>													
                                                    	<th>Action</th>
														
													</tr>
												</thead>

												<tbody>
													
														
																	

  <?php $i=1;
  if(mysql_num_rows($categoryBrands)>0) {
  while($row = mysql_fetch_array($categoryBrands))
  {
	  
	 ?><tr>
														<td class="center">
															<?php echo $i; ?>
														</td>

														<td><?php echo $row['brand']; ?>
															
														</td>
                                                       
                                                        
														<td>
                                                        
                                                           <?php if($row['status']==1) { ?>
                                                           <span class="label label-sm label-success">Active</span>
                                                           <?php  } else if($row['status']==0) { ?>
                                                           <span class="label label-sm label-warning">Deactive</span>
                                                           <?php  } ?>
															
														</td>
                                                         <td>
															
                                                             
                                            
                                            <button class="btn btn-danger btn-sm" title="delete" onclick="confirmDelete('<?php echo $_GET['cid'] ?>','<?php echo $row['autoId'] ?>')">
												<i class="ace-icon fa fa-trash icon-only"></i>
											</button>
                                            
														</td>

													
													</tr>
<?php  $selected[] = $row['id'];
 $i++; } } else {
  
  ?>             
  <tr> <td colspan="4">No Data found.</td> </tr>
  <?php } ?>
                                    </tbody>
											</table>
                                            
                                            
                                             
                                
                                
                                
                                
										</div>
									</div>
                                    
                                    <div class="col-xs-6">
                                    
                                    <div class="table-header">
										Assign Brands to	<?php echo $category['category']; ?>
										</div>
                                   <form action="" method="post">
                               <div class="form-group">
										

										<div class="col-sm-12">
											<!-- #section:plugins/input.duallist -->
											<div class="bootstrap-duallistbox-container row moveonselect"> 
                                            <select multiple="multiple" size="10" name="duallistbox_demo1[]" id="duallist" style="display: none;">
                                            <?php
											while($brand = mysql_fetch_array($brands))
											{ 
											if(!(in_array($brand['id'],$selected))) {
											 ?>
												<option value="<?php echo $brand['id']; ?>"><?php echo $brand['brand']; ?></option>
                                                <?php } } ?>
                                               
											</select>

											
										</div>
                                        
                                        </div>
                                        
                              
                                      
                                    

                                          <div class="col-md-offset-6 col-sm-6">
                                            
											<button class="btn btn-info" type="submit" name="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>
                                            </div>
                                            
                                            
                                            
                                            

									      </div>
                                          
                                          </form>
                                          
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
        
              <script src="assets/js/jquery.validate.js"></script>
              
              <link rel="stylesheet" href="assets/css/bootstrap-duallistbox.css" />
		<link rel="stylesheet" href="assets/css/bootstrap-multiselect.css" />
		<link rel="stylesheet" href="assets/css/select2.css" />
        <script src="assets/js/jquery.bootstrap-duallistbox.js"></script>
		<script src="assets/js/jquery.raty.js"></script>
		<script src="assets/js/bootstrap-multiselect.js"></script>
		<script src="assets/js/select2.js"></script>
		<script src="assets/js/typeahead.jquery.js"></script>


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
  
  function confirmDelete(cid,did)
  {
	  if(confirm("Confirm Delete"))
	  {
	    window.location = 'manage_category.php?cid='+cid+'&did='+did;
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
			
			
			// validateion
				
				$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					ignore: "",
					rules: {
						category: {
							required: true,
							
						}
					},
			    	highlight: function (e) {
						$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
					},
			
					success: function (e) {   
						$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
						$(e).remove();
					}
			
				
				});
				// validation
				
				 var demo1 = $('select[name="duallistbox_demo1[]"]').bootstrapDualListbox({infoTextFiltered: '<span class="label label-purple label-lg">Filtered</span>'});
				var container1 = demo1.bootstrapDualListbox('getContainer');
				container1.find('.btn').addClass('btn-white btn-info btn-bold');

				
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
