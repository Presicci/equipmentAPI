<?php
header('Content-Type: application/json');
header('HTTP/1.1 200 OK');
/*$output[] = 'Status: API Main';
$output[] = 'MSG: Primary Endpoint reached';
$output[] = 'Action: None';
$responseData=json_encode($output);
echo $responseData;*/
$url=$_SERVER['REQUEST_URI']; //request URI component of URL
//echo '<h3>'.$url.'</h3>'; //echo URI component to browser
$path=parse_url($url,PHP_URL_PATH);
//echo '<h3>'.$path.'</h3>';
$pathComponents=explode("/",trim($path,"/"));
//echo '<h3>Number of URL components: '.count($pathComponents).'</h3>';
//echo '<pre>';
//print_r($pathComponents);
//echo '</pre>';
$endPoint=$pathComponents[1];	//take the value at index 1 in the array and assign to endPoint var

function db_iconnect($dbName)
{
	$un="webuser_server";
	$pw="xyhSA5Wk3(8v4pf-";
	$db=$dbName;
	$hostname="localhost";
	$dblink=new mysqli($hostname,$un,$pw,$db);
	return $dblink;
}
$dblink=db_iconnect("clean");

switch($endPoint) {
	case "search":
		include("search.php");
		break;
	case "insert_device":
		include("insert_device.php");
		break;
	case "insert_manu":
		include("insert_manu.php");
		break;
	case "insert_type":
		include("insert_type.php");
		break;
	default:
		$output[] = 'Status: Error';
		$output[] = 'MSG: '.$endPoint.' Endpoint Not Found';
		$output[] = 'Action: None';
		$responseData=json_encode($output);
		echo $responseData;
		break;
}
?>
