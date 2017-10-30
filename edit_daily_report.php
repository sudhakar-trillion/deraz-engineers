<?php  include("includes/header.php");  ?>

<?PHP

if(isset($_POST['edit_report']))
{
//	echo "<pre>";
extract($_POST);
//print_r($_POST); 
//exit;
	
	//update the daily report table
	//convert date form to Y-m-d
	
	$futr = date_create($futureDate);
	$futur = date_format($futr,"Y-m-d");
	
	//echo $date."<BR>";
	$reportdate = date_create($date);
	$date=date_format($reportdate,'Y-m-d');
	
//	echo $date; exit;
	
	
	mysql_query("update daily_reports set company=".$company.", reportDate='".$date."',contactPerson='".$contactPerson."', designation='".$designation."', phone='".$phone."', email='".$email."', clientStatus='".$clientStatus."', leadType='".$leadType."', leadStatus='".$leadStatus."', futureDate='".$futur."', remarks='".$remarks."',paymentType='".$paymentType."', lastupdated_on='".date('Y-m-d H:i:s')."' where reportId=".$_GET['rid']);
	
	
	//update the daily_reports_data with the necessary data
	
	$revisionid = $revisionid;
	$drdids = $drdids;
	
	$categories_cnt = sizeof($categories);

	for($i=0;$i<$categories_cnt;$i++)
	{
		$getprice_qry = mysql_query("select price from product_price where ProductId='".$products[$i]."' and ModelNo='".$models[$i]."'");
		$getPrice=mysql_fetch_object($getprice_qry);	

		mysql_query("update daily_reports_data set categoryId=".$categories[$i].", brandId=".$brands[$i].",productId=".$products[$i].",modelId=".$models[$i].",price='".$getPrice->price."',quantity='". $quantity[$i] ."'  where revisionId=".$revisionid." and id=".$drdids[$i]);
		
	}
	
 header("location: edit_daily_report.php?rid=".$_GET['rid']."&update=1");	

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
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="sa_daily_reports.php">Daily Reports</a>
							</li>

							<li class="active">Edit Report</li>
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
								
<?PHP

//get the content of a daily report

//here  we have a relation between the tables as follows
//first when we add a daily report it will add into the table called daily_reports and the inserted id will become the foreign for the table called daily_reports_revision what ever the revisonid generated will become the foreign key for the table called daily_reports_data

//so now letes prepare the query based on the relation ship

//lets first bring the data apart from the product details

$qry = mysql_query("select date_format(dr.reportDate,'%d-%m-%Y') as reportDate,dr.company, dr.reportId,  dr.contactPerson, dr.designation, dr.phone, dr.email, dr.clientStatus, dr.leadType, dr.leadStatus, date_format(dr.futureDate,'%d-%m-%Y') as futureDate, dr.remarks, dr.paymentType from daily_reports as dr where dr.reportId=".$_GET['rid']);

$reportdata = mysql_fetch_object($qry);
$categories = mysql_query("select id, category from categories order by category");
		
//$companies = mysql_query("select customerId, company from customers where addedBy = '". $_SESSION['id'] ."' order by company");

$companies = mysql_query("select customerId, company from customers where company!=''  group by customerId,company order by company ASC");

?>
	<?PHP
if(isset($_GET['update']) && $_GET['update']=="1")
{
?>								
<div class="alert alert-success">Daily report updated successfully</div>
<?PHP
}
?>							

   <form class="form-horizontal" role="form" action="" method="post" id="validation-form">

								<div class="row">
									<div class="col-xs-12">
										
										<div class="table-header">
											Edit Report
										</div>

                                       <div class="space-6"></div>
                                
                                         
									<!-- #section:elements.form -->
									 <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="date">Report Date </label>

										<div class="col-sm-9">
                                        	<input type="text" id="date" name="date" placeholder="Report Date" class="col-xs-10 col-sm-12 date-picker" value="<?PHP echo $reportdata->reportDate;?>"  />
                                        </div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="company"> Company Name </label>

										<div class="col-sm-9">
											<select id="company" name="company" class="col-xs-10 col-sm-12" onChange="getCompanyDetails(this.value)">
                                            <option value="">Select Company</option>
                                            <?php
											while($company = mysql_fetch_array($companies))
												{	
													if(trim( $company['company'])!='')
													{
													?> 
													<option value="<?php echo $company['customerId']; ?>" <?PHP if($reportdata->company==$company['customerId'] ) { echo 'selected="selected"'; }?> ><?php echo $company['company'] ?></option>
													<?php 
													}
												} 
												?>
                                            </select>
										</div>
									</div>
                                    
                                    <div id="div1">
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="contactPerson"> Contact Person </label>

										<div class="col-sm-9">
											<input type="text" id="contactPerson" name="contactPerson" placeholder="Contact Person" class="col-xs-10 col-sm-12" value="<?PHP echo $reportdata->contactPerson;?>" />
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="designation"> Designation </label>

										<div class="col-sm-9">
											<input type="text" id="designation" name="designation" placeholder="Designation" class="col-xs-10 col-sm-12"  value="<?PHP echo $reportdata->designation;?>" />
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="email"> Email </label>

										<div class="col-sm-9">
											<input type="text" id="email" name="email" placeholder="Email" class="col-xs-10 col-sm-12" value="<?PHP echo $reportdata->email;?>"   />
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="phone"> Phone </label>

										<div class="col-sm-9">
											<input type="text" id="phone" name="phone" placeholder="Phone" class="col-xs-10 col-sm-12" value="<?PHP echo $reportdata->phone;?>"   />
										</div>
									</div>
                                    </div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="clientStatus"> Client Status </label>

										<div class="col-sm-9">
											<select id="clientStatus" name="clientStatus" class="col-xs-10 col-sm-12">
                                            <option value="1" <?PHP if($reportdata->clientStatus=="1") { echo 'selected="selected"';}?> >Existing</option>
                                            <option value="2" <?PHP if($reportdata->clientStatus=="2") { echo 'selected="selected"';}?>>New</option>
                                            </select>
                                            
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="leadType"> Lead Type </label>

										<div class="col-sm-9">
											<select id="leadType" name="leadType" class="col-xs-10 col-sm-12" >
                                            <option value="0">Select Lead Type</option>
                                             <option value="4" <?PHP if($reportdata->leadType=="4") { echo 'selected="selected"';}?>>Offer Followup</option>
                                            <option value="5" <?PHP if($reportdata->leadType=="5") { echo 'selected="selected"';}?>>Order Followup</option>
                                            <option value="6" <?PHP if($reportdata->leadType=="6") { echo 'selected="selected"';}?>>Payment Followup</option>
                                            <option value="7" <?PHP if($reportdata->leadType=="7") { echo 'selected="selected"';}?>> General Visit</option>
                                            <option value="1" <?PHP if($reportdata->leadType=="1") { echo 'selected="selected"';}?>>General Enquiry</option>
                                            <option value="2" <?PHP if($reportdata->leadType=="2") { echo 'selected="selected"';}?>>Customer Call</option>
                                            <option value="3" <?PHP if($reportdata->leadType=="3") { echo 'selected="selected"';}?>>Reference</option>
                                            </select>
                                            
										</div>
                                        <!--<div class="col-sm-9">
											<select id="leadType" name="leadType" class="col-xs-10 col-sm-12">
                                            <option value="">Select Lead Type</option>
                                            <option value="1">General Enquiry</option>
                                            <option value="2">Customer Call</option>
                                            <option value="3">Reference</option>
                                            </select>
                                            
										</div>-->
									</div>
                                    
                                    <div id="enquiry" class="form-group" style="display:none">
										<label class="col-sm-3 control-label no-padding-right" for="enquiryNumber"> Enquiry Number </label>
                                     <div class="col-sm-9">   
                                   
                                     <input type="text" name="enquiryNumber" id="enquiryNumber" class="col-xs-10 col-sm-12" placeholder="Enquiry Number"/>
                                     </div></div>
                                     
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="leadStatus"> Lead Status </label>

										<div class="col-sm-9">
											<select id="leadStatus" name="leadStatus" class="col-xs-10 col-sm-12">
                                            <option value="">Select Lead Status</option>
                                            <option value="Offer to be generated" <?PHP if($reportdata->leadStatus=="Offer to be generated") { echo 'selected="selected"';}?>>Offer to be generated</option>
                                            <option value="Follow up" <?PHP if($reportdata->leadStatus=="Follow up") { echo 'selected="selected"';}?>>Follow up</option>
                                            <!--<option value="Offer to be submitted">Offer to be submitted</option>
                                            <option value="Offer to be accepted">Offer to be accepted</option>
                                            <option value="Offer to be resubmitted">Offer to be resubmitted</option>
                                            <option value="Offer resubmission">Offer resubmission</option>
                                            <option value="PO to be collected">PO to be collected</option>
                                            <option value="Proforma invoice submited">Proforma invoice submited</option>-->
                                           </select>
                                            
										</div>
									</div>
                                    
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="futureDate"> Followup Date </label>

										<div class="col-sm-9">
											<input type="text" id="futureDate" name="futureDate" placeholder="Followup Date" class="col-xs-10 col-sm-12 date-picker" value="<?PHP echo $reportdata->futureDate;?>"  />
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="remarks"> Remarks </label>

										<div class="col-sm-9">
											<textarea id="remarks" name="remarks" placeholder="Remarks" class="col-xs-10 col-sm-12"><?PHP echo $reportdata->remarks;?></textarea>
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="paymentType"> Payment Type </label>

										<div class="col-sm-9">
											<select id="paymentType" name="paymentType" class="col-xs-10 col-sm-12">
                                            <option value="">Select Payment Type</option>
                                            <option value="1" <?PHP if($reportdata->paymentType=="1"){ echo 'selected="selected"';}?> >Proforma</option>
                                            <option value="2" <?PHP if($reportdata->paymentType=="2"){ echo 'selected="selected"';}?>>Through Bank</option>
                                            <option value="3" <?PHP if($reportdata->paymentType=="3"){ echo 'selected="selected"';}?>>Direct Payment</option>
                                            <option value="4" <?PHP if($reportdata->paymentType=="4"){ echo 'selected="selected"';}?>>Against Delivery</option>
                                            </select>
                                            
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="po"> Offer to be Generated  </label>

										<div class="col-sm-3">
                                        
                                        <label class="col-sm-3 control-label no-padding-right" for="po">
											<input type="checkbox" id="po" name="po" value="1" onclick="openList()" checked="checked" />
                                            Yes  </label>
                                            
										</div>
									</div>
                                    
                                    </div>
								</div>
					
                      <!-- open --> 
                      
                      <div id="formContainer" style="display: block;" class="row">
                     
                     <?PHP
					 //get the numbber of product has been added into this report by the user
					 
					 //as i explained above products will be stored in a table called daily_reports_data and ihe foreign key here is revisionId which is the primary key in the table called daily_reports_revision and for this table( daily_reports_revision )reportId which is the primary key for the daily_reports table is the foreign key
					 
					 $prdcts_qry = mysql_query("select drd.id, drd.revisionId, drd.categoryId, drd.brandId, drd.productId, drd.modelId, drd.quantity from daily_reports_data as drd left join daily_reports_revision as drv on drv.revisionId=drd.revisionId where drv.reportId=".$_GET['rid']);
					 
				 
					 ?> 
                      	<div class="col-xs-12 form-group">    
                            <?PHP
							$prdcnt=0;
								while($data = mysql_fetch_object($prdcts_qry))
									{
										$prdcnt++;	
										$category ='';
										$categories = mysql_query("select id, category from categories order by category");
										$companies = mysql_query("select customerId, company from customers where addedBy = '". $_SESSION['id'] ."' order by company");

							?>    
                                <div class="col-sm-3">
                                <input type="hidden" name="revisionid" value="<?PHP echo $data->revisionId;?>">
                                </div> 
                                <div class="col-sm-2">
                                <span>Category</span>
                                
                                <select id="category<?PHP echo $prdcnt; ?>" name="categories[]" class="col-xs-10 col-sm-12" onchange="getBrands('<?PHP echo $prdcnt; ?>')">
                                            <option value="0">Select Category</option>
                                                <?php
									
									while($category = mysql_fetch_array($categories))
									{ 
									?>
                                            <option value="<?php echo $category['id']; ?>" <?PHP if($data->categoryId==$category['id']){ echo ' selected';} ?>><?php echo $category['category']; ?></option>
                                            <?php  }
                                    ?>                                          </select>
                                </div> 
                                 <div class="col-sm-2">
                                <span>Brand</span>
                                <select id="brand<?PHP echo $prdcnt; ?>" name="brands[]" class="col-xs-10 col-sm-12" onchange="getProducts('<?PHP echo $prdcnt; ?>')">
                                <?PHP
								$brands = mysql_query("select brands.id, brands.brand from category_brands left join brands on category_brands.brandId = brands.id where category_brands.categoryId = '".trim($data->categoryId)."' order by brands.brand");
								?>
                                            <option value="0">Select Brand</option>
                                           <?PHP
                                            while($brand = mysql_fetch_array($brands))
											{	
											?> 
                                            	<option value="<?php echo $brand['id'] ?>" <?PHP if($data->brandId==$brand['id']){ echo 'selected="selected"';} ?>><?php echo $brand['brand'] ?></option>
											<?php 
											} 	
											?>
                                             </select>
                                </div> 
                                 <div class="col-sm-2">
                                <span>Product</span>
                                <select name="products[]" id="product<?PHP echo $prdcnt; ?>" class="col-xs-10 col-sm-12" onchange="getModels('<?PHP echo $prdcnt; ?>')">
                                            <option value="0">Select Product</option>
                                             <?PHP
											$products = mysql_query("select productId, product from products where category = '".trim($data->categoryId)."' and brand = '". trim($data->brandId) ."' order by productId");
											
											
											while($product = mysql_fetch_array($products))
											{	
											?> 
                                            	<option value="<?php echo $product['productId'] ?>" <?PHP if($data->productId==$product['productId']){ echo 'selected="selected"'; } ?> ><?php echo $product['product']; ?></option>
											<?php 
											} 
											?>
                                            </select>
                                </div>
                                
                                <div class="col-sm-2">
                                <span>Model No</span>
             <select name="models[]" id="model<?PHP echo $prdcnt; ?>" class="col-xs-10 col-sm-12">
                                            <option value="0">Select Model</option>
                                            
                                             <?PHP
											$products = mysql_query("SELECT ModelId,ModelNo FROM product_model WHERE ProductId=".$data->productId);
											
											while($product = mysql_fetch_array($products))
											{	
											?> 
                                            
											<option value="<?php echo $product['ModelId']; ?>" <?PHP if($data->modelId==$product['ModelId'] ) { echo ' selected="selected"'; } ?>><?php echo $product['ModelNo']; ?></option><?php 
											} 
											?>
                                            </select>
                                </div> 
                                
                                <div id="productdetails1"> 
                                <div class="col-sm-1">
                                <span>quantity</span>
                                <input type="text" name="quantity[]" size="10" id="quantity<?PHP echo $prdcnt; ?>" value="<?PHP echo $data->quantity;?>" >
                                <input type="hidden" value="<?PHP echo $data->id; ?>" name="drdids[]" >
                                
                                </div> 
                                
                                </div>
                                
							<?PHP 
									}
							?>                                 
                                </div>
                      
                       </div>           
                                    
                                            
                                            <!-- close -->    
                                            
                                             <div class="col-xs-12 form-group">           
                                    <div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
                                        
                                        <div class="col-sm-2">
											<button class="btn btn-sm btn-info" type="submit" name="edit_report">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Edit
											</button>
                                            </div>

<!--											
<div class="col-sm-2" id="addRowSpan" style="display:none;">
<button class="btn btn-sm btn-success" type="button" id="addRow" name="addRow" onclick="displayFields(this.value)" value="1">
												<i class="ace-icon fa fa-plus bigger-110"></i>
												Add More
											</button>
                                            </div>
                                          
                                            
                                        <div class="col-sm-2" id="removeRowSpan" style="display:none;">
                    <button class="btn btn-sm btn-danger" type="button" id="removeRow" onclick="removeFields(this.value)" value="1">
												<i class="ace-icon fa fa-minus bigger-110"></i>
												Remove
											</button>
                                            </div>
                                          
 -->                                           
                                            
										</div>
									</div>
                                    </div>

                                    
                                   
                                    
										
									

								 </form>
                                
                                
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
 
 
 function addEnquiry(val)
 {
	 
	 if(val==='4')
	 {
	
		 document.getElementById('enquiry').style.display = 'block';	
	 }
	 else
	 {   
		 document.getElementById('enquiry').style.display = 'none';	 
	 }
	
	 
 }
 
 
 function getCompanyDetails(cid)
 {

	  $.ajax({url: "ajax/getCompanyDetails.php?cid="+cid, success: function(result){
        $("#div1").html(result);
    }});
	 
 }
 
 function openList()
 {
	
	if(document.getElementById('po').checked)
	{
		document.getElementById('formContainer').style.display = 'block';	
		document.getElementById('addRowSpan').style.display = 'block';	
		document.getElementById('removeRowSpan').style.display = 'block';	
	}
	else
	{
	   document.getElementById('formContainer').style.display = 'none';	
	   document.getElementById('addRowSpan').style.display = 'none';	
		document.getElementById('removeRowSpan').style.display = 'none';	
	}
 }
 
 function getProducts(id)
 {  
 $.ajax({url: "ajax/getCompanyDetails.php?cid="+cid, success: function(result){
        $("#div1").html(result);
    }});
	 
 }
 
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
		/* $.ajax({url: "ajax/getModelsList.php?bid="+brand+"&cid="+category+"&product="+product, success: function(result){
        $("#model"+rid).html(result);
		*/
		
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
					todayHighlight: true,
					format: 'dd-mm-yyyy'
					
				})
				
				// validateion
				
				$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					ignore: "",
					rules: {
						date: {
							required: true,
							
						},
						company: {
							required: true,
							
						},
						contactPerson: {
							required: true,
							
						},
						designation: {
							required: true,  
							
						},
						email: {
							required: true,
							email:true
							
						},
						phone: {
							required: true,
							
						},
						leadType: {
							required: true,
							
						},
						leadStatus: {
							required: true,
							
						},
						paymentType: {
							required: true,
							
						}
					},
			
					messages: {
						email: {
							required: "Please provide a valid email.",
							email: "Please provide a valid email."
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
