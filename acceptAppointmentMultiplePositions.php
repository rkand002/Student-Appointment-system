<?php
	require 'Send_Mail.php';
	include ('connect.php');
	session_start();
	$recruitmentId = $_SESSION["recruitmentId"];
	$uploaddir = 'Assets/Uploads/';
	$uploadfile = $uploaddir . basename($_FILES['filetoUpload']['name']);
	$relatedRecIdsFromQuery = array();
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
	OR die ('Could not connect to MySQL: '.mysql_error());

	if ($conn->connect_error) {
		die("Connection failed: ".$conn->connect_error);
	}

	$toList= array();
	$ccList = array();
	
	$stmt1 = "SELECT firstname,lastname,email FROM Staff WHERE isadmin = 1 or isadmin = 2";
	$result1 = $conn->query($stmt1);
	    // output data of each row
	   while($row = $result1->fetch_assoc()) {
	   	$admin = array();
	      $admin["name"] =  $row["firstname"]." ".$row["lastname"];
	      $admin["email"] = $row["email"];            
	      array_push($toList,$admin);
	   } 
	   
	
	//this changed query is to get the details for these multiple postions
/*	$stmt = $conn->prepare('SELECT R.student_id AS stu_id,R.id,Stu.firstname as stu_fn,
 	Stu.lastname AS stu_ln,Stu.email AS stu_email,R.currentpost,R.semester,R.year,R.salarypaid,Sta.faculty_id as sta_id, Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.email AS sta_email,
 	R.isreappointed FROM Recruitments R JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id,(select student_id,semester,year from Recruitments where id=?) A where R.student_id=A.student_id and R.semester=A.semester and R.year=A.year');
*/
	   
	//changed - multiple positions	
	$stmt = $conn->prepare('SELECT R.student_id AS stu_id,R.id, Stu.firstname as stu_fn, 
	Stu.lastname AS stu_ln,Stu.email AS stu_email,RP.currentpost,R.semester,R.year,RP.startdate,RP.enddate,RP.salarypaid,RP.fundingtype,Sta.faculty_id as sta_id,
	 Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.email AS sta_email,
	  R.isreappointed FROM Recruitments1 R join RecruitPositions RP on R.id = RP.recruitment_id JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id where R.id= ?');
	
	
	
	$stmt->bind_param('s', $recruitmentId);
	$stmt->execute();
	$result = $stmt->get_result();

    while($row = $result->fetch_assoc()) {  
    	    	
    	$stuObj = array();
	    $stuObj["name"] =  $row["stu_fn"]." ".$row["stu_ln"];
	    $stuObj["email"] = $row["stu_email"];     
	    array_push($ccList,$stuObj);
		    	   	
	    $emailSubject = "Student ". $stuObj["name"]."(".$row['stu_id'].") has accepted the Appointment."; 	    	
	    $emailBody = "Hello Admin, <br /> Student ". $stuObj["name"] ."(".$row['stu_id'].") has accepted the Offer. <br /> <br />";  
	    $emailBody .= "Here is the signed Offer Letter, Please go ahead with the remaining formalities. <br /><br /> Thank you.";			    	
    	array_push($relatedRecIdsFromQuery,intval($row["id"]));	
		$Query = "UPDATE Recruitments1 SET offerstatus='4' WHERE id=".$row["id"];		
		/*	$insertQueryRes = mysql_query($Query); //SQL query
	       echo $insertQueryRes;*/
    	if(mysqli_query($conn, $Query)){

    	}else{
			return false;
		}	
    }
	
    /*if(count($relatedRecIdsFromQuery) > 1){
    	if($relatedRecIdsFromQuery[0] > $relatedRecIdsFromQuery[1]){
	 		$uploadfile_newname = $uploaddir."offersigned_".$relatedRecIdsFromQuery[1]."_".$relatedRecIdsFromQuery[0].".pdf";
		 }else{
		 	$uploadfile_newname = $uploaddir."offersigned_".$relatedRecIdsFromQuery[0]."_".$relatedRecIdsFromQuery[1].".pdf"; 
		 }    
    }else{
    	$uploadfile_newname = $uploaddir."offersigned_".$recruitmentId.".pdf";   	
    }*/
    
    $uploadfile_newname = $uploaddir."offersigned_".$recruitmentId.".pdf";   	

	 //echo $uploadfile_newname;
	$uploadSucess = move_uploaded_file($_FILES['filetoUpload']['tmp_name'], $uploadfile_newname);
	//$path =  $uploadfile;
	$path =  $uploadfile_newname;
	if(!$uploadSucess){
		echo "error";
		return;
	}else{
		 if(Send_Mail($toList,$ccList,$emailSubject,$emailBody,$path)){
		    echo "success";
		 }else{
		    echo "error";
		 } 
	}
	
    mysqli_close($con);
   	unset($_SESSION["recruitmentId"]);
//echo json_encode($array);
?>