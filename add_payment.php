<?php include("includes/header.php"); 


		if(isset($_POST['submit']))
		{
			if($_POST['amount']>$_POST['pending'])
			{
				
			 header("location: add_payment.php?excess=1");	
			}
		else
		{
			
			$paidDate = explode('-',$_POST['paidOn']);
			$paidDate = $paidDate[2].'-'.$paidDate[1].'-'.$paidDate[0];
		

	// inserting payment into collections
		   mysql_query("insert into collections (`invoiceId`, `paymentType`, `amount`, `paidDate`, `dateTime`) values ('". $_POST['invoiceId'] ."', '". $_POST['paymentType'] ."', '". $_POST['amount'] ."', '". $paidDate ."', NOW())");			
			
			$lastId = mysql_insert_id();
			
			if($lastId>0)
			{
				
				// close payment 
				 // total amount;
			//	 echo "select grandTotal from invoices   where invoiceId = '". $_POST['invoiceId'] ."'";
			
	 
	  $totalAmount = mysql_query("select grandTotal from invoices   where invoiceId = '". $_POST['invoiceId'] ."'");
 
  $grandTotal = 0;
 if(mysql_num_rows($totalAmount)>0)
  {
	  
	 while($total = mysql_fetch_array($totalAmount))
	 {
		$grandTotal = $total['grandTotal']+$grandTotal; 
	 }
	   
  }
  
  
   // pending amount
  $result = mysql_query("select collections.amount from collections                     
                        left join daily_reports on collections.invoiceId = daily_reports.reportId
						where collections.invoiceId = '". $_POST['invoiceId'] ."'
						order by collections.id");	
						
						 
						 $grandCollected = 0;
			if(mysql_num_rows($result)>0)
  {
	
	  
	 while($row = mysql_fetch_array($result))
	 {
		$grandCollected = $row['amount']+$grandCollected; 
	 }
	 

	   
  }
  

 // closing the invoice 
   if($grandCollected>=$grandTotal)
   {
	   mysql_query("update invoices set paymentStatus = 'closed' where invoiceId = '". $_POST['invoiceId'] ."'");
	     
   }
 
      header("location: add_payment.php?add=1");	
			}
			else
			{
			  header("location: add_payment.php?error=1");	
			}
			
		
		   	
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
								<a href="payments.php">Payments</a>
							</li>

							<li class="active">Add Payment</li>
						</ul><!-- /.breadcrumb -->

						<!-- #section:basics/content.searchbox -->
						<div class="nav-search" id="nav-search">
							
						</div><!-- /.nav-search -->

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
											Add Payment
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div>
											
                                           <?php
	   if(isset($_GET['add']))
{ echo '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Collection has been added!</div>'; }
else if(isset($_GET['error']))
{ echo '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }
else if(isset($_GET['excess']))
{ echo '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, entered amount is greater than pending.</div>'; }

 
										   ?> 
                                            
                                           
                                            
                               
                                            
                                           
                                
                                            <form class="form-horizontal" role="form" action="" method="post" id="validation-form" autocomplete="off">
									<!-- #section:elements.form -->
                                     <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="paidOn"> Paid On </label>

										<div class="col-sm-9"><div class="clearfix">
											<input type="text" id="paidOn" name="paidOn" placeholder="Paid On" class="col-xs-10 col-sm-12 date-picker"  />
										</div></div>
									</div>
                                    
									 <div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="invoice">Proforma Invoice </label>

										<div class="col-sm-9">
                                        <div class="clearfix">
                                        
                                            <input type="hidden" id="rvid" name="rvid"  />
                                            <input type="hidden" id="invoiceId" name="invoiceId"  />
											<input type="text" id="invoice" name="invoice" placeholder="Proforma Invoice" class="col-xs-10 col-sm-12" onKeyUp="getInvoiceList(this.value)"  />
                                            
                                           <ul class="typeahead dropdown-menu" style="top: 28px; left: 0px; display: none;" id="invoiceList">
                                           </ul>
                                            </div>
										</div>
									</div>
                                    
                                    <div id="div1"></div>
                                    
                                    
                                    
                                    
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
		
		function getInvoiceList(val)
		{
			
			document.getElementById("invoiceList").style.display = 'block';
				$.ajax({url: "ajax/getInvoiceDetails.php?val="+val, success: function(result){
		
		
        $("#invoiceList").html(result);
    }});	
			
		}
		
		
		function getInvoiceDetails(id,val)
		{
			document.getElementById("invoiceList").style.display = 'none';
			document.getElementById("invoiceId").value = id;
			document.getElementById("invoice").value = val;
		
			
			$.ajax({url: "ajax/GetInvoicePaymentData.php?invoiceId="+id, success: function(result){
		          $("#div1").html(result);
            }});
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
						paidOn: {
							required: true,
							
						},
						paymentType: {
							required: true,
							
						},
						amount: {
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
