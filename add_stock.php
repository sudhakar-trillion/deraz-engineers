<?php 
include("includes/header.php"); 
// in this stock will be added in 3 tables. stock details added in stock table and stock products added in stock_products. And available stock also added available_stock table.

// add stock
		if(isset($_POST['addStock']))
		{
	   		extract($_POST);
			
			//first insert the stock details into the stock table
			$disdate = date_create($disdate);
			$disdate = date_format($disdate,"Y-m-d");
				
			//firstduedate,secondduedate,thirdduedate
			
			$firstduedate = date_create($firstduedate);
			$firstduedate = date_format($firstduedate,'Y-m-d');
			
			$secondduedate = date_create($secondduedate);
			$secondduedate  = date_format($secondduedate ,"Y-m-d");
			
			$thirdduedate = date_create($thirdduedate);
			$thirdduedate  = date_format($thirdduedate,"Y-m-d");
			
			
$qry = mysql_query("insert into stock  values ('','".$lr."','".$moc."','".$disdate."','".$firstduedate."','".$secondduedate."','".$thirdduedate."','','".date('Y-m-d H:i:s')."','".$invoice."','".$amount."','".$vendor."','".$category."','".$brand."','','".$product."') ");


$lastId = mysql_insert_id();

	//now insert the models, price and quantity of the product into the stock_models table


for($i=0;$i<sizeof($code); $i++)
{
	//echo  "insert into stock_models  values ('',$lastId,'".$code[$i]."','".$quantity[$i]."','1','".$price[$i]."','".time()."'<br>";
	 mysql_query("insert into stock_models  values ('',$lastId,'".$code[$i]."','".$quantity[$i]."','1','".$price[$i]."','".time()."')");
}


//now insert the data into the products table


$max_id = mysql_query("select productId from `products` order by productId desc limit 1");
			$max_id = mysql_fetch_array($max_id);
	//	echo $max_id['productNumber']; exit; 
		
		$product_id=$max_id['productId'];
		$product_id = $product_id+1;
		$product_id = 'PR'.$product_id;
		
		mysql_query("insert into products (`StockId`,`productNumber`, `product`,  `category`, `brand`, `minQuantity`, `status`, `addedBy`,`Stock_Store`, `dateTime`) values ('".$lastId."','$product_id', '$product', '$category', '$brand', '1', '1', '".$_SESSION['id'] ."','Store',NOW())");

		$productId = mysql_insert_id();



		for($i=0;$i<sizeof($code); $i++)
		{
			mysql_query("insert into product_model  values ('',$productId,'".$code[$i]."','".$codeuom[$i]."','".$equipment[$i]."','".$quantity[$i]."','".$minquantity[$i]."','".$price[$i]."','".time()."')");

			$modeId = mysql_insert_id();
			
			mysql_query("insert into product_price  values ('',$productId,'".$modeId."','".$price[$i]."','".date('Y-m-d')."','".time()."')");
		}
			
		}

