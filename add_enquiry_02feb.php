<?php include("includes/header.php");
		if(isset($_POST['addEnquiry']))
		{

 if($_POST['companyId']==0)
   {
	        $customerNumber = mysql_query("select customerNumber from customers order by customerId desc limit 1");
			$customerNumber = mysql_fetch_array($customerNumber);
			$customerNumber = $customerNumber['customerNumber']+1;


		   mysql_query("insert into customers (`customerNumber`, `company`, `contactPerson`, `email`, `phone`, `status`, `dateTime`, `addedBy`) values ('". $customerNumber ."', '". $_POST['customer'] ."', '". $_POST['name'] ."', '". $_POST['email'] ."', '". $_POST['phone'] ."', '1', NOW(), '". $_SESSION['id'] ."')");	
		   $companyId = mysql_insert_id();	
	   
   }
   else
   {
	       $companyId =   $_POST['companyId']; 
   }

			
			/*$enquiryNumber = mysql_query("select enquiryNumber from enquiries order by enquiryId desc limit 1");
			$enquiryNumber = mysql_fetch_array($enquiryNumber);
			$enquiryNumber = $enquiryNumber['enquiryNumber']+1; */

 
		   mysql_query("insert into enquiries (`enquiryNumber`, `offerNumber`, `name`, `company`, `companyId`,  `phone`, `email`, `source`, `addedBy`,`dateTime`) values ('". $_POST['enquiryNumber'] ."','". $_POST['offerNumber'] ."', '". $_POST['name'] ."', '". $_POST['customer'] ."', '". $companyId ."', '". $_POST['phone'] ."', '". $_POST['email'] ."', '". $_POST['source'] ."', '". $_SESSION['id'] ."', NOW())");			
			
		$enquiryId = mysql_insert_id();	
			
			
mysql_query("insert into enquiry_assign (`enquiryId`,`assignedTo`,`assignedBy`,`dateandtime`) values ('". $enquiryId ."', '". $_POST['employee'] ."', '". $_SESSION['id'] ."', NOW())");
				 
			$eaId = mysql_insert_id();
			

			if($eaId>0)
			{
				$count = count($_POST['categories']);
				
				for($i=0;$i<$count;$i++)
				{ 
				echo ''.
					
mysql_query("insert into enquiry_products (`enquiryId`, `eaId`, `codeId`, `productId`, `brandId`, `categoryId`, `quantity` ) values ('".$enquiryId."','".$eaId."', ". $_POST['models'][$i] .", ". $_POST['products'][$i] .", ". $_POST['brands'][$i] .", ". $_POST['categories'][$i] ."," . $_POST['quantity'][$i] . ")");

				//	echo mysql_insert_id(); exit;
				}
			 header("location: add_enquiry.php?add=1");	
			}
			else
			{
			 header("location: add_enquiry.php?error=1");	
			}
			
		
		}

