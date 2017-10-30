<?php include("includes/header.php"); 


// delete stock
 if(isset($_GET['did']))
 {
  
 
   mysql_query("delete from product_price where autoId = '". $_GET['did'] ."'");	
   
   header("location: manage_price.php?pid=".$_GET['pid']."&delete=success");
	   
 }
 

// adding price into product_price table. 
		if(isset($_POST['addPrice']))
		{
			extract($_POST);

		$appliesFrom = explode('/',$appliesFrom);
		$appliesFrom = $appliesFrom[2].'-'.$appliesFrom[0].'-'.$appliesFrom[1];
		
//mysql_query("insert into product_price (`productId`, `price`, `fromDate`, `dateTime`) values ('". $_GET['pid'] ."', '$price', '$appliesFrom', NOW())");
	
	mysql_query("update product_price set `price`='".$price."', dateTime=NOW() where productId=".$_GET['pid']." and ModelNo='".$model_no."'");
//	echo "update product_price set `price`='".$price."', NOW() where productId=".$_GET['pid']." and ModelNo='".$model_no."'"; exit; 

header("location: manage_price.php?pid=".$_GET['pid']."&add=success");
/*
$lastId = mysql_insert_id();

if($lastId>0)
{
   header("location: manage_price.php?pid=".$_GET['pid']."&add=success");
   
   
} else { header("location: manage_price.php?pid=".$_GET['pid']."&error=1"); }

*/		

			
		}

$categories = mysql_query("select id, category from categories order by category");

$brands = mysql_query("select id, brand from brands order by brand");

$product = mysql_query("select product, pm.ModelNo as code, pm.ModelId, category, brand from products left join product_model as pm on pm.ProductId=products.productId where products.productId = '". $_GET['pid'] ."'");


$prd = mysql_fetch_object($product);


if(mysql_num_rows($product)<1)
{
  header("location: products.php");	 exit;
}
    $product = mysql_fetch_array($product);

 
 $prices = mysql_query("select autoId, price, fromDate from product_price where productId = '". $_GET['pid'] ."' and ModelNO=".$prd->ModelId); 

?>
		
        	<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="products.php">Products</a>
							</li>

							<li class="active">Manage Price</li>
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
					
