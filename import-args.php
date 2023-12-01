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
/*echo "Hello from php process $argv[1] about to process file:$argv[2]\n";
$dblink=db_iconnect("test");
$count=0;
$fp=fopen("/home/ubuntu/$argv[2]","r");
$time_start=microtime(true);
echo "PHP ID:$argv[1]-Start time is: $time_start\n";
while (($row=fgetcsv($fp)) !== FALSE)
{
	$clened_manuf=preg_replace("~'?(?<!s)s\b~", '', $row[1]);
	$sql="INSERT into `equipment` (`type`,`manufacturer`,`serial_num`) values ('";
	$sql.=addslashes($row[0])."', '";
	$sql.=addslashes($row[1])."', '";
	$sql.=addslashes($row[2])."')";
	$dblink->query($sql) or 
		die("Something went wrong with $sql<br>\n".$dblink->error);
	$count++;
}
$end_time=microtime(true);
echo "PHP ID:$argv[1]-End time is: $end_time\n";
$seconds=$time_end-$time_start;
$execution_time=($seconds)/60;
echo "PHP ID:$argv[1]-Execution time is: $execution_time minutes or $seconds seconds.\n";
$rowsPerSecond=$count/$seconds;
echo "PHP ID:$argv[1]-Insert rate: $rowsPerSecond rows per second.\n";
fclose($fp);*/
?>