<?php
	include ('connect.php');

	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
	OR die ('Could not connect to MySQL: '.mysql_error());

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	


	$sql = "SELECT faculty_id,firstname FROM `Staff` where isadmin!=1 and isadmin!=2";
    	//echo $sql;
    	$result = mysqli_query($conn, $sql);
		$array = array();		
		while ($row = mysqli_fetch_assoc($result)) {
			$array[] = $row['firstname']."-".$row['faculty_id'];
		}	
		echo json_encode($array); //Return the JSON Array
		
?>