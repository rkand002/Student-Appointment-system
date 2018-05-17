
<?php
	include ('connect.php');
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		//echo "Inside addNewProject";

		$issgraproj=$_REQUEST['issgraproj'];
		$facultyid=$_REQUEST['faculty_id'];
		$name = ucwords(strtolower($_REQUEST['name']));
		$agency = ucwords(strtolower($_REQUEST['agency']));
		$projDesc = ucwords(strtolower($_REQUEST['projectdescription']));
		$startDate = $_REQUEST['projectstartdate'];
		$endDate = $_REQUEST['projectenddate'];
		
		$ExtraFacultyStr=explode("-", $_REQUEST['faculty_name']);
		$ExtraFacultyID=$ExtraFacultyStr[1];
	    $bool = true;

	   // echo "ll".$issgraproj;
		mysql_connect(DB_HOST, DB_USER,DB_PASSWORD) or die('Could not connect to MySQL: '.mysql_error()); //Connect to server
		mysql_select_db(DB_NAME) or die("Cannot connect to database"); //Connect to database
		
		if($bool) 
		{
			if($issgraproj==1)
				$Query = "INSERT INTO `".DB_NAME."`.`Projects` (`id`, `name`, `issgraproj`, `faculty_id`, `status`, `agency`, `projectdescription`, `projectstartdate`, `projectenddate`) VALUES (NULL, '$name', '$issgraproj', NULL, '1', '$agency', '$projDesc', '$startDate', '$endDate')";
			else if($issgraproj==0)
				$Query = "INSERT INTO `".DB_NAME."`.`Projects` (`id`, `name`, `issgraproj`, `faculty_id`, `status`, `agency`, `projectdescription`, `projectstartdate`, `projectenddate`) VALUES (NULL, '$name', '$issgraproj', '$facultyid', '1', '$agency', '$projDesc', '$startDate', '$endDate')";
			//echo $Query;
			$insertQueryRes = mysql_query($Query); 
			$lastInserted_projectId = mysql_insert_id();
			


			if($issgraproj==0){
				$QueryforStaffProjectIP = "INSERT INTO `".DB_NAME."`.`StaffProjectIP` (`id`, `projectid`, `staffid`) VALUES (NULL, '$lastInserted_projectId', '$facultyid')";
				$insertforMainFaculty = mysql_query($QueryforStaffProjectIP);


				$QueryforAddingExtraFaculty = "INSERT INTO `".DB_NAME."`.`StaffProjectIP` (`id`, `projectid`, `staffid`) VALUES (NULL, '$lastInserted_projectId', '$ExtraFacultyID')";
				$insertforExtraFaculty = mysql_query($QueryforAddingExtraFaculty);
			
			}





			
			if (!$insertQueryRes) {
				
				echo "fail-duplicate";
				
			}
			
			else
			{
				/*$responseQuery="SELECT id,name,issgraproj FROM `Projects` where issgraproj=1 or (issgraproj=0 and faculty_id=$facultyid)";*/
				$responseQuery="SELECT Projects.id,Projects.name,Projects.issgraproj FROM `Projects` join StaffProjectIP on Projects.id=StaffProjectIP.projectid where Staffid=$facultyid 
					union
					SELECT id,name,issgraproj FROM Projects where Projects.issgraproj=1";
				//echo $responseQuery;
				$QueryRes = mysql_query($responseQuery);
				
				if(mysql_num_rows($QueryRes)>0)
				{
					$arrayRes=array();
					while ($rowRes = mysql_fetch_assoc($QueryRes)) {
						$arrayRes[] = $rowRes;
						//echo $rowRes;
					}
					echo json_encode($arrayRes);
				}	
				else
				{
					echo "fail-noproj";
				}
			}
			
			

		
		}

	}
	mysqli_close($con);
?>