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
	if (isset($_POST['insertdevicesubmit'])) {
		echo '<h3>Insert Device Results</h3>';
		$manu=$_POST['manufacturer'];
		$type=$_POST['type'];
		$serial=$_POST['serial'];
		$active=isset($_POST['active']) ? "&active=1" : "";

		$curl=curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://ec2-3-133-83-78.us-east-2.compute.amazonaws.com/api/insert_device?manufacturer=$manu&type=$type&serial=$serial$active",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_SSL_VERIFYPEER => false
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		if ($err) {
			echo "<h3>cURL Error Insert_device API #: $err</h3>";
			die();
		}
		$results = json_decode($response, true);
		$tmp=explode(":",$results[0]);
		$status=trim($tmp[1]);
		echo '<h5>'.$status.'</h5>';
		$tmp=explode(":",$results[1]);
		$message=trim($tmp[1]);
		echo '<p>'.$message.'</p>';
		$tmp=explode(":",$results[2]);
		$action=trim($tmp[1]);
		echo '<p>'.$action.'</p>';
	} else if (isset($_POST['insertmanusubmit'])) {
		echo '<h3>Insert Manufacturer Results</h3>';
		$name=$_POST['name'];
		$isactive=(int) isset($_POST['active']);

		$curl=curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://ec2-3-133-83-78.us-east-2.compute.amazonaws.com/api/insert_manu?name=$name&active=$isactive",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_SSL_VERIFYPEER => false
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		if ($err) {
			echo "<h3>cURL Error Insert_manufacturer API #: $err</h3>";
			die();
		}
		$results = json_decode($response, true);
		$tmp=explode(":",$results[0]);
		$status=trim($tmp[1]);
		echo '<h5>'.$status.'</h5>';
		$tmp=explode(":",$results[1]);
		$message=trim($tmp[1]);
		echo '<p>'.$message.'</p>';
		$tmp=explode(":",$results[2]);
		$action=trim($tmp[1]);
		echo '<p>'.$action.'</p>';
	} else if (isset($_POST['inserttypesubmit'])) {
		echo '<h3>Insert Type Results</h3>';
		$name=$_POST['name'];
		$isactive=(int) isset($_POST['active']);

		$curl=curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://ec2-3-133-83-78.us-east-2.compute.amazonaws.com/api/insert_type?name=$name&active=$isactive",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_SSL_VERIFYPEER => false
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		if ($err) {
			echo "<h3>cURL Error Insert_type API #: $err</h3>";
			die();
		}
		$results = json_decode($response, true);
		$tmp=explode(":",$results[0]);
		$status=trim($tmp[1]);
		echo '<h5>'.$status.'</h5>';
		$tmp=explode(":",$results[1]);
		$message=trim($tmp[1]);
		echo '<p>'.$message.'</p>';
		$tmp=explode(":",$results[2]);
		$action=trim($tmp[1]);
		echo '<p>'.$action.'</p>';
	}
	echo '<form method="post" action="">';
	echo '<button type="back" name="back" value="back">Back</button>';
	echo '</form>';
} else {
	$dblink=db_iconnect("clean");
	$time_start=microtime(true);
	
	if (isset($_POST['insertdevice'])) {
		echo '<h3>Insert new device</h3>';
		
		echo '<form method="post" action="">';
		echo '<button name="back" value="back">Back</button>';
		echo '<br><br>';
		
		// Manu
		$sql="Select `name` from `manufacturer` order by `name`";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		echo '<label for="manufacturer">Manufacturer:<br></label>';
		echo '<select name="manufacturer">';
		while ($data=$result->fetch_array(MYSQLI_NUM)) {
			echo '<option value="'.$data[0].'">'.$data[0].'</option>';
		}
		echo '</select>';
		echo '<br>';
		echo '<button name="insertmanu" value="insertmanu">Create new manufacturer</button>';
		echo '<br><br>';
		
		// Type
		$sql="Select `name` from `type` order by `name`";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		echo '<label for="type">Type:<br></label>';
		echo '<select name="type">';
		while ($data=$result->fetch_array(MYSQLI_NUM)) {
			echo '<option value="'.$data[0].'">'.$data[0].'</option>';
		}
		echo '</select>';
		echo '<br>';
		echo '<button name="inserttype" value="inserttype">Create new type</button>';
		echo '<br><br>';
		
		echo '<label for="serial">Serial number:<br></label>';
		echo '<input name="serial" type="text" placeholder="Serial number..."></input>';
		echo '<br><br>';
		
		echo '<label for="active">Acitve: </label>';
		echo '<input type="checkbox" name="active" value="Active">';
		echo '<br><br>';
		
		echo '<input type="hidden" name="insertdevicesubmit" value="insertdevice"></input>';
		echo '<button name="submit" value="submit">Submit</button>';
		
		echo '</form>';
	} else if (isset($_POST['insertmanu'])) {
		echo '<h3>Insert new manufacturer</h3>';
		
		echo '<form method="post" action="">';
		echo '<button name="back" value="back">Back</button>';
		echo '<br><br>';
		
		echo '<label for="name">Manufacturer name:<br></label>';
		echo '<input name="name" type="text"></input>';
		echo '<br><br>';
		
		echo '<label for="active">Acitve: </label>';
		echo '<input type="checkbox" name="active" value="Active">';
		echo '<br><br>';
		
		echo '<input type="hidden" name="insertmanusubmit" value="insertmanu"></input>';
		echo '<button name="submit" value="submit">Submit</button>';
		
		echo '</form>';
	} else if (isset($_POST['inserttype'])) {
		echo '<h3>Insert new type</h3>';
		
		echo '<form method="post" action="">';
		echo '<button name="back" value="back">Back</button>';
		echo '<br><br>';
		
		echo '<label for="name">Type name:<br></label>';
		echo '<input name="name" type="text"></input>';
		echo '<br><br>';
		
		echo '<label for="active">Acitve: </label>';
		echo '<input type="checkbox" name="active" value="Active">';
		echo '<br><br>';
		
		echo '<input type="hidden" name="inserttypesubmit" value="inserttype"></input>';
		echo '<button name="submit" value="submit">Submit</button>';
		
		echo '</form>';
	} else {
		echo '<h3>Insert</h3>';
		echo '<form method="post" action="">';
		echo '<button type="insertmanu" name="insertmanu" value="insertmanu">Insert manufacturer</button>';
		echo '<br><br>';
		echo '<button type="inserttype" name="inserttype" value="inserttype">Insert type</button>';
		echo '<br><br>';
		echo '<button type="insertdevice" name="insertdevice" value="insertdevice">Insert device</button>';
		echo '</form>';
	}
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time is: $execution_time minutes or $seconds seconds.</p>\n";
}
?>