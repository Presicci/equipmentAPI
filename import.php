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
/*$dblink=db_iconnect("test");
$count=0;
$fp=fopen("var/www/html/equipment-part2.txt","r");
$time_start=microtime(true);
echo "<p>Start time is: $time_start</p>\n";
$sql="Set autocommit=0";	// Speeds up inserting by a lot
$dblink->query($sql) or 	// Speeds up inserting by a lot
	die("Something went wrong with $sql<br>\n".$dblink->error);
while (($row=fgetcsv($fp)) !== FALSE)
{
	$sql="INSERT into `equipment2` (`type`,`manufacturer`,`serial_num`) values ('$row[0]', '$row[1]', '$row[2]')";
	$dblink->query($sql) or 
		die("Something went wrong with $sql<br>\n".$dblink->error);
	$count++;
}
$sql="Commit";	// Speeds up inserting by a lot
$dblink->query($sql) or	// Speeds up inserting by a lot
	die("Something went wrong with $sql<br>\n".$dblink->error);
$time_end=microtime(true);
echo "<p>End time is: $end_time</p>\n";
$seconds=$time_end-$time_start;
$execution_time=($seconds)/60;
echo "<p>Execution time is: $execution_time minutes or $seconds seconds.</p>\n";
$rowsPerSecond=$count/$seconds;
echo "<p>Insert rate: $rowsPerSecond rows per second.</p>\n";
fclose($fp);*/
?>