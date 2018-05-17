<?php
	include ('connect.php');
		$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
	OR die ('Could not connect to MySQL: '.mysql_error());
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}



	$sql = "SELECT * FROM `Notes`";
    	//echo $sql;
    	$result = mysqli_query($conn, $sql);
			
		$row = mysqli_fetch_assoc($result); 
	    echo $row["note"];
	    
		
			//echo $row['name']."-".$row['id'];	
		//echo json_encode($array); //Return the JSON Array
	/*$query = mysql_query("Select * from Staff"); //Query the users table
	$row = mysql_fetch_array($query);*/
	/*$notes=$row['note'];
	echo $notes;*/




?>
