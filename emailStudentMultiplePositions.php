<?php 
 session_start();
require 'Send_Mail.php';
include ('connect.php');
  	if($_SESSION['user']){
    }
    else{ 
       header("location:home.php");
    }
$user_id = $_SESSION['user_id'];
$relatedRecIdsFromQuery= array();
$isAdmin = False;
if($_SESSION['isAdmin'] == "True"){
	 $isAdmin = True;
}
$recruitmentId =  @trim(stripslashes($_POST['recruitmentID']));
$uploaddir = 'Assets/Uploads/';
$uploadfile = $uploaddir . basename($_FILES['filetoUpload']['name']);
$stuObj = array();
$toList = array();
//$uploadfile_newname= $uploaddir."offerunsigned_".$recruitmentId.".pdf";
//echo $new_file_name;
$emailSubject="";
$emailBody="";
  
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
	OR die ('Could not connect to MySQL: '.mysql_error());
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	//echo $recruitmentId;
	//$path =  $uploadfile;
	
	//echo "File is valid, and was successfully uploaded.".$path;
	

	//this changed query is to get the details for these multiple postions
/*	$recSelQuery = $conn->prepare('SELECT R.student_id AS stu_id,R.id, Stu.firstname as stu_fn,
	 	Stu.lastname AS stu_ln,Stu.email AS stu_email,R.currentpost,R.semester,R.year,R.salarypaid,R.offerstatus,R.hours,Sta.faculty_id as sta_id,
	 	 Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.email AS sta_email,
	 	R.isreappointed,R.project_id As proj_id FROM Recruitments R JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id ,
		(select student_id,semester,year from Recruitments where id=?) A where R.student_id=A.student_id and R.semester=A.semester and R.year=A.year');
	*/
//changed - multiple positions	
	$recSelQuery = "SELECT R.student_id AS stu_id,R.id, Stu.firstname as stu_fn,Stu.lastname AS stu_ln,Stu.email AS stu_email,RP.currentpost,R.semester,R.year,RP.salarypaid,R.offerstatus,RP.hours,Sta.faculty_id as sta_id,Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.email AS sta_email,R.isreappointed,RP.project_id As proj_id FROM Recruitments1 R join RecruitPositions RP on R.id = RP.recruitment_id JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id where R.id=".$recruitmentId ;	
	//echo $recSelQuery;
	
	$recSelResult = mysqli_query($conn, $recSelQuery);
	while($row = $recSelResult->fetch_assoc()) {  	  	
	  	$stuObj = array();
	  	$toList = array();
		$ccList = array();
    	$stuObj["name"] =  $row["stu_fn"]."-".$row["stu_ln"];
      	$stuObj["email"] = $row["stu_email"];     
    	array_push($toList,$stuObj);    	
		$emailSubject ="offer letter for ".$row["semester"]."|".$row["year"];
		
		$emailBody = "Hi ".$row["stu_fn"]." ".$row["stu_ln"].", <br />Professor ".$row["sta_fn"]." ".$row["sta_ln"]." has released an offer. Please find the attached offer, download to read and sign. <br /><br />";
		$emailBody.= "click on the below link to upload and accept the offer. <br /><br />";		
		// this is the link that student clicks to upload his offer letter and clicks on accept
		$emailBody .= "http://qav2.cs.odu.edu/".SERVERHOST."_StudentAppointmentSystem/landingPageStudent.php?recid=".$row["id"];			
		//$emailBody .= "http://qav2.cs.odu.edu/Prod_StudentAppointmentSystem/landingPageStudent.php?recid=".$recruitmentId;			
		
		$emailBody.="<br /> <br />Thank you";
		array_push($relatedRecIdsFromQuery,intval($row["id"]));		
		// a recent change to incorporate the change that admin must be able to edit the credits and tutuion fee stuff	
		//$updatOfferStatQuery= "UPDATE Recruitments SET offerstatus='1',tutionwaive='".$admUpdatedTution."',salarypaid='".$admUpdatedSalary ."',credithours='".$admUpdatedCredits."' ,fundingtype='".$admUpdatedFT ."' WHERE id=".$recruitmentId;				
		$updatOfferStatQuery= "UPDATE Recruitments1 SET offerstatus='1' WHERE id=".$row["id"];									
		//echo $updatOfferStatQuery;
		if(mysqli_query($conn, $updatOfferStatQuery)){
			
		}else{
			return false;
		}		
	    
	 }
	 
/*	 if(count($relatedRecIdsFromQuery) > 1){ 
		 if($relatedRecIdsFromQuery[0] > $relatedRecIdsFromQuery[1]){
		 	$uploadfile_newname = $uploaddir."offerunsigned_".$relatedRecIdsFromQuery[1]."_".$relatedRecIdsFromQuery[0].".pdf";
		 }else{
		 	$uploadfile_newname = $uploaddir."offerunsigned_".$relatedRecIdsFromQuery[0]."_".$relatedRecIdsFromQuery[1].".pdf"; 
		 }
	 }else{
	 	   $uploadfile_newname = $uploaddir."offerunsigned_".$recruitmentId.".pdf";   	
	 	
	 }*/
	 
	 $uploadfile_newname = $uploaddir."offerunsigned_".$recruitmentId.".pdf";   	
	// echo $uploadfile_newname;
	 $result = move_uploaded_file($_FILES['filetoUpload']['tmp_name'], $uploadfile_newname); // for this to work the destination folder must have edit permissions 
	$path = $uploadfile_newname;
	//echo $uploadfile;
	if ($result) {  
		if(Send_Mail($toList,$ccList,$emailSubject,$emailBody,$path)){		
			echo "success";		
	    }else{
	    	echo "error";
	    }
	} else {
	    echo "Some problem while saving the file. Check permissions for the folder\n";
	    return false;
	}
?>