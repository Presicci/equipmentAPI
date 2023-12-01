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




// API STUFF
if (isset($_POST['submit'])) {
	echo '<h3>Search Results</h3>';
	$manu=$_POST['manufacturer'];
	$type=$_POST['type'];
	$curl=curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://ec2-3-133-83-78.us-east-2.compute.amazonaws.com/api/search?manufacturer=$manu&type=$type",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_SSL_VERIFYPEER => false
	));
	$response = curl_exec($curl);
	$err = curl_error($curl);
	if ($err) {
		echo "<h3>cURL Error Search API #: $err</h3>";
		die();
	}
	$results = json_decode($response, true);
	$tmp=explode(":",$results[0]);
	$status=trim($tmp[1]);
	if ($status=="Success") {
		$tmp=explode(":",$results[1]);
		$data=json_decode($tmp[1], true);
		if (empty($data[0])) {
			echo "<p>No devices found with manufacturer: $manu and type: $type.</p>";
			die();
		}
		echo '<table id="results" class="display" cellspacing="o" width=100%>';
		echo '<thead>';
		echo '<tr>';
		echo '<th>Type</th>';
		echo '<th>Manufacturer</th>';
		echo '<th>Serial Number</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach($data as $key=>$value) {
			$tmp=explode(",", $value);
			echo '<tr>';
			echo '<td>'.$tmp[0].'</td>';
			echo '<td>'.$tmp[1].'</td>';
			echo '<td>'.$tmp[2].'</td>';
			echo '</tr>';
		}
		echo '</tbody>';
		echo '</table>';
	} else {
		echo '<h5>'.$status.'</h5>';
		$tmp=explode(":",$results[1]);
		$message=trim($tmp[1]);
		echo '<p>'.$message.'</p>';
		$tmp=explode(":",$results[2]);
		$action=trim($tmp[1]);
		echo '<p>'.$action.'</p>';
	}
} else {
	$dblink=db_iconnect("clean");
	$time_start=microtime(true);
	
	echo '<h3>Search Devices</h3>';
	
	// Manufacturer
	$sql="Select `name` from `manufacturer` order by `name`";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<form method="post" action="">';
	echo '<label for="manufacturer">Select a manufacturer: </label>';
	echo '<select name="manufacturer">';
	echo '<option value="all">All</option>';
	while ($data=$result->fetch_array(MYSQLI_NUM)) {
		echo '<option value="'.$data[0].'">'.$data[0].'</option>';
	}
	echo '</select>';
	echo '<br><br>';
	
	// Type
	$sql="Select `name` from `type` order by `name`";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<label for="type">Select a device type: </label>';
	echo '<select name="type">';
	echo '<option value="all">All</option>';
	while ($data=$result->fetch_array(MYSQLI_NUM)) {
		echo '<option value="'.$data[0].'">'.$data[0].'</option>';
	}
	echo '</select>';
	echo '<br><br>';
	
	// All
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '</form>';
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time is: $execution_time minutes or $seconds seconds.</p>\n";
}
?>