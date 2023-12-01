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

if (isset($_POST['insertdevice']) || isset($_POST['insertmanu']) || isset($_POST['inserttype'])) {
	insert_form();
} else if (isset($_POST['submitinsertdevice']) || isset($_POST['submitinsertmanu']) || isset($_POST['submitinserttype'])) {
	insert();
} else if (isset($_POST['modifydevice']) || isset($_POST['modifymanu']) || isset($_POST['modifytype'])) {
	modify_form();
} else if (isset($_POST['submitupdatedevice']) || isset($_POST['submitupdatemanu']) || isset($_POST['submitupdatetype'])) {
	modify();
} else {
	selection();
}

function selection()
{
	$dblink=db_iconnect("clean");
	$time_start=microtime(true);
	
	// Insert
	echo '<h3>Insert</h3>';
	echo '<form method="post" action="">';
	echo '<button type="insertmanu" name="insertmanu" value="insertmanu">Insert manufacturer</button>';
	echo '<br><br>';
	echo '<button type="inserttype" name="inserttype" value="inserttype">Insert type</button>';
	echo '<br><br>';
	echo '<button type="insertdevice" name="insertdevice" value="insertdevice">Insert device</button>';
	echo '<br><br>';
	echo '<h3>Modify</h3>';
	
	// Manufacturer
	$sql="Select `name` from `manufacturer` order by `name`";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<label for="manufacturer">Manufacturer:<br></label>';
	echo '<select name="manufacturer">';
	while ($data=$result->fetch_array(MYSQLI_NUM)) {
		echo '<option value="'.$data[0].'">'.$data[0].'</option>';
	}
	echo '</select>';
	echo '<button name="modifymanu" value="modifymanu">Modify</button>';
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
	echo '<button name="modifytype" value="modifytype">Modify</button>';
	echo '<br><br>';
	
	// Serial Num
	echo '<label for="autoid">Device:<br></label>';
	echo '<input name="autoid" placeholder="Enter serial number..."></input>';
	echo '<button name="modifydevice" value="modifydevice">Modify</button>';
	echo '</form>';
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time is: $execution_time minutes or $seconds seconds.</p>\n";
}

function insert_form() {
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
		
		echo '<button name="submitinsertdevice" value="submitinsertdevice">Submit</button>';
		
		echo '</form>';
	} else if (isset($_POST['insertmanu'])) {
		echo '<h3>Insert new manufacturer</h3>';
		
		echo '<form method="post" action="">';
		echo '<button name="back" value="back">Back</button>';
		echo '<br><br>';
		
		echo '<label for="manuname">Manufacturer name:<br></label>';
		echo '<input name="manuname" type="text"></input>';
		echo '<br><br>';
		
		echo '<label for="active">Acitve: </label>';
		echo '<input type="checkbox" name="active" value="Active">';
		echo '<br><br>';
		
		echo '<button name="submitinsertmanu" value="submitinsertmanu">Submit</button>';
		
		echo '</form>';
	} else if (isset($_POST['inserttype'])) {
		echo '<h3>Insert new type</h3>';
		
		echo '<form method="post" action="">';
		echo '<button name="back" value="back">Back</button>';
		echo '<br><br>';
		
		echo '<label for="typename">Type name:<br></label>';
		echo '<input name="typename" type="text"></input>';
		echo '<br><br>';
		
		echo '<label for="active">Acitve: </label>';
		echo '<input type="checkbox" name="active" value="Active">';
		echo '<br><br>';
		
		echo '<button name="submitinserttype" value="submitinserttype">Submit</button>';
		
		echo '</form>';
	}
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time is: $execution_time minutes or $seconds seconds.</p>\n";
}

