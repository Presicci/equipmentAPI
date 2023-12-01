<?php
if (!isset($_REQUEST['name'])) {
	$output[]='Status: ERROR';
	$output[]='MSG: Manufacturer name data NULL';
	$output[]='Action: Resend Manufacturer name data';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
if (!isset($_REQUEST['active'])) {
	$output[]='Status: ERROR';
	$output[]='MSG: Active data NULL';
	$output[]='Action: Resend Active data';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}
$time_start=microtime(true);
$name=$_REQUEST['name'];
$isactive=$_REQUEST['active'];
if ($name=="") {
	$output[]='Status: ERROR';
	$output[]='MSG: Manufacturer name data empty';
	$output[]='Action: Resend Manufacturer name data';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}

$sql="Select * from `manufacturer` where `name`='$name'";
$result=$dblink->query($sql) or
	die("Something went wrong with: $sql<br>".$dblink->error);
if ($result->num_rows!=0) {
	$output[]='Status: ERROR';
	$output[]='MSG: Manufacturer name already exists';
	$output[]='Action: Recheck Manufacturer name';
	$responseData=json_encode($output);
	echo $responseData;
	die();
}

$sql="Insert into `manufacturer` (`name`, `active`) values ('$name', '$isactive')";
$result=$dblink->query($sql) or
	die("Something went wrong with: $sql<br>".$dblink->error);
$autoid=$dblink->insert_id;

$time_end=microtime(true);
$seconds=$time_end-$time_start;
$execution_time=($seconds)/60;
$output[] = 'Status: Success';
$output[] = 'MSG: Successfully inserted manufacturer with name ['.$name.'] at auto_id ['.$autoid.']';
$output[] = 'Action: Execution time was '.$execution_time.' minutes';
$responseData=json_encode($output);
echo $responseData;
?>