// olde code
// report type is 1 for enquiry
/*
mysql_query("insert into daily_reports (`offer`, `currentRevisionId`, `reportDate`, `company`, `contactPerson`, `phone`, `email`, `leadStatus`, `po`, `addedOn`, `addedBy`, `reportType`, `source`) values ('1', '0', NOW(), '". $companyId ."', '". $_POST['name'] ."', '". $_POST['phone'] ."', '". $_POST['email'] ."', 'Offer to be generated', '0', NOW(), '". $_POST['eid'] ."', '1', '". $_POST['source'] ."')");	

   $lastId = mysql_insert_id();
   
   if($lastId>0)
   {
    mysql_query("insert into daily_reports_revision (`reportId`, `revision`, `revisionDateTime`) values ('". $lastId ."', '0', NOW())");
				  $revisionId = mysql_insert_id();
				  
				  $count = count($_POST['categories']);
				  
				  for($i=0;$i<$count;$i++)
				  {
					
					mysql_query("insert into daily_reports_data (`revisionId`, `categoryId`, `brandId`, `productId`, `quantity`) values ('". $revisionId ."', '". $_POST['categories'][$i] ."', '". $_POST['brands'][$i] ."', '". $_POST['models'][$i] ."', '". $_POST['quantity'][$i] ."')");  
				  }
				  
				  

      header("location: add_enquiry.php?add=".$lastId);	
			}
			else
			{
			 header("location: add_enquiry.php?error=1");	
			}
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
								<a href="enquiries.php">Enquiry</a>
							</li>

							<li class="active">Add Enquiry</li>
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
											Add Enquiry
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div id="outclick">
											
                                           <?php
	   if(isset($_GET['add']))
{ echo '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Enquiry has been added!</div>'; }

else if(isset($_GET['error']))
{ echo '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

 			$categories = mysql_query("select id, category from categories order by category");


  									   ?> 

                                
                                            <form class="form-horizontal" role="form" action="" method="post" autocomplete="off">
									<!-- #section:elements.form -->
                                    
                                    
                                   <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="company"> Company Name </label>

										<div class="col-sm-9">
                                        <div class="clearfix">
		<input type="text" id="customer" name="customer" placeholder="Company" class="col-xs-10 col-sm-12"  />
        <input type="hidden" id="companyId" name="companyId" value="0"  />
         <ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="customersList">
                                           </ul>
                                           
                                            </div>
										</div>
									</div>
                                    
                                    
                                   
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="name"> Name</label>

										<div class="col-sm-9">
                                        <div class="clearfix">
											<input type="text" id="name_cus" name="name" placeholder="Name" class="col-xs-10 col-sm-12"  />
                                            
                                            </div>
										</div>
									</div>
                                    
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="phone"> Phone</label>

										<div class="col-sm-9">
                                        <div class="clearfix">
											<input type="text" id="phone_cus" name="phone" placeholder="Phone" class="col-xs-10 col-sm-12"  />
										</div></div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="email"> Email</label>

										<div class="col-sm-9"><div class="clearfix">
											<input type="email" id="email_cus" name="email" placeholder="Email" class="col-xs-10 col-sm-12"  />
										</div></div>
									</div>
                                     
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="source"> Source</label>

						
                        				<div class="col-sm-9">
											<select id="source" name="source" class="col-xs-10 col-sm-12">
                                            <option value="">Select Source</option>
                                            <option value="Email">Email</option>
                                            <option value="Tele Call">Tele Call</option>
                                            <option value="Person Visit">Person Visit</option>
                                            </select>
                                            
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="enquiryNumber"> Enquiry Number</label>

						
                        				<div class="col-sm-9">
     <input type="text" id="enquiryNumber" name="enquiryNumber" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Enquiry Number" />
           
                                           
                                </div></div>
                                
                                
                                <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="offerNumber"> Offer Number</label>

						
                        				<div class="col-sm-9">
     <input type="text" id="offerNumber" name="offerNumber" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Offer Number" />
           
                                           
                                </div></div>
                                    
                                    
                                  <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="employee"> Employee</label>

						
                        				<div class="col-sm-9">
     <input type="hidden" id="eid" name="eid"  />
     <input type="text" id="employee" name="employee" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Employee" onkeyup="getEmployee(this.value)"   />
           <ul class="typeahead dropdown-menu" style="top: 28px; left: 0px; display: none; height:100px; width:400px; overflow:auto; margin:0px; padding:0px; border:0px;" id="employeesList">
                                           </ul>
                                           
                                </div></div>
                                
                                    
                                    <!--<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="products"> Products</label>

										<div class="col-sm-9">
                                        <div class="clearfix">
											<input type="text" id="products" name="products" placeholder="Products" class="col-xs-10 col-sm-12" onKeyUp="getProductsList(this.value)"  />
             <ul class="typeahead dropdown-menu" style="top: 28px; left: 0px; display: none;" id="productList"></ul>
             
             <div id="productSelected">
             
             
             </div>


                                            </div>
										</div>
									</div>-->
                                    
                                    
                                    
                                    
                                    <!--one-->
                                    
                                    <div id="formContainer" style="display: block;" class="row">
                      <div class="col-xs-12 form-group">    
                                
                                <div class="col-sm-3">
                                
                                </div> 
                                <div class="col-sm-2">
                                <span>Category</span>
                                <select id="category1" name="categories[]" class="col-xs-10 col-sm-12" onchange="getBrands('1')">
                                            <option value="0">Select Category</option>
                                                <?php
									
									while($category = mysql_fetch_array($categories))
									{ 
									?>
                                            <option value="<?php echo $category['id']; ?>"><?php echo $category['category']; ?></option>
                                            <?php  }
                                    ?>                                          </select>
                                </div> 
                                 <div class="col-sm-2">
                                <span>Brand</span>
                                <select id="brand1" name="brands[]" class="col-xs-10 col-sm-12" onchange="getProducts('1')">
                                            <option value="0">Select Brand</option>
                                             </select>
                                </div> 
                                
                                 <div class="col-sm-2">
                                <span>Product</span>
                                <select name="products[]" id="product1" class="col-xs-10 col-sm-12" onchange="getModels('1')">
                                            <option value="--">Select Product</option>
                                            </select>
                                </div>
                                
                                <div class="col-sm-2">
                                <span>Model No</span>
             <select name="models[]" id="model1" class="col-xs-10 col-sm-12">
                                            <option value="--">Select Model</option>
                                            </select>
                                </div> 
                                
                                <div id="productdetails1"> 
                                <div class="col-sm-1">
                                <span>quantity</span>
                                <input type="text" name="quantity[]" size="10" id="quantity1" value="" onkeyup="getPrice('1')">
                                </div> 
                                
                                </div>
                                
                                 
                                </div>
                               </div>
                               
                                    <!--one-->
                                  
                                    
                                    <div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-3">
											<button class="btn btn-info" type="submit" name="addEnquiry">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>

											&nbsp; &nbsp; &nbsp;
											<button class="btn" type="reset">
												<i class="ace-icon fa fa-undo bigger-110"></i>
												Reset
											</button>
										</div>
                                        
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
        
        
        
        <script type="text/javascript">
		
	
			//$("#employee").blur(function(){
				
	
			
		
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

		
		/*function getCompaniesList(val)
		{
			
			    document.getElementById("companiesList").style.display = 'block';
				$.ajax({url: "ajax/getCustomerList.php?val="+val, success: function(result){
	            $("#companiesList").html(result);
		
    }});	
			
		}
		*/
		
			$(document).ready(function(){
				
				$(document).on('click','#outclick,#breadcrumbs',function(){
				$("#employeesList,#customersList").css

('display','none'); 
});


				/*$(document).on('blur','#employee',function(){
				$("#employeesList").css('display','none'); 
			});*/
				
				
			 $("#customer").keyup(function(){
				var customer = $(this).val();
				$.ajax({
							type:'POST',
							url:'ajax/getCustomerList.php',
							data:{'customer':customer},
							success:function(resp){
								$("#customersList").html(resp);
								$("#customersList").css('display','block');
							}
						})
			}) 
		});
		
		function selectCustomer(id,val)
		{
			document.getElementById("customersList").style.display = 'none';
			document.getElementById("companyId").value = id;
			document.getElementById("customer").value = val;
			
		}
		
		function getProductsList(val)
		{
			var category = document.getElementById('category').value;
			
			
				
			document.getElementById("productList").style.display = 'block';
		
			if(category>0)
			{
				$.ajax({url: "ajax/getCategoryProductDetails.php?val="+val+"&category="+category, success: function(result){
		
		
        $("#productList").html(result);
    }});	
			
		} else {
			
		 alert("select category");	
		}
		}
		
		
		function selectProduct(id,val)
		{
			
			
			document.getElementById("productList").style.display = 'none';
			
			document.getElementById("products").value = '';
			
			data = '<p id="pid'+id+'"><a href="javascript:void()" onclick="removeProduct('+id+')"><i class="ace-icon fa fa-trash icon-only"></i></a><input type="hidden" name="selectedProducts[]" value="'+id+'" />'+val+'</p>';
			
			$("#productSelected").append(data);
			
			
		
		}
		
		
		function removeProduct(rid)
		{
		
		//document.getElementById('pid'+rid).
		
		$("#pid"+rid).remove();
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
						company: {
							required: true,
							
						},
						location: {
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
						address: {
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
				
			
			
			});
			
			$(document).ready(function() {
				
			
			$(document).on('click',"#customersList", function() { 
			
			var company_name = $(this).find('li').find('a').html();
			$.ajax({
				  url : "ajax/getCustomerDetails.php",
				  type: 'POST',
				  data: {'company_name':company_name},
				  success:function(data){
					data = $.parseJSON(data);
					$("#name_cus").val(data.contactPerson);
					$("#phone_cus").val(data.phone);
					$("#email_cus").val(data.email);
				  }
							
				
				  });
			
			
			
			
			});
			
			});
			
			
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
