<?php include("includes/header.php"); 


		if(isset($_POST['submit']))
		{
			
			
           // inserting vendors details in vendors table.
//mysql_query("insert into vendors (`company`, `contactPerson`, `designation`, `email`, `phone`, `address`, `category`, `brand`, `product`, `modelNo`, `dateTime`, `addedBy`) values ('". $_POST['company'] ."' , '". $_POST['contactPerson'] ."', '". $_POST['designation'] ."', '". $_POST['email'] ."', '". $_POST['phone'] ."', '". addslashes($_POST['address']) ."', '". $_POST['category'] ."', '". $_POST['brand'] ."', '". $_POST['product'] ."', '". $_POST['modelNo'] ."', NOW(), '". $_SESSION['id'] ."')");			


mysql_query("insert into vendors (`company`, `contactPerson`, `designation`, `phone`, `address`,`brand`, `product`,`dateTime`, `addedBy`) values ('". $_POST['company'] ."' , '". $_POST['contactPerson'] ."', '". $_POST['designation'] ."','". $_POST['phone'] ."', '". addslashes($_POST['address']) ."', '". $_POST['brand'] ."', '". $_POST['product'] ."', NOW(), '". $_SESSION['id'] ."')");			
			
			$lastId = mysql_insert_id();
			
			if($lastId>0)
			{
			  header("location: add_vendor.php?add=1");	
			}
			else
			{
			  header("location: add_vendor.php?error=1");	
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
								<a href="vendors.php">Vendors</a>
							</li>

							<li class="active">Add Vendor</li>
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
											Add Vendor
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div>
											
                                           <?php
	   if(isset($_GET['add']))
{ echo '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Vendor has been added!</div>'; }
else if(isset($_GET['error']))
{ echo '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  									   ?> 
                                            
                                           
                                
<form class="form-horizontal" role="form" action="" method="post" id="validation-form" autocomplete="off">
<!-- #section:elements.form -->
<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="company"> Company Name </label>

<div class="col-sm-9">
<div class="clearfix">
<input type="text" id="company" name="company" placeholder="Company Name" class="col-xs-10 col-sm-12"  />
</div>
</div>
</div>



<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="contactPerson"> Contact Person </label>

<div class="col-sm-9">
<div class="clearfix">
<input type="text" id="contactPerson" name="contactPerson" placeholder="Contact Person" class="col-xs-10 col-sm-12"  />
</div></div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="designation"> Designation </label>

<div class="col-sm-9"><div class="clearfix">
<input type="text" id="designation" name="designation" placeholder="Designation" class="col-xs-10 col-sm-12"  />
</div></div>
</div>

<!--
<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="email"> Email </label>

<div class="col-sm-9"><div class="clearfix">
<input type="text" id="email" name="email" placeholder="Email" class="col-xs-10 col-sm-12"  />
</div></div>
</div>
-->

<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="phone"> Phone </label>

<div class="col-sm-9">
<div class="clearfix">
<input type="text" id="phone" name="phone" placeholder="Phone" class="col-xs-10 col-sm-12"  />
</div></div>
</div>


<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="address"> Address </label>

<div class="col-sm-9"><div class="clearfix">
<input type="text" id="address" name="address" placeholder="Address" class="col-xs-10 col-sm-12"  />
</div></div>
</div>

<!--
<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="category"> Category  </label>

<div class="col-sm-9">
<div class="clearfix">
<input type="text" id="category" name="category" placeholder="Category" class="col-xs-10 col-sm-12" />
</div>


</div>
</div>
-->

<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="brand"> Brand  </label>

<div class="col-sm-9">
<div class="clearfix">
<input type="text" id="brand" name="brand" placeholder="Brand" class="col-xs-10 col-sm-12"  />
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


<!--
<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="modelNo">Model No </label>

<div class="col-sm-9">
<div class="clearfix">
<input type="text" id="modelNo" name="modelNo" placeholder="Model No" class="col-xs-10 col-sm-12"  />
</div>
</div>
</div>
-->




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
