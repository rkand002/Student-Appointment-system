<?php
	include ('connect.php');
	require 'Send_Mail1.php';
	session_start();
	$user_id = $_SESSION['user_id'];
	if($_SESSION['user']){
    }
    else{ 
       header("location:login.php");
    }
    $isMultiPosition = false;
	$insertedRecIDs= array();
	$jsonString = $_POST["data"];   
	 $jsonObj = json_decode($jsonString);
	 $dataArr=$jsonObj->dataset;
   		$totalMaxHours = 20;
	 if(count($dataArr) > 1){
	   	$isMultiPosition = true;
	 }
    
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
				OR die ('Could not connect to MySQL: '.mysql_error());
			
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$forSecondTime = false;
	
	
	 $insertedRecruitmentID = 0;
	 
	 foreach($dataArr as $position)
	 {
    		$exisitingrecruitmentId = @trim(stripslashes($position->recid));
			//echo $exisitingrecruitmentId;			
			$newProjDet = explode("-",@trim(stripslashes($position->reProj)));
			$newProjID = $newProjDet[1];
			$newSal = @trim(stripslashes($position->reSalaryIP));
			$newPost = @trim(stripslashes($position->rePostIP));
			$newHours = @trim(stripslashes($position->reHourIP));
			$newTution = @trim(stripslashes($position->reTutionIP));
			$newCredits = @trim(stripslashes($position->reCreditsIP));
			$semester = @trim(stripslashes($position->semester));
			$year = @trim(stripslashes($position->yearIP));
			$startdate = date('Y-m-d', strtotime(@trim(stripslashes($position->startdate))));
			$enddate = date('Y-m-d', strtotime(@trim(stripslashes($position->enddate))));
			$fundingType= @trim(stripslashes($position->reFundingIP));
			$newInsertedID="";

				$toList= array();
				$ccList = array();
				$stuObj = array();
				$proffName="";
				$academicSem = "";
				$studentDet ="";
		    
			/*$existingRecSelQuery = $conn->prepare('SELECT R.student_id AS stu_id, Stu.firstname as stu_fn,
			 	Stu.lastname AS stu_ln,Stu.email AS stu_email,R.currentpost,R.semester,R.year,R.salarypaid,R.offerstatus,R.hours,Sta.faculty_id as sta_id, Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.email AS sta_email,
			 	R.isreappointed,R.project_id As proj_id FROM Recruitments R JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id and R.id = ?');
			*/
				
				//changed - multiple positions	
							    		
			$existingRecSelQuery = 'SELECT R.student_id AS stu_id, Stu.firstname as stu_fn,
			 	Stu.lastname AS stu_ln,Stu.email AS stu_email,R.currentpost,R.semester,R.year,R.offerstatus,Sta.faculty_id as sta_id, Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.email AS sta_email,
			 	R.isreappointed FROM Recruitments1 R JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id where R.id ='.$exisitingrecruitmentId;

				//echo $existingRecSelQuery;
				
				$recSelResult = mysqli_query($conn, $existingRecSelQuery);
				
			/*	 if (mysqli_num_rows($recSelResult) > 0) {
					echo "query executed successfully";
				 }*/
				
				while($row = $recSelResult->fetch_assoc()) {
				 	
			  		$studentDet = $row["stu_fn"]." ".$row["stu_ln"]."(".$row["stu_id"].")";			  		
			    	$stuObj["name"] =  $row["stu_fn"]."-".$row["stu_ln"];
			      	$stuObj["email"] = $row["stu_email"];     
			
			      	$proffName = $row["sta_fn"]." ".$row["sta_ln"];
			  	
			    	// some businesslogiv to be written here yet
			    	$studentid = $row['stu_id'];
			    	//$semester= $row['semester'];
			    	$facultyid= $_SESSION['user_id'];
			    	//$year = $row['year'];
			    	$isreappointed = 0;
				    $semAndYear = "";

			
			  		// code to check whether a student is already been allocated with with 20Hours of work from another professor
					//$stuSelQuery ="Select * FROM Recruitments WHERE student_id='".$studentid."' and semester='".$semester."' and year='".$year."'";
					//changed - multiple positions				    
				    $stuSelQuery ="Select Recruitments1.id,Recruitments1.faculty_id,RecruitPositions.hours FROM Recruitments1 join RecruitPositions on Recruitments1.id = RecruitPositions.recruitment_id WHERE Recruitments1.student_id='".$studentid."' and Recruitments1.semester='".$semester."' and Recruitments1.year='".$year."'";
					//echo $stuSelQuery;
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
				    	$totalHours = $exiHoursSum+intval($newHours);
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
									
					//echo $studentid.",".$facultyid.",".$semester.",".$currentpost.",".$year.",".$salarypaid.",".$newHours.",".$projid.",".$isreappointed;   	
					$offerStatus =0;   	
			      	$tutionQuery ="Select * FROM AdminSettings WHERE 1";	
			    	$tutionRecRes = mysqli_query($conn, $tutionQuery);
			    	$currentTution= 0.0;
				    if (mysqli_num_rows($tutionRecRes) > 0) {
				    	while($row = $tutionRecRes->fetch_assoc()) {   		
				 				$currentTution = floatval($row["currenttution"]);	 			
				 		}	    	
				    }
				    $academicSem = $semester."|".$year;
				    
				   // this was the query written prior to multiple positions
			       //$Query1 ="INSERT INTO Recruitments(student_id, faculty_id, semester, currentpost,year,tutionwaive,credithours,currenttution,salarypaid,hours,project_id,isreappointed,offerstatus,startdate,enddate,fundingtype) VALUES 
			       //									('$studentid','$facultyid','$semester','$newPost','$year',$newTution,$newCredits,$currentTution,$newSal,$newHours,$newProjID,$isreappointed,$offerStatus,'$startdate' ,'$enddate',$fundingType)"; //SQL query   		
				    
			       // if the condition satisfied meaning,
		   			if($insertedRecruitmentID == 0){
			    		$queryInsertIntoRecruitment = "INSERT INTO Recruitments1(student_id, faculty_id, semester,currentpost,year,tutionwaive,credithours,currenttution,isreappointed,offerstatus,startdate,enddate) VALUES 
			    											('$studentid',$facultyid,'$semester','$newPost','$year',$newTution,$newCredits,$currentTution,$isreappointed,$offerStatus,'$startdate' ,'$enddate')"; //SQL query	
		   						    	   	
				    	//echo $queryInsertIntoRecruitment;
						$Query2 = "UPDATE Recruitments SET isreappointed='1' WHERE id=".$exisitingrecruitmentId;		
				    	    	
				    	if(mysqli_query($conn,$queryInsertIntoRecruitment)){
				    		$insertedRecruitmentID = mysqli_insert_id($conn);  					    		
							$newInsertedID = mysqli_insert_id($conn);
						    // for inserting the position 
				    	 	$queryInsertIntoRecruitPositions = "INSERT INTO RecruitPositions(recruitment_id,currentpost, project_id, startdate,enddate,fundingtype,salarypaid,hours) VALUES ($insertedRecruitmentID,'$newPost' ,$newProjID,'$startdate' ,'$enddate',$fundingType,$newSal ,$newHours)"; //SQL query			
							//echo "Query in to RecruitmentPosition -".$queryInsertIntoRecruitPositions;		    	 	
					    	 	if(mysqli_query($conn,$queryInsertIntoRecruitPositions)){
					    	 		if(!mysqli_query($conn,$Query2)){
						    			echo "something went wrong with insertion";
						    			exit;
						    		}	    
									//echo "newlyInserted:". $newInsertedID;
							    	// code to email added from right hre 
							    	//array_push($ccList, $_SESSION['user_email']);
							    	//array_push($ccList, $row['stu_email']);
								    $emailSubject ="Offer Initiated for ".$academicSem; // this it got to be dymamic, right now it is hardcoded   		   
									$emailBody = "Hi ".$studentDet.", <br />Professor ".$proffName." has initiated the Appointment.<br /><br />";
									$emailBody.= "please click on the below link to accept or decline the offer. <br /><br />";		
									
									// this is the link that student clicks to upload his offer letter and clicks on accept
									//$emailBody .= "http://qav2.cs.odu.edu/Prod_StudentAppointmentSystem/landingPageStudent1.php?recid=".$newInsertedID;		
													
									$emailBody .= "http://qav2.cs.odu.edu/".SERVERHOST."_StudentAppointmentSystem/landingPageStudent1.php?recid=".$newInsertedID;	
									
									$emailBody.="<br /> <br />Thank you";
									
									$stuObj["emailSubject"] = $emailSubject;
									$stuObj["emailBody"] = $emailBody;		
							      	array_push($toList,$stuObj); 

					    	 	}else{
					    	 		return "fail";
					    	 	}    	  			    		
					    	}
					    else{
					    	echo "fail";
					    	return false;
					    } 
		   			}else{
				    	 	$queryInsertIntoRecruitPositions = "INSERT INTO RecruitPositions(recruitment_id,currentpost, project_id, startdate,enddate,fundingtype,salarypaid,hours) VALUES ($insertedRecruitmentID,'$newPost' ,$newProjID,'$startdate' ,'$enddate',$fundingType,$newSal ,$newHours)"; //SQL query			
		   					//echo "Query in to RecruitmentPosition -".$queryInsertIntoRecruitPositions;
				   			if(mysqli_query($conn,$queryInsertIntoRecruitPositions)){
				    	 		//echo "Interted into RecruitmentPosition";

				    	 	}else{
				    	 		return "fail";
				    	 	}
				   	}
			   } 
			    			    
			  $adminSelQuery = "SELECT * FROM Staff WHERE isadmin = 1 or isadmin = 2";
				$adminResult = $conn->query($adminSelQuery);
			    // output data of each row
				while($row = $adminResult->fetch_assoc()) {
				   	$admin = array();
				    $admin["name"] =  $row["firstname"]."-".$row["lastname"];
				    //echo $admin["name"] ;
				    $admin["email"] = $row["email"];
				     //echo $admin["email"] ;
				    
				    $emailSubject ="Offer Initiated for Student ".$studentDet."- ".$academicSem.".";
				    $emailBody = "Hi ".$row["firstname"]." ".$row["firstname"].", <br />Professor ".$proffName." has initiated the Appointment to the Student ".$studentDet."<br /><br />";	    	
				    //echo "adminType:".$row["isadmin"];
				    if(intval($row["isadmin"]) == 1){
				    	$emailBody.= "You may have to verify the Funds availbalility once the student accepts the Appointment. <br /><br />";			    	
				    }else{
				    	$emailBody.= "You may have to verify the Student's Academic Status and stuff once he accepts the Appointment. <br /><br />";		    	
				    }       	    
					//this is the link that student clicks to upload his offer letter and clicks on accept
					//$emailBody.= "Here is the link that you may go for: <br /><br /> http://qav2.cs.odu.edu/Prod_StudentAppointmentSystem/home.php";			    
				    $emailBody.= "Here is the link that you may go for: <br /><br /> http://qav2.cs.odu.edu/".SERVERHOST."_StudentAppointmentSystem/home.php";			
					$emailBody.="<br /> <br />Thank you";
						    
				    $admin["emailSubject"] = $emailSubject;
				    $admin["emailBody"] = $emailBody;   
				    //echo  $admin["emailSubject"];
				   // echo $admin["emailBody"];
				    array_push($ccList,$admin);
				} 
				//echo json_encode($toList);
				//echo json_encode($ccList);
				array_push($insertedRecIDs,$newInsertedID);
				if(!$forSecondTime){
					if(Send_Mail1($toList,$ccList)){		     	
		 		 
					 }else{
						  echo "error";
					 } 	
				}
				// to avoid duplicated emails			     	
			$forSecondTime = true;
	 }
	 
	 
	 //echo "before multiposition uodate check";
	/* if($isMultiPosition){	
		   	$updateQuery1 = "UPDATE Recruitments SET ismultiple=".$insertedRecIDs[0]." WHERE id=".$insertedRecIDs[1];	
		   	//echo $updateQuery1;
	    	if(mysqli_query($conn, $updateQuery1)){
	    		$updateQuery2 = "UPDATE Recruitments SET ismultiple=".$insertedRecIDs[1]." WHERE id=".$insertedRecIDs[0];		
	    		 //echo $updateQuery2;
		    	if(mysqli_query($conn, $updateQuery2)){
			   	
			   	}else{
			   		echo "fail";
			   		return;
			   	}
		   	}else{
		   		echo "fail";
		   		return;		   		
		   	}
	  }*/
	  
	  
	 echo "success-Student Successfully reappointed";
   // mysqli_commit($con);
   mysqli_close($con);
//echo json_encode($array);
?>
