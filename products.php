<?php include("includes/header.php"); 
error_reporting(0);


 // delete
 if(isset($_GET['did']))
 {
	
	mysql_query("delete from products where productId = '". $_GET['did'] ."'"); 
	 header("location: products.php?delete=1");	
 }

 $limit = 10;
 //  we get the product data from products,categories,brands table
/*relation between enquiries and enquiry_assign is enquiryId.
relation between employees and products are employees.id = products.addedBy
relation between catgories and products are products.category = categories.id.
relation between brands and products are products.brand = brands.id
relation between available_stock and products are products.productId = available_stock.productId
*/

$numRecords = mysql_query("select products.productId, products.productNumber, products.product, pm.ModelNo as code, products.minQuantity, products.status, categories.category, brands.brand, available_stock.stock from products 
                         left join employees on products.addedBy = employees.id
						 left join categories on products.category = categories.id
						 left join brands on products.brand = brands.id
						 left join product_model as pm on pm.ProductId=products.productId
						 left join available_stock on products.productId = available_stock.productId
						 group by products.productId
						 order by products.productId");
						 
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
 
 
 // search options  where daily_reports.po = '0'
   
									
										
			  if(isset($_GET['proposalSearch'])) {
				  
				  /* from date to todate search based on "daily_reports.dateTime".
					category search based on categories.id
					brand search based on brands.id.  
					product search based on products.productId
					code search based on products.productId
					*/	
							
				 						$where = 'where ';					
									
									// by category
									
									if(isset($_GET['category'])&& $_GET['category']!='')
									{
									   if(isset($_GET['cid']) && $_GET['cid']>0)
									   {  
									  
										    $whereItems[] = "categories.id =  '". $_GET['cid'] ."'";
									   }
									}
									else
										$_GET['category']='';
									   
									   // by brand
									   if(isset($_GET['brand']) && $_GET['brand']!='')
									   {
										   if(isset($_GET['bid']) && $_GET['bid']>0)
										   {  
										  
												$whereItems[] = "brands.id =  '". $_GET['bid'] ."'";
										   }
									   }
									   else
									   $_GET['brand']='';
									
									 
									    // by product
										if(isset($_GET['product']) && $_GET['product']!='')
										{
											if(isset($_GET['pid']) && $_GET['pid']>0  ) 
											{  
											
												$whereItems[] = "products.productId =  '". $_GET['pid'] ."'";
											}	
										}
										else
											$_GET['pid']='';
										
									   
									   
									   // by product
									   if(isset($_GET['code']) && $_GET['code']!='')
									   {
										   if(isset($_GET['cdid']) && $_GET['cdid']>0)
										   {  
										  
												//$whereItems[] = "products.productId =  '". $_GET['cdid'] ."'";
												$whereItems[] = "pm.ModelId=".$_GET['cdid'];
												
										   }
									   }
									   else
									   	$_GET['code']='';
									   
									 
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

 //  we get the product data from products,categories,brands table 
$products = mysql_query("select products.productId,products.StockId, products.Stock_Store, products.productNumber, products.product, pm.ModelNo as code, products.minQuantity, products.status, categories.category, brands.brand from products left join employees on products.addedBy = employees.id left join categories on products.category = categories.id left join brands on products.brand = brands.id left join product_model as pm on pm.ProductId=products.productId $where and products.StockId<1 group by products.productId order by products.productId desc limit $start, $limit");
						 
}
else 
{
	 //  we get the product data from products,categories,brands table 
 $products = mysql_query("select products.productId,products.StockId, products.Stock_Store, products.productNumber, products.product, pm.ModelNo as code, products.minQuantity, products.status, categories.category, brands.brand from products left join employees on products.addedBy = employees.id left join categories on products.category = categories.id left join brands on products.brand = brands.id left join product_model as pm on pm.ProductId=products.productId where products.StockId<1 group by products.productId order by products.productId desc limit $start, $limit");	
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
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Products   </li>
						</ul><!-- /.breadcrumb -->
                        
                        <a href="excel/products.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>

						<!-- #section:basics/content.searchbox -->
						<div class="nav-search" id="nav-search">
							
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
                                <div class="col-sm-2">
                                <span>Category</span>
    <input type="hidden" id="cid" name="cid"  value="<?PHP echo @$_GET['cid']; ?>" />
     <input type="text" id="category" name="category" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Category" <?php if(isset($_GET['category'])) { ?> value="<?php echo $_GET['category']; ?>" <?php } ?> onkeyup="getCategory(this.value)" />
     
      <ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="categoryList"></ul>
                    
                                </div> 
                                
                                
                                <div class="col-sm-2">
                                <span>Brand</span>
    <input type="hidden" id="bid" name="bid"  value="<?PHP echo @$_GET['bid']; ?>" />
     <input type="text" id="brand" name="brand" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Brand" <?php if(isset($_GET['brand'])) { ?> value="<?php echo $_GET['brand']; ?>" <?php } ?> onkeyup="getBrand(this.value)" />
     
      <ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="brandList"></ul>
                    
                                </div>
                                
                                <div class="col-sm-2">
                                <span>Product</span>
    <input type="hidden" id="pid" name="pid" value="<?PHP echo @$_GET['pid']; ?>"   />
     <input type="text" id="product" name="product" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Product" <?php if(isset($_GET['product'])) { ?> value="<?php echo $_GET['product']; ?>" <?php } ?> onkeyup="getProduct(this.value)" />
     
      <ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="productList"></ul>
                    
                                </div>
                                
    <div class="col-sm-2">
                                <span>Code</span>
                                
    <input type="hidden" id="cdid" name="cdid" value="<?PHP if( isset($_GET['code']) && $_GET['code']!='' ) { echo @$_GET['cdid']; }?>"   />
    
     <input type="text" id="code" name="code" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Code" <?php if(isset($_GET['code'])) { ?> value="<?php echo $_GET['code']; ?>" <?php } ?> onkeyup="getCode(this.value)" />
     
      <ul class="typeahead dropdown-menu" style="top: 48px; left: 0px; display: none; height:100px; width:300px; overflow:auto; margin:0px; padding:0px; border:0px;" id="codeList"></ul>
                    
                                </div>                            
                            
                                       
  <div class="form-group col-sm-2">
   <br/>
    <input type="submit" class="btn btn-sm btn-success" name="proposalSearch" value="Search" />
  </div>
  
  </div>
                                
                              
                                 
                               
  
</form>
<div class="space"></div>

<?PHP
	if(isset($_GET['prd_edit']) && $_GET['prd_edit']=="success")
	{
		echo "<div class='alert alert-success'> Product updated successfully </div>";	
	}
?>
</div>
								<!-- PAGE CONTENT BEGINS -->
								

							

  <?php
	   if(isset($_GET['delete']))
{ $alertMsg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Product has been deleted!</div>'; }
else if(isset($_GET['error']))
{ $alertMsg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  if(isset($alertMsg)) {  echo $alertMsg; 
  }
										   ?> 
                                            
                                           
                                            

								<div class="row">
									<div class="col-xs-12">
										
										<div class="table-header">
											Products
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
                                                         <th>Category</th>
                                                        <th>Brand</th>
														<th>Product</th>
                                                      	<th>Code</th>	
                                                        <th>Equipment Number</th>													
                                                    	<th>Price</th>
                                                       
                                                        <th>Available Stock</th>
                                                        <th>Min. Stock</th>
														<th>Status</th>
														<th>Action</th>
													</tr>
												</thead>

												<tbody>
													
														
																	

  <?php 
  // to download the all data in excel. 
  $i = 1;
  if(mysql_num_rows($products)>0 )
  {
	  $i = $start+1; 
 	 $list[] = array('S. No', 'Category', 'Brand', 'Product', 'Code', 'Price', 'Available Stock', 'Min. Stock', 'Status' );
  
  while($product = mysql_fetch_array($products))
  {
	

	$currentDate = date('Y-m-d');  



if(isset($_GET['cdid']) && $_GET['cdid']>0  ) 
{
	$ModelNo = " and ModelId='".$_GET['cdid']."'";
	$getsModels = mysql_query("select ModelId, ModelNo from product_model where ProductId='".$product['productId']."' $ModelNo");
}
else
$getsModels = mysql_query("select ModelId, ModelNo from product_model where ProductId='".$product['productId']."'");

//echo "select ModelId, ModelNo from product_model where ProductId='".$product['productId']."' $ModelNo";
#echo mysql_num_rows($getsModels); 
#exit; 

while($models = mysql_fetch_object($getsModels))
{
	
	
	$prices = mysql_query("select `price` from product_price where productId = '". $product['productId'] ."' and ModelNo='".$models->ModelId."' order by fromDate desc limit 1");
$price = mysql_fetch_array($prices);


$equipmentNo = mysql_query("select EquipmentNo,ModelNo,Quantity,Minquantity from product_model where ProductId = '". $product['productId'] ."' and ModelId='".$models->ModelId."'");

$equip = mysql_fetch_object($equipmentNo);

$availstock = mysql_query("select Quantity from stock_models where StockId=".$product['StockId']." and  ModelCode='".$equip->ModelNo."'");
//echo "select Quantity from stock_models where StockId=".$product['StockId']." and  ModelCode='".$equip->ModelNo."'<br>";
$availableStock = mysql_fetch_object($availstock);	  
	 ?><tr>
														<td class="center">
															<?php echo $i; ?>
														</td>
                                                        
                                                         <td>
															<?php echo $product['category']; ?>
														</td>
                                                        <td>
															<?php echo $product['brand']; ?>
														</td>

														<td>
															<?php echo $product['product']; ?>
														</td>
                                                        <td>
															<?php 
															#echo $product['code']; 
															echo $models->ModelNo;
															?>
														</td>
                                                        <td>
                                                        	<?PHP if($equip->EquipmentNo=='') echo 'NA'; else echo $equip->EquipmentNo;?>
                                                        </td>
                                                        <td>
															<?php echo $price['price']; ?>
														</td>
                                                       
                                                         <td>
															<?php if($equip->Quantity!='') echo $equip->Quantity; else echo 'NA';  ?>
														</td>
                                                        <td>
															<?php echo $equip->Minquantity; ?>
														</td>
														<td class="hidden-480">
                                                        
                                                           <?php if($product['status']==1) {  $status = 'Active'; ?>
                                                           <span class="label label-sm label-success"><?php echo $status;  ?></span>
                                                           <?php  } else if($product['status']==0) { $status = 'Deactive'; ?>
                                              <span class="label label-sm label-warning"><?php echo $status;  ?></span>
                                                           <?php  } ?>
															
														</td>
                                                       <td>
                                                        
                                                  
        
        
      <!--       <a href="manage_price.php?pid=<?php echo $product['productId']; ?>" class="btn btn-warning btn-sm" title="Add Price">
           <i class="fa fa-rupee"></i>
        </a> --> 
                                                        

<?PHP                                          
 if($product['Stock_Store'] == "Stock")
 {
 ?> 
                                       <a class="btn btn-primary btn-sm" href="edit_product.php?pid=<?php echo $product['productId']; ?>&model=<?PHP echo $models->ModelId;?>" title="Edit Product">
												<i class="ace-icon fa fa-edit icon-only"></i>
											</a>
                                            
<?PHP 
 }
 else if($product['Stock_Store'] == "Store")
 {
 ?> 
                                       <a class="btn btn-primary btn-sm" href="edit_storeproduct.php?stockid=<?PHP echo $product['StockId'];?>&pid=<?php echo $product['productId']; ?>" title="Edit Product">
												<i class="ace-icon fa fa-edit icon-only"></i>
											</a>
                                            
<?PHP 
 }

 ?>
                                            
                                            <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?php echo $product['productId']; ?>')" title="Delete Product">
												<i class="ace-icon fa fa-trash icon-only"></i>
											</button>
                                                      
                                                  
                                                          
														</td>

													
													</tr>
                                                   
<?php
  // to download the all data in excel.
$rowlist[] = $i;
$rowlist[] = $product['category'];
$rowlist[] = $product['brand'];
$rowlist[] = $product['product'];
$rowlist[] = $product['code'];
$rowlist[] = $price['price'];
$rowlist[] = $product['stock']; 
$rowlist[] = $product['minQuantity'];
$rowlist[] = $status;
 $list[] = $rowlist;
 unset($rowlist);




} 

 $i++; } } 
 
 else { ?> <tr><td colspan="10">No Data found.</td></tr> <?php }
 
 if($numRecords>$limit && !(isset($_GET['proposalSearch']))) {
   
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
  <?php }
 

?>   
 
  </tbody>
											</table>
                                            <?php                              
$fp = fopen('excel/products.csv', 'w');

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
        
        
        <!-- page specific plugin scripts -->
		<script src="assets/js/date-time/bootstrap-datepicker.js"></script>
		<script src="assets/js/jqGrid/jquery.jqGrid.src.js"></script>
		<script src="assets/js/jqGrid/i18n/grid.locale-en.js"></script>
        
        <!-- page specific plugin styles -->
		<link rel="stylesheet" href="assets/css/jquery-ui.css" />
		<link rel="stylesheet" href="assets/css/datepicker.css" />
		<link rel="stylesheet" href="assets/css/ui.jqgrid.css" />




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
		
		/*$(document).ready(function(){
			$("#category").blur(function() {
				//$(document).on('blur','#category',function(){
				$("#categoryList").css('display','none'); 
			});
		});*/
		

		
		function confirmDelete(did)
		{
			if(confirm("Do you want to delete the product."))
			{
				window.location = 'products.php?did='+did;
				
			}
		   	
		}
		
function goToPage(pid)
{
   window.location = 'products.php?page='+pid;	
}


function getCategory(val)
		{
			
			document.getElementById("categoryList").style.display = 'block';
				$.ajax({url: "ajax/getExeCategoryList.php?val="+val, success: function(result){
		$("#categoryList").html(result);
    }});	
			
		}
		
		
		function selectCategory(id,firstName)
		{
			document.getElementById("categoryList").style.display = 'none';
			document.getElementById("cid").value = id;
			document.getElementById("category").value = firstName;
	
		}	
		
		function getBrand(val)
		{
			
			document.getElementById("brandList").style.display = 'block';
				$.ajax({url: "ajax/getExeBrandList.php?val="+val, success: function(result){
		$("#brandList").html(result);
    }});	
			
		}
		
		
		function selectBrand(id,firstName)
		{
			document.getElementById("brandList").style.display = 'none';
			document.getElementById("bid").value = id;
			document.getElementById("brand").value = firstName;
	
		}	
		
		function getProduct(val)
		{
			
			document.getElementById("productList").style.display = 'block';
				$.ajax({url: "ajax/getExeProductList.php?val="+val, success: function(result){
		$("#productList").html(result);
    }});	
			
		}
		
		
		function selectProduct(id,firstName)
		{
			document.getElementById("productList").style.display = 'none';
			document.getElementById("pid").value = id;
			document.getElementById("product").value = firstName;
		}	
		
		
		function getCode(val)
		{
			
			document.getElementById("codeList").style.display = 'block';
				$.ajax({url: "ajax/getCodeList.php?val="+val, success: function(result){
		$("#codeList").html(result);
    }});	
			
		}
		
		
		function selectCode(id,firstName)
		{
			document.getElementById("codeList").style.display = 'none';
			document.getElementById("cdid").value = id;
			document.getElementById("code").value = firstName;
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
			
			$(document).on('click','#outclick,#breadcrumbs',function(){
				$('#brandList,#categoryList,#productList,#codeList').css('display','none');
				
				
				});
			
			
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
