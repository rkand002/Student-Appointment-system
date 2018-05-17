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
     	$jsonString = $_POST["data"];   
		$jsonObj = json_decode($jsonString);
		$dataArr=$jsonObj->dataset;
		$position = $dataArr[0];
		 $rectuitmentid = @trim(stripslashes($position->recId));
	   	   $recPositionId = @trim(stripslashes($position->recPosId));
	   	   $admUpdatedPost= @trim(stripslashes($position->adminUpdatedStuPost));       
	       $admUpdatedStudentProj = @trim(stripslashes($position->adminUpdatedStudentProj));              
	       $adminUpdatedStartDate = date('Y-m-d', strtotime(@trim(stripslashes($position->adminUpdatedStartDate))));    
	       $adminUpdatedEndDate = date('Y-m-d', strtotime(@trim(stripslashes($position->adminUpdatedEndDate))));  
	       $admUpdatedTution= @trim(stripslashes($position->adminUpdateTution));       
	       $admUpdatedSal = @trim(stripslashes($position->adminUpdatedSal));
	       $admUpdatedFT = @trim(stripslashes($position->adminUpdatedFT));       
		   $admUpdatedCredits = @trim(stripslashes($position->adminUpdatedCredits));
       	   $admUpdatedHrs = @trim(stripslashes($position -> adminUpdatedHours));
			$QueryUpdateRecruitment = "UPDATE Recruitments1 SET isfinanceverified='1',tutionwaive=".$admUpdatedTution.",credithours='".$admUpdatedCredits."'";
	    	$QueryUpdateRecruitment.= ",currentpost='".$admUpdatedPost."',startdate='".$adminUpdatedStartDate ."',enddate='".$adminUpdatedEndDate ."'  WHERE id='".$rectuitmentid."'";
    		$insertQueryRes = mysqli_query($conn,$QueryUpdateRecruitment); //Inserts the value to table Student				
			if (!$insertQueryRes) {		
				echo "fail";
				return false;				
			}else{
			
				foreach($dataArr as $position)
			   	{
			   	   $rectuitmentid = @trim(stripslashes($position->recId));
			   	   $recPositionId = @trim(stripslashes($position->recPosId));
			   	   $admUpdatedPost= @trim(stripslashes($position->adminUpdatedStuPost));       
			       $admUpdatedStudentProj = @trim(stripslashes($position->adminUpdatedStudentProj));              
			       $adminUpdatedStartDate = date('Y-m-d', strtotime(@trim(stripslashes($position->adminUpdatedStartDate))));    
			       $adminUpdatedEndDate = date('Y-m-d', strtotime(@trim(stripslashes($position->adminUpdatedEndDate))));  
			       $admUpdatedTution= @trim(stripslashes($position->adminUpdateTution));       
			       $admUpdatedSal = @trim(stripslashes($position->adminUpdatedSal));
			       $admUpdatedFT = @trim(stripslashes($position->adminUpdatedFT));       
				   $admUpdatedCredits = @trim(stripslashes($position->adminUpdatedCredits));
		       	   $admUpdatedHrs = @trim(stripslashes($position -> adminUpdatedHours));
					
		       	   //echo "Updated Hours -".$admUpdatedHrs;
		       	   //changed - multiple positions	
			      //$Query = "UPDATE Recruitments SET isfinanceverified='1',tutionwaive=".$admUpdatedTution." ,salarypaid=".$admUpdatedSal." ,credithours='".$admUpdatedCredits."' ,fundingtype='".$admUpdatedFT ."'  WHERE id='".$rectuitmentid."'";
					/*$Query = "UPDATE Recruitments SET isfinanceverified='1',tutionwaive=".$admUpdatedTution.",salarypaid=".$admUpdatedSal." ,credithours='".$admUpdatedCredits."',hours='".$admUpdatedHrs."'";
			    	$Query.= ",fundingtype='".$admUpdatedFT ."',currentpost='".$admUpdatedPost."',project_id=".$admUpdatedStudentProj.",startdate='".$adminUpdatedStartDate ."',enddate='".$adminUpdatedEndDate ."'  WHERE id='".$rectuitmentid."'";
					   */			    	
			    	//echo $Query;
			    	/*$insertQueryRes = mysqli_query($conn,$QueryUpdateRecruitment); //Inserts the value to table Student				
					if (!$insertQueryRes) {		
						echo "fail";
						return false;				
					}*/
		       	   
		       	   
						$QueryUpdateRecPosition = "UPDATE  RecruitPositions SET salarypaid=".$admUpdatedSal." ,hours='".$admUpdatedHrs."'";
			    		$QueryUpdateRecPosition.= ",currentpost='".$admUpdatedPost."' ,fundingtype='".$admUpdatedFT ."',project_id=".$admUpdatedStudentProj.",startdate='".$adminUpdatedStartDate ."',enddate='".$adminUpdatedEndDate ."'  WHERE id='".$recPositionId."'";
			    		//echo $QueryUpdateRecPosition;
			    		$insertQueryRes = mysqli_query($conn,$QueryUpdateRecPosition); //Inserts the value to table Student		

						
						if (!$insertQueryRes) {		
							echo "fail";
							return false;				
						}	
					}	
			   		echo "success";
	   		}
	   			
	    }else{
	    	echo "fail";
	    }
    mysqli_close($con);
 	
?>