<?php require('../includes/db.php');
extract($_POST);

$models_ids = array();
$models = array();

$price_ids = array();
$prices = array();

$deletedModels = array();
$deletedPrices = array();
//check whether the deleted models and prices are there or not

if($delmodels!='')
{
	$del_mod=explode(",",$delmodels);
	foreach($del_mod as $val)
	{
		$deletedModels[] = $val;
	}
	
	
}

if($delprices!='')
{
	$del_pric=explode(",",$delprices);
	foreach($del_pric as $val)
	{
		$deletedPrices[] = $val;
	}	
}







// prepare the arrays for models and their ids
foreach($models_arr as $arr)
{
	//get the ids and models
	foreach($arr as $key=>$val)
	{
		$models_ids[] = $key;
		$models[]=$val;	
	}
	
}
// prepare the arrays for prices and their ids
foreach($prices_arr as $arr)
{
	//get the ids and models
	foreach($arr as $key=>$val)
	{
		$price_ids[] = $key;
		$prices[]=$val;	
	}
	
}

//	print_r($models_ids);
	print_r($models);
	echo "<br>";
//	print_r($price_ids);
	print_r($prices);


//get the models ids 

//$productId

$qry = mysql_query("SELECT ModelId FROM product_model WHERE ProductId='".$productId."'");

$modelids_db = array();

while($data = mysql_fetch_object($qry))
{
	$modelids_db[] = $data->ModelId;
}

//get the prices id

$qry ='';

$qry = mysql_query("SELECT autoId FROM product_price WHERE productId=".$productId);

$priceids_db = array();

while($data = mysql_fetch_object($qry))
{
	$priceids_db[] = $data->autoId;
}

//loop through the priceids and the modelids to check whether user had deleted any model or price or added any 


print_r($models_ids); echo "<br>";
		print_r($modelids_db); echo "<br>";
		print_r($price_ids); echo "<br>";
		print_r($priceids_db);echo "<br>";

//loop through the models
$cnt=0;
$newmodelIds = array();

foreach($models_ids as $val)
{
	//check whether the ids exists if so then update
	if( in_array($val,$modelids_db ) )
	{
		//update the old model 
		mysql_query("update product_model set ModelNo='".$models[$cnt]."' where ProductId=".$productId." and ModelId=".$val);
//	echo "update"."update product_model set ModelNo='".$models[$cnt]."' where ProductId=".$productId." and ModelId=".$val."<br>";
		
	}
	else
	{
		$lastId='';
		//if not delete that and insert the model as a new one under and store the new model id under an array and this array will be used later when we add a price 	
	
	//check whether the 
		mysql_query("insert into product_model values('',$productId,'".$models[$cnt]."','".time()."')" );
		//echo "insert into product_model values('',$productId,'".$models[$cnt]."','".time()."')"."<br>" ;
		
		$lastId = mysql_insert_id();
		
		$newmodelIds[] =$lastId;
		
	}
	$cnt++;
	
}




//loop through the models
$cnt=0;
$newcnt=0;

foreach($price_ids as $val)
{
	//check whether the ids exists if so then update
	if( in_array($val,$priceids_db ) )
	{
		//update the old model 
		
		
		mysql_query("update product_price set ModelNo='".$models_ids[$cnt]."', price='".$prices[$cnt]."' where ProductId=".$productId." and ModelNo='".$models_ids[$cnt]."'");
		
	}
	else
	{
		$lastId='';
		//if not delete that and insert the model as a new one under and store the new model id under an array and this array will be used later when we add a price 	
	//	echo "New".$models[$cnt]."<br>";
		//"",".$productId.", '".$val."', '".time()."'
	//	echo $newmodelIds[$newcnt]; exit; 
	echo "insert into product_model values('',$productId,'".$newmodelIds[$newcnt]."','".$prices[$cnt]."','".date('Y-m-d')."','".time()."')" ; 
		mysql_query("insert into product_price values('',$productId,'".$newmodelIds[$newcnt]."','".$prices[$cnt]."','".date('Y-m-d')."','".time()."')" );
		$newcnt++;
		
	}
	$cnt++;
	
}


//delete the models and prices which are delted by the user

//check whether user deletes any models


//$deletedPrices = array();
if($delmodels!='')
{
	foreach($deletedModels  as $val )		
	{
		mysql_query("DELETE FROM product_model WHERE ProductId=".$productId." and ModelId=".$val);
	}
}

if($delprices!='')
{
	$cnt=0;
	foreach($deletedPrices  as $val )		
	{
	//	echo "deleted price ids are ".$val."<br>";	
		mysql_query("DELETE FROM product_price where productId=".$productId." and ModelNo='".$delmodels[$cnt]."' and autoId=".$val);
		$cnt++;
	}
	
}




	/*
		print_r($models_ids); echo "<br>";
		print_r($modelids_db); echo "<br>";
		print_r($price_ids); echo "<br>";
		print_r($priceids_db);
	*/
?>