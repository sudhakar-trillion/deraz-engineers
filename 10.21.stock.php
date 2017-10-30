<?php include("includes/header.php"); 


// delete 
if(isset($_GET['did']))
{
  if(mysql_query("delete from customers where customerId = '". $_GET['did'] ."' and addedBy = '". $_SESSION['id'] ."'"))
  {
	header("location: customers.php?delete=success");  
  }
  else
  {
	header("location: customers.php?error=fail");    
  }
}

// search 
if(isset($_GET['stockSearch']))
									{
										
										$where = 'where ';
										
									if(isset($_GET['fromDate']) && strlen($_GET['fromDate']>8) && isset($_GET['toDate']) && strlen($_GET['toDate']>8))
									{
										
									
										$fromDate = explode('/',$_GET['fromDate']);
										$fromDate = $fromDate[2].'-'.$fromDate[0].'-'.$fromDate[1];
										
										$toDate = explode('/',$_GET['toDate']);
										$toDate = $toDate[2].'-'.$toDate[0].'-'.$toDate[1];
									 
									 
									 $whereItems[] = "stock.date >  '". $fromDate ."'";
									 
									 $whereItems[] = "stock.date <  '". $toDate ."'";
									}
									
									
									   // by products
									   if($_GET['category']>0 || $_GET['brand']>0 || $_GET['product']>0 || $_GET['model']>0)
									   {  
									   if($_GET['category']>0)
									   {  
										    $whereSubItems[] = "products.category =  '". $_GET['category'] ."'";
									   }
									   
									   if($_GET['brand']>0)
									   {  
										    $whereSubItems[] = "products.brand =  '". $_GET['brand'] ."'";
									   }
									   
									   if($_GET['product']>0)
									   { 
										    $whereSubItems[] = "products.product =  '". $_GET['product'] ."'";   
									   }
									   
									   if($_GET['model']>0)
									   {  
										    $whereSubItems[] = "products.model =  '". $_GET['code'] ."'";
									   }
									
									//echo count($whereSubItems);  echo '<br />';
										if(count($whereSubItems)>1)
										{
										//	echo 'hii';
											$whereSubCondition = implode(' and ',$whereSubItems);
										}
										else
										{ //echo 'hello';
										   $whereSubCondition = $whereSubItems[0];
										}
										   
										   //echo '<br />';
										   //echo "select productId from products where $whereSubCondition"; echo '<br />';
										   $productSearch = mysql_query("select productId from products where $whereSubCondition");
									       
										   while($product = mysql_fetch_array($productSearch))
										   {
											  $setProducts[] = $product['productId'];   
										   }
										   
										   
										   if(count($setProducts)>1)
										   {
											 $selectedProducts = implode(', ',$setProducts);
											 $selectedProducts = '( '.$selectedProducts.' )';
											
											$whereItems[] =  "stock_products.productId in ". $selectedProducts ."";   
											  
										   }
										   else
										   {
											$whereItems[] =  "stock_products.productId = '". $setProducts[0] ."'";   
										   }
										  
										   
									    
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
else								
{
  $where = '';	
}
									
							
// pagination
 $limit = 10;
 
 $numRecords = mysql_query("select stock.lr, stock.moc, stock.date, stock.addedBy, stock_products.quantity, products.product, products.code, categories.category, brands.brand, employees.firstName from stock 
                       left join stock_products on stock.stockId = stock_products.stockId
					   left join products on stock_products.productId = products.productId
					   left join brands on products.brand = brands.id
					   left join categories on products.category = categories.id
					   left join employees on stock.addedBy = employees.id
					   $where
					   order by stock.stockId desc");
 
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




									 $stocks = mysql_query("select stock.lr, stock.moc, stock.date, stock.addedBy, stock_products.quantity, products.product, products.code, categories.category, brands.brand, employees.firstName from stock 
                       left join stock_products on stock.stockId = stock_products.stockId
					   left join products on stock_products.productId = products.productId
					   left join brands on products.brand = brands.id
					   left join categories on products.category = categories.id
					   left join employees on stock.addedBy = employees.id
					   $where
					   order by stock.stockId desc limit $start, $limit"); 


$categories = mysql_query("select id, category from categories order by category");

?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Stocks </li>
						</ul><!-- /.breadcrumb -->

						<!-- #section:basics/content.searchbox -->
						<div class="nav-search" id="nav-search">
							<form class="form-search">
								<span class="input-icon">
									<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
									<i class="ace-icon fa fa-search nav-search-icon"></i>
								</span>
							</form>
						</div><!-- /.nav-search -->

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
{ echo '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Customer has been deleted!</div>'; }
else if(isset($_GET['error']))
{ echo '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  
										   ?> 


								<div class="row">
									<div class="col-xs-12">
                                    
                                 
                                    
                                    <form class="form-inline" method="get" action="">
                                  
                      
  <div class="row">              
  <div class="form-group col-md-offset-2 col-md-1"> <span><br /></span>
  By Date
  </div>                                  
  <div class="form-group col-sm-2">
   <span>From Date</span>
    <input type="text" class="form-control date-picker input-sm" id="fromDate" name="fromDate" placeholder="From Date" <?php if(isset($_GET['fromDate'])) { ?> value="<?php echo $_GET['fromDate']; ?>" <?php } ?> />
  </div>
  <div class="form-group col-sm-2">
   <span>To Date</span>
   <input type="text" class="form-control date-picker input-sm" id="toDate" name="toDate" placeholder="To Date" <?php if(isset($_GET['toDate'])) { ?> value="<?php echo $_GET['toDate']; ?>" <?php } ?> />
  </div>
  </div>
 
         
                      <div class="row">  
                      <div class="form-group col-md-offset-2 col-md-1">
                      <span><br /></span>
  By Product
  </div>                                    
                               
                                <div class="col-sm-2">
                                <span>Category</span>
                                <select id="category1" name="category" class="form-control col-xs-4 col-sm-12 input-sm" onchange="getBrands('1')">
                                            <option value="0">Select Category</option>
                                            <?php
											while($category = mysql_fetch_array($categories))
											{
	 ?>   <option value="<?php echo $category['id']; ?>"  <?php if(isset($_GET['category']) && $_GET['category']==$category['id']) { ?> selected="selected"  <?php } ?>><?php echo $category['category']; ?></option> <?php	
											}
											?>
                                </select>
                                </div> 
                                 <div class="col-sm-2">
                                <span>Brand</span>
                                <select id="brand1" name="brand" class="col-xs-10 col-sm-12" onchange="getProducts('1')">
                                            <option value="0">Select Brand</option>
                                </select>
                                </div> 
                                 <div class="col-sm-2">
                                <span>Product</span>
                                <select name="product" id="product1" class="col-xs-10 col-sm-12" onchange="getModels('1')">
                                            <option value="--">Select Product</option>
                                            </select>
                                </div>
                                
                                <div class="col-sm-2">
                                <span>Model No</span>
             <select name="model" id="model1" class="col-xs-10 col-sm-12">
                                            <option value="--">Select Model</option>
                                            </select>
                                </div> 
                                
                                </div>
                                
                                
                                <div class="row">              
  <div class="form-group col-md-offset-3  col-sm-2">
   <span><br /></span>
    <input type="submit" class="btn btn-sm btn-success"  name="stockSearch" />
  </div>
  
  </div>
                                
                              
                                 
                               
  
</form>

</div>
<div class="col-xs-12">
										
										<div class="table-header">
											Stocks
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
                                                         <th>Date</th>
														<th>LR No.</th>
                                                        <th>MOC No.</th>
                                                        <th>Category</th>
                                                        <th>Brand</th>
                                                      	<th>Product</th>													
                                                    	<th>Model No.</th>
                                                        <th>Quantity</th>
                                                       
                                                        <th>Added By</th>
                                                       
														
													</tr>
												</thead>

												<tbody>
													
														
																	

  <?php 
  if(mysql_num_rows($stocks)>0)
  {
	   $i = $start+1;
  while($stock = mysql_fetch_array($stocks))
  { 
			
			 ?>										                                
                                            
                                        
<tr>
														<td class="center">
															<?php echo $i; ?>
														</td>
                                                         <td><?php $date = explode('-',$stock['date']);    
														          echo $date[2].'-'.$date[1].'-'.$date[0];      
														       ?></td>
														<td><?php echo $stock['lr'] ?></td>
                                                        <td><?php echo $stock['moc'] ?></td>
                                                        <td><?php echo $stock['category'] ?></td>
                                                        <td><?php echo $stock['brand'] ?></td>
                                                        <td><?php echo $stock['product'] ?></td>
                                                        <td><?php echo $stock['code'] ?></td>
                                                       <td><?php echo $stock['quantity'] ?></td>
                                                         <td><?php echo $stock['firstName'] ?></td>
                                                  	</tr>
                                                    
                                                    <?php $i++; } } else
													{
													
													
													?><tr><td colspan="10">No Data found.</td></tr> <?php	
													}
  
  ?>                                            
  
  
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
  
  
  
  
  
  </td></tr>  <?php } ?> </tbody>
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
  
  function confirmDelete(did)
  {
	  if(confirm("Confirm Delete"))
	  {
	  window.location = 'customers.php?did='+did;
	  }
  }
  
    function goToPage(pid)
{
   window.location = 'stock.php?page='+pid;	
}
 

 
  function getBrands(rid)
 {
	 
	
	var category = document.getElementById("category"+rid).value;
	
	
	if(category==0)
	{  
		document.getElementById("category"+rid).focus();
	}
	else 
	{
	    $.ajax({url: "ajax/getBrandsList.php?cid="+category, success: function(result){
        $("#brand"+rid).html(result);
    }});
	}

	 
 }
 
 function getProducts(rid)
 {
	
	 
	var category = document.getElementById("category"+rid).value;
	var brand =  document.getElementById("brand"+rid).value;
	
	if(category==0)
	{  
		document.getElementById("category"+rid).focus();
	}
	else if(brand==0)
	{ 
		document.getElementById("brand"+rid).focus();
	}
	else if(category>0 && brand>0)
	{
	    $.ajax({url: "ajax/getProductsList.php?bid="+brand+"&cid="+category, success: function(result){
        $("#product"+rid).html(result);
	  //  $("#div1").html(result);
    }});
	}

	 
 }
 
  function getModels(rid)
 {
	 
	 	 
	var category = document.getElementById("category"+rid).value;
	var brand =  document.getElementById("brand"+rid).value;
	var product =  document.getElementById("product"+rid).value;
	
	if(category==0)
	{  
		document.getElementById("category"+rid).focus();
	}
	else if(brand==0)
	{ 
		document.getElementById("brand"+rid).focus();
	}
	else if(category>0 && brand>0)
	{
	    $.ajax({url: "ajax/getModelsList.php?bid="+brand+"&cid="+category+"&product="+product, success: function(result){
        $("#model"+rid).html(result);
	 
    }});
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
