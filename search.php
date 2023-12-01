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

//echo "<pre>"; print_r($_POST) ;  echo "</pre>";
if (isset($_POST['all'])) {
	all_results();
} else if (isset($_POST['submit']) && ($_POST['submit'] == "submit")) {
	results();
} else if (isset($_POST['next']) && ($_POST['next'] == "next")) {
	if (isset($_POST['manufacturer'])) {
		manu_search();
	} else {
		type_search();
	}
} else {
	first_search();
}

/**
 * Initial pageview, can search by manufacturer, type, serial number, or disply all equipment.
 */
function first_search()
{
	$dblink=db_iconnect("clean");
	$time_start=microtime(true);
	
	// Manufacturer
	$sql="Select `name` from `manufacturer` order by `name`";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<label for="manufacturer">By manufacturer:</label>';
	echo '<form method="post" action="">';
	echo '<select name="manufacturer">';
	while ($data=$result->fetch_array(MYSQLI_NUM)) {
		echo '<option value="'.$data[0].'">'.$data[0].'</option>';
	}
	echo '</select>';
	echo '<button type="next" name="next" value="next">Next</button>';
	echo '</form>';
	
	// Type
	$sql="Select `name` from `type` order by `name`";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<label for="type">By type:</label>';
	echo '<form method="post" action="">';
	echo '<select name="type">';
	while ($data=$result->fetch_array(MYSQLI_NUM)) {
		echo '<option value="'.$data[0].'">'.$data[0].'</option>';
	}
	echo '</select>';
	echo '<button type="next" name="next" value="next">Next</button>';
	echo '</form>';
	
	// Serial Num
	echo '<label for="serial">By serial number:</label>';
	echo '<form method="post" action="">';
	echo '<input name="serial" type="text"></input>';
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '</form>';
	
	// All
	echo '<form method="post" action="">';
	echo '<button type="all" name="all" value="all">All</button>';
	echo '</form>';
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time is: $execution_time minutes or $seconds seconds.</p>\n";
}

/**
 * Searching by type, lets the user filter by manufacturer if desired or display all results.
 */
function type_search()
{
	$dblink=db_iconnect("clean");
	$time_start=microtime(true);
	
	$type_post=$_POST['type'];
	$sql="Select `auto_id` from `type` where `name`='$type_post'";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$type_id=$result->fetch_row();
	$sql="Select distinct `manufacturer` from `equipment_prod` where `type`='$type_id[0]'";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<h3>Search by type: '.$type_post.'</h3>';
	echo '<label for="manufacturer">Manufacturers:</label>';
	echo '<form method="post" action="">';
	echo '<input type="hidden" name="type" value="'.$type_post.'"></input>';
	echo '<select name="manufacturer">';
	while ($data=$result->fetch_array(MYSQLI_NUM)) {
		$sql="Select `name` from `manufacturer` where `auto_id`='$data[0]'";
		$manu=$dblink->query($sql);
		$manu_name=$manu->fetch_row();
		echo '<option value="'.$manu_name[0].'">'.$manu_name[0].'</option>';
	}
	echo '</select>';
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '</form>';
	
	echo '<form method="post" action="">';
	echo '<input type="hidden" name="type" value="'.$type_post.'"></input>';
	echo '<button type="submit" name="submit" value="submit">All</button>';
	echo '</form>';
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time is: $execution_time minutes or $seconds seconds.</p>\n";
}

/**
 * Searching by manufacturer, lets the user filter by type if desired or display all results.
 */
function manu_search()
{
	$dblink=db_iconnect("clean");
	$time_start=microtime(true);
	
	$manu_post=$_POST['manufacturer'];
	$sql="Select `auto_id` from `manufacturer` where `name`='$manu_post'";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$manu_id=$result->fetch_row();
	$sql="Select distinct `type` from `equipment_prod` where `manufacturer`='$manu_id[0]'";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	echo '<h3>Search by manufacturer: '.$manu_post.'</h3>';
	echo '<label for="type">Types:</label>';
	echo '<form method="post" action="">';
	echo '<input type="hidden" name="manufacturer" value="'.$manu_post.'"></input>';
	echo '<select name="type">';
	while ($data=$result->fetch_array(MYSQLI_NUM)) {
		$sql="Select `name` from `type` where `auto_id`='$data[0]'";
		$manu=$dblink->query($sql);
		$manu_name=$manu->fetch_row();
		echo '<option value="'.$manu_name[0].'">'.$manu_name[0].'</option>';
	}
	echo '</select>';
	echo '<button type="submit" name="submit" value="submit">Submit</button>';
	echo '</form>';
	
	echo '<form method="post" action="">';
	echo '<input type="hidden" name="manufacturer" value="'.$manu_post.'"></input>';
	echo '<button type="submit" name="submit" value="submit">All</button>';
	echo '</form>';
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time is: $execution_time minutes or $seconds seconds.</p>\n";
}

/**
 * Displays final results table
 */
