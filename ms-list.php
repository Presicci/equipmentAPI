<?php
function db_iconnect($dbName)
{
	$un="webuser_server";
	$pw="xyhSA5Wk3(8v4pf-";
	$db=$dbName;
	$hostname="localhost";
	$dblink=new mysqli($hostname,$un,$pw,$db);
	return $dblink;
}
/*$dblink=db_iconnect("clean");
$sql="Select * from `equipment_prod` where `manufacturer`='Microsoft'";
$time_start=microtime(true);
$result=$dblink->query($sql) or
	die("Something went wrong with: $sql<br>".$dblink->error);
$count=$result->num_rows;
$time_end=microtime(true);
$seconds=$time_end-$time_start;
$execution_time=($seconds)/60;
echo "<p>Number of rows for manufacturer type: Microsoft: $count</p>";
echo "<p>Execution time is: $execution_time minutes or $seconds seconds.</p>\n";*/
?>