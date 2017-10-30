<?php include("includes/header.php"); 

     // data comes from stock table
					   $vendors = mysql_query("select stock.stockId, stock.lr, stock.moc, stock.date, stock.addedBy, stock.invoiceNumber, stock.invoiceAmount, vendors.company, employees.firstName from stock   left join employees on stock.addedBy = employees.id
					      left join vendors on stock.vendorId = vendors.vendorId
					   where stock.stockId=".$_GET['stkid']);

     // data comes from stock,stock_products table
$stocks=mysql_query("select stkprd.productId, prd.product product, stk.vendorId from stock as stk left join stock_products as stkprd on stk.stockId=stkprd.stockId  join products as prd on prd.productId=stkprd.productId where stk.stockId=".$_GET['stkid']);	   

$products='';
$prd_excel='';

if(mysql_num_rows($stocks)>0)
{
	while($prdct_stock = mysql_fetch_object($stocks) )	
	{
		$products.="<li>".$prdct_stock->product."</li>";
		$prd_excel.=$prdct_stock->product.",";
	}
}
$products=trim($products,",");
$prd_excel = trim($prd_excel,",");

?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Stocks-View </li>
                            
                        	
						</ul>
                        <?PHP
								$sheet_num=time();
						?>
                        <a href="excel/<?PHP echo $sheet_num;?>-stock-view.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>
                      
                        <!-- /.breadcrumb -->

						<!-- #section:basics/content.searchbox -->
                         
						<div class="nav-search" id="nav-search">
                        
                          
							
						</div><!-- /.nav-search -->

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
										
										<div class="table-header">
											Stocks-View
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
														<th>Invoice</th>
                                                         <th>Category</th>
                                                        <th>Brand</th>
                                                        <th>Product</th>
                                                        <th>Amount</th>
                                                        <th>Quantity</th>
                                                        <th>Model No.</th>
                                                        <th>LR No.</th>
                                                        <th>MOC No.</th>
                                                        <th>Company</th>
                                                     <!--   <th>Added By</th>-->
                                                        
													</tr>
												</thead>

												<tbody>
													
														
																	

  <?php 
   $list[] = array('S. No', 'Date', 'Invoice', 'Category','Brand','Product','Model No','Amount','Quantity','LR No.', 'MOC No.', 'Company', 'AddedBy' );
   $rowlist = array();
  if(mysql_num_rows($vendors)>0)
  {
	   $i = $start+1;
	    	 

  while($vendor = mysql_fetch_array($vendors))
  { 
	
	
	
	$stocks = mysql_query("select  stock_products.quantity, products.product, products.code, categories.category, brands.brand from stock_products 
                       left join products on stock_products.productId = products.productId left join brands on products.brand = brands.id
					   left join categories on products.category = categories.id  where stock_products.stockId = '". $_GET['stkid']."'");
					   $stock = mysql_fetch_array($stocks);
					   
					   
					   ?>
                                            
                                        
<tr>
														<td class="center">
															<?php echo $i; ?>
														</td>
                                                         <td><?php $date = explode('-',$vendor['date']);    
														          echo $date =  $date[2].'-'.$date[1].'-'.$date[0];      
														       ?></td>
														<td><?php echo $vendor['invoiceNumber'] ?></td>	
                                                       
                                                        <td><?php echo $stock['category']; ?></td>
                                                        <td><?php echo $stock['brand']; ?></td>
                                                        <td><?php echo $products;//$stock['product']; ?></td>
                                                         <td><?php echo $vendor['invoiceAmount'] ?></td>
                                                        <td><?php echo $stock['quantity']; ?></td>
                                                        <td><?php echo $stock['code']; ?></td>
                                                        
                                                        <td><?php echo $vendor['lr'] ?></td>
                                                        <td><?php echo $vendor['moc'] ?></td>
                                                        <td><?php echo $vendor['company'] ?></td>
                                                       <!-- <td><?php echo $vendor['firstName'] ?></td>-->
                                                        
                                                  	</tr>
                                                    
<?php 

//array('S. No', 'Date', 'Invoice', 'Category','Brand','Product','Model No','Amount','Quantity','LR No.', 'MOC No.', 'Company', 'AddedBy' );

$rowlist[] = $i;
$rowlist[] = $date;
$rowlist[] = $vendor['invoiceNumber'];
$rowlist[] = str_replace(","," ",$stock['category']);
$rowlist[] = str_replace(","," ",$stock['brand']);

$rowlist[] = str_replace(","," ",$prd_excel);
$rowlist[] = $stock['code'];

$rowlist[] = $vendor['invoiceAmount'];
$rowlist[] = $stock['quantity'];
$rowlist[] = $vendor['lr'];
$rowlist[] = $vendor['moc'];
$rowlist[] = $vendor['company'];
$rowlist[] = $vendor['firstName'] ;

//$rowlist[] = $status;
$list[] = $rowlist;

unset($rowlist);

//$list[] = array('S. No', 'Date', 'Invoice', 'Category','Brand','Product','Model No','Amount','Quantity','LR No.', 'MOC No.', 'Company', 'AddedBy' );												
	$i++; } 
} 
	else
													{
													
													
													?><tr><td colspan="10">No Data found.</td></tr> <?php	
													}
  
  ?>                                            
  
   </tbody>
											</table>
	<?php
$fle = 'excel/'.$sheet_num.'-stock-view.csv';
    $fp = fopen($fle, 'w');

    foreach ($list as $fields) {
		
    fputcsv($fp, $fields);
    }
    
    fclose($fp);  ?>
             
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
