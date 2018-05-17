<?php

	define('FPDF_FONTPATH','font/');
	require('fpdf.php');
	include ('connect.php');
	include ('GlobalConstants.php');
	session_start();
	$user_id = $_SESSION['user_id'];
	if($_SESSION['user']){
    }
    else{ 
       header("location:login.php");
    }
    
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
	OR die ('Could not connect to MySQL: '.mysql_error());

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
    
    $recID =  $_REQUEST['recid'];
    //changed - multiple positions	
    // $stmt = $conn->prepare('SELECT R.student_id AS stu_id, Stu.firstname as stu_fn, Stu.lastname AS stu_ln,Stu.email AS stu_email,R.currentpost,R.tutionwaive,R.credithours,R.semester,R.year,R.startdate,R.enddate,R.salarypaid,R.fundingtype,Sta.faculty_id as sta_id, Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.email AS sta_email, R.isreappointed FROM Recruitments R JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id,(select student_id,semester,year from Recruitments where id=?) A where R.student_id=A.student_id and R.semester=A.semester and R.year=A.year');
    	$stmt = $conn->prepare('SELECT R.student_id AS stu_id, Stu.firstname as stu_fn, Stu.lastname AS stu_ln,Stu.email AS stu_email,RP.currentpost,R.tutionwaive,
    	R.credithours,R.semester,R.year,RP.startdate,RP.enddate,RP.salarypaid,RP.fundingtype,
    	Sta.faculty_id as sta_id, Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.email AS sta_email, R.isreappointed 
    	FROM Recruitments1 R JOIN RecruitPositions RP on R.id = RP.recruitment_id JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id where R.id= ?');

	$stmt->bind_param('s', $recID);
	$stmt->execute();
	$result = $stmt->get_result();
	$studentDet="";
    $professorName= "";
    $offerDet="";
    $semester="";
    $accYear="";
    $startDate="";
    $endDate = "";
    $curDate = date("m/d/Y");
   	$tutionWaive ="";
   	$creditHours = "";
   	$currentPost="";
    $salary="";
    $fundingtype="";

    while($row = $result->fetch_assoc()) {  
     	$currentPost=$row["currentpost"];
       	$studentDet = $row["stu_fn"]." ".$row["stu_ln"]." (".$row["stu_id"].")";
       	$professorName= $row["sta_fn"]." ".$row["sta_ln"];
    	$offerDet= $row["currentpost"]." for the Semester ".$row["semester"]." Academic Year ".$row["year"] ;
    	$semester = $row["semester"];
    	$accYear = $row["year"];
    	if($startDate!="")
			$startDate = min(date('m-d-Y',strtotime($row["startdate"])),date('m-d-Y',strtotime($startDate)));
		else
			$startDate = date('m-d-Y',strtotime($row["startdate"]));
		$endDate = date('m-d-Y',strtotime(max($row["enddate"],$endDate)));
		$tutionWaive = $row["tutionwaive"];
		$creditHours = $row["credithours"];
		$salary= $row["salarypaid"];
		if($row["fundingtype"] == "1"){
			$fundingtype = "ODU";
		}else{
			$fundingtype = "ODURF";
		}
		
      }
     
    $line2 = "Dear ".$studentDet;
    
     $para1 = "I am pleased to offer you an assistantship for the semester ".$semester ." ".$accYear." i.e from ".$startDate." to ".$endDate;
     $para1.= " Please see below for information regarding your assistantship and financial award.";
     $para1.=" In addition, you will receive a ".$tutionWaive ."% tuition waiver for up to ". $creditHours."Credits of graduate courses. ";
     
     
     // $para2='All registration must be completed prior to your hire paperwork being forwarded to the Payroll office for completion. Please contact Ariel Sturtevant at asturtev@odu.edu if you are unable to complete registration within 24 hours of signing this letter. Please note that, according to University policy, assistantship and/or fellowships (including tuition support) will be immediately terminated if you do not maintain a 3.00 grade point average.';
      $para3='Refer to the Graduate Assistantship Requirements document (http://www.cs.odu.edu/files/graduate-assistant-';
      $para31='requirements.pdf) for information on course registration and teaching assistant training requirements.';
      $para4='Information on student health insurance is available at ';
      $para41='https://www.odu.edu/life/health-safety/health/monarch-wellness/physical-wellness/student-health-center/billing/insurance.';
       $para5='According to the provisions of the Immigration Reform and Control Act of 1986, it is the responsibility of Old Dominion University to examine original documents, provided by new employees (including students who are to receive assistantships), which demonstrate the individual\'s identity and employment eligibility.  Documents must be presented in person.  Domestic students can present documents to the department.  All international students must visit Visa and Immigration Service Advising, Dragas Hall Room 2006 (757) 683-4756 or intlstu@odu.edu to discuss visa arrangements and complete the Employment Eligibility Verification Form (commonly referred to as the Form 1-9)';

     $para6= 'Old Dominion University (ODU) adheres to the “Resolution regarding Graduate Scholars, Fellows, Trainees and Assistants';
      $para6.=' of the Council of Graduate Schools of the United States. Please read the resolution at http://www.cgsnet.org/ckfinder/portals/0/pdf_CGS_Resolution.pdf to make';
     $para6.=' yourself aware of the responsibilities.This letter supersedes and replaces any earlier letter(s) for this period of appointment.';
     $para6.=' registration and teaching assistant training requirements.';
     
     
     $para2 = "All registration must be completed prior to your hire paperwork being forwarded to the Payroll office for completion. Please contact Ariel Sturtevant at ";
     $para2 .="asturtev@odu.edu if you are unable to complete registration within 24 hours of signing this letter. ";
     $para2 .="Please note that, according to University policy,assistantship and/or fellowships (including tuition support) will be";
      $para2 .=" immediately terminated if you do not maintain a 3.00 grade point average.";
     
     
     $para7 = "Congratulations on receiving this appointment. I look forward to receiving your formal acceptance of the assistantship(s) and/or fellowship. ";
     

    //$appointmentDet = "professor ".$professorName." has appointed a student ".$studentDet." for the post ";
    //$appointmentDet1 =$offerDet;

	class PDF extends FPDF
	{
		// Page header
		function Header()
		{
		    // Logo
		    $this->Image('Assets/Images/odulogo.JPG',75,6,40);
		    $this->Ln(20);
		}
		
		// Page footer
		function Footer()
		{
		    // Position at 1.5 cm from bottom
		    $this->SetY(-15);
		    // Arial italic 8
		    $this->SetFont('Arial','I',8);
		    // Page number
		    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		}
		
		
		function BasicTable($header)
		{
		    // Header
		   // foreach($header as $col)
		    for($i=0;$i<count($header) ;$i++)
		    {
		    	if($i== 5){
		    		$this->Cell(48,7,$header[$i],1);
		    	}else{
		    		  $this->Cell(25,7,$header[$i],1);
		    	}
		    } 
		        
		    $this->Ln();
		}

		function row($data)
		{
			 for($i=0;$i<count($data) ;$i++)
		    {
		       // foreach($row as $col)
		       if($i== 5){
		       	 $this->Cell(48,6,$data[$i],1);
		       }else{
		       	 $this->Cell(25,6,$data[$i],1);
		       }

		    }
		     $this->Ln();
		}
	}
	
	// Instanciation of inherited class
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Times','',12);
	
	/*$pdf->Cell(0,10,$appointmentDet,0,1);
	$pdf->Cell(0,10,$appointmentDet1,0,1);*/

	/*for($i=1;$i<=40;$i++)
	    $pdf->Cell(0,10,Offer_Text.$i,0,1);*/
	

	$pdf->Cell(0,10,$curDate,0,1);
	$pdf->Cell(0,10,$line2,0,1);

	$pdf->MultiCell(0,5,$para1);
	$pdf->Ln(4);
	$header = array('Postion', 'Award', 'ODU/RF', 'Report To',"Department","Date");
	$pdf->BasicTable($header);
	 $result->data_seek(0);
	 while($row = $result->fetch_assoc()) {	 
	 	$currentPost=$row["currentpost"];
       	$studentDet = $row["stu_fn"]." ".$row["stu_ln"]." (".$row["stu_id"].")";
       	$professorName= $row["sta_fn"]." ".$row["sta_ln"];
    	$offerDet= $row["currentpost"]." for the Semester ".$row["semester"]." Academic Year ".$row["year"] ;
    	$semester = $row["semester"];
    	$accYear = $row["year"];
		$startDate = date('m-d-Y',strtotime($row["startdate"]));
		$endDate = date('m-d-Y',strtotime($row["enddate"]));

		$tutionWaive = $row["tutionwaive"];
		$creditHours = $row["credithours"];
		$salary= $row["salarypaid"];
		if($row["fundingtype"] == "1"){
			$fundingtype = "ODU";
		}else{
			$fundingtype = "ODURF";
		}
		$data = array($currentPost ,$salary,$fundingtype ,$professorName,"CS",$startDate." to ".$endDate);
		$pdf->row($data);
     }
	
	$pdf->Ln(4);
	$pdf->MultiCell(0,5,$para2);
	$pdf->Ln(2);
	$pdf->MultiCell(0,5,$para3);
	// $pdf->Ln(1);
	$pdf->MultiCell(0,5,$para31);
	$pdf->Ln(2);
	$pdf->MultiCell(0,5,$para4);
	// $pdf->Ln(1);
	$pdf->MultiCell(0,5,$para41);
	$pdf->Ln(2);
	$pdf->MultiCell(0,5,$para5);
	$pdf->Ln(2);
	$pdf->MultiCell(0,5,$para6);
	$pdf->Ln(2);
	$pdf->MultiCell(0,5,$para7);
	
	/*$pdf->Cell(0,10,$para2,0,1);

	$pdf->Cell(0,10,$para3,0,1);

	$pdf->Cell(0,10,$para4,0,1);*/

	   $lastlines = "Sincerely yours,
		Michele Weigle, PhD 
		Graduate Program Director
		Department of Computer Science
		UIN 
		Student’s Signature
		Date";
    $pdf->Ln();
	$pdf->Cell(0,10,"Sincerely yours,",0,1);
	$pdf->Ln();

	$pdf->Cell(0,10,"Michele Weigle, PhD,",0,1);
	$pdf->Cell(0,10,"Graduate Program Director",0,1);
	$pdf->Cell(0,10,"Department of Computer Science",0,1);
	$pdf->Ln(5);


	$pdf->Cell(0,10,"I understand and agree to the terms and requirements of this financial support award.",0,1);
	$pdf->Ln();
	$pdf->Cell(0,10,"UIN                                         Student’s Signature                                      Date",0,1);
	
	
	$pdf->Output();
?>

