<?PHP if( isset($_GET['add']) && $_GET['add'] =='success'){?>
<div class="alert alert-success"> Product price has been successfully updated    </div>
<?PHP }?>
        
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
                                <div class="row">
									<div class="col-xs-6">
                                    
                                    <div class="table-header">
											Price Data
										</div>
                                        
                                   
                                   
                                   <table id="sample-table-1" class="table table-striped table-bordered table-hover dataTable">
												<thead>
													<tr>
														<th class="center">
															
														</th>
														<th>Price</th>
                                                      	<th>Applicable From</th>													
                                                        <th>Action</th>
													</tr>
												</thead>

												<tbody>
													
														
			<?php 
			if(mysql_num_rows($prices)>0) { 
			$i=1;
			
			while($price = mysql_fetch_array($prices)) 
			{ 
			$prd_price = $price['price'];
			 ?>										                                
                                            
                                        
<tr>
														<td class="center">
															<?php echo $i; ?>
														</td>

														<td><?php echo $price['price'] ?></td>
                                                        <td><?php $date = explode('-',$price['fromDate']);    
														          echo $date[2].'-'.$date[1].'-'.$date[0];      
														       ?></td>
															
                                                       <td>
                                                        
                                                   
                                            
                                            <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?php echo $_GET['pid'] ?>','<?php echo $price['autoId'] ?>')" title="Delete Record">
												<i class="ace-icon fa fa-trash icon-only"></i>
											</button>
                                                      
                                                  
                                                          
														</td>

													
													</tr>
                                                    
                                                    <?php $i++; } } else { ?> <tr><td colspan="4">No Data found.</td></tr> <?php } ?>

                                            
                                            
												
                                                      
                                               </tbody>
											</table>

												
                                            </div>
                                            <div class="col-xs-6">
										
										<div class="table-header">
											Add Price
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div>
											
                                           <?php
	   if(isset($_GET['add']))
{ $alertMsg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Price has been added!</div>'; }
else if(isset($_GET['error']))
{ $alertMsg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  if(isset($msg)) {  echo $alertMsg; 
  }
										   ?> 
                                            
                                           
                                            
                               
                                            
                                           
                                
                                            <form class="form-horizontal" role="form" action="" method="post" id="validation-form">
									<!-- #section:elements.form -->
									 <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="category"> Category </label>

										<div class="col-sm-9">
											<select id="category" name="category" class="col-xs-10 col-sm-12" disabled>
                                            <option value="0">Select Category</option>
                                            <?php while($category = mysql_fetch_array($categories))
											{ ?>
                                            
                                            <option <?php if($category['id']==$product['category']) { ?> selected <?php } ?> value="<?php echo $category['id']; ?>"><?php echo $category['category']; ?></option>
                                            <?php } ?>
                                            </select>
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="brand"> Brands </label>

										<div class="col-sm-9">
											<select id="brand" name="brand" class="col-xs-10 col-sm-12" disabled>
                                            <option value="0">Select Category</option>
                                            <?php while($brand = mysql_fetch_array($brands))
											{ ?>
                                            
                                            <option <?php if($brand['id']==$product['brand']) { ?> selected <?php } ?> value="<?php echo $brand['id']; ?>"><?php echo $brand['brand']; ?></option>
                                            <?php } ?>
                                            </select>
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="product"> Product </label>

										<div class="col-sm-9">
		<input type="text" id="product" name="product" placeholder="Product" class="col-xs-10 col-sm-12" value="<?php echo $product['product']; ?>"  disabled  />
										</div>
									</div>
                                    
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="code"> Code </label>

										<div class="col-sm-9">
		<input type="text" id="code" name="code" placeholder="Code" class="col-xs-10 col-sm-12" value="<?php echo $product['code']; ?>"  disabled  />
										</div>
									</div>
                                    
                                    
                                    
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="price"> Price </label>

										<div class="col-sm-9">
											<input type="text" id="price" name="price" placeholder="Price" class="col-xs-10 col-sm-12" value="<?PHP  echo $prd_price;   ?>"  />
                                            <input type="hidden" name="model_no" value="<?PHP echo $prd->ModelId; ?>" />
										</div>
									</div>
                                    
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="appliesFrom">Applies from. </label>

										<div class="col-sm-9">
											<input type="text" id="appliesFrom" name="appliesFrom" class="col-xs-10 col-sm-12 date-picker"  />
										</div>
									</div>
                                 
                                    
                                     <div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="submit" name="addPrice">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>

											&nbsp; &nbsp; &nbsp;
											<button class="btn" type="reset">
												<i class="ace-icon fa fa-undo bigger-110"></i>
												Reset
											</button>

										</div>
									</div>

                                    
                                    </form>
                                    
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
        
         <script src="assets/js/jquery.validate.js"></script>
        

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
        
        
        
        <script src="assets/js/date-time/bootstrap-datepicker.js"></script>
		<script src="assets/js/date-time/bootstrap-timepicker.js"></script>
		<script src="assets/js/date-time/moment.js"></script>
		<script src="assets/js/date-time/daterangepicker.js"></script>
		<script src="assets/js/date-time/bootstrap-datetimepicker.js"></script>
        
        
         
        <script>
		
		
		function confirmDelete(pid,did)
		{
			if(confirm("Do you want to delete the record."))
			{
				window.location = 'manage_price.php?pid='+pid+'&did='+did;
				
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
			
			
			
			// datepicker
			$('.date-picker').datepicker({
					autoclose: true,
					todayHighlight: true
				})
				
				
				
				 // validateion
				
				$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					ignore: "",
					rules: {
						price: {
							required: true,
							numbers: true,
							
						}
						
						
					},
					messages: {
						price: {
							required: "Please provide a valid email.",
							numbers: "Enter only numeric value."
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