function insert() {
	$dblink=db_iconnect("clean");
	$time_start=microtime(true);
	
	echo '<form method="post" action="">';
	echo '<button name="back" value="back">Back</button>';
	echo '</form>';
	if (isset($_POST['submitinsertdevice'])) {
		$manuname=$_POST['manufacturer'];
		$typename=$_POST['type'];
		$active=isset($_POST['active']);
		$serial=$_POST['serial'];
		if ($serial=="") {
			// Error
			echo "<p>No serial number was entered.</p>";
			return;
		}
		
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
			echo "<p>A device with serial number $serial already exists.</p>";
			return;
		}
		
		$sql="Insert into `equipment_prod` (`type`, `manufacturer`, `serial_num`) values ('$typeid', '$manuid', '$serial')";
		if ($dblink->query($sql) == FALSE) {
			echo "<p>Error inserting device with serial number=$serial</p>";
		} else {
			$autoid=$dblink->insert_id;
			if (!$active) {
				$sql="Insert into `inactive_devices` (`device_id`) values ('$autoid')";
				$dblink->query($sql) or
					die("Something went wrong with: $sql<br>".$dblink->error);
			}
			echo "<p>Success.</p>";
		}	
	} else if (isset($_POST['submitinsertmanu'])) {
		$manuname=$_POST['manuname'];
		$isactive=(int) isset($_POST['active']);
		if ($manuname=="") {
			// Error
			echo "<p>No name was entered.</p>";
			return;
		}
		$sql="Select * from `manufacturer` where `name`='$manuname'";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		if ($result->num_rows!=0) {
			echo "<p>A manufacturer named $manuname already exists.</p>";
			return;
		}
		
		$sql="Insert into `manufacturer` (`name`, `active`) values ('$manuname', '$isactive')";
		if ($dblink->query($sql) == FALSE) {
			echo "<p>Error inserting manufacturer with name=$manuname</p>";
		} else {
			echo "<p>Success.</p>";
		}		
	} else if (isset($_POST['submitinserttype'])) {
		$typename=$_POST['typename'];
		$isactive=(int) isset($_POST['active']);
		if ($typename=="") {
			// Error
			echo "<p>No name was entered.</p>";
			return;
		}
		$sql="Select * from `type` where `name`='$typename'";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		if ($result->num_rows!=0) {
			echo "<p>A type named $typename already exists.</p>";
			return;
		}
		
		$sql="Insert into `type` (`name`, `active`) values ('$typename', '$isactive')";
		if ($dblink->query($sql) == FALSE) {
			echo "<p>Error inserting type with name=$typename</p>";
		} else {
			echo "<p>Success.</p>";
		}	
	}
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time is: $execution_time minutes or $seconds seconds.</p>\n";
}

function modify_form() {
	$dblink=db_iconnect("clean");
	$time_start=microtime(true);
	if (isset($_POST['modifydevice'])) {
		$autoid=$_POST['autoid'];
		echo '<h3>Modify device: '.$autoid.'</h3>';
		
		echo '<form method="post" action="">';
		echo '<button name="back" value="back">Back</button>';
		echo '<br><br>';
		
		$sql="Select * from `equipment_prod` where `serial_num`='$autoid'";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		if ($result->num_rows==0) {
			echo "<p>Fatal error. Device doesn't exist.</p>";
			return;
		}
		$data=$result->fetch_array(MYSQLI_NUM);
		
		// Manu		
		$sql="Select `name`,`auto_id` from `manufacturer` order by `name`";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		echo '<label for="manufacturer">Manufacturer:<br></label>';
		echo '<select name="manufacturer">';
		while ($manu=$result->fetch_array(MYSQLI_NUM)) {
			if ($manu[1]==$data[2]) {
				echo '<option selected value="'.$manu[0].'">'.$manu[0].'</option>';
			} else {
				echo '<option value="'.$manu[0].'">'.$manu[0].'</option>';
			}
		}
		echo '</select>';
		echo '<br>';
		echo '<button type="insertmanu" name="insertmanu" value="insertmanu">Create new manufacturer</button>';
		echo '<br><br>';
		
		// Type
		$sql="Select `name`,`auto_id` from `type` order by `name`";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		echo '<label for="type">Type:<br></label>';
		echo '<select name="type">';
		while ($type=$result->fetch_array(MYSQLI_NUM)) {
			if ($type[1]==$data[1]) {
				echo '<option selected value="'.$type[0].'">'.$type[0].'</option>';
			} else {
				echo '<option value="'.$type[0].'">'.$type[0].'</option>';
			}
		}
		echo '</select>';
		echo '<br>';
		echo '<button type="inserttype" name="inserttype" value="inserttype">Create new type</button>';
		echo '<br><br>';
		
		$sql="Select * from `inactive_devices` where `device_id`='$autoid'";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		echo '<label for="active">Acitve: </label>';
		if ($result->num_rows==0) {
			echo '<input type="checkbox" name="active" value="Active" checked>';
		} else {
			echo '<input type="checkbox" name="active" value="Active">';
		}
		echo '<br><br>';
		
		echo '<button name="submitupdatedevice" value="'.$autoid.'">Update</button>';
		
		echo '</form>';
	} else if (isset($_POST['modifymanu'])) {
		$manu=$_POST['manufacturer'];
		echo '<h3>Modify manufacturer: '.$manu.'</h3>';
		
		echo '<form method="post" action="">';
		echo '<button name="back" value="back">Back</button>';
		echo '<br><br>';
		
		$sql="Select * from `manufacturer` where `name`='$manu'";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		if ($result->num_rows==0) {
			echo "<p>Fatal error. Manufacturer doesn't exist.</p>";
			return;
		}
		$data=$result->fetch_array(MYSQLI_NUM);
		echo '<label for="manuname">Manufacturer name:<br></label>';
		echo '<input name="manuname" type="text" value="'.$data[1].'"></input>';
		echo '<br><br>';
		
		
		echo '<label for="active">Acitve: </label>';
		if ($data[2]==1) {
			echo '<input type="checkbox" name="active" value="Active" checked>';
		} else {
			echo '<input type="checkbox" name="active" value="Active">';
		}
		echo '<br><br>';
		
		echo '<button name="submitupdatemanu" value="'.$manu.'">Update</button>';
		
		echo '</form>';
	} else if (isset($_POST['modifytype'])) {
		$type=$_POST['type'];
		echo '<h3>Modify type: '.$type.'</h3>';
		
		echo '<form method="post" action="">';
		echo '<button name="back" value="back">Back</button>';
		echo '<br><br>';
		
		$sql="Select * from `type` where `name`='$type'";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		if ($result->num_rows==0) {
			echo "<p>Fatal error. Type doesn't exist.</p>";
			return;
		}
		$data=$result->fetch_array(MYSQLI_NUM);
		echo '<label for="typename">Type name:<br></label>';
		echo '<input name="typename" type="text" value="'.$data[1].'"></input>';
		echo '<br><br>';
		
		echo '<label for="active">Acitve: </label>';
		if ($data[2]==1) {
			echo '<input type="checkbox" name="active" value="Active" checked>';
		} else {
			echo '<input type="checkbox" name="active" value="Active">';
		}
		echo '<br><br>';
		
		echo '<button name="submitupdatetype" value="'.$type.'">Update</button>';
		
		echo '</form>';
	}
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time is: $execution_time minutes or $seconds seconds.</p>\n";
}

