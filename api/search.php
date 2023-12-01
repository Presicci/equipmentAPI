<?php
if (!isset($_REQUEST['manufacturer'])) {
	$output[]='Status: ERROR';
	$output[]='MSG: Manufacturer data NULL';
	$output[]='Action: Resend Manufacturer data';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
if (!isset($_REQUEST['type'])) {
	$output[]='Status: ERROR';
	$output[]='MSG: Type data NULL';
	$output[]='Action: Resend Type data';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}

// API STUFF
$info=array();
$time_start=microtime(true);
if ($_REQUEST['manufacturer']=="all")
	$manu="`manufacturer` like '%'";
else {
	$sql="Select `auto_id` from `manufacturer` where `name`='$_REQUEST[manufacturer]'";
	$result=$dblink->query($sql) or 
		die("Something went wrong with: $sql<br>".$dblink->error);
	$tmp=$result->fetch_array(MYSQLI_ASSOC);
	$manu="`manufacturer`='$tmp[auto_id]'";
}

if ($_REQUEST['type']=="all")
	$type="`type` like '%'";
else {
	$sql="Select `auto_id` from `type` where `name`='$_REQUEST[type]'";
	$result=$dblink->query($sql) or 
		die("Something went wrong with: $sql<br>".$dblink->error);
	$tmp=$result->fetch_array(MYSQLI_ASSOC);
	$type="`type`='$tmp[auto_id]'";
}

$sql="Select * from `equipment_prod` where $manu and $type limit 1000";
$result=$dblink->query($sql) or
	die("Something went wrong with: $sql<br>".$dblink->error);
while ($data=$result->fetch_array(MYSQLI_ASSOC)) {
	$sql="Select `name` from `type` where `auto_id`='$data[type]'";
	$rst=$dblink->query($sql) or 
		die("Something went wrong with: $sql<br>".$dblink->error);
	$tmp=$rst->fetch_array(MYSQLI_ASSOC);
	$type=$tmp['name'];
	$sql="Select `name` from `manufacturer` where `auto_id`='$data[manufacturer]'";
	$rst=$dblink->query($sql) or 
		die("Something went wrong with: $sql<br>".$dblink->error);
	$tmp=$rst->fetch_array(MYSQLI_ASSOC);
	$manu=$tmp['name'];
	$info[]="$type,$manu,$data[serial_num]";
}
$infoJson=json_encode($info);
$time_end=microtime(true);
$seconds=$time_end-$time_start;
$execution_time=($seconds)/60;
$output[] = 'Status: Success';
$output[] = 'MSG: '.$infoJson;
$output[] = 'Action: '.$execution_time;
$responseData=json_encode($output);
echo $responseData;
?>