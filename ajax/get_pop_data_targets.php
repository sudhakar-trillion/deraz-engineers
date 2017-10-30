<?php require('../includes/db.php');

extract($_REQUEST);
if($mnth_yearly=="monthlyachieved")
{
	getmonthdata($empl_ide,$mnth);
}
elseif($mnth_yearly=="yearachieved")
{
	getyeardata($empl_ide,$yr);	
}


function getmonthdata($empl_ide,$mnth)
{
	if($mnth>0)	
	{
	$qry =mysql_query("select inv.invoiceId,inv.invoiceNumber,cus.company,inv.invoiceDateTime, col.amount,emp.firstName from daily_reports as dr join invoices as inv on inv.reportId=dr.reportId join collections as col on col.invoiceId=inv.invoiceId join employees as emp on emp.id=dr.addedBy join customers as cus on cus.customerId=dr.company  where dr.addedBy=".$empl_ide." and MONTH(col.paidDate)='".$mnth."'");
	}
	else
	{
	$qry =mysql_query("select inv.invoiceId,inv.invoiceNumber,cus.company,inv.invoiceDateTime, col.amount,emp.firstName from daily_reports as dr join invoices as inv on inv.reportId=dr.reportId join collections as col on col.invoiceId=inv.invoiceId join employees as emp on emp.id=dr.addedBy join customers as cus on cus.customerId=dr.company  where dr.addedBy=".$empl_ide." and MONTH(col.paidDate)='".date('m')."'");
		
	}
	
	if(mysql_num_rows($qry)>0)
	{
		$slno=0;
		$empl='';
		$totalAmnt=0;
	
		while($data=mysql_fetch_object($qry))
		{
			$slno++;
			$rows.="<tr><td>$slno</td><td>".$data->invoiceDateTime."</td><td>".$data->invoiceNumber."</td><td>".$data->company."</td><td>".$data->amount."</td></tr>";
			$empl=$data->firstName;
			$totalAmnt=$totalAmnt+$data->amount;
		}
		$dat='<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top"><tr><th>Slno</th><th>Invoice Date</th><th>Invoice Number</th><th>Company</th><th>Value</th></tr>'.$rows.'<tr><td colspan=4></td><td><b>'.$totalAmnt.'</b></tr></tr></table>';
		
		$outputarray = array("nodata"=>"no","Employee"=>$empl,"data"=>$dat);
		echo json_encode($outputarray);
			
	}//if ends here
	else
	{
		$outputarray = array("nodata"=>"yes");
		echo json_encode($outputarray);
	}
}//getgetyeardata data


function getyeardata($empl_ide,$yr)
{

	if($yr!=0)	
	{
		$yer = explode("-",$yr);
	$qry =mysql_query("select inv.invoiceId,inv.invoiceNumber,cus.company,inv.invoiceDateTime, col.amount,emp.firstName from daily_reports as dr join invoices as inv on inv.reportId=dr.reportId join collections as col on col.invoiceId=inv.invoiceId join employees as emp on emp.id=dr.addedBy join customers as cus on cus.customerId=dr.company  where dr.addedBy=".$empl_ide." and (YEAR(col.paidDate)='".$yer[0]."' or YEAR(col.paidDate)='".$yer[1]."' )");
	}
	else
	{
	$qry =mysql_query("select inv.invoiceId,inv.invoiceNumber,cus.company,inv.invoiceDateTime, col.amount,emp.firstName from daily_reports as dr join invoices as inv on inv.reportId=dr.reportId join collections as col on col.invoiceId=inv.invoiceId join employees as emp on emp.id=dr.addedBy join customers as cus on cus.customerId=dr.company  where dr.addedBy=".$empl_ide." and ( YEAR(col.paidDate)='".(date('Y'))."' or YEAR(col.paidDate)='".(date('Y')-1)."')");
		
	}
	
	if(mysql_num_rows($qry)>0)
	{
		$slno=0;
		$empl='';
		$totalAmnt=0;
	
	
		while($data=mysql_fetch_object($qry))
		{
			$slno++;
			$rows.="<tr><td>$slno</td><td>".$data->invoiceDateTime."</td><td>".$data->invoiceNumber."</td><td>".$data->company."</td><td>".$data->amount."</td></tr>";
			$empl=$data->firstName;
			$totalAmnt=$totalAmnt+$data->amount;
		}
		$dat='<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top"><tr><th>Slno</th><th>Invoice Date</th><th>Invoice Number</th><th>Company</th><th>Value</th></tr>'.$rows.'<tr><td colspan=4 ></td> <td><b>'.$totalAmnt.'</b></tr></tr></table>';
		
		$outputarray = array("nodata"=>"no","Employee"=>$empl,"data"=>$dat);
		echo json_encode($outputarray);
			
	}//if ends here
	else
	{
		$outputarray = array("nodata"=>"yes");
		echo json_encode($outputarray);
	}



}//getgetyeardata data


?>