function modify() {
	$dblink=db_iconnect("clean");
	$time_start=microtime(true);
	
	echo '<form method="post" action="">';
	echo '<button name="back" value="back">Back</button>';
	echo '</form>';
	if (isset($_POST['submitupdatedevice'])) {
		$manuname=$_POST['manufacturer'];
		$typename=$_POST['type'];
		$autoid=$_POST['submitupdatedevice'];
		$active=isset($_POST['active']);
		
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
		
		$sql="Update `equipment_prod` set `manufacturer`='$manuid', `type`='$typeid' where `auto_id`=$autoid";
		if ($dblink->query($sql) == FALSE) {
			echo "<p>Error updating device with auto_id=$autoid</p>";
			return;
		}
		if (!isset($_POST['active'])) {
			$sql="Insert into `inactive_devices` (`device_id`) values ('$autoid')";
			$dblink->query($sql) or
				die("Something went wrong with: $sql<br>".$dblink->error);
		} else {
			$sql="Delete from `inactive_devices` where `device_id`='$autoid'";
			$dblink->query($sql) or
				die("Something went wrong with: $sql<br>".$dblink->error);
		}
		
		echo "<p>Success</p>";
	} else if (isset($_POST['submitupdatemanu'])) {
		$manuname=$_POST['manuname'];
		$oldname=$_POST['submitupdatemanu'];
		$isactive=(int) isset($_POST['active']);
		if ($manuname=="") {
			// Error
			echo "<p>No name was entered.</p>";
			return;
		}
		$sql="Update `manufacturer` set `name`='$manuname', `active`='$isactive' where `name`='$oldname'";
		if ($dblink->query($sql) == FALSE) {
			echo "<p>Error updating manufacturer with name=$oldname</p>";
			return;
		}
		echo "<p>Success.</p>";
	} else if (isset($_POST['submitupdatetype'])) {
		$typename=$_POST['typename'];
		$oldname=$_POST['submitupdatetype'];
		$isactive=(int) isset($_POST['active']);
		if ($typename=="") {
			// Error
			echo "<p>No name was entered.</p>";
			return;
		}
		$sql="Update `type` set `name`='$typename', `active`='$isactive' where `name`='$oldname'";
		if ($dblink->query($sql) == FALSE) {
			echo "<p>Error updating type with name=$oldname</p>";
			return;
		}
		echo "<p>Success.</p>";
	}
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time is: $execution_time minutes or $seconds seconds.</p>\n";
}
?>