<?php
	 session_start();
	include ('connect.php');
  	if($_SESSION['user']){
  		 $user_id = $_SESSION['user_id'];
    }
    else{ 
       header("location:home.php");
    }

    $id = intval(@trim(stripslashes($_REQUEST["userid"])));
    $admin=$_REQUEST["admin"];
    
	function download_csv_results($results, $name)
	{            
	    header('Content-Type: text/csv');
	    header('Content-Disposition: attachment; filename='. $name);
	    header('Pragma: no-cache');
	    header("Expires: 0");

	    $outstream = fopen("php://output", "w");    
	    fputcsv($outstream, array_keys($results[0]));

	    foreach($results as $result)
	    {
	        fputcsv($outstream, $result);
	    }
		$outstream = stream_get_contents($outstream);
		echo $outstream;
	    fclose($outstream);
	}

   $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
			OR die ('Could not connect to MySQL: '.mysql_error());			
				
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}   
    if($admin)
    	//$Query="SELECT R.id as rec_id , R.student_id AS stu_id, Stu.firstname as stu_fn, Stu.lastname AS stu_ln,Stu.email as stu_email,Stu.i9expiry as stu_i9expiry, R.currentpost,R.semester,R.year,R.tutionwaive,R.credithours,R.salarypaid,R.currenttution,R.offerstatus,R.hours,R.startdate,R.enddate,R.isfinanceverified,R.fundingtype,R.existingAppImportedBy,Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.faculty_id as sta_id,R.isreappointed,R.project_id FROM Recruitments R JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id ";
    	$Query="SELECT R.id as rec_id , R.student_id AS stu_id, Stu.firstname as stu_fn, Stu.lastname AS stu_ln,Stu.email as stu_email,Stu.i9expiry as stu_i9expiry, R.currentpost,R.semester,R.year,R.tutionwaive,R.credithours,RP.salarypaid,R.currenttution,R.offerstatus,RP.hours,RP.startdate,RP.enddate,R.isfinanceverified,RP.fundingtype,R.existingAppImportedBy,Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.faculty_id as sta_id,R.isreappointed,RP.project_id FROM Recruitments1 R join RecruitPositions RP on R.id = RP.recruitment_id JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id ";	
    else	
		$Query="SELECT R.id as rec_id , R.student_id AS stu_id, Stu.firstname as stu_fn, Stu.lastname AS stu_ln,Stu.email as stu_email,Stu.i9expiry as stu_i9expiry, R.currentpost,R.semester,R.year,R.tutionwaive,R.credithours,RP.salarypaid,R.currenttution,R.offerstatus,RP.hours,RP.startdate,RP.enddate,R.isfinanceverified,RP.fundingtype,R.existingAppImportedBy,Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.faculty_id as sta_id,R.isreappointed,RP.project_id FROM Recruitments1 R join RecruitPositions RP on R.id = RP.recruitment_id JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id and R.faculty_id=$id";
	

	$apptsRes = mysqli_query($conn, $Query);
	    if (mysqli_num_rows($apptsRes) > 0) {
	    	while($row = mysqli_fetch_assoc($apptsRes)) {   		
	 			$array[]=$row;	 			
	 		}
	    	
	    }else{
	    	echo "fail";
	    }
// echo json_encode($array);

$file_name="appts.csv";
    download_csv_results($array, $file_name); 

	mysqli_close($con);
?>