$categories = mysql_query("select id, category from categories order by category");
$vendors = mysql_query("select vendorId, company from vendors order by company");
/*$brands = mysql_query("select id, brand from brands order by brand");
$products = mysql_query("select product, code, category, brand from products where productId = '". $_GET['pid'] ."'");
 while($product = mysql_fetch_array($products))
 {
	 
 }

 
 $stocks = mysql_query("select * from stock where productId = '". $_GET['pid'] ."'"); 
*/
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
								<a href="stock.php">Stock</a>
							</li>

							<li class="active">Add Stock</li>
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
									
                                            <div class="col-xs-12">
										
										<div class="table-header">
											Add Stock
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div>
											
                                           <?php
	   if(isset($_GET['add']))
{ $alertMsg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Stock has been added!</div>'; }
else if(isset($_GET['error']))
{ $alertMsg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  if(isset($msg)) {  echo $alertMsg; 
  }
										   ?> 
                                            
                                            <form class="form-horizontal" role="form" action="" method="post" id="validation-form" autocomplete="off">
                                      <div class="row">  
                                      
                                      <div class="space-6"></div>         
                                  <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="vendor">Vendors </label>

										<div class="col-sm-9">
											<select id="vendor" name="vendor" class="col-xs-10 col-sm-12">
                                            <option value=""></option>
                                            <?php while($vendor = mysql_fetch_array($vendors)) { ?>
                                            <option value="<?php echo $vendor['vendorId']; ?>"><?php echo $vendor['company']; ?></option>
                                            <?php } ?>
                                            </select>
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="invoice">Invoice no. </label>

										<div class="col-sm-9">
											<input type="text" id="invoice" name="invoice" placeholder="Invoice no." class="col-xs-10 col-sm-12"  />
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="amount">Invoice Amount. </label>

										<div class="col-sm-9">
											<input type="text" id="amount" name="amount" placeholder="Invoice Amount" class="col-xs-10 col-sm-12"  />
										</div>
									</div>
                                   
								    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="date">Dispatch Date </label>

										<div class="col-sm-9">
											<input type="text" id="disdate" name="disdate" placeholder="Dispatch Date" class="col-xs-10 col-sm-12 date-picker"  />
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="date">First due date</label>

										<div class="col-sm-9">
											<input type="text" id="firstduedate" name="firstduedate" placeholder="First due date" class="col-xs-10 col-sm-12 date-picker"  />
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="date">Second due Date </label>

										<div class="col-sm-9">
											<input type="text" id="secondduedate" name="secondduedate" placeholder="Second due Date" class="col-xs-10 col-sm-12 date-picker"  />
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="date">Third due Date </label>

										<div class="col-sm-9">
											<input type="text" id="thirdduedate" name="thirdduedate" placeholder="Third due Date " class="col-xs-10 col-sm-12 date-picker"  />
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="lr">LR no. </label>

										<div class="col-sm-9">
											<input type="text" id="lr" name="lr" placeholder="LR no." class="col-xs-10 col-sm-12"  />
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="moc">MOC no. </label>

										<div class="col-sm-9">
											<input type="text" id="moc" name="moc" placeholder="MOC no." class="col-xs-10 col-sm-12 moc"  />
										</div>
									</div>
                                </div> <!-- end row  -->
                                 
                                 <?PHP
									$categories = mysql_query("select id, category from categories order by category");
									$brands = mysql_query("select id, brand from brands order by brand");

								 ?>
                                 
                                 <!-- category -->
                                 <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="category"> Category </label>

										<div class="col-sm-9">
											<select id="cid" name="category" class="col-xs-10 col-sm-12 getBrandOptions">
                                            <option value="">Select Category</option>
                                            <?php while($category = mysql_fetch_array($categories))
											{ ?>
                                            
                                            <option value="<?php echo $category['id']; ?>"><?php echo $category['category']; ?></option>
                                            <?php } ?>
                                            </select>
										</div>
									</div>
                                  
                                 <!-- category edns here -->
                                 
                                 <!-- brand -->
                                 
                                 <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="brand"> Brands </label>

										<div class="col-sm-9">
											<select id="brand" name="brand" class="col-xs-10 col-sm-12">
                                            
                                            </select>
										</div>
									</div>
                                    
                                 <!-- Brnads ends here -->   
                                 
                                 <!-- products -->
                                 
                                 <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="product"> Product </label>

										<div class="col-sm-9">
<input type="hidden" id="pid" name="pid"  />
<input type="text" id="product" name="product" placeholder="Product" class="col-xs-10 col-sm-12" onkeyup="getProduct(this.value)" />
<ul class="typeahead dropdown-menu" style="top: 28px; left: 0px; display: none; height:100px; width:400px; overflow:auto; margin:0px; padding:0px; border:0px;" id="productsList">
                                           </ul>
										</div>
									</div>
                                 
                                 <!-- products ends here -->
                                    
                               <!--product code -->
                               
                              
                               
                               
                               <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="code"> Product Code </label>

							<div class="col-sm-9 codes">
                                <input type="hidden" id="pcid" name="pcid"  />
                                <input type="text" id="code" name="code[]" placeholder="Code" class="col-xs-2"/>
                                <span class="col-md-1 add_model_no" style="padding-top:0px; margin-top:3px; cursor:pointer; margin-left:5px; font-size:18px; background:#069; width:auto; color:#fff">+</span>
							</div>
									</div>
                                    <!-- product code ends here -->
                                    
                                    <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="code"> Code UOM </label>
                                
                                <div class="col-sm-9 codeuoms">
                                
                                <input type="text" id="codeuom" name="codeuom[]" placeholder="Code UOM" class="col-xs-2"/>
                                <span class="col-md-1 add_codeuom" style="padding-top:0px; margin-top:3px; cursor:pointer; margin-left:5px; font-size:18px; background:#069; width:auto; color:#fff">+</span>
                                
                                
                                </div>
                                </div>
                                    
                                    
                               <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="code"> Product Price</label>

										<div class="col-sm-9 prices">

<input type="text" id="price" name="price[]" placeholder="Price" class="col-xs-2"/>
<span class="col-md-1 add_price" style="padding-top:0px; margin-top:3px; cursor:pointer; margin-left:5px; font-size:18px; background:#069; width:auto; color:#fff">+</span>


										</div>
									</div>
                                    <!-- product price ends here -->
                                    
                               <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="code"> Quantity</label>
                                
                                <div class="col-sm-9 quantities">
                                
                                <input type="text" id="quantity" name="quantity[]" placeholder="Quantity" class="col-xs-2"/>
                                <span class="col-md-1 add_quantity" style="padding-top:0px; margin-top:3px; cursor:pointer; margin-left:5px; font-size:18px; background:#069; width:auto; color:#fff">+</span>
                                
                                
                                </div>
                                </div>                                    
                               <!--Quantity -->
                                    
							   <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="code"> Minimum Quantity</label>
                                
                                <div class="col-sm-9 minquantities">
                                
                                <input type="text" id="minquantity" name="minquantity[]" placeholder="Minimum Quantity" class="col-xs-2"/>
                                <span class="col-md-1 add_minquantity" style="padding-top:0px; margin-top:3px; cursor:pointer; margin-left:5px; font-size:18px; background:#069; width:auto; color:#fff">+</span>
                                
                                
                                </div>
                                </div>
                                                                    
                                    <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="code"> Equipment Number</label>
                                
                                <div class="col-sm-9 equipments">
                                
                                <input type="text" id="equipment" name="equipment[]" placeholder="Equipment Number" class="col-xs-2"/>
                                <span class="col-md-1 add_equipment" style="padding-top:0px; margin-top:3px; cursor:pointer; margin-left:5px; font-size:18px; background:#069; width:auto; color:#fff">+</span>
                                
                                
                                </div>
                                </div>
                                <!-- equipment number ends here -->
                                    
                                     <div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
                                        
                                        <div class="col-sm-2">
											<button class="btn btn-sm btn-info" type="submit" name="addStock">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>
                                            </div>
                                            
                                       

											
                                         <!--<div class="col-sm-2" id="addRowSpan" style="display: block;">
<button class="btn btn-sm btn-success" type="button" id="addRow" name="addRow" onclick="displayFields(this.value)" value="2">
												<i class="ace-icon fa fa-plus bigger-110"></i>
												Add More
											</button>
                                            </div>-->
                                          
                                            
                                        <!--<div class="col-sm-2" id="removeRowSpan" style="display: block;">
                    <button class="btn btn-sm btn-danger" type="button" id="removeRow" onclick="removeFields(this.value)" value="2">
												<i class="ace-icon fa fa-minus bigger-110"></i>
												Remove
											</button>
                                            </div>-->
                                          
                                            
                                            
										</div>
									</div>
                                    
                                    
                                    
                                    

                                    
                                    </form>
                                    
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

 function displayFields(id)
 {
	 document.getElementById("addRow").value = parseInt(id)+1;
	  document.getElementById("removeRow").value = parseInt(id)+1;
	  
	  id = parseInt(id)+1;
	  
	 $.ajax({url: "ajax/getFields.php?id="+id, success: function(result){
        $("#formContainer").append(result);
    }});
	
 }
 
  function removeFields(id)
 {
	 document.getElementById("addRow").value = parseInt(id)-1;
	  document.getElementById("removeRow").value = parseInt(id)-1;
	// document.getElementById("pr"+id).value = parseInt(id)-1;
	 document.getElementById("pr"+id).remove();
	
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
	    $.ajax({url: "ajax/GetBrandList.php?cid="+category, success: function(result){
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
	    		
		$.ajax({
				url:"ajax/getModelsList.php",
				type:'POST',
				data:{'bid':brand,'cid':category,'product':product},
				success: function(result){
 				$("#model"+rid).html(result);
				}
				
				});

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
					format: 'dd-mm-yyyy',
					todayHighlight: true
				})
				
				
			
			$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					ignore: "",
					rules: {
						vendor: {
							required: true,
							
						},
						invoice: {
							required: true,
							
						},
						amount: {
							required: true,
							
						},
						date: {
							required: true,
							
						},
						lr: {
							required: true,
							
						},
						moc: {
							required: true,
							
						},
						model1: {
							required: true,
							
						},
						cid:{ required:true, },
						brand:{required:true, },
						product:{required:true},
						'code[]':{required:true},
						'price[]':{required:true},
						'quantity[]':{required:true}
						
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

<script>
	$(document).ready(function(){
			$(document).on('change','.getBrandOptions',function(){
				var cid = $("#cid").val();
				
				$.ajax({
					url: 'ajax/GetBrandList.php',
					type: 'GET',
					data: {'cid':cid},
					success: function(data){
					$("#brand").html(data);
					
					}
					});
				
			});

	$(document).on('click',".add_model_no",function() 
			{ 
				var parent = $(".codes");
				
				if( parent.children().hasClass('morecodes') )
				{
					var len = $('.morecodes').length;
						len = parseInt(len)+1;
					
					$(".morecodes:last-child").after('<div class="morecodes" cds="morecodes_'+len+'" style="position: relative;display: inline-block;width:16.6%;margin-right: 5px;margin-left: 5px;"><input type="text" style="width:100%;"   name="code[]" id="code"><span class="package-removal">X</span></div> ');
					$(".morecodes:last-child").css({'margin-left':'5px'});
					
				}
				else
					{
						$(".add_model_no").after('<div class="morecodes"  cds="morecodes_1" style="position: relative;display: inline-block;width:16.6%;margin-right: 5px;margin-left: 5px;"><input type="text" style="width:100%;" name="code[]" id="code"><span class="package-removal">X</span></div> ');
						$(".morecodes:last-child").css({'margin-left':'5px'});
					}
			});	

/* ###################################################################  					  ##############################################*/			
/* ###################################################################  model codesends here  ##############################################*/
/* ###################################################################  					  ##############################################*/
	
	

$(document).on('click',".add_price",function() 
			{ 
				var parent = $(".prices");
				
				if( parent.children().hasClass('moreprices') )
				{
					
					var len = $('.moreprices').length;
						len = parseInt(len)+1;
						
					$(".moreprices:last-child").after('<div class="moreprices"  prce="moreprices_'+len+'" style="position: relative;display: inline-block;width:16.6%;margin-right: 5px;margin-left: 5px;"><input type="text" style="width:100%;"   name="price[]" id="code"><span class="package-removal">X</span></div> ');
					$(".moreprices:last-child").css({'margin-left':'5px'});
					
				}
				else
					{
						$(".add_price").after('<div class="moreprices" prce="moreprices_1" style="position: relative;display: inline-block;width:16.6%;margin-right: 5px;margin-left: 5px;"><input type="text" style="width:100%;" name="price[]" id="price"><span class="package-removal">X</span></div> ');
						$(".moreprices:last-child").css({'margin-left':'5px'});
					}
			});	
	
/* ###################################################################  					  ##############################################*/			
/* ###################################################################  model prices here 	  ##############################################*/
/* ###################################################################  					  ##############################################*/	
	
	

$(document).on('click',".add_quantity",function() 
			{ 
				var parent = $(".quantities");
				
				if( parent.children().hasClass('morequantities') )
				{
					var len = $('.morequantities').length;
						len = parseInt(len)+1;
					
					
					$(".morequantities:last-child").after('<div class="morequantities" quant="morequantities_'+len+'"  style="position: relative;display: inline-block;width:16.6%;margin-right: 5px;margin-left: 5px;"><input type="text" style="width:100%;"   name="quantity[]" id="code"><span class="package-removal">X</span></div> ');
					$(".morequantities:last-child").css({'margin-left':'5px'});
					
				}
				else
					{
						$(".add_quantity").after('<div class="morequantities" quant="morequantities_1" style="position: relative;display: inline-block;width:16.6%;margin-right: 5px;margin-left: 5px;"><input type="text" style="width:100%;" name="quantity[]" id="price"><span class="package-removal">X</span></div> ');
						$(".morequantities:last-child").css({'margin-left':'5px'});
					}
			});	
	
/* ###################################################################  					  ##############################################*/			
/* ###################################################################  model prices here 	  ##############################################*/
/* ###################################################################  					  ##############################################*/	



<!-- <input type="text" id="minquantity" name="minquantity[]" placeholder="Minimum Quantity" class="col-xs-2"/>                                <span class="col-md-1 add_minquantity" style="padding-top:0px; margin-top:3px; cursor:pointer; margin-left:5px; font-size:18px; background:#069; width:auto; color:#fff">+</span>-->


$(document).on('click',".add_minquantity",function() 
			{ 
				var parent = $(".minquantities");
				
				if( parent.children().hasClass('moreminquantities') )
				{
					var len = $('.morequantities').length;
						len = parseInt(len)+1;
					
					
					$(".moreminquantities:last-child").after('<div class="moreminquantities" minquant="moreminquantities_'+len+'"  style="position: relative;display: inline-block;width:16.6%;margin-right: 5px;margin-left: 5px;"><input type="text" style="width:100%;"   name="minquantity[]" id="code"><span class="package-removal">X</span></div> ');
					$(".moreminquantities:last-child").css({'margin-left':'5px'});
					
				}
				else
					{
						$(".add_minquantity").after('<div class="moreminquantities" minquant="moreminquantities_1" style="position: relative;display: inline-block;width:16.6%;margin-right: 5px;margin-left: 5px;"><input type="text" style="width:100%;" name="minquantity[]" id="price"><span class="package-removal">X</span></div> ');
						$(".moreminquantities:last-child").css({'margin-left':'5px'});
					}
			});	
	
/* ###################################################################  					  ##############################################*/			
/* ###################################################################  minimum quantity ends ##############################################*/
/* ###################################################################  					  ##############################################*/	

<!-- <input type="text" id="codeuom" name="codeuom[]" placeholder="Code UOM" class="col-xs-2"/>                                <span class="col-md-1 add_codeuom" style="padding-top:0px; margin-top:3px; cursor:pointer; margin-left:5px; font-size:18px; background:#069; width:auto; color:#fff">+</span>

-->




$(document).on('click',".add_codeuom",function() 
			{ 
				var parent = $(".codeuoms");
				
				if( parent.children().hasClass('morecodeuoms') )
				{
					var len = $('.morecodeuoms').length;
						len = parseInt(len)+1;
					
					
					$(".morecodeuoms:last-child").after('<div class="morecodeuoms" uom="morecodeuoms_'+len+'"  style="position: relative;display: inline-block;width:16.6%;margin-right: 5px;margin-left: 5px;"><input type="text" style="width:100%;"   name="codeuom[]" id="codeuom"><span class="package-removal">X</span></div> ');
					$(".moreminquantities:last-child").css({'margin-left':'5px'});
					
				}
				else
					{
						$(".add_codeuom").after('<div class="morecodeuoms" uom="morecodeuoms_1" style="position: relative;display: inline-block;width:16.6%;margin-right: 5px;margin-left: 5px;"><input type="text" style="width:100%;" name="codeuom[]" id="price"><span class="package-removal">X</span></div> ');
						$(".morecodeuoms:last-child").css({'margin-left':'5px'});
					}
			});	



$(document).on('click',".add_equipment",function() 
			{ 
				var parent = $(".equipments");
				
				if( parent.children().hasClass('moreequipments') )
				{
					var len = $('.moreequipments').length;
						len = parseInt(len)+1;
					
					
					$(".moreequipments:last-child").after('<div class="moreequipments" equip="moreequipments_'+len+'"  style="position: relative;display: inline-block;width:16.6%;margin-right: 5px;margin-left: 5px;"><input type="text" style="width:100%;"   name="equipment[]" id="code"><span class="package-removal">X</span></div> ');
					$(".moreequipments:last-child").css({'margin-left':'5px'});
					
				}
				else
					{
						$(".add_equipment").after('<div class="moreequipments" equip="moreequipments_1" style="position: relative;display: inline-block;width:16.6%;margin-right: 5px;margin-left: 5px;"><input type="text" style="width:100%;" name="equipment[]" id="price"><span class="package-removal">X</span></div> ');
						$(".moreequipments:last-child").css({'margin-left':'5px'});
					}
			});	
	
/* ###################################################################  					  ##############################################*/			
/* ###################################################################  model prices here 	  ##############################################*/
/* ###################################################################  					  ##############################################*/	
		
			
			
			
//remove text-field

$(document).on('click','.package-removal',function()
{
	if(confirm("Do you want to delete this and its corresponding attributes"))
	{
		var cls = $(this).parent().attr('class');

		if(cls=="morequantities") { var atr="quant"; }
		if(cls=="moreprices") { var atr="prce";  }
		if(cls=="morecodes") { var atr="cds"; }
		if(cls=="moreequipments") { var atr="equip"; }
		
		if(cls=="moreminquantities") { var atr="minquant"; }
		if(cls=="morecodeuoms") { var atr="uom"; }
		
		var cnt_attr = $(this).parent().attr(atr);
			cnt_attr = cnt_attr.split("_");
			
		$(this).parent().remove();	
		//$("."+cls).remove();

		
		
		
		if(cls=="morequantities") { 
									
									var mreprc = "moreprices_"+cnt_attr[1];
									var mrecodes = "morecodes_"+cnt_attr[1];
									var mreequip =  "moreequipments_"+cnt_attr[1];
									var mreminquant = "moreminquantities_"+cnt_attr[1];
									
									var mreuoms = "morecodeuoms_"+cnt_attr[1];
									
									$(".morecodeuoms").each(function() { if($(this).attr('uom')==mreuoms){ $(this).remove(); }   });
									$(".moreminquantities").each(function() { if($(this).attr('minquant')==mreminquant){ $(this).remove(); }   });
									
									$(".moreprices").each(function() { if($(this).attr('prce')==mreprc){ $(this).remove(); }   });
									$(".morecodes").each(function() { if($(this).attr('cds')==mrecodes){ $(this).remove(); }   });
									$(".moreequipments").each(function() { if($(this).attr('equip')==mreequip){ $(this).remove(); }   });
								 }
								 
		if(cls=="moreprices") { 
									
									var mreqnt = "morequantities_"+cnt_attr[1];
									var mrecodes = "morecodes_"+cnt_attr[1];
									var mreequip =  "moreequipments_"+cnt_attr[1];
									var mreminquant = "moreminquantities_"+cnt_attr[1];
									
									var mreuoms = "morecodeuoms_"+cnt_attr[1];
									
									$(".morecodeuoms").each(function() { if($(this).attr('uom')==mreuoms){ $(this).remove(); }   });
									$(".moreminquantities").each(function() { if($(this).attr('minquant')==mreminquant){ $(this).remove(); }   });
									
									$(".moreequipments").each(function() { if($(this).attr('equip')==mreequip){ $(this).remove(); }   });
									$(".morecodes").each(function() { if($(this).attr('cds')==mrecodes){ $(this).remove(); }   });
									$(".morequantities").each(function() { if($(this).attr('quant')==mreqnt){ $(this).remove(); }   });
								 }	
								 
		if(cls=="morecodes") { 
									
									var mreprc = "moreprices_"+cnt_attr[1];
									var mreqnt = "morequantities_"+cnt_attr[1];
									var mreequip =  "moreequipments_"+cnt_attr[1];
									var mreminquant = "moreminquantities_"+cnt_attr[1];
									var mreuoms = "morecodeuoms_"+cnt_attr[1];
									
									$(".morecodeuoms").each(function() { if($(this).attr('uom')==mreuoms){ $(this).remove(); }   });
									$(".moreminquantities").each(function() { if($(this).attr('minquant')==mreminquant){ $(this).remove(); }   });
									
									$(".moreequipments").each(function() { if($(this).attr('equip')==mreequip){ $(this).remove(); }   });
									$(".moreprices").each(function() { if($(this).attr('prce')==mreprc){ $(this).remove(); }   });
									$(".morequantities").each(function() { if($(this).attr('quant')==mreqnt){ $(this).remove(); }   });
								 }									 							 
	if(cls=="moreequipments") { 
									
									var mreprc = "moreprices_"+cnt_attr[1];
									var mreqnt = "morequantities_"+cnt_attr[1];
									var mrecodes = "morecodes_"+cnt_attr[1];
									var mreminquant = "moreminquantities_"+cnt_attr[1];
									var mreuoms = "morecodeuoms_"+cnt_attr[1];
									
									$(".morecodeuoms").each(function() { if($(this).attr('uom')==mreuoms){ $(this).remove(); }   });
									$(".moreminquantities").each(function() { if($(this).attr('minquant')==mreminquant){ $(this).remove(); }   });
									
									$(".morecodes").each(function() { if($(this).attr('cds')==mrecodes){ $(this).remove(); }   });
									$(".moreprices").each(function() { if($(this).attr('prce')==mreprc){ $(this).remove(); }   });
									$(".morequantities").each(function() { if($(this).attr('quant')==mreqnt){ $(this).remove(); }   });
								 }									 							 

if(cls=="moreminquantities") { 
									
									var mreprc = "moreprices_"+cnt_attr[1];
									var mreqnt = "morequantities_"+cnt_attr[1];
									var mrecodes = "morecodes_"+cnt_attr[1];
									var mreequip =  "moreequipments_"+cnt_attr[1];
									var mreuoms = "morecodeuoms_"+cnt_attr[1];
									
									$(".morecodeuoms").each(function() { if($(this).attr('uom')==mreuoms){ $(this).remove(); }   });
									$(".moreequipments").each(function() { if($(this).attr('equip')==mreequip){ $(this).remove(); }   });
									$(".morecodes").each(function() { if($(this).attr('cds')==mrecodes){ $(this).remove(); }   });
									$(".moreprices").each(function() { if($(this).attr('prce')==mreprc){ $(this).remove(); }   });
									$(".morequantities").each(function() { if($(this).attr('quant')==mreqnt){ $(this).remove(); }   });
								 }								 

if(cls=="morecodeuoms") { 
									
									var mreprc = "moreprices_"+cnt_attr[1];
									var mreqnt = "morequantities_"+cnt_attr[1];
									var mrecodes = "morecodes_"+cnt_attr[1];
									var mreequip =  "moreequipments_"+cnt_attr[1];
										var mreminquant = "moreminquantities_"+cnt_attr[1];
									
									$(".moreprices").each(function() { if($(this).attr('prce')==mreprc){ $(this).remove(); }   });
									$(".morequantities").each(function() { if($(this).attr('quant')==mreqnt){ $(this).remove(); }   });
									
									$(".morecodes").each(function() { if($(this).attr('cds')==mrecodes){ $(this).remove(); }   });
									$(".moreequipments").each(function() { if($(this).attr('equip')==mreequip){ $(this).remove(); }   });
									$(".moreminquantities").each(function() { if($(this).attr('minquant')==mreminquant){ $(this).remove(); }   });
									
								 }								 

		
		
		
	}
});			

		});


</script>

<script  src="http://code.jquery.com/jquery-1.12.4.js"></script>
<script  src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script >
$(function() {
	
	
	
	//autocomplete
	$(".moc").autocomplete({
		 source: "ajax/getmocs.php"
	});				
});
</script>

	</body>
</html>
