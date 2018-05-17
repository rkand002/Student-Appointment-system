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
	   if($_SESSION['isAdmin'] == "True"){
	   		$isAdmin = True;
	   }
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
       $studentid = @trim(stripslashes($_POST['uinIP']));       
       $facultyid = @trim(stripslashes($_POST['staffIP']));
       $extAppImpBy = @trim(stripslashes($_POST['extAppImpBy']));
       
       $startdate =date('Y-m-d', strtotime(@trim(stripslashes($_POST['startDateIP']))));
       $enddate =date('Y-m-d', strtotime(@trim(stripslashes($_POST['endDateIP']))));
       //echo $startdate,$enddate;
       $semester = @trim(stripslashes($_POST['semesterIP']));
       $currentpost = @trim(stripslashes($_POST['postIP']));
       $year = @trim(stripslashes($_POST['yearIP']));
       
       // for the additional requirement of tution and no of credits
       $tution = @trim(stripslashes($_POST['tutionWaiveIP']));
       $noofcredits = @trim(stripslashes($_POST['noOfCreditsIP']));
       //$tution $noofcredits
       $salarypaid = @trim(stripslashes($_POST['salaryIP'])); 
       $projid = @trim(stripslashes($_POST['projID']));  //it can be a string if it we are adding a new project on a fly
       $projip = @trim(stripslashes($_POST['projIP']));
       $hoursip = @trim(stripslashes($_POST['hoursIP']));
       $fundingip = @trim(stripslashes($_POST['fundingIP']));
       $appointmentToEdit = @trim(stripslashes($_POST['appointmentID']));
       $recPosID = @trim(stripslashes($_POST['recPosID']));
        $isreappointed = 0;
	 	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
		OR die ('Could not connect to MySQL: '.mysql_error());
		
	    if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		/* changed because of the multiple Project Ips and Mutiple Positions
		 * $Query1 ="Select * from Projects WHERE name='$projip'";
		$result = mysqli_query($conn, $Query1);
	    if (mysqli_num_rows($result) > 0) {
	    	// do nothing
	    	while($row = $result->fetch_assoc()) {
	 			$projid = $row["id"];
	 		}
	    	
		} else {
		   if($projid != "0" && $projip!=="NONE"){
		   		$isSGRAProj = 0;
		   		if($currentpost == "SGRA" || $currentpost == "PHD_SGRA"){
		   			$isSGRAProj = 1;
		   		}
		   	
		    	$projInsQuery = "INSERT INTO Projects(name,issgraproj,faculty_id,status) VALUES ('$projip','$isSGRAProj','$user_id','1')"; //SQL insert into project query	 
				if(mysqli_query($conn,$projInsQuery)){
			 		$insertedID = mysqli_insert_id($conn);  
			 		$projid = $insertedID;    				  		
				}
			    else
			    {
			    	exit();		   
			    } 
			}else{
				$projid="";
			}		  	
		}*/
		
    	$tutionQuery ="Select * FROM AdminSettings WHERE 1";	
    	$tutionRecRes = mysqli_query($conn, $tutionQuery);
    	$currentTution= 0.0;
	    if (mysqli_num_rows($tutionRecRes) > 0) {
	    	while($row = $tutionRecRes->fetch_assoc()) {   		
	 				$currentTution = floatval($row["currenttution"]);	 			
	 		}	    	
	    }
		//echo $currentTution;

		//$Query2 = "UPDATE Recruitments SET semester='$semester', currentpost='$currentpost',year='$year',tutionwaive=$tution,credithours=$noofcredits,currenttution=$currentTution,salarypaid=$salarypaid,hours=$hoursip,project_id=$projid,isreappointed=$isreappointed,offerstatus=4,startdate='$startdate',enddate='$enddate',fundingtype=$fundingip,existingAppImportedBy=$extAppImpBy WHERE id=".$appointmentToEdit;

	    $QueryUpdateRec = "UPDATE Recruitments1 SET semester='$semester', currentpost='$currentpost',year='$year',tutionwaive=$tution,credithours=$noofcredits,currenttution=$currentTution,startdate='$startdate',enddate='$enddate',existingAppImportedBy=$extAppImpBy WHERE id=".$appointmentToEdit;
		
	    $QueryUpdateRecPos = "UPDATE RecruitPositions SET salarypaid=$salarypaid,hours=$hoursip,project_id=$projid,fundingtype=$fundingip WHERE id=".$recPosID;
	    
	    
      //echo $Query2;
		if(mysqli_query($conn,$QueryUpdateRec)){
	 			
	  		//echo $studentid.",".$facultyid.",".$semester.",".$facultyid.",".$currentpost.",".$year.",".$salarypaid.",".$isreappointed;
	 		//header("location:home.php");
	 		if(mysqli_query($conn,$QueryUpdateRecPos)){
	 				echo "success";
	 		}
		}
	    else
	    {
	    	echo "fail";
	     // header("location:home.php");
	    }
    }
    else
    {
    	echo "fail";
     // header("location:home.php");
    }
    mysqli_close($con);
?>