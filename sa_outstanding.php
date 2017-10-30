<?php include("includes/sa_header.php"); 


 // delete
 if(isset($_GET['did']))
 {
	
	mysql_query("delete from products where productId = '". $_GET['did'] ."'"); 
	 header("location: products.php?delete=1");	
 }

// month
if(isset($_GET['cm']))
 {
	 $cm = $_GET['cm']; 
 }
 else
 {  
   $cm = date('m'); 
 }

$currentYear = date('Y');
$like = $currentYear.'-'.$cm.'-%'; 

// team
 if(isset($_GET['tmid']))
									{  
									
						if($_GET['tmid']>0)
						{
	                        $whereItems[] = "daily_reports.addedBy = '". $_GET['tmid'] ."'";		
							
							$page_where = "daily_reports.addedBy = '". $_GET['tmid'] ."' and ";				
							
						}
						else 
						{ 
						
									  // team
	  $myteam = mysql_query("select teamId from teams where teamLeaderId = '". $_SESSION['id'] ."'");
	  
	
	 
	  if(mysql_num_rows($myteam)>0)
	  {
		    $myteam = mysql_fetch_array($myteam);
	    $teamMembers = mysql_query("select employees.id, employees.firstName  from team_members 
								  left join employees on team_members.memberId = employees.id
								  where team_members.teamId = '". $myteam['teamId'] ."'
								  order by team_members.teamMemberId desc");
								  
								  if(mysql_num_rows($teamMembers)>0)
								  {
								  while($teamMember = mysql_fetch_array($teamMembers))
								  {
									
									 $team_members[] = $teamMember['id'];
								  }
								  }
						   // all	
						$in_team_members = implode(', ',$team_members);
						$in_team_members = '('.$in_team_members.')';
						   
						$whereItems[] = "daily_reports.addedBy in ".$in_team_members;	
						$page_where = "daily_reports.addedBy in ".$in_team_members.' and ';		
						   
	  }   }
									
					 } else
							{   // logged in user
								$whereItems[] = "daily_reports.addedBy = '". $_SESSION['id'] ."'";
								$page_where = "daily_reports.addedBy = '". $_SESSION['id'] ."' and ";				
								
								
							}
$limit = 10;
	// get the daily_reports table
$numRecords = mysql_query("select daily_reports.reportId
                        from invoices
						left join daily_reports on invoices.reportId = daily_reports.reportId 
                        left join employees on daily_reports.addedBy = employees.id
                        where $page_where daily_reports.inv = '2' and invoices.paymentStatus = 'open'  
					    order by invoices.invoiceId desc");
						
 $numRecords = mysql_num_rows($numRecords);  
 
  $numPages = (int)($numRecords/$limit);  
 
  $reminder = ($numRecords%$limit);
 
 if($reminder>0)
 {
	 $numPages = $numPages+1;
 }
 else
 {
	  $numPages = $numPages;
 }
 
 
 if(isset($_GET['page']) && $_GET['page']>1)
 {
	$start = ($_GET['page']*$limit)-$limit;  
 }
 else
 {
	$start = 0; 
 }


// search options  where daily_reports.po = '0'

	$where = 'where ';
	 $whereItems[] = "daily_reports.inv = '2'";
	 $whereItems[] = "invoices.paymentStatus = 'open'";
	
	 
	 
    
  if(isset($_GET['outstandingSearch']))
									{
										
									   
									   // by num days
									   if(isset($_GET['numDays']) && $_GET['numDays']>0)
									   {  
									   
									   
		 							    $numDays = '-'.$_GET['numDays'];
									    $fromDate = strtotime($numDays." days", time() + (86400));
									    $fromDate = date("Y-m-d",$fromDate);
										$whereItems[] = "daily_reports.proformaInvoiceDateTime > '". $fromDate ."'";
									   }
									   
									   // by customer
									  if(isset($_GET['customer']) && $_GET['customer']!='')
										{
									   if(isset($_GET['cid']) && $_GET['cid']>0)
									   {  
									  
										    $whereItems[] = "daily_reports.company =  '". $_GET['cid'] ."'";
									   }
										}
										else 
										$_GET['customer']='';
									   
									
										
									
									
												if(count($whereItems)>1)
												{ 
												$whereCondition = implode(' and ',$whereItems);
												$where = $where.$whereCondition;
												}
												else if(count($whereItems)==1)
												{   
													$whereCondition = $whereItems[0];
													$where = $where.$whereCondition;
												}
												else
												{  
												  $where = '';	
												}

	// get the daily_reports table
$query = "select employees.firstName,employees.id,  daily_reports.reportId, daily_reports.enquiryNumber,date_format(invoices.invoiceDateTime,'%d-%m-%Y') as invoiceDateTime,invoices.invoiceNumber,invoices.invoiceId, customers.company, invoices.proInvoiceNumber, invoices.grandTotal 
                        from invoices
						left join daily_reports on invoices.reportId = daily_reports.reportId 
                        left join employees on daily_reports.addedBy = employees.id
						left join customers on daily_reports.company = customers.customerId
                        $where 
					    order by invoices.invoiceId desc";
									}
else								
{									
							
		// get the daily_reports table										
$query = "select employees.firstName,employees.id, daily_reports.enquiryNumber, date_format(invoices.invoiceDateTime,'%d-%m-%Y') as invoiceDateTime,daily_reports.reportId,invoices.invoiceId, customers.company, invoices.invoiceNumber, invoices.proInvoiceNumber, invoices.grandTotal from invoices
						left join daily_reports on invoices.reportId = daily_reports.reportId 
                        left join employees on daily_reports.addedBy = employees.id
						left join customers on daily_reports.company = customers.customerId
                        where daily_reports.addedBy = '". $_SESSION['id'] ."'
					    order by invoices.invoiceId desc limit $start, $limit";
}

$outstandings = mysql_query($query);
?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Outstandings   </li>
						</ul><!-- /.breadcrumb -->
                        
                         <a href="excel/sa_outstanding.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>

						<!-- #section:basics/content.searchbox -->
						<div class="nav-search" id="nav-search">
                        
                       
							<form class="form-search">
                            
                            <?php                       
                               // team
	  $myteam = mysql_query("select teamId from teams where teamLeaderId = '". $_SESSION['id'] ."'");
	  
	  if(mysql_num_rows($myteam)>0) {
	  $myteam = mysql_fetch_array($myteam);
	  $teamMembers = mysql_query("select employees.id, employees.firstName  from team_members 
                          left join employees on team_members.memberId = employees.id
						  where team_members.teamId = '". $myteam['teamId'] ."'
						  order by team_members.teamMemberId desc");
      ?>                    <span class="input-icon">
									 <select class="nav-search-input" onchange="changeMonth(this.value)" id="tmid">
<option value="<?php echo $_SESSION['id']; ?>" <?php if(isset($_GET['tmid']) && $_SESSION['id']==$_GET['tmid']) { ?> selected="selected" <?php } ?>><?php echo $_SESSION['firstName']; ?></option>
<option value="0" <?php if(isset($_GET['tmid']) && $_GET['tmid']==0) { ?> selected="selected" <?php } ?>>Team</option>
                                    <?php while($teamMember = mysql_fetch_array($teamMembers))
								{ ?>
<option value="<?php echo $teamMember['id']; ?>" <?php if(isset($_GET['tmid']) && $teamMember['id']==$_GET['tmid']) { ?> selected="selected" <?php } ?>><?php echo $teamMember['firstName']; ?></option>
          
                                      <?php } ?>
                                    </select>
                              </span>
                              <?php } ?>
                             
								
                                
                           
                                <i class="ace-icon fa fa-search nav-search-icon"></i>
                               
                                

							</form>
						</div>

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content" id="outclick">
						<!-- #section:settings.box -->
						<!-- /.ace-settings-container -->

						<!-- /section:settings.box -->
					

						<div class="row">
                        <div class="col-xs-12">
                                    
                                    
                                                                        
                                    <form class="form-inline" method="get" action="" autocomplete="off">
                                  
                      
  
  
</form>

							<div class="col-xs-12">
                    <form class="form-inline" method="get" action="" autocomplete="off">
                                  
                      
  <div class="row">              
                                  
  <div class="form-group col-sm-2">
   <span>No. Days<br /></span>
    <select class="form-control date-picker input-sm" id="numDays" name="numDays">
    <option value="0">Select Days</option>
    <option value="30" <?php if(isset($_GET['numDays']) && $_GET['numDays']==30) { ?> selected="selected" <?php } ?>>30 Days</option>
    <option value="60" <?php if(isset($_GET['numDays']) && $_GET['numDays']==60) { ?> selected="selected" <?php } ?>>60 Days</option>
    <option value="90" <?php if(isset($_GET['numDays']) && $_GET['numDays']==90) { ?> selected="selected" <?php } ?>>90 Days</option>
    <option value="120" <?php if(isset($_GET['numDays']) && $_GET['numDays']==120) { ?> selected="selected" <?php } ?>>120 Days</option>
    </select>
    <?php  if( isset($_GET['tmid']) && $_GET['tmid']!='' )

	{ ?>
    <input type="hidden" name="tmid"  value="<?php  echo $_GET['tmid']; ?>"   /><?php } ?>
  </div>
 
 
 <div class="col-sm-2">
                                <span>Customer</span>
                                <input type="hidden" id="cid" name="cid" value="<?PHP echo $_GET['cid']; ?>" />

<input type="text" id="customer" name="customer" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Customer" <?php if(isset($_GET['customer'])) { ?> value="<?php echo $_GET['customer']; ?>" <?php } ?>  />
     
      <ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="customersList"></ul>
                               </div>
 
  <div class="form-group col-sm-2">
   <br>
    <input type="submit" class="btn btn-sm btn-success" name="outstandingSearch" value="Search">
  </div>
  
  </div>
  
</form>
<div class="space"></div>
</div>
                            
                              <!-- PAGE CONTENT BEGINS -->

  <?php
   if(isset($_GET['delete']))
      { echo '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Product has been deleted!</div>'; 
	  } else if(isset($_GET['error'])) 
		{ echo '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; 
	  }

 
										   ?> 
                                            
                                           
                                            

								<div class="row">
									<div class="col-xs-12">
										
										<div class="table-header">
											Outstandings
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">
											<table id="sample-table-1" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th class="center">S.no</th>
                                                     <th> Company Name</th>
                                                     <th>Enquiry Number</th>
                                                     <th>Invoice Number</th>
                                                     <th>Invoice Date</th>
                                                   <th>Product</th>
                                                   <th>Model</th>
                                                   <th>Quantity</th>

                                                        <th>Invoice No</th>
														<th>Total</th>
                                                        <th>Outstanding</th>
                                                       </tr>
												</thead>

												<tbody>
													
														
																	

  <?php 
		 $list[] = array('S. No', 'Employee', 'Date', 'Proforma No', 'Invoice No', 'Total', 'Outstanding' ); 

  if(mysql_num_rows($outstandings)>0)
  {
	 $i = $start+1; 
	 
	  
	  $grandOutstanding = 0;
  while($outstanding = mysql_fetch_array($outstandings))
  {
  // total amount;

  // pending amount
  $collectedAmount = mysql_query("select sum(collections.amount) as amount from collections                     
                        left join daily_reports on collections.invoiceId = daily_reports.reportId
						where collections.invoiceId = '". $outstanding['Invoiceid'] ."'
						order by collections.id");	


$qry = mysql_query("select proInvoiceNumber, reportId from invoices inv where inv.invoiceNumber='".$outstanding['invoiceNumber']."'");
$proinv = mysql_fetch_object($qry);


$qreey = mysql_query( "select drd.id from daily_reports_data as drd left join daily_reports_revision as drv on drv.revisionId=drd.revisionId left join daily_reports as dr on dr.reportId=drv.reportId where drv.reportId=".$proinv->reportId); 

	  $prd_quantity=0;

	  $prd_name="<ol>";
		 $prd_quantity = "<ol>";
		 $model = "<ol>";
while( $data = mysql_fetch_object($qreey) )
{

$qry_prdct = mysql_query("select drd.price,drd.quantity, prd.product,inv.proInvoiceNumber as invoiceNumber,pm.ModelNo from daily_reports_data drd join invoices inv on drd.invoice_id=inv.invoiceId join products prd on prd.productId=drd.productId left join product_model as pm on pm.ModelId=drd.modelId where inv.invoiceNumber='".$outstanding['invoiceNumber']."' and drd.id=".$data->id." order by drd.id desc");


		
		 
	  if(mysql_num_rows($qry_prdct)>0)
	  {
		while($prd = mysql_fetch_object($qry_prdct))  
		{
			  $prd_name.="<li>".$prd->product."</li>";
			  $prd_quantity.="<li>".$prd->quantity."</li>";
			  $model.="<li>".$prd->ModelNo."</li>";
		}
	  }
}
	  $prd_name.="</ol>";
	  $prd_quantity.="</ol>";
	  $model.="</ol>";




						
						 $grandCollected = 0;
 if(mysql_num_rows($collectedAmount)>0)
  {
	  
	 while($total = mysql_fetch_array($collectedAmount))
	 {
		$grandCollected = $total['amount']+$grandCollected; 
	 }
	   
  }
  
  $pending = $outstanding['grandTotal']-$grandCollected;
  $grandOutstanding =  $grandOutstanding+$pending;
	  
	 ?><tr>
														<td class="center">
															<?php echo $i; ?>
														</td>
                                                        
                                                        <td><?PHP echo $outstanding['company']; ?></td>
                                                        <td>
                                                        <?PHP 
															//invoiceDateTime,enquiryNumber
															if($outstanding['enquiryNumber']!='')
															{
																echo $outstanding['enquiryNumber'];
															}
															else
															echo "---";;
														?>
                                                        </td>
                                                        <td>
                                                        <?PHP echo $outstanding['invoiceNumber']; ?>
                                                        </td>
                                                         <td>
                                                        <?PHP 
															//invoiceDateTime,enquiryNumber
															echo $outstanding['invoiceDateTime'];
														?>
                                                        </td>
                                                       <td>
                                                       <?PHP
													   echo $prd_name;
													   /*$prd_quantity
													   $model*/
													   ?>
                                                       
													   
                                                       </td> 
                                                       <td> <?PHP echo $model;?></td>
                                                        <td> <?PHP echo $prd_quantity;?></td>
                                                       
                                                        <td>
															<?php echo $outstanding['invoiceNumber']; ?>
														</td>
                                                        <td>
															<?php echo $outstanding['grandTotal']; ?>
														</td>
<td>

<?PHP

$fetch_inv = mysql_query("select inv.invoiceId,dr.reportId,inv.invoiceNumber,emp.id,inv.grandTotal, emp.firstName from employees as emp left join daily_reports as dr on dr.addedBy=emp.id left join invoices as inv on inv.reportId=dr.reportId where emp.id=".$outstanding['id']);

	  
$InvoiceIDE = '';	
$pending=0;  										  
//inv.invoiceId, inv.invoiceNumber,emp.id,inv.grandTotal, emp.firstName fetch_inv
if(mysql_num_rows($fetch_inv))
{
	while($fet_data = mysql_fetch_object($fetch_inv) )
	{
		//get the paid and remaing amount
		if($outstanding['reportId']==$fet_data->reportId)
		{
			$InvoiceIDE = $fet_data->invoiceNumber;
		
			$qry = mysql_query("select sum(amount) totalpaid from collections where invoiceid=".$fet_data->invoiceId);
			
			if(mysql_num_rows($qry)>0)
			{
				$paid = mysql_fetch_object($qry);
				$pending= ($fet_data->grandTotal)-(int)($paid->totalpaid);
				$grandTotalPending=($grandTotalPending)+($pending);
				echo $pending;
			}
			
			
		}			
	}
}
else
		$pending=0;
		
		?>   



<?php  #echo $pending; ?>
</td>
                                                       </tr>
<?php

$rowlist[] = $i;
$rowlist[] = ucfirst($outstanding['firstName']);
$rowlist[] = $invDate;
$rowlist[] = $outstanding['proformaInvoice']; 
$rowlist[] = $outstanding['invoice']; 
$rowlist[] = $grandTotal;
$rowlist[] = $pending;
 $list[] = $rowlist;
 unset($rowlist);


 $i++; }
 $rowlist[] = '';
 $rowlist[] = '';
 $rowlist[] = '';
 $rowlist[] = '';
 $rowlist[] = '';
 
 
 
 $rowlist[] = $grandOutstanding;
 $list[] = $rowlist;
 unset($rowlist);
 
 
 
 ?>  <tr><td colspan="10"></td><td><?php echo   $grandOutstanding; ?></td></tr> <?php
 
  } else { ?> <tr><td colspan="10">No Data found.</td></tr> <?php }
 
 
    if($numRecords>$limit &&  !(isset($_GET['outstandingSearch']))) 
	{
  if(isset($_GET['page']))
 {
	$start = $_GET['page']*$limit; 
	$currentPage = $_GET['page']; 
 }
 else
 {
	$start = 0;
	$currentPage = 1;  
 }
  
   
  if($currentPage==$numPages)
  {
	  $firstlink = '';
	  $secondlink = '';
	  $thirdlink = 'ui-state-disabled';
	  $fourthlink = 'ui-state-disabled';
	 
	  
  }
  else if($currentPage<$numPages)
  {
	 
	  if($currentPage==1)
	  {  
	  $firstlink = 'ui-state-disabled';
	  $secondlink = 'ui-state-disabled';  
	  $thirdlink = '';
	  $fourthlink = '';
	  }
	  
	  
	  
  }
  
  ?>  
 <tr><td colspan="6">
  
  <table cellspacing="0" cellpadding="0" border="0" style="table-layout:auto;" class="ui-pg-table"><tbody>
  <tr>
  <td id="first_grid-pager" class="ui-pg-button ui-corner-all <?php echo $firstlink; ?>" onclick="goToPage('1')">
                 <span class="ui-icon ace-icon fa fa-angle-double-left bigger-140"></span>
  </td>
  <td id="prev_grid-pager" class="ui-pg-button ui-corner-all <?php echo $secondlink; ?>" onclick="goToPage('<?php echo $currentPage-1; ?>')">
               <span class="ui-icon ace-icon fa fa-angle-left bigger-140"></span>
  </td>
  <td class="ui-pg-button ui-state-disabled" style="width:4px;">
       <span class="ui-separator"></span>
  </td>
  <td dir="ltr">
             Page <input class="ui-pg-input" type="text" size="2" maxlength="7" value="<?php echo $currentPage; ?>" role="textbox"> of <span id="sp_1_grid-pager"><?php echo $numPages; ?></span>
  </td>
  <td class="ui-pg-button ui-state-disabled" style="width: 4px; cursor: default;">
         <span class="ui-separator"></span>
  </td>
  <td id="next_grid-pager" class="ui-pg-button ui-corner-all <?php echo $thirdlink; ?>" style="cursor: default;" onclick="goToPage('<?php echo $currentPage+1; ?>')">
                <span class="ui-icon ace-icon fa fa-angle-right bigger-140">></span>
  </td>
  <td id="last_grid-pager" class="ui-pg-button ui-corner-all <?php echo $fourthlink; ?>" onclick="goToPage('<?php echo $numPages; ?>')">
                <span class="ui-icon ace-icon fa fa-angle-double-right bigger-140"></span>
  </td>
  </tr></tbody></table>
  
  
  
  
  
  </td></tr>     
  <?php } ?>                                        
  </tbody>
											</table>
                                            
 <?php                              
$fp = fopen('excel/sa_outstanding.csv', 'w');

foreach ($list as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp) ?>
                                            
                                            
                                            
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
        
        
        <!-- page specific plugin scripts -->
		<script src="assets/js/date-time/bootstrap-datepicker.js"></script>
		<script src="assets/js/jqGrid/jquery.jqGrid.src.js"></script>
		<script src="assets/js/jqGrid/i18n/grid.locale-en.js"></script>
        
        <!-- page specific plugin styles -->
		<link rel="stylesheet" href="assets/css/jquery-ui.css" />
		<link rel="stylesheet" href="assets/css/datepicker.css" />
		<link rel="stylesheet" href="assets/css/ui.jqgrid.css" />




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
		
			$(document).on('click','#outclick,#breadcrumbs',function(){
			$('#customersList').css('display','none');
			});
	

$(document).on('keyup','#customer',function(){
			var customer = $(this).val();
			
			$.ajax({
				   url: 'ajax/getCustomerList.php',
				   type: 'POST',
				   data: {'customer':customer},
				   success:function(data){ 
				   $("#customersList").html(data);
				$("#customersList").css('display','block');
				   }
				
				
				});
			
			
			});


	
		
		function selectCustomer(id,firstName)
		{
			document.getElementById("customersList").style.display = 'none';
			document.getElementById("cid").value = id;
			document.getElementById("customer").value = firstName;
	
		}	

		
		function confirmDelete(did)
		{
			if(confirm("Do you want to delete the product."))
			{
				window.location = 'products.php?did='+did;
				
			}
		   	
		}
		
			function changeMonth(tmid)
		{
			//tmid
			window.location = 'sa_outstanding.php?tmid='+tmid;
		}
		
		
function goToPage(pid)
{
   window.location = 'sa_outstanding.php?page='+pid;	
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
