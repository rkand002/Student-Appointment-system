<html>
    <head>
    	  <meta name="description" content="Students Appointment System CS ODU">
		  <meta name="keywords" content="Appointment System,CS,ODU,Norfolk,Students,Tracking,Appointment,Maheedhar,Handson">
		  <meta name="author" content="Maheedhar,Handson">
        <title>Student Recruitment TS</title>
        <!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">		
		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="Assets/Script/sitescript.js"></script>
		<link rel="stylesheet" href="./Assets/CSS/site.css">
		<link rel="stylesheet" href="./Assets/CSS/loginStyle.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
		<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css">				
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
   		<script src="Assets/Script/landingPageScript.js"></script>
   		 <script src="Assets/Script/downloadToExcel.js"></script>
   		
    </head>
   <?php
	 session_start(); //starts the session
	 $isLoggedIn = false;
	 $adminType = 0;
	   if($_SESSION['user']){ // checks if the user is logged in  
		   $user = $_SESSION['user']; //assigns user value
		   $isLoggedIn = true;
		   $user_id = $_SESSION['user_id'];
		   $loginUser_FullName = $_SESSION['loginUser_FullName'];
		   $isAdmin = False;
			$adminType = 0;
		   if($_SESSION['isAdmin'] == "True"){
		   		$isAdmin = True;
		   		$adminType = intval($_SESSION['adminType']);
		   		$currAppType= $_REQUEST['splAdmin'];
			
		   }
	   }else{
	   	$isLoggedIn = false;
	   }

   ?>
    <body class="body" style="background-color:#4b8e87">    
    	<!--  this modal is for project details publishing -->
    	<div class="modal fade" id="projectDetPopup" role="dialog">
		    <div class="modal-dialog modal-sm">
		      <div class="modal-content">
			        <div class="modal-header">
			          <h4 class="modal-title">Create Project</h4>
			        </div>
			        <div class="modal-body">
			        	<form class="projCreatForm">
						    <div class="form-group">
						      <label for="projNoCNP"">Project No:</label>
						      <input type="text" class="form-control" name="name" id="projNoCNP" placeholder="Enter Project Code">
						    </div>
						    <div class="form-group">
						      <label for="projTypeCNP"">Project Type:</label>
						    	<select class="form-control projType" name="issgraproj">
							    	<option value="0">GRA</option>
							    	<option value="1">SGRA</option>
								</select>						 
						    </div>
						    
						   <div class="form-group">
						     <label for="projAgencyCNP"">Agency:</label>
						      <input type="text" class="form-control" name="agency" id="projAgencyCNP" placeholder="Enter Agency">
						    </div>
						    <div class="form-group">
						     <label for="projDescCNP"">Description:</label>
						      <textarea  class="form-control" id="projDescCNP" name="projectdescription" > </textarea>
						    </div>
						    <div class="form-group">
						     <label for="projSDateCNP"">Start Date:</label>
						      <input  type="date" class="form-control" id="projSDateCNP" name="projectstartdate" /> 
						    </div>
						    <div class="form-group">
						     <label for="projEDateCNP"">End Date:</label>
						      <input type="date" class="form-control" id="projEDateCNP" name="projectenddate" /> 
						    </div>
						    <div class="form-group">
						      <label for="faculty_name"">Faculty Name:</label>
						    	<select class="form-control faculty_name" name="faculty_name">
							    
								</select>						 
						    </div>

						 </form>
			          
			        </div>		
			          <div class="modal-footer">
				        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				        <button type="button" class="btn btn-primary newProjCreateButt">Create</button>
				      </div>	       
		      </div>
		    </div>
	  	</div>
    
      <!--this Modal popup is for showing the success Msgs -->
		<div class="modal fade" id="successModal" role="dialog">
		    <div class="modal-dialog modal-sm">
		      <div class="modal-content">
			        <div class="modal-header">
			          <!--<button type="button" class="close" data-dismiss="modal">&times;</button>
			          -->
			          <h4 class="modal-title">Information!</h4>
			        </div>
			        <div class="modal-body">
			          <p>This is a Success modal.</p>
			        </div>   
			         <div class="modal-footer">
			        	<button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>
			        </div>     			       
		      </div>
		    </div>
	  	</div>
	  	
	  	
	  	
	
		<!--this Modal popup is for showing the error Msg-->
		<div class="modal fade" id="errorModal" role="dialog">
		    <div class="modal-dialog modal-sm">
		      <div class="modal-content">
			        <div class="modal-header">
			          <button type="button" class="close" data-dismiss="modal">&times;</button>
			          <h4 class="modal-title">Error!!</h4>
			        </div>
			        <div class="modal-body">
			          <p>This is a error Modal.</p>
			        </div>
			          <div class="modal-footer">
			        	<button type="button" class="btn btn-error" data-dismiss="modal">Ok</button>
			        </div>	  	       
		      </div>
		    </div>
	  	</div>		
	  	
	  	<!--this Modal popup is for conformation-->
		<div class="modal fade" id="confirmationModal" role="dialog">
		    <div class="modal-dialog modal-lg">
		      <div class="modal-content">
			        <div class="modal-header">
			          <button type="button" class="close" data-dismiss="modal">&times;</button>
			          <h4 class="modal-title">Information!!</h4>
			        </div>
			        <div class="modal-body">
			          <p>This is a Confirmation Modal.</p>
			        </div>		
			         <div class="modal-footer">
			        	<button type="button" class="btn btn-warning notConfirmed" data-dismiss="modal">No</button>
			        	<button type="button" class="btn btn-success confirmed" data-dismiss="modal">Yes</button>
			        </div>	       
		      </div>
		    </div>
	  	</div>	
	  	

	  	<div class="modal fade" id="AddStudentModal" role="dialog">
		    <div class="modal-dialog">
		    
		      <!-- Modal content-->
			      <div class="modal-content">
			        <div class="modal-header">
			          <button type="button" class="close" data-dismiss="modal">&times;</button>
			          <h4 class="modal-title">Add Student Details</h4>
			        </div>
			        <div class="modal-body">
			          <form data-toggle="validator" role="form" id="Add_student_form">
			          <div class="form-group">
			            <label for="usr">UIN:</label>
			            <input name="uin" type="number" name="uin" class="form-control" id="uin" placeholder="01041411" maxlength="8">
			          </div>
			          <div class="form-group">
			            <label>firstname:</label>
			            <input name="first_name" type="text" class="form-control" id="addstudent_firstname" required>
			          </div>
			          <div class="form-group">
			            <label>lasttname:</label>
			            <input name="last_name" type="text" class="form-control" id="addstudent_lastname" required>
			          </div>
			          <div class="form-group">
			            <label>email</label>
			            <input name="email" type="email" class="form-control" id="addstudent_email" placeholder="ex-example@odu.edu" data-error="Bruh, that email address is invalid" required>
			          </div>
			          <div class="form-group">
			            <label>gradlevel</label>
			            <input  name="gradlevel" type="text" class="form-control" id="addstudent_gradlevel" placeholder="ex-grad/php" required>
			          </div>
			          <div class="form-group">
			            <label>i9expiry</label>
			            <input name="i9expiry" type="text" class="form-control" id="addstudent_i9expiry">
			          </div>
			        </form>
			          
			        </div>
			        <div class="modal-footer">
			        <button type="button" class="btn btn-default"  onclick="AddStudenttoDB()" >Add Student</button>
			        </div>
			      </div>
		      
		    </div>
 		 </div>
	  	
	  				
	<nav class="navbar navbar-default navbar-inverse" role="navigation" style="background-color:#01414e" >
		<div class="container-fluid">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header" style="margin-left:6%;">
		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		       <a class="navbar-brand" href="home.php">Student Appointments Tracker</a>
		    </div>
		
		    <!-- Collect the nav links, forms, and other content for toggling -->
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			  <?php 			  
				  if($isLoggedIn){
			  		 echo '<ul class="nav navbar-nav"></ul>';
			       }		       
			  ?>		          
		      <ul class="nav navbar-nav navbar-right">
		      <?php	            
	            	//echo '<script>alert("result:'.$loginUser_FullName .'");</script>';	
	           	if(!$isLoggedIn){ // checks if the user is logged in  	     				  	  		       			     
	           		echo '<li class="dropdown loginDD">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>Login</b> <span class="caret"></span></a>
						<ul id="login-dp" class="dropdown-menu">
							<li>
								 <div class="row">
										<div class="col-md-12">
											 	<div class="form">		        		
													   <form action="#" method="POST" class="defalutForms register-form">
													      <input type="text" name="username" placeholder="username" required="required" />
													      <input type="password" name="password" placeholder="password" required="required"/>
													      <input type="text" name="firstname" placeholder="First Name" required="required" /> <br/>
												          <input type="text" name="lastname" placeholder="Last Name" required="required" /> <br/>
													      <input type="email" name="email" required="required" placeholder="email address"/>
													       <div class="form-group">
														        <label for="isAdminCB" class="control-label">Register as Admin</label>
														        <div>
														            <input type="checkbox" id="isAdminCB" name="isAdminCB" >
														        </div>
														    </div>
															<button id="submitButtRegister">create</button>
													      <p class="message">Already registered? <a href="#">Sign In</a></p>
													    </form>
													    
													    <form  action="#" method="POST" class="defalutForms login-form">
													      <input type="text" name="username" required="required" placeholder="username" />
													      <input type="password" name="password" required="required" placeholder="password"/>
													      <button id="submitButtLogin">Login</button>
													      <!-- <input type="button" class="btn btn-success" id="submitButtLogin" value="Login" />
													    <p class="message">Not registered? <a href="#">Register here</a></p> -->
													    </form>
												  </div>
										</div>
										
								 </div>
							</li>
						</ul>
			        </li>';
		
				 }
		      	 else{
				   	$compName = explode(",", $loginUser_FullName);
	            	$userTitle = "";
	            	if($isAdmin){
	            		$userTitle = "Admin";
            			//this one is for the GPD and Chair Role 
	            		if($adminType == 3){
	            			$userTitle = "Professor";
	            		}
	            	}else{
	            		$userTitle = "Professor";	            		
	            	}
	            	
	           		echo '<li><a href="#"> Hello '.$userTitle.' <b class="loggedinusername">'.$compName[0]." ".$compName[1].'!</b><span class="loggedInUID hidden">'.$user_id."-".$isAdmin."-".$adminType.'<span></a></li>';

	           		if($isAdmin){	
	           			echo '<li class="dropdown moreDD">
	           					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>More</b> <span class="caret"></span></a>
	           					<ul id="more-dp" class="dropdown-menu" style="width:50px;">
	           							<li><a href="#" data-toggle="modal" data-target="#AddStudentModal">Add Students</a></li>
	           					</ul>
	           				  </li>';



						 echo '<li class="dropdown settingsDD">
				          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>Settings</b> <span class="caret"></span></a>
							<ul id="settings-dp" class="dropdown-menu" style="width:300px;">
								<li>
									 <div class="row">
											<div class="col-md-12">        																 
														    <form  action="#" class="form-horizontal" style="margin-left:7%;"> <br />														    
														      <div class="form-group">
															      <label class="control-label col-sm-4" for="currentTution">Tution Per Credit:</label>
															      <div class="col-sm-5">
															        <input type="number" class="form-control" id="currentTution" placeholder="Enter Tution">
															      </div>
															      <div class="col-sm-2" style="margin-left:-9%;">
															      	<input type="button" class="btn btn-default updateTutionButt" value="Update" style="display:none;" />
															      </div>
															   </div>
																<div class="form-group">
															     
															       <div class="col-sm-5">
															       		<select class="form-control semseladmsett" style="width:107%;"><option value="Fall">Fall</option><option value="Spring">Spring</option><option value="Summer">Summer</option></select>
															       </div>
															       <div class="col-sm-6">
															       		<select class="form-control yearseladmsett"></select>
															       </div>
															    </div>
															    
																<div class="form-group">
															      <label class="control-label col-sm-4" for="currentTution">ODU/ODURF</label>
															      <div class="col-sm-5">
															        <select class="form-control oduRRFDatesAdmSett"><option value="1">ODU</option><option value="2">ODURF</option></select>
															      </div>															     
															   	</div>												       

															    <div class="admdatesetup">
																    <div class="form-group">
																      <label class="control-label  col-sm-4" for="currentTution">Start Date:</label>
																      <div class="col-sm-7">
																        <input type="date" class="form-control" id="startDateAdmSett">
																      </div>
																    </div>
																    <div class="form-group">
																      <label class="control-label col-sm-4" for="currentTution">End Date:</label>
																      <div class="col-sm-7">
																        <input type="date" class="form-control" id="endDateAdmSett">
																      </div>
																    </div>
																     <div class="form-group">
																	      <div class="col-sm-3" style="margin-left:2%;">
																	      	<input type="button" class="btn btn-default updateSemDatesButt" value="Update Dates" style="display:none" />
																	      </div													        
															   		 </div>
														    </form>
											</div>
											
									 </div>
								</li>
							</ul>
				        </li>';
	           		}
	           		echo '<li><a href="logout.php"><b>logout</b></a></li>';
				  }				            
	           	?>
		         
		         
		      </ul>
		    </div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
	</nav>		
		
	   <?php
	    if(!$isLoggedIn){
		   echo '<div class="container" style="margin-top:15%;">
				    <div class="jumbotron">
					    <h1>Student Appointments Tracker</h1>
					    <p>This is an Application that can be used by the faculty to track their Student Job Appointments.</p>
					 </div>
		    	</div>';
		  }
	    ?>
	    
	    <div id="cover" style="display:none;"></div>
	    <div class="container" style="width:100%;">	  
	    <div class="col-lg-12 mainTableDiv" style="margin-top:0%;display:<?php if($isLoggedIn){echo "block;";}else{echo "none;";} ?>">
      		<div class="well well-sm hidden"><b style="margin-left: 40%;">Appointed Students List</b></div>
      		<div class="row studentTableTop">
      		<?php	            
			           if($isAdmin){
			           		echo '<div class="adminNotes col-lg-6" style="float:left; width:50%;" ><textarea class="adminNotes_textarea well"  id="NotesFromAdmin"  rows="2" style="width:100%;"><span class="glyphicon glyphicon-edit"></span></textarea><div id="EditButtonforNotes"  style="float:left; margin-left:1%"><a i data-toggle="modal" href="#myModal" class="btn btn-primary">Submit Notes</a></div></div>';
			           }
			           else{
			           		echo '<div class="adminNotes col-lg-6"><textarea class="adminNotes_textarea well" readonly id="NotesFromAdmin"  rows="2" ><span class="glyphicon  glyphicon-edit"></span></textarea></div>';
			           }
			 ?>
      		

			<div id="myModal" class="modal fade">
			    <div class="modal-dialog">
			        <div class="modal-content">
			            <div class="modal-header">
			                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
			                <h4>Notes updated</h4>
			            </div>
			            
			        </div> 
			    </div>
			</div>  
			      		
      			<?php	
      			      echo "<div class='actionbutts col-lg-6'>";    
				           if(!$isAdmin){
				            		echo '<button type="button" class="addNewAppointmet btn btn-success btn-circle btn-xl pull-right" style="margin-bottom: 1%; margin-left: 1%; width:60px; height:60px; border-radius:30px"><i class="glyphicon glyphicon-plus" style="font-size:30px;"></i></button><button type="button" class="btn downloadCsvButt btn-success btn-circle btn-xl pull-right" title="Download Existing Appointment" style="margin-bottom: 1%; width:60px; height:60px; border-radius:30px;"><i class="glyphicon glyphicon-download-alt" style="font-size:30px;"></i></button>';  
				           }else{
					           if($adminType == 3){
					           		if($currAppType == "1"){
					           			$isAdmin = true;
					           			echo '<button type="button" class="addNewAppointmet btn btn-success btn-circle btn-xl pull-right" style="margin-bottom: 1%; margin-left: 1%; width:60px; height:60px; border-radius:30px; display:none;"><i class="glyphicon glyphicon-plus" style="font-size:30px;"></i></button><button type="button" class="btn downloadCsvButt btn-success btn-circle btn-xl pull-right" title="Download Existing Appointment" style="margin-bottom: 1%; width:60px; height:60px; border-radius:30px;"><i class="glyphicon glyphicon-download-alt" style="font-size:30px;"></i></button><select title="Appointments currently showing" class="form-control splAdminSelBox pull-right" style="width: 20%;float: right; margin-right: 2%;margin-top: 2%;"><option value="1" selected>All Appointments</option><option value="2">My Appointments</option></select>';  	           				           							           		
					           		}else{
					           			$isAdmin = false;
					           			echo '<button type="button" class="addNewAppointmet btn btn-success btn-circle btn-xl pull-right" style="margin-bottom: 1%; margin-left: 1%; width:60px; height:60px; border-radius:30px"><i class="glyphicon glyphicon-plus" style="font-size:30px;"></i></button><button type="button" class="btn downloadCsvButt btn-success btn-circle btn-xl pull-right" title="Download Existing Appointment" style="margin-bottom: 1%; width:60px; height:60px; border-radius:30px;"><i class="glyphicon glyphicon-download-alt" style="font-size:30px; float:right"></i></button><select title="Appointments currently showing" class="form-control splAdminSelBox " style="width: 20%;float: right; margin-right: 2%;margin-top: 2%;"><option value="1">All Appointments</option><option value="2" selected>My Appointments</option></select>';  	           				           							           			
					           		}
			            		}else{
					           		echo '<button type="button" class="addNewAppointmet btn btn-success btn-circle btn-xl pull-right" style="margin-bottom: 1%; margin-left: 1%; width:60px; height:60px; border-radius:30px"><i class="glyphicon glyphicon-plus" style="font-size:30px;"></i></button><button type="button" class="btn downloadCsvButt btn-success btn-circle btn-xl pull-right" title="Download Existing Appointment" style="margin-bottom: 1%; width:60px; height:60px; border-radius:30px;"><i class="glyphicon glyphicon-download-alt" style="font-size:30px;"></i></button>';  	           				           			
			            		}
					           
				           }      
					 echo "</div>"; 
			           
	           	?>
      		</div>
      		<!--<button type="button" class="addNewAppointmet btn btn-primary pull-right" style="margin-bottom: 1%">Add Appointment</button> -->
			
			<!--	
				now chaninging the order of the fields from order :				
				UIN,StudentName, Post, StartDate, EndDate,%T,#cr,$Sal,Hrs,Project(Budjet),Funcding,Faculty,OfferStatus,Offer Docs,Action
				to Following order : 
				UIN,StudentName,Post,Faculty,Project(Budjet),Funding, StartDate, EndDate,%T,#cr,$Sal,Hrs,OfferStatus,Offer Docs,Action
			-->
			<table id="RecruitedStuTable" class="table display" cellspacing="0">
	    		<thead>
		    		<tr> 
			    		<th title="Student UIN">UIN</th>
			    		<th>Student Name</th>
			    		<?php	            
			            	if($isAdmin){
			            		echo '<th>Faculty</th>';  
			            	}        					            	
	           			?>
	           			
			    		<th>Post</th>
			    		<th>Funding</th>		    		
	          			<th title="Budget Code/Project #">BCode/P#</th>
	          			<th title='Semester'>Sem</th>         			
			    		<th title="Sem Start Date" style="max-width:106px !important;">StartDate</th>
			    		<th title="Sem End Date" style="max-width:106px !important;">EndDate</th>
			    		<!--<th title="Semester">Sem</th>
			    		<th>Year</th> -->
			    		<th title="Tution Waive%">%T</th>
			    		<th title="No Of Credits">#Cr</th>
			    		<th title="Salary Amount $">$Sal</th>
			    		<th title="No Of Hours">Hrs</th>			    				
	           			<th title="Appointment Status">Appmt Status</th> 
	           			<th title="Appointment Docs">Appmt Docs</th> 
	           			<?php	            
			            	if($isAdmin){
			            		echo '<th>Action</th>';  
			            	}else{
			            		echo '<th>Re-Appoint</th>';			    
			            	}   					            	
	           			?>
		    		</tr>
	    		</thead>
	    		
	    	<!--	
				now chaninging the order of the fields from order :				
				UIN,StudentName, Post, StartDate, EndDate,%T,#cr,$Sal,Hrs,Project(Budjet),Funcding,Faculty,OfferStatus,Offer Docs,Action
				to Following order :
				 
				UIN,StudentName,Post,Faculty,Project(Budjet),Funding, StartDate, EndDate,%T,#cr,$Sal,Hrs,OfferStatus,Offer Docs,Action				
			-->	    		
	    		<tfoot class="searchHeader" style="background-color:#0a5c73">
		           <tr> 
			    		<th purp='UIN'>UIN</th>
			    		<th purp='Name'>Name</th>			    		
			    		<?php	            
			            	if($isAdmin){
			            		echo '<th purp="Staff">Faculty</th>';  
			            	}        					            	
	           			?>
	           			<th purp='Post'>Post</th>
	           			<th purp='Funding'>Funding</th>
	           			<th purp='Project'>Budget Code/Project #</th>
	           			<th purp='Semester'>Sem</th>
			    		<th purp='SemStartDate' style="max-width:106px !important;">StartDate</th>
			    		<th purp='SemEndDate' style="max-width:106px !important;">EndDate</th>
							    	
			    	<!-- 	<th purp='Year'>Year</th> -->
			    		
			    		<th purp='Tution'>%T</th>
			    		<th purp='NoOfCredits'>#Cr</th>
			    		<th purp='Salary'>$Sal</th>
			    		<th purp='Hours'>Hrs</th>
			    				    			    		
	           			<th purp='Offer Status'>Status</th>
	           			<th purp='Docs'>Appmt Docs</th> 
			    		<?php	            
			            	if($isAdmin){
			            		echo '<th purp="Action">Action</th>';  
			            	}else{
			            		echo '<th purp="RApp">Re-Appoint</th>';			    
			            	}   					            	
	           			?>
		    	   </tr>
	        	</tfoot>
	        	<tbody>
	    		<?php 
	    			include ('connect.php');
	    			mysql_connect(DB_HOST, DB_USER,DB_PASSWORD) or die('Could not connect to MySQL: '.mysql_error()); //Connect to server
					mysql_select_db(DB_NAME) or die("Cannot connect to database"); //Connect to database
					$recQueryStr="";
					if($isAdmin){
						/*$recQueryStr = "SELECT R.id as rec_id , R.student_id AS stu_id,R.ismultiple As rec_relatedid, Stu.firstname as stu_fn, Stu.lastname AS stu_ln,Stu.email as stu_email,Stu.i9expiry as stu_i9expiry,";
						$recQueryStr.="R.currentpost,R.semester,R.year,R.tutionwaive,R.credithours,R.salarypaid,R.currenttution,R.offerstatus,R.hours,R.startdate,R.enddate,R.isfinanceverified,R.fundingtype,R.existingAppImportedBy,Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.faculty_id as sta_id,R.isreappointed,R.project_id ";
						$recQueryStr.="FROM Recruitments R  JOIN Student Stu ON R.student_id = Stu.uin ";
						$recQueryStr.="JOIN Staff Sta ON R.faculty_id = Sta.faculty_id order by R.id DESC";	*/			

						//changed - multiple positions
						$recQueryStr = "SELECT R.id as rec_id , R.student_id AS stu_id, RP.id as rec_positionid, Stu.firstname as stu_fn, Stu.lastname AS stu_ln,Stu.email as stu_email,Stu.i9expiry as stu_i9expiry,";
						$recQueryStr.="RP.currentpost,R.semester,R.year,R.tutionwaive,R.credithours,RP.salarypaid,R.currenttution,R.offerstatus,RP.hours,RP.startdate,RP.enddate,R.isfinanceverified,RP.fundingtype,R.existingAppImportedBy,Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.faculty_id as sta_id,R.isreappointed,RP.project_id ";
						$recQueryStr.="FROM Recruitments1 R JOIN RecruitPositions RP ON R.id = RP.recruitment_id JOIN Student Stu ON R.student_id = Stu.uin ";
						$recQueryStr.="JOIN Staff Sta ON R.faculty_id = Sta.faculty_id order by R.id DESC";	
						//echo $recQueryStr;
						
					}else{
						/*$recQueryStr = "SELECT R.id as rec_id , R.student_id AS stu_id,R.ismultiple As rec_relatedid, Stu.firstname as stu_fn, Stu.lastname AS stu_ln,Stu.email as stu_email,Stu.i9expiry as stu_i9expiry,";
						$recQueryStr.="R.currentpost,R.semester,R.year,R.tutionwaive,R.credithours,R.salarypaid,R.currenttution,R.offerstatus,R.hours,R.startdate,R.enddate,R.isfinanceverified,R.fundingtype,R.existingAppImportedBy,Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.faculty_id as sta_id,R.isreappointed,R.project_id ";
						$recQueryStr.="FROM Recruitments R JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id ";
						$recQueryStr.="and R.faculty_id=".$user_id." order by R.id DESC";*/		
						
						//changed - multiple positions
						$recQueryStr = "SELECT R.id as rec_id , R.student_id AS stu_id, RP.id as rec_positionid, Stu.firstname as stu_fn, Stu.lastname AS stu_ln,Stu.email as stu_email,Stu.i9expiry as stu_i9expiry,";
						$recQueryStr.="RP.currentpost,R.semester,R.year,R.tutionwaive,R.credithours,RP.salarypaid,R.currenttution,R.offerstatus,RP.hours,RP.startdate,RP.enddate,R.isfinanceverified,RP.fundingtype,R.existingAppImportedBy,Sta.firstname As sta_fn,Sta.lastname AS sta_ln,Sta.faculty_id as sta_id,R.isreappointed,RP.project_id ";
						$recQueryStr.="FROM Recruitments1 R JOIN RecruitPositions RP ON R.id = RP.recruitment_id JOIN Student Stu ON R.student_id = Stu.uin JOIN Staff Sta ON R.faculty_id = Sta.faculty_id ";
						$recQueryStr.="and R.faculty_id=".$user_id." order by R.id DESC";		
						//echo $recQueryStr;
					}
					//echo $recQueryStr;							
					$query = mysql_query($recQueryStr);								
					//$query = mysql_query("Select * from Recruitments"); //Query the users table
									
					/*now chaninging the order of the fields from order :				
					UIN,StudentName, Post, StartDate, EndDate,%T,#cr,$Sal,Hrs,Project(Budjet),Funcding,Faculty,OfferStatus,Offer Docs,Action
					to Following order :
					 
					UIN,StudentName,Post,Faculty,Project(Budjet),Funding, StartDate, EndDate,%T,#cr,$Sal,Hrs,OfferStatus,Offer Docs,Action		*/		
				 
					$curRecId = -1;
					$isMultiPos="false";
					while($row = mysql_fetch_array($query)) //display all rows from query
					{
					 	$projID = $row['project_id'];
					 	if(intval($row['rec_id']) == $curRecId){
					 		$isMultiPos="true";
					 	}else{
					 		$isMultiPos="false";
					 	}	
					 	$curRecId = intval($row['rec_id']);	 	
						echo"<tr class='text_center_overflow dataRow ".$row['rec_id']."' id='".$row['rec_id']."' recposid='".$row['rec_positionid']."' multipos='".$isMultiPos."' >";						
						echo"<td class='stuUIN' i9expiry=".$row['stu_i9expiry']." >".$row['stu_id']."</td>";					
						echo"<td class='stuName' emailid='".$row['stu_email'] ."'>".$row['stu_ln'].",".$row['stu_fn']."</td>";
						if($isAdmin){
							echo"<td class='staName' staffid='".$row['sta_id']."'>".$row['sta_ln'].",".$row['sta_fn']."</td>";
						}
						//echo"<td class='stuEmail'>".$row['stu_email']."</td>";
						//echo"<td class='stuPost'><span class='oSpan'>".$row['currentpost']."</span></td>";
						
						if(!$isAdmin){	
							echo"<td class='stuPost'><span class='oSpan'>".$row['currentpost']."</span></td>";						
	                        if($row['fundingtype'] == "1"){
	                        	echo"<td class='stuFundingType' title='Funded by ODU'><span class='oSpan' style='color:blue;'>ODU</span></td>";
	                        }else{
	                        	echo"<td class='stuFundingType' title='Funded by ODU Research Foundation'><span class='oSpan' style='color:green;'>ODURF</span></td>";                        	
	                        }
						}else{
							if($row['offerstatus'] == "2"){
								
								if($adminType == 1){
									switch ($row['currentpost']) {
										    case "GRA":
										       echo "<td class='stuPost'><select class='form-control adminUpdatedStuPost'><option value='GRA' selected >GRA</option><option value='SGRA'>SGRA</option><option value='Grader'>Grader</option><option value='GTA'>GTA</option></select></td>";										    	
										        break;
										    case "SGRA":
												echo "<td class='stuPost'><select class='form-control adminUpdatedStuPost'><option value='GRA'>GRA</option><option value='SGRA' selected>SGRA</option><option value='Grader'>Grader</option><option value='GTA'>GTA</option></select></td>";
										    	break;
										    case "Grader":
												echo "<td class='stuPost'><select class='form-control adminUpdatedStuPost'><option value='GRA'>GRA</option><option value='SGRA'>SGRA</option><option value='Grader' selected>Grader</option><option value='GTA'>GTA</option></select></td>";
										    	break;
										    case "GTA":
												echo "<td class='stuPost'><select class='form-control adminUpdatedStuPost'><option value='GRA'>GRA</option><option value='SGRA'>SGRA</option><option value='Grader'>Grader</option><option value='GTA' selected>GTA</option></select></td>";
										    	break; 
									}
									if($row['fundingtype'] == "1"){									
			                        	echo"<td class='stuFundingType'><select class='form-control adminUpdatedFT' disabled><option value='1' selected>ODU</option><option value='2'>ODURF</option></select></td>";
			                        }else if($row['fundingtype'] == "2"){
			                        	echo"<td class='stuFundingType'><select class='form-control adminUpdatedFT' disabled><option value='1'>ODU</option><option value='2' selected>ODURF</option></select></td>";                        	
			                        }
								}else{
									echo"<td class='stuPost'><span class='oSpan'>".$row['currentpost']."</span></td>";	
									if($row['fundingtype'] == "1"){
			                        	echo"<td class='stuFundingType' title='Funded by ODU'><span class='oSpan' style='color:blue;'>ODU</span></td>";
			                        }else{
			                        	echo"<td class='stuFundingType' title='Funded by ODU Research Foundation'><span class='oSpan' style='color:green;'>ODURF</span></td>";                        	
			                        }
								}
		                        
							}else{
								echo"<td class='stuPost'><span class='oSpan'>".$row['currentpost']."</span></td>";	
								if($row['fundingtype'] == "1"){	
		                        	echo"<td class='stuFundingType' title='Funded by ODU'><span class='oSpan' style='color:blue;'>ODU</span></td>";
		                        }else{
		                        	echo"<td class='stuFundingType' title='Funded by ODU Research Foundation'><span class='oSpan' style='color:green;'>ODURF</span></td>";                        	
		                        }
							}
						}
												
						if($projID == ""){
							echo"<td class='stuProj' projId='0'><span class='oSpan'>None</span></td>";
						}else{	// here a change is to be made which populates all the projects to accomodate the edits by the admins					
							
							$adminUpdatedProjSelBox = "";
							$projectTD ="";
							$projQueryStr="Select * FROM Projects WHERE id=".$projID;		
							$projQuery = mysql_query($projQueryStr);
							while($projRow = mysql_fetch_array($projQuery)){
										
								$projectTD = "<td class='stuProj' projid='".$projRow['id']."'><span title='".$projRow['agency'] ."' class='oSpan'>".trim(str_replace('/','-',$projRow['name']))."</span></td>";
							}	
														
							echo $projectTD;
								
						
						}	
						
																	
						//----------------------- this block one is for Displaying Start & End Dates (Change Request)-----
						//$startDate =  date('m/d/Y',strtotime($row['startdate']));
						//$endDate =  date('m/d/Y',strtotime($row['enddate']));
						
						$startDate =  date('Y/m/d',strtotime($row['startdate']));
						$endDate =  date('Y/m/d',strtotime($row['enddate']));
						echo"<td class='stuSem'><span class='oSpan'>".$row['semester']."</span></td>";
												
						
						if(!$isAdmin){			
							$startDate =  date('Y/m/d',strtotime($row['startdate']));
							$endDate =  date('Y/m/d',strtotime($row['enddate']));	
							echo"<td class='stuStartDate' title='".$row['semester']." | ".$row['year']."'><span class='oSpan'>".$startDate ."</span></td>";
							echo"<td class='stuEndDate' title='".$row['semester']." | ".$row['year']."'><span class='oSpan'>". $endDate."</span></td>";
						}else{	
							$startDate =  date('Y-m-d',strtotime($row['startdate']));
							$endDate =  date('Y-m-d',strtotime($row['enddate']));						
							echo"<td class='stuStartDate' title='".$row['semester']." | ".$row['year']."'><input type='date' name='adminUpdatedStartDate' class='adminUpdatedStartDate form-control width_83Per' placeholder='StartDate' required value='".$startDate."' /></td>";
							echo"<td class='stuEndDate' title='".$row['semester']." | ".$row['year']."'><input type='date' name='adminUpdatedEndDate' class='adminUpdatedEndDate form-control width_83Per' placeholder='EndDate' required value='".$endDate."' /></td>";
						}

						//-------------------------------------------------------------------------------------------------						
						//-------------- removed because the start date and end date has come int   o existance --------------
						//echo"<td class='stuSem'><span class='oSpan'>".$row['semester']."</span></td>";
						//echo"<td class='stuYear'><span class='oSpan'>".$row['year']."</span></td>";						
						// the following two are for Tutionwaiver and No OF Credits, added for the additional requirements
						if(!$isAdmin){
							echo"<td class='stuTWaive' currtutionfee='".$row['currenttution'] ."'><span class='oSpan'>".$row['tutionwaive']."</span></td>";		
							echo"<td class='stuNoOfCredits'><span class='oSpan'>".$row['credithours']."</span></td>";
							echo"<td class='stuSal'><span class='oSpan'>".$row['salarypaid']."</span></td>";
							echo"<td class='stuWHours'><span class='oSpan'>".$row['hours']."</span></td>";
							
						}else{
							
							if(((int)$row['isreappointed'] == 1 && $row['offerstatus'] == "2")){  
								if($adminType == 1){
									echo"<td class='stuTWaive' currtutionfee='".$row['currenttution'] ."'><input type='number' name='adminUpdatedTution' class='adminUpdatedTutionIP form-control width_100Per' placeholder='Tution%' required value='".$row['tutionwaive']."' /></td>";		
									echo"<td class='stuNoOfCredits'><input type='number' name='adminUpdatedCredits' class='adminUpdatedCreditsIP form-control width_100Per' placeholder='No Of Credits' required value='".$row['credithours']."' /></td>";														
									echo"<td class='stuSal'><input type='number' name='adminUpdatedSalary' class='adminUpdatedSalaryIP form-control width_100Per' placeholder='Salary' required value='".$row['salarypaid']."' /></td>";
									echo"<td class='stuWHours'><input type='number' name='adminUpdatedHours' class='adminUpdatedHoursIP form-control width_100Per' placeholder='Hours' required value='".$row['hours']."' /></td>";			
								}else{
									echo"<td class='stuTWaive' currtutionfee='".$row['currenttution'] ."'><span class='oSpan'>".$row['tutionwaive']."</span></td>";			
									echo"<td class='stuNoOfCredits'><span class='oSpan'>".$row['credithours']."</span></td>";
									echo"<td class='stuSal'><span class='oSpan'>".$row['salarypaid']."</span></td>";
									echo"<td class='stuWHours'><span class='oSpan'>".$row['hours']."</span></td>";								
								}
							}else if($row['offerstatus'] == "2"){
								if($adminType == 1){
									echo"<td class='stuTWaive' currtutionfee='".$row['currenttution'] ."'><input type='number' name='adminUpdatedTution' class='adminUpdatedTutionIP form-control width_100Per' placeholder='Tution%' required value='".$row['tutionwaive']."' /></td>";		
									echo"<td class='stuNoOfCredits'><input type='number' name='adminUpdatedCredits' class='adminUpdatedCreditsIP form-control width_100Per' placeholder='No Of Credits' required value='".$row['credithours']."' /></td>";													
									echo"<td class='stuSal'><input type='number' name='adminUpdatedSalary' class='adminUpdatedSalaryIP form-control width_100Per' placeholder='Salary' required value='".$row['salarypaid']."' /></td>";
									echo"<td class='stuWHours'><input type='number' name='adminUpdatedHours' class='adminUpdatedHoursIP form-control width_100Per' placeholder='Hours' required value='".$row['hours']."' /></td>";			
																
								}else{
									echo"<td class='stuTWaive' currtutionfee='".$row['currenttution'] ."'><span class='oSpan'>".$row['tutionwaive']."</span></td>";			
									echo"<td class='stuNoOfCredits'><span class='oSpan'>".$row['credithours']."</span></td>";
									echo"<td class='stuSal'><span class='oSpan'>".$row['salarypaid']."</span></td>";
									echo"<td class='stuWHours'><span class='oSpan'>".$row['hours']."</span></td>";																	
								}

							}else{					
								echo"<td class='stuTWaive' currtutionfee='".$row['currenttution'] ."'><span class='oSpan'>".$row['tutionwaive']."</span></td>";			
								echo"<td class='stuNoOfCredits'><span class='oSpan'>".$row['credithours']."</span></td>";
								echo"<td class='stuSal'><span class='oSpan'>".$row['salarypaid']."</span></td>";
								echo"<td class='stuWHours'><span class='oSpan'>".$row['hours']."</span></td>";								
								
							}
						}
						
						//echo"<td class='stuWHours'><span class='oSpan'>".$row['hours']."</span></td>";
																
						$offerStatus = "";
						$offerStatusTitle = "";
						$textColor = "";
						if($row['offerstatus'] == "0"){
							$offerStatus = "Initiated";
							$textColor = "orange";
							$offerStatusTitle = "Student yet to accept the Offer.";
						}elseif($row['offerstatus'] == "1"){
							$offerStatus = "Released";
							$textColor = "brown";
							$offerStatusTitle = "Student received the Appoitnment Letter, yet to Sign and Return it.";
						}else if ($row['offerstatus'] == "2"){
							$offerStatus = "Accepted";
							$textColor = "blue";
							$offerStatusTitle = "Student Accepted the Offer, Admin has to send the Appointment to Student yet to sign";
						}else if ($row['offerstatus'] == "3"){
							$offerStatus = "Declined";
							$textColor = "black";
							$offerStatusTitle = "Student Declined the Offer.";
						}else if($row['offerstatus'] == "4"){
							$offerStatus = "Signed";
							$textColor = "green";
							$offerStatusTitle = "Student Signed and sent the form.";
						}else{
							$offerStatus = "Employed";
							$textColor = "green";
							$offerStatusTitle = "Student been employed and working.";
						}
						
						echo"<td class='stuRecStatus' title='".$offerStatusTitle ."' style='color:".$textColor .";'>".$offerStatus."</td>";
						
					// this td is for documents link
						$uniquefileName =$row['rec_id'];
							/*if($row['rec_positionid'] != NULL){								
								if(intval($row['rec_positionid'])>intval($row['rec_id']) ){
									$uniquefileName =$row['rec_id']."_".$row['rec_relatedid'];
								}else{
									$uniquefileName =$row['rec_relatedid']."_".$row['rec_id'];
								}
							}else{
								$uniquefileName =$row['rec_id'];
							}*/
						
						if($row['offerstatus'] == "1"){
							
							
							
							
							echo"<td class='stuRecDocs'><a href='Assets/Uploads/offerunsigned_".$uniquefileName.".pdf' target='_blank'><i class='fa fa-file-pdf-o' title='Document released to student' aria-hidden='true'></i></a> </td>";
						
						
						}else if($row['offerstatus'] == "4" || $row['offerstatus'] == "5"){
							if(intval($row['existingAppImportedBy'])!= 0){
								$filename = "Assets/Uploads/offersigned_".$uniquefileName.".pdf";
								if(file_exists($filename)){
									echo"<td class='stuRecDocs'><a href='Assets/Uploads/offersigned_".$uniquefileName.".pdf' target='_blank'><i class='fa fa-file-pdf-o' title='Document Student signed' aria-hidden='true'></i></a> </td>";																							
								}else{
									echo"<td class='stuRecDocs'><i class='fa fa-paperclip attachFileExistAppInLaterPeriod' title='Attach exisiting appointment letter' style='margin-top:10%;margin-left:8%;font-size:25px;' aria-hidden='true'></i></td>";																													
								}					
							
							}else{
								echo"<td class='stuRecDocs'><a href='Assets/Uploads/offerunsigned_".$uniquefileName.".pdf' target='_blank'><i class='fa fa-file-pdf-o' title='Document released to student' aria-hidden='true'></i></a> &nbsp; <a href='Assets/Uploads/offersigned_".$uniquefileName.".pdf' target='_blank'><i class='fa fa-file-pdf-o' title='Document Student signed' aria-hidden='true'></i></a> </td>";						
							}
						
						} else{
							echo"<td class='stuRecDocs' title='No Docs avaliable'>None</td>";
						}
						
						
						if(!$isAdmin){
							if((int)$row['isreappointed'] == 1 ){ // when reappointed by Professor											
								echo"<td class='reAppCB'><input type='checkbox' checked disabled/> <button type='button' class='reAppointButt btn btn-warning pull-right' style='display:none' >Appoint</button></td>";											
							}
							else if($row['offerstatus'] == "0" || $row['offerstatus'] == "1" || $row['offerstatus']== "2"){ // check box has to be disabled except the offer is Declined or Employeed
															
								echo"<td class='reAppCB'><input type='checkbox' disabled/> <button type='button' class='reAppointButt btn btn-warning pull-right' style='display:none'>Appoint</button></td>";												
							}
							else{						
								echo"<td class='reAppCB'><input type='checkbox' class='reAppointCB' /> <button type='button' class='reAppointButt btn btn-warning pull-right' style='display:none'>Appoint</button></td>";
							}
						}else{
							if(((int)$row['isreappointed'] == 1 && $row['offerstatus'] == "2") ||  $row['offerstatus'] == "2"){
								
								if($adminType == 1){									
									if($row['isfinanceverified'] == '1'){
										
										echo "<td class='releaseOffAdmin' isfinanceverified=". $row['isfinanceverified']."> <i class='fa fa-check releaseOffButtAdm' disabled title='Funds Availability Verified Success already' style='pointer-events:none;cursor:default;opacity:0.3;'></i>
									</td>";
										//echo "<td class='releaseOffAdmin' isfinanceverified=". $row['isfinanceverified']."> <button type='button'  disabled title='Funds Availability Verified Success already' class='releaseOffButtAdm btn btn-warning pull-right'>Verify</button></td>";																														
									}else{
										echo "<td class='releaseOffAdmin' isfinanceverified=". $row['isfinanceverified']."> <i class='fa fa-check releaseOffButtAdm' title='click to verify funds availability'></i></td>";			
										//echo "<td class='releaseOffAdmin' isfinanceverified=". $row['isfinanceverified']."> <button type='button'  title='click to verify funds availability' class='releaseOffButtAdm btn btn-warning pull-right'>Verify</button></td>";																														
									}																	
								}else{
									//echo"<td class='releaseOffAdmin' isfinanceverified=". $row['isfinanceverified']."><button type='button'  title='click to verify Academic Status and Release Appointment Letter' class='releaseOffButtAdm btn btn-warning pull-right'>Release</button></td>";
									echo"<td class='releaseOffAdmin' isfinanceverified=". $row['isfinanceverified']."><i title='click to verify Academic Status and Release Appointment Letter' class='fa fa-paper-plane releaseOffButtAdm'></i></td>";																													
								
								}							
							}
							/*else if($row['offerstatus'] == "2"){ // merged with the above if itself, revisit if something goes wrong
								echo"<td class='reLeaseOffAdmin'><button type='button' class='releaseOffButtAdm btn btn-warning pull-right'>Release</button></td>";													
							}*/
							else{
								if($row['offerstatus'] == "4"){

									echo"<td class='reLeaseOffAdmin' isfinanceverified=".$row['isfinanceverified']."><i class='fa fa-floppy-o submitEditChanges' title='Submit Edited Changes' style='display:none;' aria-hidden='true'></i> <i class='fa fa-times cancelEditAppointment' style='display:none;' title='Cancel Edit' aria-hidden='true'> </i><i class='fa fa-pencil-square-o editAppointment' title='Edit Appointment' aria-hidden='true'></i><i class='fa fa-paper-plane releaseOffButtAdm' disabled style='pointer-events:none;cursor:default;opacity:0.3;'></i>
										<i class='fa fa-check checkonsubmit' title='Click to appoint the student' aria-hidden='true'></i></td>";	
								}else if($row['offerstatus'] == "5"){

									echo"<td class='reLeaseOffAdmin' isfinanceverified=".$row['isfinanceverified']."><i class='fa fa-floppy-o submitEditChanges' title='Submit Edited Changes' style='display:none;' aria-hidden='true'></i> <i class='fa fa-times cancelEditAppointment' style='display:none;' title='Cancel Edit' aria-hidden='true'> </i><i class='fa fa-pencil-square-o editAppointment' title='Edit Appointment' aria-hidden='true'></i><i class='fa fa-paper-plane releaseOffButtAdm' disabled style='pointer-events:none;cursor:default;opacity:0.3;'></i>
										<i class='fa fa-check checkonsubmit' title='Student has been appointed'  style='color:green' aria-hidden='true'></i></td>";	
								}else{
									//echo"<td class='reLeaseOffAdmin'><i class='fa fa-floppy-o submitEditChanges' title='Submit Edited Changes' style='display:none;' aria-hidden='true'></i> <i class='fa fa-times cancelEditAppointment' style='display:none;' title='Cancel Edit' aria-hidden='true'> </i><i class='fa fa-pencil-square-o editAppointment' title='Edit Appointment' aria-hidden='true'></i><button type='button' disabled class='releaseOffButtAdm btn btn-warning pull-right'>Release</button></td>";											
									echo"<td class='reLeaseOffAdmin'><i class='fa fa-floppy-o submitEditChanges' title='Submit Edited Changes' style='display:none;' aria-hidden='true'></i> <i class='fa fa-times cancelEditAppointment' style='display:none;' title='Cancel Edit' aria-hidden='true'> </i><i class='fa fa-pencil-square-o editAppointment' title='Edit Appointment' aria-hidden='true'></i><i class='fa fa-paper-plane releaseOffButtAdm' disabled style='pointer-events:none;cursor:default;opacity:0.3;'></i></td>";								
								}



							}
						}										
					}
	    		?>
	    		</tbody>
	    	</table> 
		</div>

		</div>
		
		<div id="adminValModal" class="modal  fade" role="dialog">
		  <div class="modal-dialog modal-lg">
		
		    <!-- Modal content-->
		    <div class="modal-content">
			    <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal">&times;</button>
			        <h4 class="modal-title">Prepare Offer to Release</h4>
			    </div>
			      
			      <div class="modal-body">
			      	<br />				
			 			<div class="row">
				 			<div class="col-lg-12">
				 				<h5> Please validate the following Check List of the Student - <label class="studentDetADM"> </label> before Preparing Offer Letter.</h5>
				 			</div>					
			 			</div>
			 				
			 			<div class="row">
			 				<div class="col-lg-2">
				 			</div>
				 			<div class="col-lg-8">					
								<div class="well  col-sm-12" style="padding-left:5%;">
								
									<form class="form-inline formLPA formFinancialAdmin" action="#" Method="POST" name="offerUploadForm_LPA">						    						    								
										    <div class="CheckBox" style="margin-top:-5px;">									 
												<label><input type="checkbox"  purp="Funds Availability" value="" class="fundsValidateCB"><b style="margin-left:4px;">Funds Availability</b></label>
										    </div>
										     
										    <div class="form-group" style="margin-left:20%;">
										        - Tution Cost: $
												<input class="form-control tutionCost" type="number" disabled style="width:20%;"/>
												Student Stipend: $
												<input class="form-control studentsCost" type="number" disabled style="width:20%;"/>
										    </div>
										    <div class="form-group fundsValidateButtFG">
												<div class="col-sm-8">
									        		<button type="button" class="btn btn-default btn-success" id="fundsvalidateButt_LPFA" title="Click to update the funds Availablity"  the style="display:none;">Validate</button>
									      		</div>	
								      		</div>
									</form>  
								
									<form class="form-inline formLPA formGradAdmin" action="#" Method="POST" name="offerUploadForm_LPA">						    						    								
	
										    <div class="CheckBox" style="margin-top:-5px;">									 
												<label><input type="checkbox" purp="I9"  value=""><b style="margin-left:4px;">Validate I9</b> </label>
										    </div>
										     
										    <div class="form-group">
										      -   Expiry Date: 
												<input class="form-control studentsI9Expiry" type="date" /> &nbsp; <input type="button" class="btn btn-default i9ExpiryUpdateButt" value="Update" style="display:none;"/>
										    </div>
									</form>  
	
									<form class="form-inline formLPA formGradAdmin" action="#" Method="POST" name="offerUploadForm_LPA">						    						    								
	
										    <div class="CheckBox" style="margin-top:-5px;">									 
												<label><input type="checkbox" purp="Academic Status"  value=""><b style="margin-left:4px;">Student's Academic Status</b> </label>
										    </div>									     
									</form>  
									
									<form class="form-inline formLPA formGradAdmin" action="#" Method="POST" name="offerUploadForm_LPA">						    						    									
										    <div class="CheckBox" style="margin-top:-5px;">									 
												<label><input type="checkbox" purp="No Of Credits Registered"  value=""><b style="margin-left:4px;">Credits Registered Check</b> </label>
										    </div>										   
									</form>  
									
									 <form class="form-horizontal formGradAdmin">
										<div class="form-group">
											<div class="col-sm-5">
												<input type="file" class="btn btn-primary" style="width: 107%;" accept="application/msword,application/vnd.ms-powerpoint,application/pdf"  id="uploadOfferFile_LPA" name="filetoUpload">
											</div>
											<div class="col-sm-5">
												<a href="#" class="sendEmailToStudent" data-toggle="collapse" data-target="#emailNotifDiv" title="Send an email remainder to student">Send an Email </a><br />											
												<div id="emailNotifDiv" class="collapse well">
												
													    <div class="form-group">
													      <label for="emailNotifSub">Email Subject:</label>
													      <input type="text" id="emailNotifSub" class="form-control "placeholder="Enter email Subject">
													    </div>
													    <div class="form-group">
													      <label for="emailNotifBody">Body:</label>
													      <textarea class="form-control" id="emailNotifBody" placeholder="Enter Email Body"></textarea>
													    </div>													    
													    <input type="button" class="btn btn-default emailNotifStudentSend" value="Send"></input>

												</div>
												<a href="#" class="generateATag" target="_blank">Generate Appointment Letter!</a>												
											</div>
										</div>
	
										<div class="form-group">
											<div class="col-sm-8">
								        		<button type="button" class="btn btn-default btn-success" id="offerUpload_Email_LPA">Upload & Email</button>
								      		</div>	
								      	</div>
									 </form>
	
								</div>
				 			</div>
				 			<div class="col-lg-2"></div>
						<div>
			      </div>
		      
		      	</div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default adminValidationCloseButt" data-dismiss="modal">Close</button>
			      </div>
		      
		      
		    	</div>		
		  </div>
		</div>
		</div>
		
		<div class="modal fade" id="existAppFileAttModal" role="dialog">
		    <div class="modal-dialog">
		    
		      <!-- Modal content-->
		      <div class="modal-content">
		        <div class="modal-header">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title exitFileAttachModalHeader">Attach the Existing Offer Letter</h4>
		        </div>
		        <div class="modal-body exitFileAttachModalBody">
		          <p>Some text in the modal.</p>
		        </div>
		        <div class="modal-footer">
		          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        </div>
		      </div>
		      
		    </div>
	  	</div>


		
		
	</body>
 </html>