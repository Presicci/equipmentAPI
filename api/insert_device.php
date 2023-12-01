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
$serial=$_REQUEST['serial'];
if ($serial=="") {
	$output[]='Status: ERROR';
	$output[]='MSG: Serial number data NULL';
	$output[]='Action: Resend Serial number data';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}

$time_start=microtime(true);
$manuname=$_REQUEST['manufacturer'];
$typename=$_REQUEST['type'];
$active=isset($_REQUEST['active']);

// Get manu id
$sql="Select `auto_id` from `manufacturer` where `name`='$manuname'";
$result=$dblink->query($sql) or
	die("Something went wrong with: $sql<br>".$dblink->error);
$data=$result->fetch_array(MYSQLI_NUM);
$manuid=$data[0];

// Get type id
$sql="Select `auto_id` from `type` where `name`='$typename'";
$result=$dblink->query($sql) or
	die("Something went wrong with: $sql<br>".$dblink->error);
$data=$result->fetch_array(MYSQLI_NUM);
$typeid=$data[0];

$sql="Select * from `equipment_prod` where `serial_num`='$serial' limit 1";
$result=$dblink->query($sql) or
	die("Something went wrong with: $sql<br>".$dblink->error);
if ($result->num_rows!=0) {
	$output[]='Status: ERROR';
	$output[]='MSG: Serial number already exists in database';
	$output[]='Action: Recheck serial number';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}

$sql="Insert into `equipment_prod` (`type`, `manufacturer`, `serial_num`) values ('$typeid', '$manuid', '$serial')";
if ($dblink->query($sql) == FALSE) {
	echo "<p>Error inserting device with serial number=$serial</p>";
	die();
}
$autoid=$dblink->insert_id;
if (!$active) {
	$sql="Insert into `inactive_devices` (`device_id`) values ('$autoid')";
	$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
}
$time_end=microtime(true);
$seconds=$time_end-$time_start;
$execution_time=($seconds)/60;
$output[] = 'Status: Success';
$output[] = 'MSG: Successfully inserted device with SN:'.$serial.' at auto_id:'.$autoid;
$output[] = 'Action: Execution time was '.$execution_time.' minutes';
$responseData=json_encode($output);
echo $responseData;
?>