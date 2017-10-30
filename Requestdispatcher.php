<?PHP
ob_start();
session_start();

if( isset($_GET['func']))
{
	$funct = trim($_GET['func']);
	$funct();	
}

function dbconn()
{
	include("includes/db.php");	
}

function getengnieers()
{
	dbconn();
	$qrey = mysql_query("SELECT distinct(engineer) FROM `services` where engineer like '%".$_GET['term']."%'");

	
	$engineers = array();
	
	if(mysql_num_rows($qrey)>0)	
	{
		while($eng = mysql_fetch_object($qrey))
		{
			$engineers[] = $eng->engineer;
		}
	}
	echo json_encode($engineers);
}

?>