<?php  include("includes/header.php"); 


// update the form

if( isset($_POST['edit_enquiry']) )
{
	
extract($_POST);

	
	
//	$ReportId;

if(isset($_POST['po']))
{
$offer = 1;
}
else { $offer = 0; }

//update the  daily_report table

$rep_date = date_create($date);
$rep_date = date_format($rep_date,'Y-m-d');



$dailyreport_update_qry = mysql_query("update daily_reports set  offer=$offer,enquiryNumber ='".$enquiryNumber."', company=$company, contactPerson='".$contactPerson."',designation='".$designation."',email='".$email."', phone='".$phone."',clientStatus=$clientStatus, paymentType=$paymentType, leadStatus='".$leadStatus."',remarks='".$remarks."'   where reportId=".$ReportId);


//update enquiry table

//`enqDate`, `enquiryNumber`,`reportId`,`name`, `company`, `companyId`,  `phone`, `email`, `source`, `addedBy`,`dateTime`
$enq_date = $rep_date;
$enquiry_update_qry = mysql_query("update enquiries set enqDate='".$enq_date."',enquiryNumber='".$enquiryNumber."',name='".$contactPerson."', company='".$company."', phone='".$phone."',email='".$email."',source='".$source."'  where enquiryId=".$_GET['eid']." and  reportId=".$ReportId);	

//update the enquiry_assign table assignedTo 

$enquiry_assign_qry = mysql_query("update enquiry_assign set assignedTo='".$employee."' where enquiryId=".$_GET['eid']);

//update the product_price, daily_reports_data, enquiry_products

//get the revisionid of this enquiry

$revisId_qry=mysql_query("select revisionId from daily_reports_revision where reportId=".$ReportId);

$revisionide= mysql_fetch_object($revisId_qry);

$enqassignid = mysql_query("select eaId from enquiry_assign where enquiryId=".$_GET['eid']);
$enqassign = mysql_fetch_object($enqassignid);

 $count = count($_POST['categories']);
		
		
	  
				  for($i=0;$i<$count;$i++)
				  {
					$getprice_qry = mysql_query("select price from product_price where ProductId='".$_POST['products'][$i]."' and ModelNo='".$_POST['models'][$i]."'");
					$getPrice=mysql_fetch_object($getprice_qry);


				// update the daily_reports_data
				
				mysql_query("update daily_reports_data set categoryId=".$categories[$i].", brandId=".$brands[$i].", productId=".$products[$i].",modelId=".$models[$i].", price=".$getPrice->price.", quantity='".$quantity[$i]."' where revisionId=".$revisionide->revisionId." and id=".$drdid[$i]);	
				

				//update the enquiry_products
				
				mysql_query("update enquiry_products set codeId=".$models[$i].", productId=".$products[$i].", brandId=".$brands[$i].", categoryId=".$categories[$i].",quantity=".$quantity[$i]." where epId=".$epid[$i]." and enquiryId=".$_GET['eid'] );
	
	}
  header("location: edit_enquiry.php?eid=".$_GET['eid']."&update=1");	

}

//get the required data to fill up the form

$qry = mysql_query("select dr.reportId as ReportId, date_format(enq.enqDate,'%d-%m-%Y') as enquiryDate,enq.company Company, dr.contactPerson ,dr.designation, dr.email Email, dr.phone Phone, dr.clientStatus, enq.source, enq.enquiryNumber as Enquirynumber, enqAssign.assignedTo Employee, dr.leadStatus Leadstatus, date_format(futureDate,'%d-%m-%Y') as FollowupDate, dr.remarks Remarks, dr.paymentType as Paymenttype, emp.employeeId from  daily_reports as dr left join enquiries as enq on enq.reportId=dr.reportId left join enquiry_assign as enqAssign on enqAssign.enquiryId=enq.enquiryId left join employees as emp on trim(emp.firstName)=trim(enqAssign.assignedTo) where enq.enquiryId=".$_GET['eid']);



 $enqdata =mysql_fetch_object($qry);

//query to get the products in this enquiry


$qrey = mysql_query("select drd.categoryId as Category, drd.brandId as BrandId, drd.productId as Product, drd.modelId as ModelId,prd.product as productName,br.brand as BrandName, pm.ModelNo, drd.id as drdid, drd.quantity Quantity from daily_reports_data drd left join daily_reports_revision as drv on drv.revisionId=drd.revisionId left join product_model as pm on pm.ModelId= drd.modelId left join products as prd on prd.productId= drd.productId left join brands as br on br.id=brandId  where drv.reportId=".$enqdata->ReportId);