function results() 
{
	$dblink=db_iconnect("clean");
	$time_start=microtime(true);
	
	if (isset($_POST['serial'])) {
		$serial=$_POST['serial'];
		$sql="Select `auto_id`,`type`,`manufacturer` from `equipment_prod` where `serial_num`='$serial'";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		$sql="Select `name` from `manufacturer`";
		$manu_results=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		$manus = [];
		while ($data=$manu_results->fetch_array()) {
			$manus[]=$data['name'];
		}
		$sql="Select `name` from `type`";
		$type_results=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		$types = [];
		while ($data=$type_results->fetch_array()) {
			$types[]=$data['name'];
		}
		echo '<h3>Search by serial number: '.$serial.'</h3>';
		echo '<table>';
		echo '<tr><td>ID</td><td>Type</td><td>Manufacturer</td></tr>';
		while ($data=$result->fetch_array(MYSQLI_ASSOC)) {
			echo '<tr>';
			echo "<td>$data[auto_id]</td>";
			$type_index=intval($data['type']) - 1;
			echo "<td>$types[$type_index]</td>";
			$manu_index=intval($data['manufacturer']) - 1;
			echo "<td>$manus[$manu_index]</td>";
			echo "</tr>";
		}
		echo '</table>';
	} else {
		$sql;
		$manu=isset($_POST['manufacturer']);
		$type=isset($_POST['type']);
		
		$manu_id;
		$type_id;
		if ($manu) {
			$manu_post=$_POST['manufacturer'];
			$sql="Select `auto_id` from `manufacturer` where `name`='$manu_post'";
			$result=$dblink->query($sql) or
				die("Something went wrong with: $sql<br>".$dblink->error);
			$manu_id=$result->fetch_row();
		}
		if ($type) {
			$type_post=$_POST['type'];
			$sql="Select `auto_id` from `type` where `name`='$type_post'";
			$result=$dblink->query($sql) or
				die("Something went wrong with: $sql<br>".$dblink->error);
			$type_id=$result->fetch_row();
		}
		$select=$type ? $manu ? "" : ",`manufacturer`" : ",`type`";
		$where=$type ? $manu ? "`manufacturer`='$manu_id[0]' and `type`='$type_id[0]'" : "`type`='$type_id[0]'" : "`manufacturer`='$manu_id[0]'";
		$sql="Select `auto_id`, `serial_num`$select from `equipment_prod` where $where limit 1000";
		$result=$dblink->query($sql) or
			die("Something went wrong with: $sql<br>".$dblink->error);
		echo '<h3>Search by '.($type ? $manu ? "type: '$_POST[type]' and manufacturer: '$_POST[manufacturer]'" : "type: '$_POST[type]'" : "manufacturer: '$_POST[manufacturer]'").' '.$query.'</h3>';
		echo '<table>';
		if ($type) {
			if ($manu) {
				echo '<tr><td>ID</td><td>Serial Number</td></tr>';
				while ($data=$result->fetch_array(MYSQLI_ASSOC)) {
					echo '<tr>';
					echo "<td>$data[auto_id]</td>";
					echo "<td>$data[serial_num]</td>";
					echo "</tr>";
				}
			} else {
				echo '<tr><td>ID</td><td>Manufacturer</td><td>Serial Number</td></tr>';
				$sql="Select `name` from `manufacturer`";
				$manu_results=$dblink->query($sql) or
					die("Something went wrong with: $sql<br>".$dblink->error);
				$manus = [];
				while ($data=$manu_results->fetch_array()) {
					$manus[]=$data['name'];
				}
				while ($data=$result->fetch_array(MYSQLI_ASSOC)) {
					echo '<tr>';
					echo "<td>$data[auto_id]</td>";
					$manu_index=intval($data['manufacturer']) - 1;
					echo "<td>$manus[$manu_index]</td>";
					echo "<td>$data[serial_num]</td>";
					echo "</tr>";
				}
			}
		} else {
			echo '<tr><td>ID</td><td>Type</td><td>Serial Number</td></tr>';
			$sql="Select `name` from `type`";
			$type_results=$dblink->query($sql) or
				die("Something went wrong with: $sql<br>".$dblink->error);
			$types = [];
			while ($data=$type_results->fetch_array()) {
				$types[]=$data['name'];
			}
			while ($data=$result->fetch_array(MYSQLI_ASSOC)) {
				echo '<tr>';
				echo "<td>$data[auto_id]</td>";
				$type_index=intval($data['type']) - 1;
				echo "<td>$types[$type_index]</td>";
				echo "<td>$data[serial_num]</td>";
				echo "</tr>";
			}
		}
		echo '</table>';
	}
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time is: $execution_time minutes or $seconds seconds.</p>\n";
}

/**
 * Displays all equipment data.
 */
function all_results() {
	$dblink=db_iconnect("clean");
	$time_start=microtime(true);
	
	$sql="Select `auto_id`,`type`,`manufacturer`,`serial_num` from `equipment_prod` limit 1000";
	$result=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$sql="Select `name` from `manufacturer`";
	$manu_results=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$manus = [];
	while ($data=$manu_results->fetch_array()) {
		$manus[]=$data['name'];
	}
	$sql="Select `name` from `type`";
	$type_results=$dblink->query($sql) or
		die("Something went wrong with: $sql<br>".$dblink->error);
	$types = [];
	while ($data=$type_results->fetch_array()) {
		$types[]=$data['name'];
	}
	echo '<h3>All equipment</h3>';
	echo '<table>';
	echo '<tr><td>ID</td><td>Type</td><td>Manufacturer</td><td>Serial Number</td></tr>';
	while ($data=$result->fetch_array(MYSQLI_ASSOC)) {
		echo '<tr>';
		echo "<td>$data[auto_id]</td>";
		$type_index=intval($data['type']) - 1;
		echo "<td>$types[$type_index]</td>";
		$manu_index=intval($data['manufacturer']) - 1;
		echo "<td>$manus[$manu_index]</td>";
		echo "<td>$data[serial_num]</td>";
		echo "</tr>";
	}
	echo '</table>';
	
	$time_end=microtime(true);
	$seconds=$time_end-$time_start;
	$execution_time=($seconds)/60;
	echo "<p>Execution time is: $execution_time minutes or $seconds seconds.</p>\n";
}
?>