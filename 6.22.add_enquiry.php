<?php include("includes/header.php"); 


		if(isset($_POST['addEnquiry']))
		{
			
			$enquiryNumber = mysql_query("select enquiryNumber from enquiries order by enquiryId desc limit 1");
			$enquiryNumber = mysql_fetch_array($enquiryNumber);
			$enquiryNumber = $enquiryNumber['enquiryNumber']+1; 



   if($_POST['companyId']==0)
   {
	   
	        $customerNumber = mysql_query("select customerNumber from customers order by customerId desc limit 1");
			$customerNumber = mysql_fetch_array($customerNumber);
			$customerNumber = $customerNumber['customerNumber']+1;


		   mysql_query("insert into customers (`customerNumber`, `company`, `contactPerson`, `email`, `phone`, `status`, `dateTime`, `addedBy`) values ('". $customerNumber ."', '". $_POST['company'] ."', '". $_POST['name'] ."', '". $_POST['email'] ."', '". $_POST['phone'] ."', '1', NOW(), '". $_SESSION['id'] ."')");	
		   $companyId = mysql_insert_id();	
	   
   }
   else
   {
	       $companyId =   $_POST['companyId']; 
   }




		   mysql_query("insert into enquiries (`enquiryNumber`, `name`, `category`, `company`, `companyId`,  `phone`, `email`, `source`, `addedBy`, `dateTime`) values ('". $enquiryNumber ."', '". $_POST['name'] ."', '". $_POST['category'] ."', '". $_POST['company'] ."', '". $companyId ."', '". $_POST['phone'] ."', '". $_POST['email'] ."', '". $_POST['source'] ."', '". $_SESSION['id'] ."', NOW())");			
			
			$lastId = mysql_insert_id();
			
			
			if($lastId>0)
			{
				
				$count = count($_POST['selectedProducts']);
				
				for($i=0;$i<$count;$i++)
				{
					
				mysql_query("insert into enquiry_products (`enquiryId`, `productId`) values ('". $lastId ."', '". $_POST['selectedProducts'][$i] ."')");
				}
			 header("location: add_enquiry.php?add=1");	
			}
			else
			{
			 header("location: add_enquiry.php?error=1");	
			}
			
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
								

								<div class="row">
									<div class="col-xs-6">
										
										<div class="table-header">
											Add Enquiry
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div>
											
                                           <?php
	   if(isset($_GET['add']))
{ echo '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Enquiry has been added!</div>'; }
else if(isset($_GET['error']))
{ echo '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

 			$categories = mysql_query("select id, category from categories");


  									   ?> 

                                
                                            <form class="form-horizontal" role="form" action="" method="post" id="validation-form" autocomplete="off">
									<!-- #section:elements.form -->
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="name"> Name</label>

										<div class="col-sm-9">
                                        <div class="clearfix">
											<input type="text" id="name" name="name" placeholder="Name" class="col-xs-10 col-sm-12"  />
                                            </div>
										</div>
									</div>
                                    
                                    
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="category"> Category</label>

						
                        				<div class="col-sm-9">
											<select id="category" name="category" class="col-xs-10 col-sm-12">
                                            
                                           <option value="0">Select category</option>
                                           <?php
									
									while($category = mysql_fetch_array($categories))
									{ 
									?>
                                            <option value="<?php echo $category['id']; ?>"><?php echo $category['category']; ?></option>
                                            <?php  }
                                    ?>
                                            </select>
                                            
										</div>
									</div>
                                  
                                    
                                    
                                    
                                    
									 <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="company"> Company </label>

										<div class="col-sm-9">
                                        <div class="clearfix">
		<input type="text" id="company" name="company" placeholder="Company" onKeyUp="getCompaniesList(this.value)" class="col-xs-10 col-sm-12"  />
        <input type="hidden" id="companyId" name="companyId" value="0"  />
         <ul class="typeahead dropdown-menu" style="top: 28px; left: 0px; display: none; margin:0px; padding:0px; border:0px;" id="companiesList">
                                           </ul>
                                           
                                            </div>
										</div>
									</div>
                                    
                                    
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="phone"> Phone</label>

										<div class="col-sm-9">
                                        <div class="clearfix">
											<input type="text" id="phone" name="phone" placeholder="Phone" class="col-xs-10 col-sm-12"  />
										</div></div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="email"> Email</label>

										<div class="col-sm-9"><div class="clearfix">
											<input type="email" id="email" name="email" placeholder="Email" class="col-xs-10 col-sm-12"  />
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
										<label class="col-sm-3 control-label no-padding-right" for="products"> Products</label>

										<div class="col-sm-9">
                                        <div class="clearfix">
											<input type="text" id="products" name="products" placeholder="Products" class="col-xs-10 col-sm-12" onKeyUp="getProductsList(this.value)"  />
             <ul class="typeahead dropdown-menu" style="top: 28px; left: 0px; display: none;" id="productList"></ul>
             
             <div id="productSelected">
             
             
             </div>


                                            </div>
										</div>
									</div>
                                  
                                    
                                    <div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
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
		
		function getCompaniesList(val)
		{
			
			    document.getElementById("companiesList").style.display = 'block';
				$.ajax({url: "ajax/getCustomerList.php?val="+val, success: function(result){
	            $("#companiesList").html(result);
		
    }});	
			
		}
		
		
		function selectCustomer(id,val)
		{
			document.getElementById("companiesList").style.display = 'none';
			document.getElementById("companyId").value = id;
			document.getElementById("company").value = val;
			
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
