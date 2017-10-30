<?php include("includes/header.php"); 


		if(isset($_POST['updateService']))
		{
			extract($_POST);
			
		

 $date = explode('/',$date);
 $date = $date[2].'-'.$date[0].'-'.$date[1];
 
// edit and update using services table		
mysql_query("update services  set `date` = '$date', `customerName` = '$customerName', `product` =  '$product', `modelNo` = '$modelNo', `quantity` = '$quantity', `complaint` = '$natureComplaint', `engineer` = '$engineer' where serviceId = '". $_GET['sid'] ."'");
	
	//  mysql_query("insert into service_status (`serviceId`, `status`, `updatedOn`) values ('". $_GET['sid'] ."', '$status', NOW())");   
	  header("location: edit_service.php?sid=".$_GET['sid']."&add=1");	
}		
			
	


  $services = mysql_query("select * from services where serviceId = '". $_GET['sid'] ."'");

if(mysql_num_rows($services)<1)
{
  header("location: services.php"); 								
}
$service = mysql_fetch_array($services);

$date = explode('-',$service['date']); $date = $date[1].'/'.$date[2].'/'.$date[0];
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

							<li class="active">Edit Service</li>
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
											Edit Service
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div>
											
                                           <?php
	   if(isset($_GET['add']))
{ $alertMsg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Service has been added!</div>'; }
else if(isset($_GET['error']))
{ $alertMsg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  if(isset($msg)) {  echo $alertMsg; 
  }
							$status = mysql_query("select status from service_status where serviceId = '". $service['serviceId'] ."' order by statusId desc limit 1");
    $status  = mysql_fetch_array($status);			
				   ?> 
                                
<form class="form-horizontal" role="form" action="" method="post">
<!-- #section:elements.form -->

<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="date"> Date </label>

<div class="col-sm-9">
<input type="text" id="date" name="date" placeholder="Date" class="col-xs-10 col-sm-12 date-picker" value="<?php echo $date; ?>"  />
</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="customerName"> Customer Name </label>

<div class="col-sm-9">
<input type="text" id="customerName" name="customerName" placeholder="Customer Name" class="col-xs-10 col-sm-12" value="<?php echo $service['customerName']; ?>"  />
</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="product"> Product  </label>

<div class="col-sm-9">
<input type="text" id="product" name="product" placeholder="Product" class="col-xs-10 col-sm-12" value="<?php echo $service['product']; ?>"  />
</div>
</div>


<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="modelNo">Model No </label>

<div class="col-sm-9">
<input type="text" id="modelNo" name="modelNo" placeholder="Model No" class="col-xs-10 col-sm-12" value="<?php echo $service['modelNo']; ?>"  />
</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="equipmentNo"> Equipment No </label>

<div class="col-sm-9">
<input type="text" id="equipmentNo" name="equipmentNo" placeholder="Equipment No" class="col-xs-10 col-sm-12"  value="<?php echo $service['equipmentNo']; ?>" />
</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="quantity"> Quantity </label>

<div class="col-sm-9">
<input type="text" id="quantity" name="quantity" placeholder="Quantity" class="col-xs-10 col-sm-12" value="<?php echo $service['quantity']; ?>"  />
</div>
</div>

<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="natureComplaint"> Nature of Complaint </label>

<div class="col-sm-9">
<textarea id="natureComplaint" name="natureComplaint" placeholder="Nature of Complaint" class="col-xs-10 col-sm-12"><?php echo $service['complaint']; ?></textarea>
</div>
</div>


<div class="form-group">
<label class="col-sm-3 control-label no-padding-right" for="engineer"> Engineer </label>

<div class="col-sm-9">
<input type="text" id="engineer" name="engineer" placeholder="Engineer" class="col-xs-10 col-sm-12" value="<?php echo $service['engineer']; ?>"  />
</div>
</div>






<div class="clearfix form-actions">
<div class="col-md-offset-3 col-md-9">
<button class="btn btn-info" type="submit" name="updateService">
<i class="ace-icon fa fa-check bigger-110"></i>
Update
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
