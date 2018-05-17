
<?php
	include ('connect.php');
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		

		$faculty_id = $_REQUEST['faculty_id'];
		
		
		$note = $_REQUEST['note'];
		
	    $bool = true;
	    
	  
	 
		mysql_connect(DB_HOST, DB_USER,DB_PASSWORD) or die('Could not connect to MySQL: '.mysql_error()); //Connect to server
		mysql_select_db(DB_NAME) or die("Cannot connect to database"); //Connect to database
		
		if($bool) // checks if bool is true
		{
			$Query = "update `Notes` set note='$note',facultyid=$faculty_id where id=1";
			$updateQueryRes = mysql_query($Query); //Inserts the value to table Student
			
			if (!$updateQueryRes) {
				
				echo "fail";
				
			}
			else{
			echo "success-$note";
			}
		}

	}
	mysqli_close($con);
?>