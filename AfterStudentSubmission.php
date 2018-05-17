<?php
	include ('connect.php');

	$Curr_RecID=$_REQUEST['Curr_RecID'];



	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
	OR die ('Could not connect to MySQL: '.mysql_error());

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}


	$sql = "UPDATE Recruitments1 SET offerstatus='5' WHERE id=".$Curr_RecID;
	if(mysqli_query($conn, $sql)){
			echo "Success";
		}else{
			return false;
		}
?>