$companies = mysql_query("select customerId, company from customers where company!=''  order by company ASC");
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
								<a href="enquiries.php">Enquiries</a>
							</li>

							<li class="active">Edit Enquiry</li>
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
							<div class="col-xs-12" id="outclick">
								<!-- PAGE CONTENT BEGINS -->
<?PHP
if(isset($_GET['update']) && $_GET['update']=="1")
{
?>								
<div class="alert alert-success">Enquiry updated successfully</div>
<?PHP
}
?>						

   <form class="form-horizontal" role="form" action="" method="post" id="validation-form">

								<div class="row">
									<div class="col-xs-12">
										
										<div class="table-header">
											Edit Enquiry
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										
											
                                           <?php
										   
	   if(isset($_GET['add']))
{ $alertMsg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Report has been added!</div>'; }
else if(isset($_GET['error']))
{ $alertMsg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }
else if(isset($_GET['authorizationRequired'])) 
{ $alertMsg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Authorization required.</div>'; }

  if(isset($alertMsg)) { echo $alertMsg; }
										   ?> 
                                            
                                           
                                            
                              
                                            
                                            <div class="space-6"></div>
                                
                                         
									<!-- #section:elements.form -->
									 <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="date">Enquiry Date </label>

										<div class="col-sm-9">
                                        <input type="hidden" name="ReportId" value="<?PHP echo $enqdata->ReportId;?>" />
											<input type="text" id="date" name="date" placeholder="Enquiry Date" class="col-xs-10 col-sm-12 date-picker" value="<?PHP echo $enqdata->enquiryDate; ?>"  />
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="company"> Company Name</label>

										<div class="col-sm-9">
											<select id="company" name="company" class="col-xs-10 col-sm-12" onChange="getCompanyDetails(this.value)">
                                            <option value="0">Select Company</option>
                                            <?php
											while($company = mysql_fetch_array($companies))
											{	
											?>
                                             <option value="<?php echo $company['customerId']; ?>" <?PHP if( $enqdata->Company == $company['customerId']) { echo "selected"; }?>><?php echo $company['company'] ?></option><?php } ?>
                                            </select>
										</div>
									</div>
                                    
                                    <div id="div1">
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="contactPerson"> Contact Person </label>

										<div class="col-sm-9">
			<input type="text" id="contactPerson" name="contactPerson" placeholder="Contact Person" class="col-xs-10 col-sm-12" value="<?PHP echo $enqdata->contactPerson; ?>"  />
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="designation"> Designation </label>

										<div class="col-sm-9">
											<input type="text" id="designation" name="designation" placeholder="Designation" class="col-xs-10 col-sm-12" value="<?PHP echo $enqdata->designation; ?>"  />
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="email"> Email </label>

										<div class="col-sm-9">
											<input type="text" id="email" name="email" placeholder="Email" class="col-xs-10 col-sm-12" value="<?PHP echo $enqdata->Email; ?>"  />
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="phone"> Phone </label>

										<div class="col-sm-9">
											<input type="text" id="phone" name="phone" placeholder="Phone" class="col-xs-10 col-sm-12" value="<?PHP echo $enqdata->Phone; ?>"  />
										</div>
									</div>
                                    </div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="clientStatus"> Client Status </label>

										<div class="col-sm-9">
											<select id="clientStatus" name="clientStatus" class="col-xs-10 col-sm-12">
                                            <option value="1" <?PHP if($enqdata->clientStatus=="1"){ echo 'selected'; }?>>Existing</option>
                                            <option value="2"<?PHP if($enqdata->clientStatus=="2"){ echo 'selected'; }?>>New</option>
                                            </select>
                                            
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="source"> Source</label>

						
                        				<div class="col-sm-9">
											<select id="source" name="source" class="col-xs-10 col-sm-12">
                                            <option value="0">Select Source</option>
                                            <option value="Email" <?PHP if($enqdata->source=="Email"){ echo 'selected'; }?>>Email</option>
                                            <option value="Tele Call" <?PHP if($enqdata->source=="Tele Call"){ echo 'selected'; }?>>Tele Call</option>
                                            <option value="Person Visit" <?PHP if($enqdata->source=="Person Visit"){ echo 'selected'; }?>>Person Visit</option>
                                            </select>
                                            
										</div>
									</div>
                                    
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="enquiryNumber"> Enquiry Number </label>
                                     <div class="col-sm-9">   
                                   
                                     <input type="text" name="enquiryNumber" id="enquiryNumber" class="col-xs-10 col-sm-12" placeholder="Enquiry Number" value="<?PHP echo $enqdata->Enquirynumber; ?>"/>
                                     </div></div>
                                     
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="employee"> Employee</label>

						
                        				<div class="col-sm-9">
     <input type="hidden" id="eid" name="eid" value="<?PHP echo $enqdata->employeeId;?>" />
     <input type="text" id="employee" name="employee" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Employee" onkeyup="getEmployee(this.value)"   value="<?PHP echo $enqdata->Employee;?>" />
           <ul class="typeahead dropdown-menu" style="top: 28px; left: 0px; display: none; height:100px; width:400px; overflow:auto; margin:0px; padding:0px; border:0px;" id="employeesList">
                                           </ul>
                                           
                                </div></div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="leadStatus"> Lead Status </label>

										<div class="col-sm-9">
											<select id="leadStatus" name="leadStatus" class="col-xs-10 col-sm-12">
                                            <option value="0">Select Lead Status</option>
                                            <option value="Offer to be generated" <?PHP if($enqdata->Leadstatus=="Offer to be generated"){ echo 'selected'; }?>>Offer to be generated</option>
                                            <option value="Follow up" <?PHP if($enqdata->Leadstatus=="Follow up"){ echo 'selected'; }?>>Follow up</option>
                                            
                                           </select>
                                            
										</div>
									</div>
                                    
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="futureDate"> Followup Date </label>

										<div class="col-sm-9">
											<input type="text" id="futureDate" name="futureDate" placeholder="Followup Date" class="col-xs-10 col-sm-12 date-picker" value="<?PHP echo $enqdata->FollowupDate; ?>"  />
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="remarks"> Remarks </label>

										<div class="col-sm-9">
											<textarea id="remarks" name="remarks" placeholder="Remarks" class="col-xs-10 col-sm-12"><?PHP echo $enqdata->Remarks;?></textarea>
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="paymentType"> Payment Type </label>

										<div class="col-sm-9">
											<select id="paymentType" name="paymentType" class="col-xs-10 col-sm-12">
                                            <option value="">Select Payment Type</option>
                                            <option value="1" <?PHP if($enqdata->Paymenttype=="1"){ echo 'selected'; }?> >Proforma</option>
                                            <option value="2"  <?PHP if($enqdata->Paymenttype=="2"){ echo 'selected'; }?>>Through Bank</option>
                                            <option value="3"  <?PHP if($enqdata->Paymenttype=="3"){ echo 'selected'; }?>>Direct Payment</option>
                                            <option value="4"  <?PHP if($enqdata->Paymenttype=="4"){ echo 'selected'; }?>>Against Delivery</option>
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

//get epid from the enquiry_products table

$epidqry = mysql_query("select epId from enquiry_products where enquiryId=".$_GET['eid']);

while( $epid = mysql_fetch_object($epidqry))
{
	?>
    <input type="hidden" name="epid[]" value="<?PHP echo $epid->epId;?>" />
    <?PHP	
}



if(mysql_num_rows($qrey)>1)
{
$Model= '';
$Quantity='';

$prdct_cnt=0;


while($prdcts = mysql_fetch_object($qrey))
{
	$rows = mysql_num_rows($qrey);
	
	$prd_id = $prdcts->Product;
	$product = $prdcts->productName;
	
	$BrandId = $prdcts->BrandId; 
	$BrandName = $prdcts->BrandName; 
	
	$Category = $prdcts->Category; 
	$Model= $prdcts->ModelId;
	
	$ModelNo = $prdcts->ModelNo;
	$Quantity= $prdcts->Quantity; 
	$drdid = $prdcts->drdid;
	
	$categories = mysql_query("select id, category from categories order by category");
	$prdct_cnt++;
	
	
/*}
if($true)
{*/
?>
        				
                        <div class="col-xs-12 form-group">    
                                
                                <div class="col-sm-3">
                                
                                </div> 
                                <div class="col-sm-2">
                                <span>Category <?PHP echo $prdct_cnt; ?></span>
                                <input type="hidden" name="drdid[]" value="<?PHP echo $drdid;?>" />
                                <select id="category<?PHP echo $prdct_cnt;?>" name="categories[]" class="col-xs-10 col-sm-12" onchange="getBrands('<?PHP echo $prdct_cnt;?>')">
                                            <option value="0">Select Category </option>
                                                <?php
									
									while($category = mysql_fetch_array($categories))
									{ 
									?>
                                            <option value="<?php echo $category['id']; ?>" <?PHP if(trim($Category)==$category['id']){ echo 'selected="selected"'; }?>><?php echo $category['category']; ?></option>
                                            <?php  }
                                    ?>                                          </select>
                                </div> 
                                 <div class="col-sm-2">
                                <span>Brand</span>
                                
                                <?PHP
                                ///get the brands of the selected category
								
								$brands = mysql_query("select brands.id, brands.brand from category_brands left join brands on category_brands.brandId = brands.id where category_brands.categoryId = '".trim($Category)."' order by brands.brand");

								
                                ?>
                                <select id="brand<?PHP echo $prdct_cnt;?>" name="brands[]" class="col-xs-10 col-sm-12" onchange="getProducts('<?PHP echo $prdct_cnt;?>')">
                                            <option value="0">Select Brand</option>
                                            
                                            <?PHP
                                            while($brand = mysql_fetch_array($brands))
											{	
											?> 
                                            	<option value="<?php echo $brand['id'] ?>" <?PHP if($BrandId==$brand['id']){ echo 'selected="selected"';} ?>><?php echo $brand['brand'] ?></option>
											<?php 
											} 	
											?>
                                            
                                             </select>
                                </div> 
                                 <div class="col-sm-2">
                                <span>Product</span>
                                <select name="products[]" id="product<?PHP echo $prdct_cnt;?>" class="col-xs-10 col-sm-12" onchange="getModels('<?PHP echo $prdct_cnt;?>')">
                                            <option value="0">Select Product</option>
                                            <?PHP
											$products = mysql_query("select productId, product from products where category = '".trim($Category)."' and brand = '". trim($BrandId) ."' order by productId");
											
											
											while($product = mysql_fetch_array($products))
											{	
											?> 
                                            	<option value="<?php echo $product['productId'] ?>" <?PHP if($prd_id==$product['productId']){ echo 'selected="selected"'; } ?> ><?php echo $product['product']; ?></option>
											<?php 
											} 
											?>
                                            

                                            </select>
                                </div>
                                
                                <div class="col-sm-2">
                                <span>Model No</span>
             <select name="models[]" id="model<?PHP echo $prdct_cnt;?>" class="col-xs-10 col-sm-12">
                                            <option value="0">Select Model</option>
                                            <?PHP
											$products = mysql_query("SELECT ModelId,ModelNo FROM product_model WHERE ProductId=".$prd_id);
											
											while($product = mysql_fetch_array($products))
											{	
											?> 
                                            
											<option value="<?php echo $product['ModelId']; ?>" <?PHP if($Model ==$product['ModelId'] ) { echo ' selected="selected"'; } ?>><?php echo $product['ModelNo']; ?></option><?php 
											} 
											?>
                                           
                                            </select>
                                </div> 
                                
                                <div id="productdetails1"> 
                                <div class="col-sm-1">
                                <span>quantity</span>
                                <input type="text" name="quantity[]" size="10" id="quantity<?PHP echo $prdct_cnt;?>" onkeyup="getPrice('1')" value="<?PHP echo $Quantity;?>">
                                 <input type="hidden" name="ids[]" value="<?PHP echo $Model;?>" />
                                </div> 
                                
                                </div>
                                
                                 
                                </div>
                        
                        
                        	                    
<?PHP

	}//while ends here
}// if ends here
else
{
	$prdct_cnt=1;
$prdcts = mysql_fetch_object($qrey);

	$Category ='0';



$prd_id = $prdcts->Product;
	$product = $prdcts->productName;
	
	$BrandId = $prdcts->BrandId; 
	$BrandName = $prdcts->BrandName; 
	
	$Category = $prdcts->Category; 
	$Model= $prdcts->ModelId;
	
	$ModelNo = $prdcts->ModelNo;
	$Quantity= $prdcts->Quantity; 
	$drdid = $prdcts->drdid;
	
	
	$categories = mysql_query("select id, category from categories order by category");
?>
                        
                        
                        <div class="col-xs-12 form-group">    
                                
                                <div class="col-sm-3">
                                
                                </div> 
                                <div class="col-sm-2">
                                <span>Category</span>
                                <input type="hidden" name="drdid[]" value="<?PHP echo $drdid;?>" />
                              <select id="category<?PHP echo $prdct_cnt;?>" name="categories[]" class="col-xs-10 col-sm-12" onchange="getBrands('1')">
                               
                                            <option value="0">Select Category </option>
                                                <?php
									
									while($category = mysql_fetch_array($categories))
									{ 
									?>
                                            <option value="<?php echo $category['id']; ?>" <?PHP if(trim($Category)==$category['id']){ echo 'selected="selected"'; }?>><?php echo $category['category']; ?></option>
                                            <?php  }
                                    ?>                                          </select>
                                </div> 
                                 <div class="col-sm-2">
                                <span>Brand</span>
                                <select id="brand1" name="brands[]" class="col-xs-10 col-sm-12" onchange="getProducts('1')">
                                            <option value="0">Select Brand</option>
                                            <option value="<?PHP echo $BrandId;?>" selected="selected"><?PHP echo $BrandName;?> </option>
                                             </select>
                                </div> 
                                 <div class="col-sm-2">
                                <span>Product</span>
                                <select name="products[]" id="product1" class="col-xs-10 col-sm-12" onchange="getModels('1')">
                                            <option value="0">Select Product</option>
                                            <option value="<?PHP echo $prd_id; ?>" selected="selected"> <?PHP echo $product; ?> </option>
                                            </select>
                                </div>
                                
                                <div class="col-sm-2">
                                <span>Model No</span>
             <select name="models[]" id="model1" class="col-xs-10 col-sm-12">
                                            <option value="0">Select Model</option>
                                            <option value="<?PHP echo $ModelId; ?>" selected="selected"> <?PHP echo $ModelNo; ?> </option>
                                            </select>
                                </div> 
                                
                                <div id="productdetails1"> 
                                <div class="col-sm-1">
                                <span>quantity</span>
                                <input type="text" name="quantity[]" size="10" id="quantity1" onkeyup="getPrice('1')" value="<?PHP echo $Quantity;?>">
                                <input type="hidden" name="ids[]" value="<?PHP echo $Model;?>" />
                                </div> 
                                
                                </div>
                                
                                 
                                </div>
                         <?PHP
						 
						}?>
                            
                                
                               </div>           
                                    
                                            
                                            <!-- close -->    
                                            
                                             <div class="col-xs-12 form-group">           
                                    <div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
                                        
                                        <div class="col-sm-2">
											<button class="btn btn-sm btn-info" type="submit" name="edit_enquiry">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Edit
											</button>
                                            </div>

										<!--	
                                         <div class="col-sm-2" id="addRowSpan" style="display:block;">
                               <button class="btn btn-sm btn-success" type="button" id="addRow" name="addRow" onclick="displayFields(this.value)" value="1">
												<i class="ace-icon fa fa-plus bigger-110"></i>
												Add More
											</button>
                                            </div>
                                          
                                            
                                        <div class="col-sm-2" id="removeRowSpan" style="display:block;">
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
 function getEmployee(val)
		{
			
			document.getElementById("employeesList").style.display = 'block';
				$.ajax({url: "ajax/getEmployeesList.php?val="+val, success: function(result){
				
		$("#employeesList").html(result);
	//	$("#employeesList").css('display','none'); 
    }});	
			
		}
		
		/*if(document.getElementById('name').value.length<1)
	{
	  alert("Enter name");
	  document.getElementById('name').focus();	
	  return false;
	}*/
		function selectEmployee(id,firstName)
		{
			document.getElementById("employeesList").style.display = 'none';
			document.getElementById("eid").value = id;
			document.getElementById("employee").value = firstName;
	
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
		
 		$.ajax({
					url: "ajax/getCompanyDetails.php?cid="+cid, 
					success: function(result)
					{
					$("#div1").html(result);
					}
			});
	 
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
	    $.ajax({
					url: "ajax/getProductsList.php?bid="+brand+"&cid="+category, 
					headers: {'Cache-Control': 'max-age=0' },
					success: function(result)
					{
						$("#product"+rid).html(result);
					}
				});
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
			
			$(document).on('click','#outclick',function(){
				$('#employeesList').css('display','none');
				
				
				
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
