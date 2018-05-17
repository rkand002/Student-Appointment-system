<?php
    session_start();
    include ('connect.php');
    if($_SESSION['user']){
    }
    else{ 
       header("location:home.php");
    }
	$user_id = $_SESSION['user_id'];
	$isAdmin = False;
	$totalMaxHours = 20;
	$succReturnStr ="success-";
	if($_SESSION['isAdmin'] == "True"){
	   	$isAdmin = True;
	}
	$isMultiPosition = false;
	$insertedRecIDs= array();
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
	   
	   if(count($dataArr) > 1){
	   		$isMultiPosition = true;
	   }
	   $insertedRecruitmentID = 0;
	   
    	

	   foreach($dataArr as $position)
	   {
	      // echo "Inside For";
		   $studentid = @trim(stripslashes($position->uinIP));       
	       $facultyid = @trim(stripslashes($position->staffIP));
	        
	       $startdate =date('Y-m-d', strtotime(@trim(stripslashes($position->startDateIP))));
	       $enddate =date('Y-m-d', strtotime(@trim(stripslashes($position->endDateIP))));
	       //echo $startdate,$enddate;
	       $semester = @trim(stripslashes($position->semesterIP));
	       $currentpost = @trim(stripslashes($position->postIP));
	       $year = @trim(stripslashes($position->yearIP));
	       
	       // for the additional requirement of tution and no of credits
	       $tution = @trim(stripslashes($position->tutionWaiveIP));
	       $noofcredits = @trim(stripslashes($position->noOfCreditsIP));
	       //$tution $noofcredits
	       $salarypaid = @trim(stripslashes($position->salaryIP)); 
	       $projid = @trim(stripslashes($position->projID));  //it can be a string if it we are adding a new project on a fly
	       $projip = @trim(stripslashes($position->projIP));
	       $hoursip = @trim(stripslashes($position->hoursIP));
	       $fundingip = @trim(stripslashes($position->fundingIP));
	       $isreappointed = 0;

			// code to check whether a student is already been allocated with with 20Hours of work from another professor
			//$stuSelQuery ="Select * FROM Recruitments WHERE student_id='".$studentid."' and semester='".$semester."' and year='".$year."'";
			//changed - multiple positions
			$stuSelQuery ="Select Recruitments1.id,Recruitments1.faculty_id,RecruitPositions.hours FROM Recruitments1 join RecruitPositions on Recruitments1.id = RecruitPositions.recruitment_id WHERE Recruitments1.student_id='".$studentid."' and Recruitments1.semester='".$semester."' and Recruitments1.year='".$year."'";

			//echo "Query to check with existing Appointments -".$stuSelQuery;
	    	$exiStuRecRes = mysqli_query($conn, $stuSelQuery);
		    if (mysqli_num_rows($exiStuRecRes) > 0) {
		    	// do nothing
		   
		    	$exiHoursSum = 0;
		    	$facultyId ="";
		    	while($row = $exiStuRecRes->fetch_assoc()) {
	    		
		 			$exiHoursSum +=  intval($row["hours"]);
		 			$facultyId = $row["faculty_id"];
		 			
		 		}
		 		//echo $exiHoursSum;
		 		//echo $facultyId;
		    	$totalHours = $exiHoursSum+intval($hoursip);
		    	//echo $totalHours;
		   			 if($semester == "Summer"){
				    		$totalMaxHours = 40;
				    	}else{
				    		$totalMaxHours = 20;
				    	}
		    	
		    	
		 		if($exiHoursSum >= $totalMaxHours){
		 			//echo "1";
		 			$facNameQuery ="Select firstname,lastname FROM Staff WHERE faculty_id='".$facultyId."'";
		 			//echo $facNameQuery;
		 			$proffName = mysqli_query($conn,$facNameQuery);
			 		while($row = $proffName->fetch_assoc()) {		    			    		
			 			echo "fail-This Student is already been appointed by Prof ".$row['lastname']." ".$row['firstname']." so you cannot appoint.";
		 				exit();
			 		}	 			
		 		}
				//echo $totalHours;
			 	  if($totalHours > $totalMaxHours){		
			 			$facNameQuery ="Select firstname,lastname FROM Staff WHERE faculty_id='".$facultyId."'";
				 			//echo $facNameQuery;
				 		$proffName = mysqli_query($conn,$facNameQuery);
				 		while($row = $proffName->fetch_assoc()) {		    			    		
				 			echo "fail-This Student is already been appointed by Prof ".$row['lastname']." ".$row['firstname']." for ".$exiHoursSum." hours, Hence you cannot appoint since the student will be overwhelemed";
				 				exit();
				 		}	
		 		   }
			  }

	    	$tutionQuery ="Select * FROM AdminSettings WHERE 1";	
	    	$tutionRecRes = mysqli_query($conn, $tutionQuery);
	    	$currentTution= 0.0;
		    if (mysqli_num_rows($tutionRecRes) > 0) {
		    	while($row = $tutionRecRes->fetch_assoc()) {   		
		 				$currentTution = floatval($row["currenttution"]);	 			
		 		}	    	
		    }
		    
			//echo "Tution Fee". $currentTution;
		    // this was the query written prior to multiple positions
			//$Query2 ="INSERT INTO Recruitments(student_id, faculty_id, semester, currentpost,year,tutionwaive,credithours,currenttution,salarypaid,hours,project_id,isreappointed,offerstatus,startdate,enddate,fundingtype) VALUES ('$studentid','$facultyid','$semester','$currentpost','$year',$tution,$noofcredits,$currentTution,$salarypaid,$hoursip,$projid,$isreappointed,'0','$startdate' ,'$enddate',$fundingip)"; //SQL query			
			
			// if the condition satisfied meaning,
		   	if($insertedRecruitmentID == 0){
			   	// added for --- multiple positions
		      	//query for the insertion into Recruitment1 table 
		      	    
			    $queryInsertIntoRecruitment = "INSERT INTO Recruitments1(student_id, faculty_id, semester,currentpost,year,tutionwaive,credithours,currenttution,isreappointed,offerstatus,startdate,enddate) VALUES ('$studentid',$facultyid,'$semester','$currentpost','$year',$tution,$noofcredits,$currentTution,$isreappointed,'0','$startdate' ,'$enddate')"; //SQL query	
				//echo "Query In to Recruitment-".$queryInsertIntoRecruitment;
			    if(mysqli_query($conn,$queryInsertIntoRecruitment)){
			 		
					$insertedRecruitmentID = mysqli_insert_id($conn);  	
					//echo "Inserted Recruitment ID -".$insertedRecruitmentID;
			  		//echo $studentid.",".$facultyid.",".$semester.",".$facultyid.",".$currentpost.",".$year.",".$salarypaid.",".$isreappointed;
		    	 	$succReturnStr.=$insertedRecruitmentID."|";		
		    	 	
		    	 	// for inserting the position 
		    	 	$queryInsertIntoRecruitPositions = "INSERT INTO RecruitPositions(recruitment_id,currentpost, project_id, startdate,enddate,fundingtype,salarypaid,hours) VALUES ($insertedRecruitmentID,'$currentpost',$projid,'$startdate' ,'$enddate',$fundingip,$salarypaid ,$hoursip)"; //SQL query			
					//echo "Query in to RecruitmentPosition -".$queryInsertIntoRecruitPositions;		    	 	
		    	 	if(mysqli_query($conn,$queryInsertIntoRecruitPositions)){
		    	 		
		    	 	}else{
		    	 		return "fail";
		    	 	}
				}
			    else
			    {
			    	return "fail";
			    }
		   	} else{
		   		
		   		$queryInsertIntoRecruitPositions = "INSERT INTO RecruitPositions(recruitment_id,currentpost, project_id, startdate,enddate,fundingtype,salarypaid,hours) VALUES ($insertedRecruitmentID,'$currentpost',$projid,'$startdate' ,'$enddate',$fundingip,$salarypaid ,$hoursip)"; //SQL query			
		    	 	//echo "Query in to RecruitmentPosition -".$queryInsertIntoRecruitPositions;
		   			if(mysqli_query($conn,$queryInsertIntoRecruitPositions)){
		    	 		//echo "Interted into RecruitmentPosition";
		    	 	}else{
		    	 		return "fail";
		    	 	}
		   	}
	   }
	   
	   /*if($isMultiPosition){	
		   	$updateQuery1 = "UPDATE Recruitments SET ismultiple=".$insertedRecIDs[0]." WHERE id=".$insertedRecIDs[1];		
	    	if(mysqli_query($conn, $updateQuery1)){
	    		$updateQuery2 = "UPDATE Recruitments SET ismultiple=".$insertedRecIDs[1]." WHERE id=".$insertedRecIDs[0];		
		    	if(mysqli_query($conn, $updateQuery2)){
			   	
			   	}else{
			   		echo "fail";
			   	}
		   	}else{
		   		echo "fail";		   		
		   	}
	   }*/
	   
		echo $succReturnStr;
    }
    else
    {
    	echo "fail";
     // header("location:home.php");
    }
    mysqli_close($conn);
?>