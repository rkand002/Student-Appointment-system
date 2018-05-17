<?php
	 session_start();
	include ('connect.php');
  	if($_SESSION['user']){
    }
    else{ 
       header("location:home.php");
 	}
 	 $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
			OR die ('Could not connect to MySQL: '.mysql_error());			
				
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}   
 	
	if($_SERVER['REQUEST_METHOD'] == "POST")
    {
	
	   $rectuitmentid = @trim(stripslashes($_POST['recId']));
	   $admUpdatedPost= @trim(stripslashes($_POST['adminUpdatedStuPost']));       
       $admUpdatedFT = @trim(stripslashes($_POST['adminUpdatedFT']));       
       $admUpdatedStudentProj = @trim(stripslashes($_POST['adminUpdatedStudentProj']));              
       $adminUpdatedStartDate = date('Y-m-d', strtotime(@trim(stripslashes($_POST['adminUpdatedStartDate']))));    
       $adminUpdatedEndDate = date('Y-m-d', strtotime(@trim(stripslashes($_POST['adminUpdatedEndDate']))));                 
       $admUpdatedTution= @trim(stripslashes($_POST['adminUpdateTution']));       
       $admUpdatedCredits = @trim(stripslashes($_POST['adminUpdatedCredits']));     
       $admUpdatedSal = @trim(stripslashes($_POST['adminUpdatedSal']));
       $admUpdatedHrs = @trim(stripslashes($_POST['adminUpdatedHours']));
       
       
    	$Query = "UPDATE Recruitments SET isfinanceverified='1',tutionwaive=".$admUpdatedTution.",salarypaid=".$admUpdatedSal." ,credithours='".$admUpdatedCredits."',hours='".$admUpdatedHrs."'";
    	$Query.= ",fundingtype='".$admUpdatedFT ."',currentpost='".$admUpdatedPost."',project_id=".$admUpdatedStudentProj.",startdate='".$adminUpdatedStartDate ."',enddate='".$adminUpdatedEndDate ."'  WHERE id='".$rectuitmentid."'";
		
    	//echo $Query;
    	$insertQueryRes = mysqli_query($conn,$Query); //Inserts the value to table Student				
		if (!$insertQueryRes) {				
			echo "fail";				
		}else{
			echo "success";
		}		
		
    }else{
    	echo "fail";
    }
    mysqli_close($con);
 	
?>