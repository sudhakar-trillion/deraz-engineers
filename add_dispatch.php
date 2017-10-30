<?php include("includes/header.php"); 
/* there are 2 ajax files are including in this when we give the invoice number then we will get the invoice data.  they are getInvoiceDetails.php and getInvoiceData.php  */


if(isset($_POST['addDispatch']))
{
$dispatchedOn = explode('-',$_POST['dispatchedOn']);
$dispatchedOn = $dispatchedOn[2].'-'.$dispatchedOn[1].'-'.$dispatchedOn[0];

$lrDate = explode('-',$_POST['lrDate']);
$lrDate = $lrDate[2].'-'.$lrDate[1].'-'.$lrDate[0];

// inserting into dispatch table
mysql_query("insert into dispatch (`invoiceId`, `dateTime`, `addedBy`, `dispatchedOn`,`lrDate`,`lrNo`) values ('". $_POST['invoiceId'] ."', NOW(), '". $_SESSION['id'] ."', '". $dispatchedOn ."','".$lrDate."','".$_POST['lrNo']."')");			

$lastId = mysql_insert_id();

if($lastId>0)
{   
$count = count($_POST['dispatchQuantity']);

for($i=0;$i<$count;$i++)
{

if($_POST['dispatchQuantity'][$i]>0)
{

//echo "insert into dispatch_items (`dispatchId`, `itemId`, `dispatchedQuantity`, `remarks`) values ('". $lastId ."', '". $_POST['item'][$i] ."', '". $_POST['dispatchQuantity'][$i] ."', '". addslashes($_POST['remarks'][$i]) ."')"; exit; 
// inserting into dispatch_items table
mysql_query("insert into dispatch_items (`dispatchId`, `itemId`, `dispatchedQuantity`, `remarks`) values ('". $lastId ."', '". $_POST['item'][$i] ."', '". $_POST['dispatchQuantity'][$i] ."', '". addslashes($_POST['remarks'][$i]) ."')");	}


// close dispatch items
$orderedQuantity = mysql_query("select quantity from daily_reports_data where id = '". $_POST['item'][$i] ."'");

if(mysql_num_rows($orderedQuantity)>0)
{
	$orderedQuantity = mysql_fetch_array($orderedQuantity);  
}

$dispatchedQuantity = mysql_query("select dispatchedQuantity from dispatch_items where itemId = '". $_POST['item'][$i] ."'");
$totalDispatched = 0;
if(mysql_num_rows($dispatchedQuantity)>0)
{

while($dispatched_Quantity = mysql_fetch_array($dispatchedQuantity))
{
	$totalDispatched = $totalDispatched+$dispatched_Quantity['dispatchedQuantity']; 
}

}

if($totalDispatched>=$orderedQuantity['quantity'])
{
	mysql_query("update daily_reports_data set dispatchStatus = '1' where id = '". $_POST['item'][$i] ."'");
}
}

// close order
//$result = mysql_query("select id from daily_reports_data where invoice_id = '". $_POST['invoiceId'] ."' and dispatchStatus = '0'");


//get the reportId from invoices
	$qrey = mysql_query("select reportId from invoices where invoiceId=".$_POST['invoiceId']);
	$reportId = mysql_fetch_object($qrey);

//get the total products quantity on this reportid


	$qry = mysql_query("select SUM(quantity) as totalquant from daily_reports_data as drd left join daily_reports_revision drv on drv.revisionId=drd.revisionId where drv.reportId=".$reportId->reportId); 
	
	
	$total_quant = mysql_fetch_object($qry);
	
	$totalquant = $total_quant->totalquant;
//	echo "total prdcts to be dispatch $totalquant<br>";
	//get the dispatched id
	$dispatchid = mysql_query("select dispatchedId from dispatch where invoiceId=".$_POST['invoiceId']);
	$dispatched_quantity = 0;
	while($dis = mysql_fetch_object($dispatchid))
	{
		//get the total dispatched items
	//	echo "select sum(dispatchedQuantity) as dispatchedQuantity  from dispatch_items where dispatchId=".$dis->dispatchedId."<br>";
	
		$dis_items = mysql_query("select sum(dispatchedQuantity) as  dispatchedQuantity  from dispatch_items where dispatchId=".$dis->dispatchedId);
		
		$items_dispatches = mysql_fetch_object($dis_items);
		$dispatched_quantity = $dispatched_quantity+$items_dispatches->dispatchedQuantity;
	
	}
//echo "Products dispatched $dispatched_quantity<br>";

if($dispatched_quantity == $totalquant)
{
//	echo "yeah";

mysql_query("update  invoices set invoiceStatus = 'Order Closed', closedDateTime = NOW() where invoiceId = '". $_POST['invoiceId'] ."'");

mysql_query("update  daily_reports set leadStatus = 'Order Closed', closedDateTime = NOW() where reportId = '". $reportId->reportId ."'");
}
else
//	echo "no"; exit; 



/* if(mysql_num_rows($orderedQuantity)>0)
{
$orderedQuantity = mysql_fetch_array($orderedQuantity);  
}*/


header("location: add_dispatch.php?add=1");	
}
else
{
header("location: add_dispatch.php?error=1");	
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
								<a href="dispatched.php">Dispatched</a>
							</li>

							<li class="active">Add Dispatch</li>
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
											Add Dispatch
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div id="outclick">
											
                                           <?php
	   if(isset($_GET['add']))
{ echo '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Dispatch data has been added!</div>'; }
else if(isset($_GET['error']))
{ echo '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

 
										   ?> 
                                            
                                           
                                            
                               
                                            
                                           
                                
                                            <form class="form-horizontal" role="form" action="" method="post" id="validation-form" autocomplete="off">
                                            <div class="space"></div>
                                    <div class="form-group">
										<label class="col-sm-1 control-label no-padding-right" for="dispatchedOn">Dispatch Schedule Date </label>

										<div class="col-sm-11">
                                        <div class="clearfix">
                                          <input type="text" id="dispatchedOn" name="dispatchedOn" placeholde="Dispatched On" class="col-xs-10 col-sm-12 date-picker" />
                                        </div></div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-1 control-label no-padding-right" for="lrDate">LR Date </label>

										<div class="col-sm-11">
                                        <div class="clearfix">
                                          <input type="text" id="lrDate" name="lrDate" class="col-xs-10 col-sm-12 date-picker" placeholde="LR Date"/>
                                        </div></div>
									</div>
                                    
                                    <div class="form-group">
										<label class="col-sm-1 control-label no-padding-right" for="lrNo"> LR No. </label>

										<div class="col-sm-11">
                                        <div class="clearfix">
                                        
											<input type="text" id="lrNo" name="lrNo" placeholder="LR No." class="col-xs-10 col-sm-12"  />

                                            </div>
										</div>
									</div>
                                    
									
									 <div class="form-group">
										<label class="col-sm-1 control-label no-padding-right" for="invoice">Proforma Invoice </label>

										<div class="col-sm-11">
                                        <div class="clearfix">
                                        
                                            <input type="hidden" id="invoiceId" name="invoiceId"  />
											<input type="text" id="invoice" name="invoice" placeholder="Proforma Invoice" class="col-xs-10 col-sm-12" onKeyUp="getInvoiceList(this.value)"  />
                                            
<ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="invoiceList">
                                           </ul>
                                            </div>
										</div>
									</div>
                                    
                                    <div id="div1">
                                    
                                    
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
        
        <script>
		
$(document).ready(function() 
{  
	getInvoiceDetails("<?PHP echo $_GET['invoiceid']; ?>","<?PHP echo $_GET['invoicenumber']; ?>");
});

/*
		
		function getInvoiceList(val)
		{
			
			document.getElementById("invoiceList").style.display = 'block';
				$.ajax({url: "ajax/getInvoiceDetails.php?val="+val, success: function(result){
		
		
        $("#invoiceList").html(result);
    }});	
			
		}
		*/
		
		
		function getInvoiceDetails(id,val)
		{
			document.getElementById("invoiceList").style.display = 'none';
			document.getElementById("invoiceId").value = id;
			document.getElementById("invoice").value = val;
			
	
		    $.ajax({url: "ajax/getInvoiceData.php?invoiceId="+id, success: function(result){
		          $("#div1").html(result);
            }});	
		}
		
		function checkQuantity(val,id)
		{
			
			
		   var pending  =  parseInt(document.getElementById('pending'+id).value);
		   val = parseInt(val);
		   if(val>pending)
		   {
			
			    document.getElementById('dispatchQuantity'+id).value = pending;
		   }
		}
		
		</script>
        
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
			
			
			$(document).on('click','#outclick,#breadcrumbs',function(){
				$('#invoiceList').css('display','none');
				
				
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
						invoice: {
							required: true,
							
						},
						dispatchedOn: {
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
