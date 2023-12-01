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
if (isset($_POST['submit']) && ($_POST['submit'] == "submit")) {
	$dblink=db_iconnect("clean");
	$time_start=microtime(true);
	$query=$_POST['manufacturer'];
	$sql="Select `auto_id` from `manufacturer` where `name`='$query'";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$id=$result->fetch_row();
	$sql="Select `type`,`serial_num` from `equipment_prod` where `manufacturer`='$id[0]'";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<h3>Search by manufacturer: '.$query.'</h3>';
	echo '<table>';
	echo '<tr><td>Type</td><td>Serial Number</td></tr>';
	while ($data=$result->fetch_array(MYSQLI_ASSOC)) {
		echo '<tr>';
		echo '<td>'.$data['type'].'</td>';
		//echo "<td>$data[manufacturer]</td>";
		echo "<td>$data[serial_num]</td>";
		echo "</tr>";
	}
	echo '</table>';
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time is: $execution_time minutes or $seconds seconds.</p>\n";
}
else {
	echo '<h3>No post data received.</h3>';
}
?>