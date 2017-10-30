<?php require('../includes/db.php');

$company_name = $_POST['company_name'];
$company_name = trim($company_name);

$qry = mysql_query("SELECT * FROM `customers` where company='".$company_name."'");

if(mysql_num_rows($qry) == 1 )
{
	$customer = mysql_fetch_assoc($qry);

	$customer_details = array(
									'contactPerson'=>$customer['contactPerson'],
									'email'=>$customer['email'],
									'phone'=>$customer['phone'],
									
									);
									echo json_encode($customer_details);	
	}
 


 ?>
