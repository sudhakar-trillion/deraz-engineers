<?php include("includes/header.php"); 
error_reporting(0);

if(isset($_GET['delete']) && $_GET['delete']=="yes")
{
	
	
//check any records with this stock id in  product_model
mysql_query("DELETE FROM `product_model` WHERE `ProductId`=".trim($_GET['product'])." and `ModelId`=".trim($_GET['model']) );

	$chk = mysql_query("select * from product_model where ProductId=".trim($_GET['product']));
	if(mysql_num_rows($chk)>0){	}
	else
	{
		//delete the stock related product from the product table and finally delete the stock from the stock table
		
		mysql_query("DELETE FROM `products` WHERE `StockId`='".trim($_GET['stockid'])."'");
		mysql_query("DELETE FROM `stock` WHERE `stockId`=".trim($_GET['stockid']));
			
	}

	header("location:stock.php?deleted=yes");
}



// pagination
 $limit = 10;
 
     // data comes from stock table
	 /*  relation between employees and stock are stock.addedBy = employees.id
	     relation vendors and stock are stock.vendorId = vendors.vendorId
	    */
	 
	  $numRecords = mysql_query("select stock.stockId, stock.lr, stock.moc, stock.disdate, stock.addedBy, stock.invoiceNumber, stock.invoiceAmount, vendors.company, employees.firstName from stock  left join employees on stock.addedBy = employees.id
					      left join vendors on stock.vendorId = vendors.vendorId
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


// search 
	
	
if(isset($_GET['stockSearch']))
{
									/* from date to todate search based on "stock.date".
									vendor search based on stock.vendorId
									customer search based on customers.customerId.  
									invoice search based on stock.stockId
									*/	

										
										$where = 'where ';
										
									/*if(isset($_GET['fromDate']) && strlen($_GET['fromDate']>8) && isset($_GET['toDate']) && strlen($_GET['toDate']>8))
									{
										
									
										$fromDate = explode('-',$_GET['fromDate']);
										$fromDate = $fromDate[2].'-'.$fromDate[1].'-'.$fromDate[0];
										
										$toDate = explode('-',$_GET['toDate']);
										$toDate = $toDate[2].'-'.$toDate[1].'-'.$toDate[0];
									 
									 
									 $whereItems[] = "stock.date >=  '". $fromDate ."'";
									 
									 $whereItems[] = "stock.date <=  '". $toDate ."'";
									}*/
									
									
									  if(isset($_GET['vid']) && $_GET['vid']>0)
									   {  
										  $whereItems[] = "stk.vendorId =  '". $_GET['vid'] ."'";
									   }
									   
									     // by customer
									  /* 
										  if(isset($_GET['cid']) && $_GET['cid']>0)
										   {  
										  
												$whereItems[] = "customers.customerId =  '". $_GET['cid'] ."'";
										   }
									   */
									   
									   // by invoice
									   if(isset($_GET['invid']) && $_GET['invid']>0)
									   {  
									    
										    $whereItems[] = "stk.stockId =  '". $_GET['invid'] ."'";
									   }
									   
									
									
									   // by products
									   /*
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
									
								
										if(count($whereSubItems)>1)
										{
								
											$whereSubCondition = implode(' and ',$whereSubItems);
										}
										else
										{
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
									   */
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
     // data comes from stock table									
	$stocks = mysql_query("select stk.stockId, stk.lr, prd.productId, ve.company, stk.moc, date_format(stk.disdate,'%d-%m-%Y') as disdate, stk.invoiceNumber, stk.invoiceAmount, stk.vendorId, stk.product, br.brand, cat.category from stock as stk left join categories as cat on cat.id=stk.category left join brands br on br.id=stk.brand left join vendors as ve on ve.vendorId=stk.vendorId left join products as prd on prd.StockId=stk.stockId $where  order by stk.stockId DESC");								

}
									
									
							
else 
{
	$stocks = mysql_query("select stk.stockId, stk.lr, prd.productId, ve.company, stk.moc, date_format(stk.disdate,'%d-%m-%Y') as disdate, stk.invoiceNumber, stk.invoiceAmount, stk.vendorId, stk.product, br.brand, cat.category from stock as stk left join categories as cat on cat.id=stk.category left join brands br on br.id=stk.brand left join vendors as ve on ve.vendorId=stk.vendorId left join products as prd on prd.StockId=stk.stockId order by stk.stockId DESC"); 


	
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
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Stocks </li>
                            
                        	
						</ul>
                        <a href="excel/stock.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>
                      
                        <!-- /.breadcrumb -->

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
								<!-- PAGE CONTENT BEGINS -->

								<div class="row">
									<div class="col-xs-12">
                                    
                                 <?PHP 
								 	if(isset($_GET['deleted']) && $_GET['deleted']=="yes")
									{
										echo "<div class='alert alert-success'> Successfully deleted </div>";	
									}
								 ?>
                                    
                                    <form class="form-inline" method="get" action="" autocomplete= "off">
                                  
                      
  <div class="row">              
                                
 <!-- <div class="form-group col-sm-2">
   <span>From Date</span>
    <input type="text" class="form-control date-picker input-sm" id="fromDate" name="fromDate" placeholder="From Date" <?php if(isset($_GET['fromDate'])) { ?> value="<?php echo $_GET['fromDate']; ?>" <?php } ?> />
  </div>
  <div class="form-group col-sm-2">
   <span>To Date</span>
   <input type="text" class="form-control date-picker input-sm" id="toDate" name="toDate" placeholder="To Date" <?php if(isset($_GET['toDate'])) { ?> value="<?php echo $_GET['toDate']; ?>" <?php } ?> />
  </div>
  -->
  <div class="col-sm-2">
                                <span>Invoice No.</span>
    <input type="hidden" id="invid" name="invid" value="<?PHP echo @$_GET['invid']; ?>"   />
     <input type="text" id="invoice" name="invoice" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Invoice No." <?php if(isset($_GET['invoice'])) { ?> value="<?php echo $_GET['invoice']; ?>" <?php } ?> onkeyup="getInvoice(this.value)" />
     
      <ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="invList"></ul>
                    
                                </div>
  
   <div class="form-group col-sm-2">
   <span>Vendor</span>
    <input type="hidden" id="vid" name="vid"   />
     <input type="text" id="vendor" name="vendor" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Vendor" onkeyup="getVendor(this.value)" <?php if(isset($_GET['vendor'])) { ?> value="<?php echo $_GET['vendor']; ?>" <?php } ?>  />
           <ul class="typeahead dropdown-menu" style="left: 10px; display: none;" id="vendorsList">
                                           </ul>
  </div>
  
  <div class="form-group col-sm-2">
   <span><br /></span>
    <input type="submit" class="btn btn-sm btn-success"  name="stockSearch" value="Search" />
  </div>
  
  

  </div>  <div class="space"></div>
 
         
                      <!--<div class="row">  
                      <div class="form-group col-md-offset-2 col-md-1">
                      <span><br /></span>
  By Product
  </div>                                    
                               
                                <div class="col-sm-2">
                                <span>Category</span>
                                <select id="category1" name="category" class="form-control col-xs-4 col-sm-12 input-sm" onchange="getBrands('1')">
                                            <option value="0">Select Category</option>
                                            <?php
										//	while($category = mysql_fetch_array($categories))
										//	{
	 ?>   <option value="<?php // echo $category['id']; ?>"  <?php// if(isset($_GET['category']) && $_GET['category']==$category['id']) { ?> selected="selected"  <?php //} ?>><?php //echo $category['category']; ?></option> <?php	
										//	}
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
                                
                                </div>-->
                                
                                
                                           
  
  

                                
                              
                                 
                               
  
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
														<th>Invoice</th>
                                                        <th>Invoice Amount</th>
                                                      <!--  <th>LR No.</th>
                                                        <th>MOC No.</th>
                                                        -->
                                                        <th> Category</th>
                                                         <th> Brand</th>
                                                        <th> Product</th>
                                                        <th> Product Model</th>
                                                        <th> UOM</th>
                                                         <th> Product Cost</th>
                                                          <th> Quantity</th>
                                                           <th> Minimum Quantity</th>
                                                        <th> Equipment Number</th>
                                                        
                                                        <th>Vendor Name</th>
                                                         <!-- <th>Added By</th>-->
                                                          <th>View</th>
                                                       
														
													</tr>
												</thead>

												<tbody>
													
														
																	

  <?php  // to download the data in excel sheet we write this code 
    $list[] = array('S. No', 'Date', 'Invoice', 'Amount', 'LR No.', 'MOC No.', 'Company', );

/*  
	if(mysql_num_rows($vendors)>0)
  {
	   $i = $start+1;
	    	 

  while($vendor = mysql_fetch_array($vendors))
  { 
	

$stocks=mysql_query("select stkprd.productId, prd.product product, stk.vendorId from stock as stk left join stock_products as stkprd on stk.stockId=stkprd.stockId  join products as prd on prd.productId=stkprd.productId where stk.stockId=".$vendor['stockId']);	   

$products='';
if(mysql_num_rows($stocks)>0)
{
	while($prdct_stock = mysql_fetch_object($stocks) )	
	{
		$products.="<li>".$prdct_stock->product."</li>";
	}
}
$products=trim($products,",");

?>								                                
                                            
                                        
<tr>
														<td class="center">
															<?php echo $i; ?>
														</td>
                                                         <td><?php $date = explode('-',$vendor['disdate']);    
														          echo $date =  $date[2].'-'.$date[1].'-'.$date[0];      
														       ?></td>
														<td><?php echo $vendor['invoiceNumber'] ?></td>	
                                                        <td><?php echo $vendor['invoiceAmount'] ?></td>
                                                        <td><?PHP echo $products; ?></td>
                                                        <td><?php echo $vendor['company'] ?></td>
                                                       <!-- <td><?php #echo $vendor['firstName'] ?></td>-->
                                                        <td>
<!-- <a href="<?php echo '#'.$vendor['stockId'].'modal-form'; ?>" role="button" class="blue" data-toggle="modal"> view </a>-->
 
<a href="stock-view.php?stkid=<?PHP echo $vendor['stockId']; ?>" class="btn btn-warning btn-sm"><i class="ace-icon fa fa-eye icon-only"></i></a>
 
                                                      <div id="<?php echo $vendor['stockId'].'modal-form'; ?>" class="modal in" tabindex="-1" aria-hidden="false" style="display: none;"><div class="modal-backdrop  in"></div>
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">×</button>
												<h4 class="blue bigger"><?php echo $vendor['lr'] ?> Stocks</h4>
											</div>

											<div class="modal-body">
												<div class="row">
													<div class="col-xs-12 col-sm-12">
														<div class="space"></div>

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
                                                        <th>Model no.</th>
                                                        <th>Quantity</th>
                                                       
													</tr>
												</thead>

												<tbody>
                                                        
                                                        <?php if(mysql_num_rows($stocks)>0)
														{  $sno = 1;
														 while($stock = mysql_fetch_array($stocks)) { ?>
                                                        <tr><td><?php echo $sno; ?></td>
                                                             <td><?php echo $stock['category']; ?></td>
                                                             <td><?php echo $stock['brand']; ?></td>
                                                             <td><?php echo $stock['product']; ?></td>
                                                             <td><?php echo $stock['code']; ?></td>
                                                             <td><?php echo $stock['quantity']; ?></td>
                                                                </tr>
                                                        <?php $sno++; } } ?>
															
                                                            </tbody>
                                                            </table>
                                                            </div>
													</div>

													
												</div>
											</div>

											<div class="modal-footer">
												<button class="btn btn-sm" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Cancel
												</button>

												
											</div>
										</div>
									</div>
								</div>
                                                    
                                                        
									 
                                                        </td>
                                                  	</tr>
                                                    
                                                    <?php 
													// to download the data in excel sheet we write this code
$rowlist[] = $i;
$rowlist[] = $date;
$rowlist[] = $vendor['invoiceNumber'];
$rowlist[] = $vendor['invoiceAmount'];
$rowlist[] = $vendor['lr'];
$rowlist[] = $vendor['moc'];
$rowlist[] = $vendor['company'];
$rowlist[] = $vendor['firstName'] ;
$rowlist[] = $status;
 $list[] = $rowlist;
 unset($rowlist);

													
	$i++; } } 
	else
		{
		?><tr><td colspan="10">No Data found.</td></tr> <?php	
		}
*/	


if(mysql_num_rows($stocks)>0)
{
	$slno=0;
	while($stock = mysql_fetch_object($stocks))	
	{
		$slno++;
		
		//get the models 
		
		$models_priices_equips = mysql_query("select * from product_model where ProductId=".$stock->productId);

		while($model_priice_equip = mysql_fetch_object($models_priices_equips))
		{
			
			
			
			
		?>
        		<tr>
                	<td> <?PHP echo $slno; ?></td>
                    <td><?PHP echo $stock->disdate; ?> </td>
                    <td><?PHP echo $stock->invoiceNumber;?> </td>
                    
                    <td><?PHP echo $stock->invoiceAmount;?> </td>
                    <td><?PHP echo $stock->category;?> </td>
                    <td><?PHP echo $stock->brand;?> </td>
                    
                    <td><?PHP echo $stock->product;?> </td>
                     <td><?PHP echo $model_priice_equip->ModelNo;?> </td>
                       <td><?PHP echo $model_priice_equip->UOM;?> </td>
                      <td><?PHP echo $model_priice_equip->Cost;?> </td>
                        <td><?PHP echo $model_priice_equip->Quantity;?> </td>
                        <td><?PHP echo $model_priice_equip->Minquantity;?> </td>
                          <td><?PHP echo $model_priice_equip->EquipmentNo;?> </td>
                    <td><?PHP echo $stock->company; ?> </td>
                    
                    <td>
                    	
                        <a href="edit_stock.php?stockid=<?PHP echo $stock->stockId;?>&model=<?PHP echo $model_priice_equip->ModelId;?>&product=<?PHP echo $stock->productId;?>"><i class="ace-icon fa fa-pencil icon-only"></i></a>
                        
                        &nbsp;&nbsp;
                        <!--<a href="stock.php?delete=yes&stockid=<?PHP echo $stock->stockId;?>&model=<?PHP echo $model_priice_equip->ModelId;?>&product=<?PHP echo $stock->productId;?>" class='text-danger delete_stock' ><i class="ace-icon fa fa-times icon-only"></i></a>-->
                        
                        <a href="javascript:void(0)" class='text-danger delete_stock' stockid="<?PHP echo $stock->stockId;?>" modelid="<?PHP echo $model_priice_equip->ModelId;?>" productid="<?PHP echo $stock->productId;?>"><i class="ace-icon fa fa-times icon-only"></i></a>
                    
                    
                     </td>
                </tr>
        <?PHP
		
$rowlist[] = $slno;
$rowlist[] = $date;
$rowlist[] = $stock->invoiceNumber;
$rowlist[] = $stock->invoiceAmount;
$rowlist[] = $stock->lr;
$rowlist[] = $stock->moc;
$rowlist[] = $stock->company;
//$rowlist[] = $vendor['firstName'] ;
//$rowlist[] = $status;
 $list[] = $rowlist;
 unset($rowlist);
		
		
		}
	}
	
}
else
{
	echo "<tr> <td colspan=13> <div class='alert alert-danger'> No Stock available in store </div> </td></tr>";
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
                                            <?php
                                            $fp = fopen('excel/stock.csv', 'w');

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
		
		function getInvoice(val)
		{
			
			document.getElementById("invList").style.display = 'block';
				$.ajax({url: "ajax/getInvList.php?val="+val, success: function(result){
		$("#invList").html(result);
    }});	
			
		}
		
		
		function selectInvoice(id,firstName)
		{
			document.getElementById("invList").style.display = 'none';
			document.getElementById("invid").value = id;
			document.getElementById("invoice").value = firstName;
		}	
		
		
		$(document).on('click','#outclick,#breadcrumbs',function(){
				$('#invList,#vendorsList').css('display','none');
				
				
				});
		
		
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
        
        <script>
		
		$(document).ready(function() 
		{
			
			$(".delete_stock").on('click',function() 
			{ 
				var stockid = $(this).attr('stockid');
					stockid = $.trim(stockid);
					
				var modelid= $(this).attr('modelid');
					modelid = $.trim(modelid);
				
				var productid = $(this).attr('productid');
					productid = $.trim(productid);
					
			
				if(confirm('Do you want to delete'))
				window.location.href="stock.php?delete=yes&stockid="+stockid+"&model="+modelid+"&product="+productid;
			
			});	
			
		 });
		
		</script>
        
	</body>
</html>
