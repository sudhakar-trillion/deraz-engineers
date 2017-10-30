<?php include("includes/header.php"); 


		if(isset($_POST['submit']))
		{
			extract($_POST);
			
			// its for serial number for service
		$max_id = mysql_query("select serialNumber from `services` order by serviceId desc limit 1");
		$max_id = mysql_fetch_array($max_id);
		
		
		$serialNumber= explode('SR',$max_id['serialNumber']);
		$serialNumber = $serialNumber[1]+1;
		$serialNumber = 'SR'.$serialNumber;
	

 $date = explode('/',$date);
 $date = $date[2].'-'.$date[0].'-'.$date[1];
 

 
// inseting service data into services table 
mysql_query("insert into services (`date`, `serialNumber`, `customerName`, `product`, `brand`, `category`, `modelNo`, `inwardDetails`, `quantity`, `complaint`, `addedBy`, `engineer`,`typeOfService`,`costForService`,`CostPerService`,`complaintNumber`,`viewed`) 
values ('$date', '$serialNumber', '$customerName', '$product', '$brand', '$category', '$modelNo', '$inwardDetails', '$quantity', '$natureComplaint', '". $_SESSION['id'] ."', '$engineer', '$typeOfService', '$costForService','$cost_for_service','$complaintNumber','Not View')");

$lastId = mysql_insert_id();

 if($lastId>0)
 {
	// adding status into service_status table			
	  mysql_query("insert into service_status (`serviceId`, `status`, `updatedOn`) values ('$lastId', 'open', NOW())");   
	  header("location: add_service.php?add=1");	
}		
			else
			{
			  header("location: add_service.php?error=1");	
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
								<a href="services.php">Services</a>
							</li>

							<li class="active">Add Service</li>
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
									<div class="col-xs-6">
										
										<div class="table-header">
											Add Service
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div>
											
                                           <?php
	   if(isset($_GET['add']))
{ $alertMsg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Service has been added!</div>'; }
else if(isset($_GET['error']))
{ $alertMsg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  if(isset($msg)) 
  {  echo $alertMsg; 
  }
										   ?> 
                                            
                                
 	<form class="form-horizontal" role="form" action="" method="post" id="validation-form" autocomplete="off">
									<!-- #section:elements.form -->
									
                                     
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="date"> Date </label>

										<div class="col-sm-9">
                                        <div class="clearfix">
											<input type="text" id="date" name="date" placeholder="Date" class="col-xs-10 col-sm-12 date-picker"  />
                                            </div>
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="customerName"> Customer Name </label>

										<div class="col-sm-9">
                                        <div class="clearfix">
											<input type="text" id="customerName" name="customerName" placeholder="Customer Name" class="col-xs-10 col-sm-12"  />
                                            </div>
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="category"> Category  </label>

										<div class="col-sm-9">
                                        <div class="clearfix">
                                       <!--<input type="hidden" id="categoryId" name="categoryId"   />
<input type="text" id="category" name="category" placeholder="Category" class="col-xs-10 col-sm-12" onkeyup="getCategories(this.value)" />
           <ul class="typeahead dropdown-menu" style="left: 10px; display: none;" id="categoryList">
                                           </ul>
-->                                            
										<input type="text" id="category" name="category" placeholder="Category" class="col-xs-10 col-sm-12" />


</div>
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="product"> Product  </label>

										<div class="col-sm-9">
                                        <div class="clearfix">
											<input type="text" id="product" name="product" placeholder="Product" class="col-xs-10 col-sm-12"  />
                                            </div>
										</div>
									</div>
                                    
                              <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="brand"> Brand  </label>

										<div class="col-sm-9">
                                        <div class="clearfix">
											<input type="text" id="brand" name="brand" placeholder="Brand" class="col-xs-10 col-sm-12"  />
                                            </div>
										</div>
									</div>      
                                    
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="modelNo">Model No </label>

										<div class="col-sm-9">
                                        <div class="clearfix">
											<input type="text" id="modelNo" name="modelNo" placeholder="Model No" class="col-xs-10 col-sm-12"  />
                                            </div>
										</div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="serialNumber"> Serial Number </label>

										<div class="col-sm-9">
                                           <div class="clearfix">
											<input type="text" id="serialNumber" name="serialNumber" placeholder="Serial Number" class="col-xs-10 col-sm-12"  />
                                            </div>
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="inwardDetails"> Inward Details </label>

										<div class="col-sm-9">
                                         <div class="clearfix">
											<input type="text" id="inwardDetails" name="inwardDetails" placeholder="Inward Details" class="col-xs-10 col-sm-12"  />
                                            </div>
										</div>
									</div>
                                    
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="quantity"> Quantity </label>

										<div class="col-sm-9">
                                        <div class="clearfix">
											<input type="text" id="quantity" name="quantity" placeholder="Quantity" class="col-xs-10 col-sm-12"  />
                                            </div>
										</div>
									</div>
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="natureComplaint"> Nature of Complaint </label>

										<div class="col-sm-9">
                                        <div class="clearfix">
											<textarea id="natureComplaint" name="natureComplaint" placeholder="Nature of Complaint" class="col-xs-10 col-sm-12"></textarea>
                                            </div>
										</div>
									</div>
                                    
                                   <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="complaintNumber"> Complaint Number </label>

										<div class="col-sm-9">
                                           <div class="clearfix">
											<input type="text" id="complaintNumber" name="complaintNumber" placeholder="Complaint Number" class="col-xs-10 col-sm-12"  />
                                            </div>
										</div>
									</div> 
                                   
                                    
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="engineer"> Engineer </label>

										<div class="col-sm-9">
                                           <div class="clearfix">
											<input type="text" id="engineer" name="engineer" placeholder="Engineer" class="col-xs-10 col-sm-12"  />
                                            </div>
										</div>
									</div>
                                    
                                    
                                    <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="typeOfService"> Type of Service </label>

										<div class="col-sm-9">
                                           <div class="clearfix">
                                           <select name="typeOfService" id="typeOfService" class="col-xs-10 col-sm-12" >
								<option value="0">Select Service </option>
								<option value="Inhouse">Inhouse</option>
								<option value="Outstation">Outstation</option>
                                               
                                                </select>
                                            </div>
										</div>
									</div>
                                    
                          <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="costForService"> Cost for Service </label>

										<div class="col-sm-6">
                                           <div class="clearfix">
                                           <select name="costForService" id="costForService" class="col-xs-10 col-sm-12" >
								<option value="0">Select Service </option>
								<option value="chargable">Chargable</option>
								<option value="freeOfCost">Free of Cost</option>
                                               
                                                </select>
                                            </div>
										</div>
                                        
<div class="col-sm-2"><input class="form-control" type="text" name="cost_for_service" id="cost_for_service" value="0" /></div>
                                        
									</div>          
                                    
                                    
                                    
                                    
                                     <div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="submit" name="submit">
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

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
		
			
			function getCategories(val)
		{
			document.getElementById("categoryList").style.display = 'block';
			$.ajax({url: "ajax/getCategoryList.php?val="+val, success: function(result){
		$("#categoryList").html(result);
    }});	
			
		}
		
		
		function selectCategory(id,category)
		{
			document.getElementById("categoryList").style.display = 'none';
			document.getElementById("categoryId").value = id;
			document.getElementById("category").value = category;
	
		}
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
						customerName: {
							required: true,
							minlength: 5,
						},
						product: {
							required: true,
							
						},
						modelNo: {
							required: true,
							
						},
						equipmentNo: {
							required: true,  
							
						},
						quantity: {
							required: true,
							
						},
						natureComplaint: {
							required: true,
							
						},
						engineer: {
							required: true,
							
						}
						
					},
			
					messages: {
						customerName: {
							required: "Please provide a valid email. empty ",
							minlength: "Please specify a secure password."
						},
						agree: "Please accept our policy"
					},
			
			
					highlight: function (e) {
						$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
					},
			
					success: function (e) {   
						$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
						$(e).remove();
					}
			
				/*	errorPlacement: function (error, element) {
						if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
							var controls = element.closest('div[class*="col-"]');
							if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
							else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
						}
						else if(element.is('.select2')) {
							error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
						}
						else if(element.is('.chosen-select')) {
							error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
						}
						else error.insertAfter(element.parent());
					},
			
					submitHandler: function (form) {
					},
					invalidHandler: function (form) {
					}*/
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
