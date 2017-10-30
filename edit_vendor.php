<?php include("includes/header.php"); 
 
		if(isset($_POST['update']))
		{
		   //edit and updating the vendors table 
			if(mysql_query("update vendors set `company` = '". $_POST['company'] ."', `contactPerson` = '". $_POST['contactPerson'] ."', `designation` = '". $_POST['designation'] ."', `phone` = '". $_POST['phone'] ."', `address` = '". $_POST['address'] ."' 
			where vendorId = '". $_GET['eid'] ."'"))
			{
			  header("location: edit_vendor.php?eid=".$_GET['eid']."&update=1");	
			}
			else
			{
			  header("location: edit_vendor.php?eid=".$_GET['eid']."&error=1");	
			}
			
		}
		
	$vendors = mysql_query("select * from vendors where vendorId = '". $_GET['eid'] ."'");
	 //$customer = mysql_fetch_array($customer);
	
	$vendor = mysql_fetch_array($vendors);
	
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

							<li class="active">Edit Vendor</li>
						</ul><!-- /.breadcrumb -->

						<!-- #section:basics/content.searchbox -->

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
											Edit Vendor
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										
											
                                           <?php
	   if(isset($_GET['update']))
{ $alertMsg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Employee has been updated!</div>'; }
else if(isset($_GET['error']))
{ $alertMsg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  if(isset($msg)) { echo $alertMsg; }
										   ?> 
                                            
                                           
                                            
                               
                                            
                                           
<form class="form-horizontal" role="form" action="" method="post" id="validation-form" autocomplete="off">
<!-- #section:elements.form -->
<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="company"> Company Name</label>

<div class="col-sm-9">
<div class="clearfix">
<input type="text" id="company" name="company" value="<?php echo $vendor['company']; ?>" class="col-xs-10 col-sm-12"  />
</div>
</div>
</div>



<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="contactPerson"> Contact Person </label>

<div class="col-sm-9">
<div class="clearfix">
<input type="text" id="contactPerson" name="contactPerson" value="<?php echo $vendor['contactPerson']; ?>" class="col-xs-10 col-sm-12"  />
</div></div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="designation"> Designation </label>

<div class="col-sm-9"><div class="clearfix">
<input type="text" id="designation" name="designation" value="<?php echo $vendor['designation']; ?>" class="col-xs-10 col-sm-12"  />
</div></div>
</div>


<!--<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="email"> Email </label>

<div class="col-sm-9"><div class="clearfix">
<input type="text" id="email" name="email" value="<?php echo $vendor['email']; ?>" class="col-xs-10 col-sm-12"  />
</div></div>
</div>-->

<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="phone"> Phone </label>

<div class="col-sm-9">
<div class="clearfix">
<input type="text" id="phone" name="phone" value="<?php echo $vendor['phone']; ?>" class="col-xs-10 col-sm-12"  />
</div></div>
</div>


<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="address"> Address </label>

<div class="col-sm-9"><div class="clearfix">
<input type="text" id="address" name="address" value="<?php echo $vendor['address']; ?>" class="col-xs-10 col-sm-12"  />
</div></div>
</div>




<div class="clearfix form-actions">
<div class="col-md-offset-3 col-md-9">
<button class="btn btn-info" type="submit" name="update">
<i class="ace-icon fa fa-check bigger-110"></i>
Update
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
						<span class="bigger-120">
							<span class="blue bolder">Tri</span>
			SMART  Application &copy; 2014-2015
						</span>

						&nbsp; &nbsp;
						<span class="action-buttons">
							<a href="#">
								<i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
							</a>

							<a href="#">
								<i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
							</a>

							<a href="#">
								<i class="ace-icon fa fa-rss-square orange bigger-150"></i>
							</a>
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
					todayHighlight: true,
					 format: 'dd-mm-yyyy'
				})
				
				
				$('.date-picker1').datepicker({
					//autoclose: true,
					//format: 'dd-mm-yyyy'
					format: "dd-mm-yyyy",
             startView: "year", 
    minViewMode: "date"
   // minViewMode: "date"
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
