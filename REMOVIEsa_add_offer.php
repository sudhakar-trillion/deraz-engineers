<?php  include("includes/sa_header.php"); 


		if(isset($_POST['submit']))
		{
			$date = explode('-',$_POST['date']);
			$date = $date[2].'-'.$date[1].'-'.$date[0];
	
			$futureDate = explode('-',$_POST['futureDate']);
			$futureDate = $futureDate[2].'-'.$futureDate[1].'-'.$futureDate[0];
			

		   mysql_query("insert into offers (`offerDate`, `company`, `contactPerson`, `designation`, `phone`, `email`, `clientStatus`, `leadType`, `leadStatus`, `futureDate`, `remarks`, `po`, `paymentType`, `addedOn`, `addedBy`) values ('". $date ."', '". $_POST['company'] ."', '". $_POST['contactPerson'] ."', '". $_POST['designation'] ."', '". $_POST['phone'] ."', '". $_POST['email'] ."', '". $_POST['clientStatus'] ."', '". $_POST['leadType'] ."', '". $_POST['leadStatus'] ."', '". $futureDate ."', '". addslashes($_POST['remarks']) ."', '". $_POST['po'] ."', '". $_POST['paymentType'] ."', NOW(), '". $_SESSION['id'] ."')");			
			
			$lastId = mysql_insert_id();
			
			if($lastId>0)
			{
				
				/*if(isset($_POST['po']))
				{
				  
				  $count = count($_POST['categories']);
				  
				  for($i=0;$i<$count;$i++)
				  {
					
					mysql_query("insert into daily_reports_data (`reportId`, `categoryId`, `brandId`, `productId`, `quantity`) values ('". $lastId ."', '". $_POST['categories'][$i] ."', '". $_POST['brands'][$i] ."', '". $_POST['models'][$i] ."', '". $_POST['quantity'][$i] ."')");  
				  }
					
				}*/
				
			  header("location: sa_add_offer.php?add=1");	
			}
			else
			{
			 header("location: sa_add_offer.php?error=1");	
			}
			
			
			
		}
		
		

