<?php include("includes/header.php"); 

if(isset($_SESSION['accountsAdmin']))
{
	header("location: pos_backup.php"); 		
}

// delet report
if(isset($_GET['did']))
{
mysql_query("delete from daily_reports where reportId = '". $_GET['did'] ."'");
header("location: proposals.php?delete=1"); 
}
 

  
// pagination
 $limit = 10;
 
// we get the po data from daily_reports table 
/* to get the enquiries data we will execute this query with employees table.
relation between daily_reports and enquiries is enquiryNumber.
relation between enquiries and customers are enquiries.company = customers.customerId.
relation between daily_reports and employees are daily_reports.addedBy = employees.id
relation between branches and employees are employees.branch = branches.branchId
*/


//if( trim($proposal['leadStatus']) == 'PO collected' || trim($proposal['leadStatus']) == 'Pending Proforma Invoice')


 $numRecords = mysql_query("select daily_reports.reportId, daily_reports.reportDate, daily_reports.currentRevisionId, daily_reports.enquiryNumber, date_format(enquiries.dateTime,'%d-%m-%Y') enqDate, branches.branch, daily_reports.leadType, daily_reports.leadStatus, daily_reports.poNo, daily_reports.poDateTime, daily_reports.inv, daily_reports.invoice, daily_reports.invoiceDateTime, daily_reports.runcard, customers.company, employees.firstName from daily_reports
inner join customers on daily_reports.company = customers.customerId
inner join employees on daily_reports.addedBy = employees.id
inner join branches on employees.branch = branches.branchId
inner join enquiries on daily_reports.enquiryNumber = enquiries.enquiryNumber
where daily_reports.offer >0 and daily_reports.po >0 and ( daily_reports.leadStatus='Pending Proforma Invoice' or daily_reports.leadStatus='PO collected' ) GROUP BY reportId  ");
 
 
 //PO collected,Pending Proforma Invoice
 
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
    $whereItems[] = "daily_reports.offer > '0' and ( daily_reports.leadStatus='Pending Proforma Invoice' or daily_reports.leadStatus='PO collected' )";
	$whereItems[] = "daily_reports.po > '0' and ( daily_reports.leadStatus='Pending Proforma Invoice' or daily_reports.leadStatus='PO collected' )";
  if(isset($_GET['proposalSearch']))
									{
										/* from date to todate search based on "daily_reports.dateTime".
					employee search based on employees.id
					customer search based on enquiries.companyId.  
					branch search based on employees.branch
					*/	
										
								//	if(isset($_GET['fromDate']) && strlen($_GET['fromDate']>8) && isset($_GET['toDate']) && strlen($_GET['toDate']>8))
									if(isset($_GET['fromDate'])  && isset($_GET['toDate']))
									{
										if($_GET['fromDate']!='' && $_GET['toDate']!='')
										{
									
											$fromDate = explode('-',$_GET['fromDate']);
											$fromDate = $fromDate[2].'-'.$fromDate[1].'-'.$fromDate[0];
											
											$toDate = explode('-',$_GET['toDate']);
											$toDate = $toDate[2].'-'.$toDate[1].'-'.$toDate[0];
										 
										 
										 $whereItems[] = "daily_reports.reportDate >='". $fromDate ."'";
										 
										 $whereItems[] = "daily_reports.reportDate <= '". $toDate ."'";
									}
									}
									
									
									   // by employee
									   if(isset($_GET['eid']) && $_GET['eid']>0)
									   {  
									    
										    $whereItems[] = "employees.id =  '". $_GET['eid'] ."'";
									   }
									   
									    // by customer
									   if(isset($_GET['cid']) && $_GET['cid']>0)
									   {  
									  
										    $whereItems[] = "customers.customerId =  '". $_GET['cid'] ."'";
									   }
									   
									
									// by Branch
									   if(isset($_GET['bid']) && $_GET['bid']>0)
									   {  
									  
										    $whereItems[] = "employees.branch =  '". $_GET['bid'] ."'";
									   }
									   
									 if( isset($_GET['catid']) && $_GET['catid']>0)
									{
										$whereItems[] = "cat.id=  '". $_GET['catid'] ."'";
									}
									
									if( isset($_GET['prdid']) && $_GET['prdid']>0)
									{
										$whereItems[] = "drd.productId=  '". $_GET['prdid'] ."'";
									}
										

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
												
					// we get the po data from daily_reports table 					
$proposals = mysql_query("select daily_reports.reportId,drd.revisionId, daily_reports.offer, daily_reports.currentRevisionId, daily_reports.enquiryNumber, branches.branch, daily_reports.offerNo, date_format(enquiries.dateTime,'%d-%m-%Y') enqDate, daily_reports.offerToBeSubmitted,  daily_reports.currentRevisionId, daily_reports.reportDate, daily_reports.leadType, daily_reports.leadStatus, daily_reports.po, daily_reports.poNo, daily_reports.poDateTime, daily_reports.inv, daily_reports.invoice, daily_reports.invoiceDateTime, daily_reports.runcard, daily_reports.proformaInvoice, daily_reports.proformaInvoiceDateTime, customers.company, employees.firstName,pm.ModelNo  from daily_reports
inner join customers on daily_reports.company = customers.customerId
inner join employees on daily_reports.addedBy = employees.id
inner join enquiries on daily_reports.enquiryNumber = enquiries.enquiryNumber
inner join branches on employees.branch = branches.branchId
inner join daily_reports_revision drv on drv.reportId=daily_reports.reportId
inner join daily_reports_data as drd on drd.revisionId=drv.revisionId
inner join categories as cat on cat.id=drd.categoryId
inner join product_model as pm on pm.ModelId=drd.modelId
$where
 GROUP BY reportId limit $start, $limit");							

}

	else
	{
										// we get the po data from daily_reports table 
	$proposals = mysql_query("select daily_reports.reportId,drd.revisionId, daily_reports.offer, daily_reports.currentRevisionId, daily_reports.enquiryNumber, branches.branch, daily_reports.offerNo, date_format(enquiries.dateTime,'%d-%m-%Y') enqDate, daily_reports.offerToBeSubmitted,  daily_reports.currentRevisionId, daily_reports.reportDate, daily_reports.leadType, daily_reports.leadStatus, daily_reports.po, daily_reports.poNo, daily_reports.poDateTime, daily_reports.inv, daily_reports.invoice, daily_reports.invoiceDateTime, daily_reports.runcard, daily_reports.proformaInvoice, daily_reports.proformaInvoiceDateTime, customers.company, employees.firstName,pm.ModelNo  from daily_reports inner join customers on daily_reports.company = customers.customerId inner join employees on daily_reports.addedBy = employees.id inner join branches on employees.branch = branches.branchId 	inner join enquiries on daily_reports.enquiryNumber = enquiries.enquiryNumber 	inner join daily_reports_revision drv on drv.reportId=daily_reports.reportId inner join daily_reports_data as drd on drd.revisionId=drv.revisionId 	inner join categories as cat on cat.id=drd.categoryId inner join product_model as pm on pm.ModelId=drd.modelId   GROUP BY reportId order by daily_reports.reportId desc limit $start, $limit");	


}



 $branches = mysql_query("select * from branches order by branch");
?>
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li class="active"> <i class="ace-icon fa fa-home home-icon"></i> PO's</li>
						</ul><!-- /.breadcrumb -->

					  <a href="excel/pos.csv" title="Download"><span class="badge badge-success"><i class="ace-icon fa fa-cloud-download bigger-110 icon-only"></i></span></a>
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
                                  
  <div class="col-sm-2" style="width:11%">
   <span>From Date</span>
    <input type="text" class="form-control date-picker input-sm" id="fromDate" name="fromDate" placeholder="From Date" <?php if(isset($_GET['fromDate'])) { ?> value="<?php echo $_GET['fromDate']; ?>" <?php } ?> style="width:100%"  />
  </div>
  <div class="col-sm-2" style="width:11%">
   <span>To Date</span>
   <input type="text" class="form-control date-picker input-sm" id="toDate" name="toDate" placeholder="To Date" <?php if(isset($_GET['toDate'])) { ?> value="<?php echo $_GET['toDate']; ?>" <?php } ?> style="width:100%"  />
  </div>
 
                 
            
        
      <div class="col-sm-2" style="width:11%">
     <span>Branch</span><br />
     <select id="bid" name="bid">
     <option value="">Select Branch</option>
     <?php  
	 while ($branch = mysql_fetch_array($branches))
	 {  ?>
 <option <?php if(isset($_GET['bid']) && $_GET['bid']==$branch['branchId']) { ?> selected="selected" <?php } ?>  value="<?php echo $branch['branchId'] ?>"><?php echo $branch['branch'] ?></option>
                                         
                                            
                                        <?php
                                           }  ?>     </select> 
</div>      
                 
    
<div class="col-sm-2" style="width:13%">
<span>Category</span><br />
<?PHP
	$categ = mysql_query("SELECT * FROM `categories`");
	
?>
<select id="catid" name="catid" style="width:100%">
<option value="">Select Category</option>
<?php  
while ($categories = mysql_fetch_array($categ))
{  ?>
<option <?php if(isset($_GET['catid']) && $_GET['catid']==$categories['id']) { ?> selected="selected" <?php } ?>  value="<?php echo $categories['id']; ?>"><?php echo $categories['category'] ?></option>


<?php
}  ?>     </select> 
</div>

<div class="col-sm-2" style="width:13%">
<span>Product</span><br />
<?PHP
	$prdcts = mysql_query("SELECT * FROM `products` order by product ASC");
	
?>
<select id="prdid" name="prdid"  style="width:100%">
<option value="">Select Product</option>
<?php  
while ($products = mysql_fetch_array($prdcts))
{  ?>
<option <?php if(isset($_GET['prdid']) && $_GET['prdid']==$products['productId']) { ?> selected="selected" <?php } ?>  value="<?php echo $products['productId']; ?>"><?php echo $products['product'] ?></option>


<?php
}  ?>     </select> 
</div>             
                 
                 
                 
                                            
                               
<div class="col-sm-2" style="width:11%">
                               
                                <span>Employee</span>
       <input type="hidden" id="eid" name="eid" value="<?PHP echo $_GET['eid']; ?>" />
     <input type="text" id="employee" name="employee" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Employee" <?php if(isset($_GET['employee'])) { ?> value="<?php echo $_GET['employee']; ?>" <?php } ?> onkeyup="getEmployee(this.value)"  />
           <ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="employeesList">
                                           </ul>
                                           
                                </div> 
                                
                                
                     
                               
<div class="col-sm-2" style="width:11%">
                                <span>Customer</span>
                                <input type="hidden" id="cid" name="cid" value="<?PHP echo $_GET['cid']; ?>" />

     <input type="text" id="customer" name="customer" class="form-control col-xs-4 col-sm-12 input-sm" placeholder="Customer" <?php if(isset($_GET['customer'])) { ?> value="<?php echo $_GET['customer']; ?>" <?php } ?>  />
     
      <ul class="typeahead dropdown-menu" style="top: 40px; left: 0px; display: none; height:100px; width:250px; overflow:auto; margin:0px; padding:0px; border:0px;" id="customersList"></ul>
                               </div> 
                                
                                
                                       
  <div class="form-group col-sm-2">
   <br/>
    <input type="submit" class="btn btn-sm btn-success" name="proposalSearch" value="Search" />
  </div>
  
  </div>
                                
                              
                                 
                               
  
</form>
<div class="space"></div>
</div>

									<div class="col-xs-12">
										
										<div class="table-header">
											PO's
										</div>


    <?php
										   
	   if(isset($_GET['delete']))
{ $alertMsg = '<div class="alert alert-info"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Proposal has been deleted!</div>'; }
else if(isset($_GET['error']))
{ $alertMsg = '<div class="alert alert-danger"><button class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>Error occured, please try again.</div>'; }

  if(isset($alertMsg)) { echo $alertMsg; }
										   ?> 
										<!-- <div class="table-responsive"> -->

										<!-- <div class="dataTables_borderWrap"> -->
										<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">
											
													
													
																	

  <?php 
    // to download file in excel format we have written this code.
   $list[] = array('S. No', 'Report Date', 'Offer No', 'Po No', 'Po Date', 'Value', 'Executive', 'Company Name', 'Status' );
  $finalTotal = 0;
  
//  echo mysql_num_rows($proposals); exit; 
  
  if(mysql_num_rows($proposals)>0)
  {
	  ?>
      
      <?PHP
  $i = $start+1; 
  
$cnte=0;
#$data_exists=0;
#echo mysql_num_rows($proposals)."<br>"; exit; 
  while($proposal = mysql_fetch_array($proposals))
  {
//	  echo $cnte."<br>".$proposal['revisionId']."<br>".$proposal['leadStatus']."<br>";
	  if($cnte == 0)
	  {
		  ?>
          <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                            
												<thead>
													<tr>
														<th class="center">
															S.no
														</th>
														<th>Customer Po No</th>
                                                        <th>Customer Po Date</th>
                                                         <th>Company Name</th>
                                                         <th>Product</th>
                                                          <th>Model</th>
                                                        <th>Value</th>
                                                        <th>Quantity</th>
                                                        <th>Status</th>
                                                       
                                                        <th>Action</th>
														
													</tr>
												</thead>

												<tbody>	
          <?PHP
	  }
	  $cnte++; 

//  if( $proposal['leadStatus'] == 'Pending Invoice' && $proposal['leadStatus'] == 'PO collected' && !isset($_SESSION['ordersandExecution'])) Invoice generated

//if( (isset($_SESSION['ordersandExecution']) && ($proposal['leadStatus'] == 'Proforma Generated') ) || ( isset($_SESSION['admin']) && ( $proposal['leadStatus'] == 'Proforma Generated' || $proposal['leadStatus'] == 'Pending Invoice' || $proposal['leadStatus'] == 'PO collected' ) ) )

//echo $proposal['leadStatus']."<br>"; 

//if( ( isset($_SESSION['superAdmin']) && ( trim($proposal['leadStatus']) == 'PO collected'  || trim($proposal['leadStatus']) == 'Pending Proforma Invoice' || trim($proposal['leadStatus']) == 'Pending Invoice' || trim($proposal['leadStatus']) == 'Invoice generated' || trim($proposal['leadStatus']) == 'Proforma generated' ) )||( isset($_SESSION['ordersandExecution']) && ( trim($proposal['leadStatus']) == 'PO collected') )||( isset($_SESSION['accountsAdmin']) && ( trim($proposal['leadStatus']) == 'Pending Proforma Invoice' || trim($proposal['leadStatus']) == 'Pending Invoice' || trim($proposal['leadStatus']) == 'Invoice generated')  ) )
//{
	



		      $data_exists=1;
	  
	     
	  $totalAmount = mysql_query("select revisionId, grandTotal from daily_reports_revision where daily_reports_revision.reportId = '". $proposal['reportId'] ."' and daily_reports_revision.revision = '". $proposal['currentRevisionId'] ."'");
	  
	  
	  

//echo  mysql_num_rows($totalAmount)."<br>"; 
$prop="";
  if(mysql_num_rows($totalAmount)>0)
  {
	  $total = mysql_fetch_array($totalAmount);
	  $rev = $total['revisionId'];
	 
	  $grandTotal = $total['grandTotal']; 
  }
  else
  
  { $grandTotal = 0; }
  
  $finalTotal = $finalTotal+$grandTotal;  
  
// get the data from product table. 
$items = mysql_query("select products.productId, products.product, daily_reports_data.id, pm.ModelNo ,daily_reports_data.quantity from daily_reports_data
 left join products on daily_reports_data.productId = products.productId
 left join product_model as pm on pm.ModelId=daily_reports_data.modelId
 where daily_reports_data.revisionId = '".$proposal['revisionId']."'");
 
		$prd_itm="<ol>";
		$prd_qty = "<ol>";
		$model="<ol>";
		
		
if(mysql_num_rows($items)>0)
{
	while($item = mysql_fetch_array($items))
	{
		
		/*
		$prd_itm.="<li>".$item['product']."</li>";
		$prd_qty.="<li>".$item['quantity']."</li>";
		$model.="<li>".$item['ModelNo']."</li>";
	*/

	 ?>
     <tr>
     
														<td class="center">
															<?php echo $i; ?>
														</td>
                                                        <td>
                                                        
															<?php
															
														
															 echo $proposal['poNo']; 
															?>
                                
														</td>
                                                        
                                                        <td><?php
															 $poDate = explode(' ',$proposal['poDateTime']); 
														     $poDate = explode('-',$poDate[0]); 
														     $poDate = $poDate[2].'-'.$poDate[1].'-'.$poDate[0];
														
														 if(strcmp('00-00-0000',$poDate)!=0)
												{
													echo $poDate;
												}
															?>
                                                        </td>
                                                        <td>
															<?php echo ucfirst($proposal['company']); ?>
														</td>
                                                         
                                                        <td>
                                                        
															<?php
															
														//echo $prd_itm;
														echo $item['product'];
															
															?>
                                
														</td>
                                                        <td>
                                                        <?PHP 
														//echo $model;
														echo $item['ModelNo'];
														?>
                                                        </td>
                                                       
                                                         <td>
															<?php  echo $grandTotal;  ?>
														</td>
                                                         
                                                        <td>
															<?php 
															//echo $prd_qty;
															echo $item['quantity'];
															 ?>
															
														</td>
                                                        <td><?PHP echo  trim($proposal['leadStatus']);?></td>
                                                        
 														<td>
                             <a href="view_po.php?pid=<?php echo $proposal['reportId']; ?>" class="btn btn-pink btn-sm">view</a>

                                                       
                                         
                                           
                                                        <?php 
														
														$exist_not = mysql_query("select * from runcards where  (ReportId=".$proposal['reportId']." and RunCard='".trim($proposal['runcard'])."') and (ProductId=".$item['productId']." and ModelId='".$item['ModelNo']."') " );
												//echo $proposal['offerToBeSubmitted']."-".$proposal['po']."-".$proposal['inv']; 
														if($proposal['offerToBeSubmitted']<2 && $proposal['po']==0)
														{ ?>
     <a class="btn btn-primary btn-sm" href="generate_offer.php?pid=<?php echo $proposal['reportId']; ?>">Add Offer</a>
                                                   <?php  
												   
												   }
//check whether this [roduct and model had a runcard number or not												   


		
		    elseif($proposal['runcard']!='' && $proposal['offer']>0 && $proposal['po']==1 && $proposal['inv']==0)  {  
			
	if(mysql_num_rows($exist_not)>0)
	{
			
			?>
     &nbsp;<a href="generate_proinvoice.php?pid=<?php echo $proposal['reportId']; ?>" class="btn btn-pink btn-sm">Add Pro Invoice</a>
    
     <?php 
	}
	else
	{
		
	}
	 
	 }
	
	 
	 else {/* ?>
     <a href="view_po.php?pid=<?php echo $proposal['reportId']; ?>" class="btn btn-pink btn-sm">view</a>
                                             <?php */} 
				if($_SESSION['ordersandExecution'])
				{ 
					if(mysql_num_rows($exist_not)==0)
					{
				?>
                
<button type="button" class="btn runcardId btn-info btn-sm" data-toggle="modal" rc_no="<?PHP echo trim($proposal['runcard']);?>" id="<?php echo $proposal['reportId'] ?>" productId="<?PHP echo $item['productId']; ?>" ponumber="<?PHP echo $proposal['poNo'];?>" modelno="<?PHP echo $item['ModelNo'];?>" data-target="#myModal">Add run card</button>
               
			<?php	
					}
			} ?>
                                             
 <!-- run card for order and execution department only  --> 

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Run Card </h4>
        <div id="success-msg">
      </div>
      <div class="modal-body">
      <div>


       <input type="text" name="runcard" id="runcard" placeholder="Run card"  />
       <input type="hidden" name="po_no" id="po_no" />
       <span id="runcard-err" class="err-msg"></span>
       </div>
      <div style="margin-top:20px;">
       <textarea name="paymentTerms" id="paymentTerms" style="width:300px;" placeholder="Payment Terms"></textarea>
       <span id="paymentTerms-err" class="err-msg"></span>
      </div>
      
       <div style="margin-top:20px;">
      <input type="text" name="delCommittedDate" id="delCommittedDate" style="width:300px;" class="date-picker" placeholder="Committed Date" />
       </div>
      
      
       <div style="margin-top:20px;">
      <input type="text" name="delAcceptanceDate" id="delAcceptanceDate" style="width:300px;" class="date-picker" placeholder="Dispatch Date" />
		</div>
        
        <div style="margin-top:20px;">
      <input type="text" name="vendorDispatch" id="vendorDispatch" style="width:300px;" class="date-picker" placeholder="Vendor Dispatch Date" />
		</div>
        
         <div style="margin-top:20px;">
         <input type="hidden" id="modelno" />
         <input type="hidden" id="productid" />
   <select style="width:300px;" name="purchasePoDrop" id="purchasePoDrop" class="dropdownpo">
   <option value="0">Select Item</option>
   <option value="1">Ex-Stock</option>
   <option value="2">Purchase Po Number</option> 
   </select>
		</div>
        
      <div style="display:none;" id="poshow">
       <div style="margin-top:20px;">
      <input type="text" name="purchasePoNumber" id="purchasePoNumber" style="width:300px;" placeholder="Purchase PO Number" />
		</div>
         <div style="margin-top:20px;">
      <input type="text" name="purchasePoDate" id="purchasePoDate" style="width:300px;" class="date-picker" placeholder="Purchase PO Date" />
		</div>  
        </div>
        
        <div style="margin-top:20px;">
   <select style="width:300px;" name="purchasePoType" id="purchasePoType">
   <option value="0">Select PO Type</option>
   <option value="1">ITA</option>
   <option value="2">MRC</option> 
   <option value="3">MDS</option>
   <option value="4">MRS</option> 
   <option value="5">DIS DT</option>
   </select>
		</div>
        
        </div>
        
      
      <div class="modal-footer">
        <button type="button" class="btn runcardSub btn-default" id="runcardSub">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>                                           
                                                        
</div>
                                        		</td>

													
													</tr>
                                                    
<?PHP
	   }
	   
  }
  ?>
  
<?php
// to download file in excel format we have written this code.
$rowlist[] = $i;
$rowlist[] = $reportDate;
$rowlist[] = $proposal['offerNo'];
$rowlist[] = $proposal['poNo'];
$rowlist[] = $poDate;
$rowlist[] = $grandTotal;
$rowlist[] = ucfirst($proposal['firstName']);
$rowlist[] = ucfirst($proposal['company']);
$rowlist[] = $proposal['leadStatus'];
$list[] = $rowlist;
 unset($rowlist);


 $i++;
 
 		
		
	
/*		
if( trim($proposal['leadStatus']) == 'PO collected' || trim($proposal['leadStatus']) == 'Pending Proforma Invoice')
{
	
}

*/
	
	
		//} // if close

 }//while close 
 $rowlist[] = '';
 $rowlist[] = '';   
  $rowlist[] = '';
 $rowlist[] = '';
 $rowlist[] = '';
  $rowlist[] = $finalTotal;
  $list[] = $rowlist;
 unset($rowlist);
  
if($data_exists==0)
{
	?>
    <tr> <td colspan="8">No data found</td> </tr>
    <?PHP	
}
   
  
   } else { ?> <tr><td colspan="15">No Data found.</td></tr> <?php }
  
    if($numRecords>$limit &&  !(isset($_GET['proposalSearch']))) 
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
	  else
	  {
		 
	  }
  }
  
 if($numRecords>$limit) {  ?> 
  <tr><td colspan="12">
  
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
             Page <input class="ui-pg-input" type="text" onkeyup="goToPage(this.value)" size="2" maxlength="7" value="<?php echo $currentPage; ?>" role="textbox"> of <span id="sp_1_grid-pager"><?php echo $numPages; ?></span>
  </td>
  <td class="ui-pg-button ui-state-disabled" style="width: 4px; cursor: default;">
         <span class="ui-separator"></span>
  </td>
  <td id="next_grid-pager" class="ui-pg-button ui-corner-all <?php echo $thirdlink; ?>" style="cursor: default;"  <?PHP if($numPages>$currentPage) { ?>onclick="goToPage('<?php echo $currentPage+1; ?>')"<?PHP }  ?> >
                <span class="ui-icon ace-icon fa fa-angle-right bigger-140">></span>
  </td>
  <td id="last_grid-pager" class="ui-pg-button ui-corner-all <?php echo $fourthlink; ?>" onclick="goToPage('<?php echo $numPages; ?>')">
                <span class="ui-icon ace-icon fa fa-angle-double-right bigger-140"></span>
  </td>
  </tr></tbody></table>
  
  
  
  
  
  </td></tr>
  <?php } ?> 
  <?php } ?>
  </tbody>
											</table>
                                            
                                            
                                                                                 <?php                              
$fp = fopen('excel/pos.csv', 'w');

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
        <script src="assets/js/date-time/bootstrap-datepicker.js"></script>

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

$(document).on('change','.dropdownpo',function(){
var abc = $(this).val();
if(abc == 2)
{
	$("#poshow").show();
}
else

$("#poshow").hide();	
	
	
});

function getEmployee(val)
		{
			
			document.getElementById("employeesList").style.display = 'block';
				$.ajax({url: "ajax/getEmployeesList.php?val="+val, success: function(result){
		$("#employeesList").html(result);
    }});	
			
		}
		
		
		function selectEmployee(id,firstName)
		{
			document.getElementById("employeesList").style.display = 'none';
			document.getElementById("eid").value = id;
			document.getElementById("employee").value = firstName;
	
		}

 
		
		
		function selectCustomer(id,firstName)
		{
			document.getElementById("customersList").style.display = 'none';
			document.getElementById("cid").value = id;
			document.getElementById("customer").value = firstName;
	
		}	
		
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

		
		
		$(document).ready(function(e){
				$(document).on('click','#outclick,#breadcrumbs',function(){
				$("#employeesList,#customersList").css('display','none'); 
			});


		// submitting run card number through ajax	
			$(document).on('click','.runcardId',function()
			{
					var ide = $(this).attr('id');
					
					$("#po_no").val(ide);
					
					var rc_no  = $(this).attr('rc_no');
					rc_no  = $.trim(rc_no);
					
					if(rc_no!='')		
					{
					$("#runcard").val(rc_no);			
					$("#runcard").attr('readonly','readonly');
					}
					else
					{
					$("#runcard").val('');			
					$("#runcard").prop('readonly',false);
					}
					
					//assign productid, modelno
					
					var productid = $(this).attr('productid');
						productid = $.trim(productid);
						
					
					var modelno = $(this).attr('modelno');
						modelno = $.trim(modelno);
						
					$("#productid").val(productid);
					$("#modelno").val(modelno);
					
					
				
			});
			
			
			
			$(document).on('focus','#runcard',function(){
			var id = $(this).next().attr('id');
			$("#"+id).html('');
			
			});

		
			
			$(document).on('click','.runcardSub',function(e){
				var runcard = $("#runcard").val();
				var delCommittedDate = $("#delCommittedDate").val();
				var delAcceptanceDate = $("#delAcceptanceDate").val();
				var vendorDispatch = $("#vendorDispatch").val();
				var purchasePoDrop = $("#purchasePoDrop").val();
				var purchasePoNumber = $("#purchasePoNumber").val();
				var purchasePoDate = $("#purchasePoDate").val();
				var purchasePoType = $("#purchasePoType").val();
				
				var paymentTerms = $("#paymentTerms").val();
				
				var productid = $("#productid").val();
				var modelno = 	$("#modelno").val();
				
			//	paymentTerms = $.trim(paymentTerms);
				
				var ide = $("#po_no").val();
				
				
				var err=0;
				if(runcard == '')
				{
					$("#runcard-err").html("Please enter the run card number");
					err=1;
				}
				
				if(paymentTerms == '')
				{
					$("#paymentTerms-err").html("Please enter the Payment Terms");
					err=1;
				}
				if(err==0)
				{ 
					$.ajax({   
						url: 'ajax/sub_runcard.php',
					    type: 'POST',
						data:{'ide':ide,'productid':productid,'modelno':modelno,'runcard':runcard,'paymentTerms':paymentTerms,'delCommittedDate':delCommittedDate,'delAcceptanceDate':delAcceptanceDate,'vendorDispatch':vendorDispatch,'purchasePoDrop':purchasePoDrop,'purchasePoNumber':purchasePoNumber,'purchasePoDate':purchasePoDate,'purchasePoType':purchasePoType},
						success:function(data){
						$("#success-msg").html(data);	
						$("form").trigger("reset");
						}
						});
				}
				else
				{
					e.preventDefault();
				}
				
				
				
			});
			
		});
			


  function goToPage(pid)
{
   window.location = 'pos.php?page='+pid;	
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
