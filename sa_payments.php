<?php include("includes/sa_header.php"); 


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
	                        $whereItems[] = "employees.id = '". $_GET['tmid'] ."'";		
							
							$page_where = "employees.id = '". $_GET['tmid'] ."' and ";				
							
						}
						else if($_GET['tmid']==0)
						{ 
						
						  // team
	  $myteam = mysql_query("select teamId from teams where teamLeaderId = '". $_SESSION['id'] ."'");
	  
	
	 
	  if(mysql_num_rows($myteam)>0)
	  {
		    $myteam = mysql_fetch_array($myteam);
	    $teamMembers = mysql_query("select employees.id, employees.firstName, branches.branch from team_members 
								 left join branches on employees.branch=branches.branchId
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
						   
						$whereItems[] = "employees.id in ".$in_team_members;	
						$page_where = "employees.id in ".$in_team_members.' and ';		
						   
					  }	}
						
						$tmid = $_GET['tmid'];
									
					 } 
else
							{   // logged in user
								$whereItems[] = "employees.id = '". $_SESSION['id'] ."'";
								$page_where = "employees.id = '". $_SESSION['id'] ."' and ";				
								$tmid = 0;
							}

// pageination
 $limit = 10;
 // get the payment data from collections table
 $numRecords = mysql_query("select employees.firstName, collections.invoiceId, customers.company, collections.paymentType, branches.branch, collections.amount, collections.paidDate, daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.contactPerson, daily_reports.invoice from collections 
							left join invoices on collections.invoiceId = invoices.invoiceId
							left join daily_reports on invoices.reportId = daily_reports.reportId
							left join employees on daily_reports.addedBy = employees.id
					        left join branches on employees.branch=branches.branchId
							left join customers on daily_reports.company = customers.customerId
							where $page_where  collections.paidDate like '". $like ."'
							order by collections.id desc");
						
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
	
	
	// $whereItems[] = "employees.id = '". $_SESSION['id'] ."'";
	// $whereItems[] = "collections.paidDate like '". $like ."'"; 
//	 $whereItems[] = "MONTH(collections.paidDate)='".date('m')."'"; 
	 
    
  if(isset($_GET['paymentSearch']))
									{
										
									   
									  if(isset($_GET['fromDate']) && isset($_GET['toDate']))
								
								{
								if($_GET['fromDate']!='' && $_GET['toDate']!='')

									{
									
										$fromDate = explode('-',$_GET['fromDate']);
										$fromDate = $fromDate[2].'-'.$fromDate[1].'-'.$fromDate[0];
										
										$toDate = explode('-',$_GET['toDate']);
										$toDate = $toDate[2].'-'.$toDate[1].'-'.$toDate[0];
									 
									 
									  $whereItems[] = "collections.paidDate >=  '". $fromDate ."'";
								
									  $whereItems[] = "collections.paidDate <=  '". $toDate ."'";
									}
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
												
							
// get the payment data from collections table												
											$collections = mysql_query("select employees.firstName, collections.invoiceId, customers.company, branches.branch, collections.paymentType, collections.amount, collections.paidDate, daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.contactPerson, daily_reports.invoice from collections 
							left join invoices on collections.invoiceId = invoices.invoiceId
							left join daily_reports on invoices.reportId = daily_reports.reportId
							left join employees on daily_reports.addedBy = employees.id
								 left join branches on employees.branch=branches.branchId
							left join customers on daily_reports.company = customers.customerId							
							$where order by collections.id desc limit $start, $limit");
							
								
							
									}
									else 
									{ 
									
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
	// get the payment data from collections table					
$collections = mysql_query("select employees.firstName, collections.paymentType, branches.branch, collections.invoiceId, customers.company, collections.amount, collections.paidDate, daily_reports.reportId, daily_reports.currentRevisionId, daily_reports.contactPerson, daily_reports.invoice from collections 
							left join invoices on collections.invoiceId = invoices.invoiceId
							left join daily_reports on invoices.reportId = daily_reports.reportId
							left join employees on daily_reports.addedBy = '".$_SESSION['id']."'
							left join branches on employees.branch=branches.branchId
						left join customers on daily_reports.company = customers.customerId	  
							 $where order by collections.id desc limit $start, $limit");

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
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> Payments  </li>
						</ul><!-- /.breadcrumb -->
                        
             <a href="excel/payments.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>


						<!-- #section:basics/content.searchbox -->
						<div class="nav-search" id="nav-search" style="display:none;">
                        
                       
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
									 <select class="nav-search-input" onchange="changeMonth('<?php echo $cm; ?>',this.value)" id="tmid">
<option value="<?php echo $_SESSION['id']; ?>" <?php if(isset($_GET['tmid']) && $_SESSION['id']==$_GET['tmid']) { ?> selected="selected" <?php } ?>><?php echo $_SESSION['firstName']; ?></option>
<option value="0" <?php if(isset($_GET['tmid']) && $_GET['tmid']==0) { ?> selected="selected" <?php } ?>>Team</option>
                                    <?php while($teamMember = mysql_fetch_array($teamMembers))
								{ ?>
<option value="<?php echo $teamMember['id']; ?>" <?php if(isset($_GET['tmid']) && $teamMember['id']==$_GET['tmid']) { ?> selected="selected" <?php } ?>><?php echo $teamMember['firstName']; ?></option>
          
                                      <?php } ?>
                                    </select>
                              </span>
                              <?php } ?>
                             
								<span class="input-icon">
									 <select class="nav-search-input" onchange="changeMonth(this.value,'<?php echo $tmid; ?>')">
                                <option value="01" <?php if($cm=='01') { echo 'selected="selected"'; } ?>>January</option>
                                <option value="02" <?php if($cm=='02') { echo 'selected="selected"'; } ?>>Febraury</option>
                                <option value="03" <?php if($cm=='03') { echo 'selected="selected"'; } ?>>March</option>
                                <option value="04" <?php if($cm=='04') { echo 'selected="selected"'; } ?>>April</option>
                                <option value="05" <?php if($cm=='05') { echo 'selected="selected"'; } ?>>May</option>
                                <option value="06" <?php if($cm=='06') { echo 'selected="selected"'; } ?>>June</option>
                                <option value="07" <?php if($cm=='07') { echo 'selected="selected"'; } ?>>July</option>
                                <option value="08" <?php if($cm=='08') { echo 'selected="selected"'; } ?>>August</option>
                                <option value="09" <?php if($cm=='09') { echo 'selected="selected"'; } ?>>September</option>
                                <option value="10" <?php if($cm=='10') { echo 'selected="selected"'; } ?>>October</option>
                                <option value="11" <?php if($cm=='11') { echo 'selected="selected"'; } ?>>November</option>
                                <option value="12" <?php if($cm=='12') { echo 'selected="selected"'; } ?>>December</option>
                                </select>
									
                                    
								</span>
                                
                           
                                <i class="ace-icon fa fa-search nav-search-icon"></i>
                               
                                

							</form>
						</div><!-- /.nav-search -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content" id="outclick">
						<!-- #section:settings.box -->
						<!-- /.ace-settings-container -->

						<!-- /section:settings.box -->
					

						<div class="row">
                        
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								
                                    <div class="row">
									        <div class="col-xs-12">
                                            <form class="form-inline" method="get" action="" autocomplete="off">
                                  
                      
  <div class="row">              
                                  
  <div class="form-group col-sm-2">
   <span>From Date</span>
    <input type="text" class="form-control date-picker input-sm" id="fromDate" name="fromDate" placeholder="From Date" <?php if(isset($_GET['fromDate'])) { ?> value="<?php echo $_GET['fromDate']; ?>" <?php } ?> />
  </div>
  <div class="form-group col-sm-2">
   <span>To Date</span>
   <input type="text" class="form-control date-picker input-sm" id="toDate" name="toDate" placeholder="To Date" <?php if(isset($_GET['toDate'])) { ?> value="<?php echo $_GET['toDate']; ?>" <?php } ?> />
  </div>
  
                     
                               
                                <div class="col-sm-2">
                                <span>Customer</span>
                                <input type="hidden" id="cid" name="cid" value="<?PHP echo $_GET['cid']; ?>" />

<input type="text" id="customer" name="customer" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Customer" <?php if(isset($_GET['customer'])) { ?> value="<?php echo $_GET['customer']; ?>" <?php } ?>  />
     
      <ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="customersList"></ul>
                               </div> 
                            
                                       
  <div class="form-group col-sm-2">
   <br/>
    <input type="submit" class="btn btn-sm btn-success" name="paymentSearch" value="Search" />
  </div>
  
  </div>
                                
                              
                                 
                               
  
</form>
                                    
										<div class="table-header">
											Payments
										</div>

										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">
                                        
                                        
                                        
											<table id="sample-table-1" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th class="center">S.no</th>
														<th>Paid Date</th>
                                                        <th>Invoice Number</th>
                                                         <th>Employee</th>
                                                        <th>Branch</th>
                                                        <th>Company Name</th>
                                                        <th> Product</th>
                                                        <th>Model</th>
                                                        <th> Quantity</th>
                                                        <th>Total</th>
                                                        <th>Collected</th>
                                                        <th>Type</th>
														</tr>
												</thead>

												<tbody>
													
														
																	

  <?php 
  		 $list[] = array('S. No', 'Date', 'Invoice', 'Customer', 'Total' , 'Collected', 'Type' );

  if(mysql_num_rows($collections)>0)
  {  
   $i = $start+1; 
  
  $finalCollected = 0;
  while($collection = mysql_fetch_array($collections))
  {
	
	
	

$qry = mysql_query("select proInvoiceNumber, reportId from invoices inv where inv.invoiceNumber='". $collection['invoice']."'");
$proinv = mysql_fetch_object($qry);


$qreey = mysql_query( "select drd.id from daily_reports_data as drd left join daily_reports_revision as drv on drv.revisionId=drd.revisionId left join daily_reports as dr on dr.reportId=drv.reportId where drv.reportId=".$proinv->reportId); 

	  $prd_quantity=0;

	  $prd_name="<ol>";
		 $prd_quantity = "<ol>";
		 $model = "<ol>";
while( $data = mysql_fetch_object($qreey) )
{

$qry_prdct = mysql_query("select drd.price,drd.quantity, prd.product,inv.proInvoiceNumber as invoiceNumber,pm.ModelNo from daily_reports_data drd join invoices inv on drd.invoice_id=inv.invoiceId join products prd on prd.productId=drd.productId left join product_model as pm on pm.ModelId=drd.modelId where inv.invoiceNumber='". $collection['invoice']."' and drd.id=".$data->id." order by drd.id desc");


		
		 
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


	
	
	
	
	
	 // total amount;
	
  $totalAmount = mysql_query("select daily_reports_revision.grandTotal from daily_reports_revision
  left join daily_reports_data on daily_reports_revision.revisionId = daily_reports_data.revisionId
where daily_reports_revision.reportId = '". $collection['reportId'] ."' and daily_reports_revision.revision = '". $collection['currentRevisionId'] ."'");
  
  /*if(mysql_num_rows($totalAmount)>0)
  {
	  
	 while($total = mysql_fetch_array($totalAmount))
	 {
		$grandTotal = $total['grandTotal']+$grandTotal; 
	 }
	   
  }*/
  if(mysql_num_rows($totalAmount)>0)
  {
	  $total = mysql_fetch_array($totalAmount);
	  $grandTotal = $total['grandTotal']; 
  }
  else
  { $grandTotal = 0; }
  

	  										  
	

  
	  
	 ?><tr>
														<td class="center">
															<?php echo $i; ?>
														</td>
                                                        <td>
															<?php 
															      $date = explode('-',$collection['paidDate']);
																  echo $date[2].'-'.$date[1].'-'.$date[0];
																       ?>
														</td>
														<td>
															<?php echo $collection['invoice']; ?>
														</td>
                                                       <td>
															<?php echo $collection['firstName']; ?>
														</td>
                                                        <td>
															<?php echo $collection['branch']; ?>
														</td>
                                                        <td>
															<?php echo ucfirst($collection['company']); ?>
														</td>
                                                          <td>                                                      
															<?php 
															//echo $prd_name; 
															
															 echo $prd_name;
	  
															?>
														</td>
														<td> <?PHP echo $model; ?> </td>
                                                        <td>                                                      
															<?php echo $prd_quantity; ?>
														</td>
                                                        
                                                        <td>
															<?php echo $grandTotal; ?>
														</td>
                                                         <td>
															<?php echo $collection['amount']; ?>
														</td>
                                                        
                                                        
														<td>
                                                        
                                                        
                                                        <?php 
														
					 if($collection['paymentType']==1) { $paymentCollection = 'Proforma'; } 
					 else if($collection['paymentType']==2) { $paymentCollection = 'Through Bank'; } 
			         else if($collection['paymentType']==3) { $paymentCollection = 'Direct Payment'; } 
				     else if($collection['paymentType']==4) { $paymentCollection = 'Against Delivery'; }
					 else if($collection['paymentType']==5) { $paymentCollection = 'By Check/DD'; } 
			         else if($collection['paymentType']==6) { $paymentCollection = 'Bank Transfer'; } 
				     else if($collection['paymentType']==7) { $paymentCollection = 'By Cash'; }
											echo $paymentCollection;			?>
                                                        
                                                          
														</td>

													
													</tr>
<?php 
 $finalCollected = $finalCollected+$collection['amount'];
 
$rowlist[] = $i;
$rowlist[] = $collection['paidDate'];
$rowlist[] = $collection['invoice']; 
$rowlist[] = ucfirst($collection['contactPerson']);
$rowlist[] = $grandTotal;
$rowlist[] = $collection['amount'];
$rowlist[] = $paymentCollection;
 $list[] = $rowlist;
 unset($rowlist);

 $i++; }
$rowlist[] = '';
$rowlist[] = '';
$rowlist[] = '';
$rowlist[] = '';
$rowlist[] = '';
 $rowlist[] = $finalCollected;
 $list[] = $rowlist;
 unset($rowlist);
 
 ?> <tr><td colspan="10"></td><td><?php echo $finalCollected; ?></td><td colspan="1"></td></tr>   <?php
 
 
  } else { ?> <tr><td colspan="8"> No Data found. </td></tr> <?php }
  
   if($numPages>1) 
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
 <tr><td colspan="8">
  
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
  <?php } ?>                                            </tbody>
											</table>
                                            
       <?php                              
$fp = fopen('excel/sa_payments.csv', 'w');

foreach ($list as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp) ?>
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
        <script src="assets/js/date-time/bootstrap-datepicker.js"></script>
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
		
		function changeMonth(cm,tmid)
		{
			
			window.location = 'sa_payments.php?cm='+cm+'&tmid='+tmid;
		}
		

		
		
		function goToPage(pid)
{
	alert(pid);
   window.location = 'sa_payments.php?page='+pid;	
}
	// datepicker
			$('.date-picker').datepicker({
					autoclose: true,
					todayHighlight: true,
					format: 'dd-mm-yyyy'
				})
				

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