$companies = mysql_query("select customerId, company from customers where addedBy = '". $_SESSION['id'] ."' order by company");
  

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
								<a href="sa_offers.php">Offers</a>
							</li>

							<li class="active">Add Offer</li>
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
												<option data-skin="skin-3" value="#D0D0D0" selected="selected">#D0D0D0</option>
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
								

							

   <form class="form-horizontal" role="form" action="" method="post" id="validation-form">

								<div class="row">
									<div class="col-xs-12">
										
										<div class="table-header">
											Add offer
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										
											
                                           <?php
										   
	   if(isset($_GET['add']))
{ $alertMsg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Offer has been added!</div>'; }
else if(isset($_GET['error']))
{ $alertMsg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }
else if(isset($_GET['authorizationRequired'])) 
{ $alertMsg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Authorization required.</div>'; }

  if(isset($alertMsg)) { echo $alertMsg; }
										   ?> 
                                            
                                           
                                            
                              
                                            
                                           <div class="space-6"></div>
                                
                                         
									<!-- #section:elements.form -->
									 <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="date"> Date </label>

										<div class="col-sm-9">
                                        <?php if(isset($_GET['aid'])) { ?>
                  <input type="text" id="date" name="date" placeholder="Date" class="col-xs-10 col-sm-12 date-picker"  value="<?php echo $reportDate; ?>"  />
                                        <?php } else { ?>
											<input type="text" id="date" name="date" placeholder="Date" class="col-xs-10 col-sm-12 date-picker"  />
                                            <?php } ?>
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="company"> Company </label>

										<div class="col-sm-9">
											<select id="company" name="company" class="col-xs-10 col-sm-12" onChange="getCompanyDetails(this.value)">
                                            <option value="">Select Company</option>
                                            <?php
											while($company = mysql_fetch_array($companies))
											{	?> <option value="<?php echo $company['customerId'] ?>"><?php echo $company['company'] ?></option><?php } ?>
                                            </select>
										</div>
									</div>
                                    
                                    <div id="div1">
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="contactPerson"> Contact Person </label>

										<div class="col-sm-9">
											<input type="text" id="contactPerson" name="contactPerson" placeholder="Contact Person" class="col-xs-10 col-sm-12"  />
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="designation"> Designation </label>

										<div class="col-sm-9">
											<input type="text" id="designation" name="designation" placeholder="Designation" class="col-xs-10 col-sm-12"  />
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="email"> Email </label>

										<div class="col-sm-9">
											<input type="text" id="email" name="email" placeholder="Email" class="col-xs-10 col-sm-12"  />
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="phone"> Phone </label>

										<div class="col-sm-9">
											<input type="text" id="phone" name="phone" placeholder="Phone" class="col-xs-10 col-sm-12"  />
										</div>
									</div>
                                    </div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="clientStatus"> Client Status </label>

										<div class="col-sm-9">
											<select id="clientStatus" name="clientStatus" class="col-xs-10 col-sm-12">
                                            <option value="1">Existing</option>
                                            <option value="2">New</option>
                                            </select>
                                            
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="leadType"> Lead Type </label>

										<div class="col-sm-9">
											<select id="leadType" name="leadType" class="col-xs-10 col-sm-12">
                                            <option value="">Select Lead Type</option>
                                            <option value="1">General Enquiry</option>
                                            <option value="2">Customer Call</option>
                                            <option value="3">Reference</option>
                                            <option value="4">Others</option>
                                            </select>
                                            
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="leadStatus"> Lead Status </label>

										<div class="col-sm-9">
											<select id="leadStatus" name="leadStatus" class="col-xs-10 col-sm-12">
                                            <option value="">Select Lead Status</option>
                                            <option value="1">Hot</option>
                                            <option value="2">Warm</option>
                                            <option value="3">Cold</option>
                                            </select>
                                            
										</div>
									</div>
                                    
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="futureDate"> Future Date </label>

										<div class="col-sm-9">
											<input type="text" id="futureDate" name="futureDate" placeholder="Future Date" class="col-xs-10 col-sm-12 date-picker"  />
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="remarks"> Remarks </label>

										<div class="col-sm-9">
											<textarea id="remarks" name="remarks" placeholder="Remarks" class="col-xs-10 col-sm-12"></textarea>
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="paymentType"> Payment Type </label>

										<div class="col-sm-9">
											<select id="paymentType" name="paymentType" class="col-xs-10 col-sm-12">
                                            <option value="">Select Payment Type</option>
                                            <option value="1">Proforma</option>
                                            <option value="2">Through Bank</option>
                                            <option value="3">Direct Payment</option>
                                            <option value="4">Against Delivery</option>
                                            </select>
                                            
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="po"> Offer to be Submitted  </label>

										<div class="col-sm-3">
                                        
                                        <label class="col-sm-3 control-label no-padding-right" for="po">
											<input type="checkbox" id="po" name="po" value="1" onclick="openList()" />
                                            Yes  </label>
                                            
										</div>
									</div>
                                    
                                    </div>
								</div>
					
                      <!-- open --> 
                      
                      <div id="formContainer" style="display: none;" class="row">
                      <div class="col-xs-12 form-group">    
                                
                                <div class="col-sm-3">
                                
                                </div> 
                                <div class="col-sm-2">
                                <span>Category</span>
                                <select id="category1" name="categories[]" class="col-xs-10 col-sm-12" onchange="getBrands('1')">
                                            <option value="0">Select Category</option>
                                             <option value="16">Air Compressors &amp; Spares</option> <option value="14">Electric Motors &amp; Spares</option> <option value="18">Industrial Special Adhesives</option> <option value="12">Pumps, Systems &amp; Spares</option> <option value="17">Speciality Lubricants</option> <option value="13">Valves</option> <option value="15">Water Treatment Plants &amp; Spares</option>                                            </select>
                                </div> 
                                 <div class="col-sm-2">
                                <span>Brand</span>
                                <select id="brand1" name="brands[]" class="col-xs-10 col-sm-12" onchange="getProducts('1')">
                                            <option value="0">Select Brand</option>
                                             <option value="3">Bosch </option> <option value="4">test brand</option>                                            </select>
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
                                    
                                            
                                            <!-- close -->    
                                            
                                             <div class="col-xs-12 form-group">           
                                    <div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
                                        
                                        <div class="col-sm-2">
											<button class="btn btn-sm btn-info" type="submit" name="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Submit
											</button>
                                            </div>

											
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
 
/* function getProducts(id)
 {  alert('hiii');
 $.ajax({url: "ajax/getCompanyDetails.php?cid="+cid, success: function(result){
        $("#div1").html(result);
    }});
	 
 }*/
 
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
