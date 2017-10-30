<?php require('../includes/db.php'); 

$ide = $_POST['ide'];
$runcard = $_POST['runcard'];	
$paymentTerms = $_POST['paymentTerms'];	

$delCommittedDate = $_POST['delCommittedDate'];	
$delCommittedDate1 = date_create($delCommittedDate);
$delCommittedDate1 = date_format($delCommittedDate1,'Y-m-d');

$delAcceptanceDate = $_POST['delAcceptanceDate'];
$delAcceptanceDate1 = date_create($delAcceptanceDate);
$delAcceptanceDate1 = date_format($delAcceptanceDate1,'Y-m-d');	

$vendorDispatch = $_POST['vendorDispatch'];
$vendorDispatch1 = date_create($vendorDispatch);
$vendorDispatch1 = date_format($vendorDispatch1,'Y-m-d');	


$purchasePoDrop = $_POST['purchasePoDrop'];
$purchasePoNumber = $_POST['purchasePoNumber'];	

$purchasePoDate = $_POST['purchasePoDate'];	
$purchasePoDate1 = date_create($purchasePoDate);
$purchasePoDate1 = date_format($purchasePoDate1,'Y-m-d');

$purchasePoType = $_POST['purchasePoType'];

$productid = $_POST['productid'];
$modelno = $_POST['modelno'];


//chech whether the reportdi already assigned any runcard or not
$qry = mysql_query("select runcard from daily_reports where reportId = '" .$ide."'");

$chk_runcard =  mysql_fetch_object($qry);
if(trim($chk_runcard->runcard)=='')
{
	$result =  mysql_query("update daily_reports set runcard = '" . $runcard. "' , paymentTerms = '". $paymentTerms ."', delCommDate = '".$delCommittedDate1."', delAcceptanceDate = '".$delAcceptanceDate1."', vendorDispatch = '".$vendorDispatch1."', purchasePoDrop = '".$purchasePoDrop."', purchasePoNumber = '".$purchasePoNumber."', purchasePoDate = '".$purchasePoDate1."', purchasePoType = '".$purchasePoType."', leadStatus = 'Pending Proforma Invoice', rcDate = NOW() where reportId = '" .$ide."'");

if(trim($purchasePoDrop)=="1")
		{
			mysql_query("insert into runcards value('','".$runcard."',$ide,$productid,'".$modelno."','Yes','".$purchasePoNumber."','','".time()."')");
			#echo "insert into runcards value('".$runcard."',$ide,$productid,'".$modelno."','Yes','".$purchasePoNumber."','','".time()."')<br>";
		}
		elseif(trim($purchasePoDrop)=="2")
		{
			mysql_query("insert into runcards value('','".$runcard."',$ide,$productid,'".$modelno."','No','".$purchasePoNumber."','".$purchasePoDate1."','".time()."')");
			#echo "insert into runcards value('".$runcard."',$ide,$productid,'".$modelno."','No','','".$purchasePoDate1."','".time()."')<br>";
		}
	
}
else
{
	//check whether the product and model with this report id is in the runcards table or not
//	$productid,$modelno,$id
	$exist_not = mysql_query("select * from runcards where  (ReportId=$ide and RunCard='".$runcard."') and (ProductId=$productid and ModelId='".$modelno."') " );
	
	if(mysql_num_rows($exist_not)>0)
	{
		
	}
	else
	{
		if(trim($purchasePoDrop)=="1")
		{
			mysql_query("insert into runcards value('','".$runcard."',$ide,$productid,'".$modelno."','Yes','".$purchasePoNumber."','','".time()."')");
		}
		elseif(trim($purchasePoDrop)=="2")
		{
			mysql_query("insert into runcards value('','".$runcard."',$ide,$productid,'".$modelno."','No','".$purchasePoNumber."','".$purchasePoDate1."','".time()."')");
		}
	}
}

	
	
	
	echo '<div class="alert alert-success">Successfully updated Runcard Number.</div>';

?>
