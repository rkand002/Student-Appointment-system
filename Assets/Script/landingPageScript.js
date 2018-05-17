
var preProjVal="";
var curRecID = "";
var curStudentUIN = "";
var parentTR = null;
var admUpdatedTution = 0;
var admUpdatedCredits = 0;
var admUpdatedSalary = 0;
var admUpdatedFT = 0;
var studentEmail = "";
var curPositionId = 0;
var relatedParentTR = null;
function start()
{
	$(document).ready(function() {
      
	   	 $('#adminValModal').on('show.bs.modal', function (e) {	 		
	   		if( (isNaN(admUpdatedTution) && isNaN(admUpdatedCredits)) || admUpdatedTution <=0 || admUpdatedCredits <=0 ){
				 $("#adminValModal").modal("hide");					
			}
		 });	 

        
		//code for admin credentails goes here 
		$(document).on("click",".releaseOffButtAdm",function(event){
			$(".generateATag").attr("href","#");
			parentTR = $(this).parents("tr");
			curPositionId = parentTR.attr("recposid");
			 relatedParentTR = null;
			 
			/*if(parentTR.attr("relatedrecid")!=""){
				relatedParentTR = $("#"+parentTR.attr("relatedrecid"));
			};*/
			
			var positionRelStr ="";
			
			if(adminType == 1){
				admUpdatedTution = parseInt($.trim(parentTR.find(".stuTWaive input").val()));
				admUpdatedCredits = parseInt($.trim(parentTR.find(".stuNoOfCredits input").val()));
				admUpdatedSalary = parseInt($.trim(parentTR.find(".stuSal input").val()));
				totalUpdatedSal = admUpdatedSalary;
				admUpdatedFT = parseInt($.trim(parentTR.find(".stuFundingType .adminUpdatedFT").val()));
				studentEmail = parentTR.find(".stuName").attr("emailid");
				
				$("."+parentTR.attr("id")).each(function(){
					
					if( $(this).attr("recposid")!= curPositionId){
						
						relatedParentTR = $(this);
						//admUpdatedTution += parseInt($.trim(relatedParentTR.find(".stuTWaive input").val()));
						//admUpdatedCredits += parseInt($.trim(relatedParentTR.find(".stuNoOfCredits input").val()));
						totalUpdatedSal += parseInt($.trim(relatedParentTR.find(".stuSal input").val()));
						
						positionRelStr = " with two positions ("+parentTR.find(".stuProj span").text()+","+relatedParentTR.find(".stuProj span").text()+") ";				
						//admUpdatedFT += parseInt($.trim(relatedParentTR.find(".stuFundingType .adminUpdatedFT").val()));
	  
					}
					
				});
				
				//changed - multiple positions	
				/*if(relatedParentTR != null ){
					//admUpdatedTution += parseInt($.trim(relatedParentTR.find(".stuTWaive input").val()));
					//admUpdatedCredits += parseInt($.trim(relatedParentTR.find(".stuNoOfCredits input").val()));
					totalUpdatedSal += parseInt($.trim(relatedParentTR.find(".stuSal input").val()));
					
					positionRelStr = " with two positions ("+parentTR.find(".stuProj span").text()+","+relatedParentTR.find(".stuProj span").text()+") ";
					//admUpdatedFT += parseInt($.trim(relatedParentTR.find(".stuFundingType .adminUpdatedFT").val()));
				}
				 */
				if((isNaN(admUpdatedTution) && isNaN(admUpdatedCredits) && isNaN(totalUpdatedSal)) || admUpdatedTution <=0 || admUpdatedCredits <=0 || totalUpdatedSal <= 0){				
					errorPopUp("Please fill out the Tution and Credits fields properly.");				
					return false;				
				}
			}
			
			if(adminType == 1){
				$(".fundsValidateCB").prop("disabled",false);
				
				if($(this).parents("td").attr("isfinanceverified") != '1'){
					 $(".fundsValidateCB").prop("checked",false);
				}								
				$(".tutionCost").val( (currTutionPSem * admUpdatedTution * admUpdatedCredits)/100 );
				$(".studentsCost").val(totalUpdatedSal);
				$(".formGradAdmin").hide();
				$(".formFinancialAdmin").show();
			}else{
				$(".formGradAdmin").show();			
				
				var aHRefForPDFGen = "/"+SERVERHOST+"_StudentAppointmentSystem/generateOfferPDF.php?recid="+parentTR.attr("id");
				//var aHRefForPDFGen = "/Prod_StudentAppointmentSystem/generateOfferPDF.php?recid="+parentTR.attr("id");
				$(".generateATag").attr("href",aHRefForPDFGen);				
				$(".formFinancialAdmin").hide();
			}
			
			$('#adminValModal').modal("show");
			curRecID = parentTR.attr("id");
			studentEmail = parentTR.find(".stuName").attr("emailid");
			curStudentUIN = $.trim(parentTR.find(".stuUIN").text());
			$(".studentsI9Expiry").val($.trim(parentTR.find(".stuUIN").attr("i9expiry")));
			
			
			$(".studentDetADM").html(parentTR.find(".stuName").text()+" ("+parentTR.find(".stuUIN").text()+")"+positionRelStr);
		
			//alert("clicked on release Button");
		});
		
		
		$(document).on("click",".fundsValidateCB",function(event){	
			if($(this).prop("checked")){
				$("#fundsvalidateButt_LPFA").show()
			}else{
				$("#fundsvalidateButt_LPFA").hide()
			}
			
		});
		
		
		
		// for financial admin, on click of validate funds checkbox
		$(document).on("click","#fundsvalidateButt_LPFA",function(event){		
			//var parentTrUnderVer = $("#RecruitedStuTable tbody").find("#"+curRecID);			
			if(!$(".fundsValidateCB").prop("checked")){
				errorPopUp("Please check the Funds Availablity, before clicking on validate.");				
				return false;	
			}
			// here there is one feature to be accomplished that, the financial admin must be able to update the credits and the %T - have to revisit
			var dataArray =[];
			var data = {};
       	 	data["recId"] = parentTR.attr("id");
       		data["recPosId"] = parseInt($.trim(parentTR.attr("recposid")));
       		data["adminUpdatedStuPost"] = parentTR.find(".stuPost .adminUpdatedStuPost").val();
       		data["adminUpdatedFT"] = parseInt(parentTR.find(".stuFundingType .adminUpdatedFT").val());
       		data["adminUpdatedStudentProj"] = parseInt($.trim(parentTR.find(".stuProj select").val()));
       		data["adminUpdatedStartDate"] = $.trim(parentTR.find(".adminUpdatedStartDate").val());
       		data["adminUpdatedEndDate"] = $.trim(parentTR.find(".adminUpdatedEndDate").val());
       		data["adminUpdateTution"] = parseInt($.trim(parentTR.find(".stuTWaive input").val()));
       		data["adminUpdatedCredits"] = parseInt($.trim(parentTR.find(".stuNoOfCredits input").val()));
       		data["adminUpdatedSal"] = parseInt($.trim(parentTR.find(".stuSal input").val()));     		
       		data["adminUpdatedHours"] = parseInt($.trim(parentTR.find(".stuWHours input").val()));
       		dataArray.push(data);
       		
       		$("."+parentTR.attr("id")).each(function(){
       			
       			if(curPositionId != $(this).attr("recposid")){
	       			var data = {};  
	       			relatedParentTR = $(this);
	       			data["recId"] = relatedParentTR.attr("id");
	           		data["recPosId"] = parseInt($.trim(relatedParentTR.attr("recposid")));
	       			data["adminUpdatedStuPost"] = relatedParentTR.find(".stuPost .adminUpdatedStuPost").val();
	           		data["adminUpdatedFT"] = parseInt($.trim(relatedParentTR.find(".stuFundingType .adminUpdatedFT").val()));
	           		data["adminUpdatedStudentProj"] = parseInt($.trim(relatedParentTR.find(".stuProj select").val()));
	           		data["adminUpdatedStartDate"] = $.trim(relatedParentTR.find(".adminUpdatedStartDate").val());
	           		data["adminUpdatedEndDate"] = $.trim(relatedParentTR.find(".adminUpdatedEndDate").val());
	           		data["adminUpdateTution"] = parseInt($.trim(relatedParentTR.find(".stuTWaive input").val()));
	           		data["adminUpdatedCredits"] =  parseInt($.trim(relatedParentTR.find(".stuNoOfCredits input").val()));
	           		data["adminUpdatedSal"] = parseInt($.trim(relatedParentTR.find(".stuSal input").val()));
	           		data["adminUpdatedHours"] = parseInt($.trim(relatedParentTR.find(".stuWHours input").val()));
	           		dataArray.push(data);
       			}
       		});
       		
       	//changed - multiple positions	
       		/*if(relatedParentTR != null ){
       			var data = {};  
       			data["recId"] = relatedParentTR.attr("id");
       			data["adminUpdatedStuPost"] = relatedParentTR.find(".stuPost .adminUpdatedStuPost").val();
           		data["adminUpdatedFT"] = parseInt($.trim(relatedParentTR.find(".stuFundingType .adminUpdatedFT").val()));
           		data["adminUpdatedStudentProj"] = parseInt($.trim(relatedParentTR.find(".stuProj select").val()));
           		data["adminUpdatedStartDate"] = $.trim(relatedParentTR.find(".adminUpdatedStartDate").val());
           		data["adminUpdatedEndDate"] = $.trim(relatedParentTR.find(".adminUpdatedEndDate").val());
           		data["adminUpdateTution"] = parseInt($.trim(relatedParentTR.find(".stuTWaive input").val()));
           		data["adminUpdatedCredits"] =  parseInt($.trim(relatedParentTR.find(".stuNoOfCredits input").val()));
           		data["adminUpdatedSal"] = parseInt($.trim(relatedParentTR.find(".stuSal input").val()));
           		data["adminUpdatedHours"] = parseInt($.trim(relatedParentTR.find(".stuWHours input").val()));
           		dataArray.push(data);
           		ajaxUpdateDetByAdmin1MultiPosition(dataArray);
			}else{
				  ajaxUpdateDetByAdmin1SinglePosition(dataArray[0]);
			}*/		
       		
       		ajaxUpdateDetByAdmin1MultiPosition(dataArray);
       		
       		
		});
		
	    var today = new Date().toISOString().split('T')[0];
	    $(".studentsI9Expiry").attr('min', today);
	    
	    // registring the trigger on I9 expiry date change
	    $(document).on("change",".studentsI9Expiry",function(){	    
	    	$(".i9ExpiryUpdateButt").show();	    	
	    });
	    
		$(document).on("click",".i9ExpiryUpdateButt",function(){	 
			    	
			    	var currI9Expiry = $.trim($(".studentsI9Expiry").val());
			    	if( currI9Expiry == ""){
			    		//alert("Error: Give a proper Expiry Date.");
			    		errorPopUp("Please give a proper Expiry Date.");	
			    		return false;
			    	}
			    	
			    	$.ajax({
				          type: "GET",
				          url: "/"+SERVERHOST+"_StudentAppointmentSystem/updateSettingsAdmin.php?action=3&currI9Expiry="+currI9Expiry+"&studentUIN="+curStudentUIN,
				         // url: "/Prod_StudentAppointmentSystem/updateSettingsAdmin.php?action=3&currI9Expiry="+currI9Expiry+"&studentUIN="+curStudentUIN,
				          dataType: "text",
				          success: function( data, textStatus, jqXHR) {		
			    			 $('#cover').hide();
			    			 if($.trim(data) == "success"){
			    				 //alert("I9Expiry is updated successfully");
			    				 successPopUp("I9Expiry is updated successfully");
			    				 parentTR.find(".stuUIN").attr("i9expiry",$(".studentsI9Expiry").val());
			    				  $(".i9ExpiryUpdateButt").hide();
			    			 }else{
			    					//alert("error: some problem while updating the I9 Expiry");
			    					errorPopUp("some problem while updating the I9 Expiry.");
			    			 }

						    // window.location.href = "http://qav2.cs.odu.edu/Maheedhar/StudentRecruitmentTS/home.php";
				          },
			  			  error: function( data, textStatus, jqXHR) {
				        	  $('#cover').hide();
			  		      	  //alert("error: some problem while updating the I9 Expiry");
				        	  errorPopUp("some problem while updating the I9 Expiry.");
			  		      }
			    	}); 
			});
		
		
		
		
        // to upload Offer Letter and email to student from Admin CC Professor, Student
        $('#offerUpload_Email_LPA').click(function() {
        	var isValidated = true;
        	 $(".formGradAdmin").find("input:checkbox").each(function(){
				 if(!$(this).is(":checked")){
					 //alert("please verify the validation Of Student "+$(this).attr("purp")+" and Check the box");
					 errorPopUp("please verify the validation Of Student "+$(this).attr("purp")+" and Check the box");
					 isValidated = false;
					 return false;
				 }
			 });
        	 
        	 if(!isValidated){
        		 return false;
        	 }
        	 
	         if( $("#uploadOfferFile_LPA").val() == ""){
	        	//alert("please choose the Appointment Letter to Email Student");
				errorPopUp("please choose the Appointment Letter to Email Student");
				return false;
	         }
	         $('#cover').show();	  
	         $.ajax({
		          type: "GET",
		         //url: "/Prod_StudentAppointmentSystem/updateSettingsAdmin.php?action=5&recid="+curRecID,
		          url: "/"+SERVERHOST+"_StudentAppointmentSystem/updateSettingsAdmin.php?action=5&recid="+curRecID,
		          dataType: "text",
		          success: function( data, textStatus, jqXHR) {		
	    			 $('#cover').hide();
	    			 if($.trim(data).split("-")[0] == "Success"){
	    				 if($.trim(data).split("-")[1] == "1"){
	    					 var formData = new FormData();
	    		              formData.append('filetoUpload', $('#uploadOfferFile_LPA')[0].files[0]);
	    		              formData.append('recruitmentID',curRecID);
	    		               /*formData.append('admUpdatedTution',admUpdatedTution);
	    		               formData.append('admUpdatedCredits',admUpdatedCredits);
	    		               formData.append('admUpdatedSalary',admUpdatedSalary);
	    		               formData.append('admUpdatedFT',admUpdatedFT);*/
	    		              $('#cover').show();
	    		              $.ajax({
	    		            	 // url: 'emailStudent.php',
	    		            	  url: 'emailStudentMultiplePositions.php',
	    		                  type: 'POST',
	    		                  data: formData,
	    		                  processData: false,
	    		                  contentType: false,
	    		                  success: function(data) {
	    		            		 $('#cover').hide();
	    			                	if($.trim(data) == "success"){	     
	    			                		 //alert("Email sent successfully with the attached Offer to the Student!");
	    			     				     //window.location.href = "http://qav2.cs.odu.edu/Maheedhar/StudentRecruitmentTS/home.php"
	    			     				    successPopUpWithRD("Email sent successfully with the attached Offer to the Student!");
	    			                         //$('#success_upload').show();	                       
	    			                  		//$('body').append('<div id="over" style="position: absolute;top:0;left:0;width: 100%;height:100%;z-index:2;opacity:0.4;filter: alpha(opacity = 50)"></div>');
	    								}
	    								else{
	    									//alert("error: some problem while sending an email!");
	    									errorPopUp("error: some problem while sending an email!");
	    								}
	    		                  },
	    		                  error: function(xhr,error){    
	    		                	  $('#cover').hide();
	    		  	    		    //alert('Holy errors, testFileUpload!');
	    		  	    		    errorPopUp("error: some problem while sending an email!");
	    		                  }
	    		              });
	    				 }else{
	    					 errorPopUp("Funds Availabilty check isn't performed yet, So please contact the Financial Admin");
	    					 return false;
	    				 }    				 
	    				 
	    			 }else{
	    					//alert("error: some problem while updating the I9 Expiry");
	    					errorPopUp("some problem while trying to get the FundsAvailability Check.");
	    					return false;
	    			 }

				    // window.location.href = "http://qav2.cs.odu.edu/Maheedhar/StudentRecruitmentTS/home.php";
		          },
	  			  error: function( data, textStatus, jqXHR) {
		        	  $('#cover').hide();
	  		      	  //alert("error: some problem while updating the I9 Expiry");
		        	  errorPopUp("some problem while trying to get the FundsAvailability Check.");
	  		      }
	    	});
	         
	         
	         
	         
              
          });
        
        
        // Student to Accept or Deny the offer, which intimates the Admin on accept and intimates the professors on Deny -- Change Request
        $('#acceptOfferButt_LPS1').click(function() {  
        	$('#cover').show();
              $.ajax({
                  url: 'acceptAppointment1.php',
                  type: 'GET',                
                  processData: false,
                  contentType: false,
                  success: function(data) {
            		 $('#cover').hide();
	                	if($.trim(data) == "success"){	 
	                		$(".descriptionDivLPS").css("display","none");
	                		//alert("Email sent successfully with the attachment");
	                		successPopUp("Email notification sent successfully, that you have accepted the appointment.");
	                		$(".studentAppAccContainer").hide();
	                		$(".statusAfterAjaxCall").html("<h3>Thanks for your response.</h3>")
	                		$(".statusAfterAjaxCall").show();
	                		// $('#success_upload_Accept').show();	
	                		$('body').append('<div id="over" style="position: absolute;top:0;left:0;width: 100%;height:100%;z-index:2;opacity:0.4;filter: alpha(opacity = 50)"></div>');
	                		$(".studentAppAccContainer").hide();
	                		
						}
						else{
							//alert("error: some problem while sending an email!");
							errorPopUp("error: some problem while sending an email!");
						}
                  },
                  error: function(xhr,error){   
                	$('#cover').hide();
  	    		    //alert('Holy errors, testFileUpload!');
                	errorPopUp('Holy errors, testFileUpload!');
                  }
              });
          });
        
        
        // Student to Accept & upload signed Offer Letter and email it back to Admin and CC Professor, Student
        $('#offerUpload_Email_LPS').click(function() {      	
        	if( $("#uploadOfferFile_LPS").val() == ""){
       		 	//alert("please choose the signed Appointment Letter to Accept the Offer.");
       		 	errorPopUp("please choose the signed Appointment Letter to Accept the Offer.");
				return false;
        	}        	        	
              var formData = new FormData();
              formData.append('filetoUpload', $('#uploadOfferFile_LPS')[0].files[0]);
         	 $('#cover').show();
              $.ajax({
                  //url: 'acceptAppointment.php',
            	  url: 'acceptAppointmentMultiplePositions.php',
                  type: 'POST',
                  data: formData,
                  processData: false,
                  contentType: false,
                  success: function(data) {
            		 $('#cover').hide();
	                	if($.trim(data) == "success"){	 
	                		$(".descriptionDivLPS").css("display","none");
	                		//alert("Email sent successfully with the attachment");
	                		successPopUp("Email sent successfully with the attachment");
	                		$(".studentAppOffUploadContainer").hide();
	                		$(".statusOffUploadAfterAjaxCall").html("<h3>Thanks for your response.</h3>")
	                		$(".statusOffUploadAfterAjaxCall").show();
	                       // $('#success_upload_Accept').show();	
	                		$('body').append('<div id="over" style="position: absolute;top:0;left:0;width: 100%;height:100%;z-index:2;opacity:0.4;filter: alpha(opacity = 50)"></div>');

						}
						else{
							//alert("error: some problem while sending an email!");
							errorPopUp("error: some problem while sending an email!");
						}
                  },
                  error: function(xhr,error){   
                	$('#cover').hide();
  	    		    //alert('Holy errors, testFileUpload!');
                	errorPopUp('Holy errors, testFileUpload!');
                  }
              });
          });
        
        $('#delineOfferButt_LPS').click(function() {
            $(".descriptionDivLPS").css("display","block");
        });	
            
        // Student to Accept & upload signed Offer Letter and email it back to Admin and CC Professor, Student
        $('#delineOffer_Email_LPS').click(function() {
        	var declineDesc= $("#declineDescription").val().trim();
        	if(declineDesc == ""){
        		//alert("please explain the reason for Declining the offer before submitting.");
        		errorPopUp("please explain the reason for Declining the offer before submitting.");
				return false;
        	}        	
        	$(".descriptionDivLPS").css("display","none");
          	var data = {};       	
        	 data["declineDescription"] = declineDesc;
        	 $('#cover').show();
	          $.post('/'+SERVERHOST+'_StudentAppointmentSystem/declineAppointment.php',data,function (data){
        	 //$.post('/Prod_StudentAppointmentSystem/declineAppointment.php',data,function (data){
        			 $('#cover').hide();
        			if(data.trim()== "success"){
        				
                		//alert("Email sent successfully with the Decline message");
                		successPopUp("Email sent successfully with the Decline message");
                		$(".studentAppAccContainer").hide();
                		$(".statusAfterAjaxCall").html("<h3>Thanks for your response.</h3>")
                		$(".statusAfterAjaxCall").show();
        				   //$('#success_upload_Decline').show();	
                		$('body').append('<div id="over" style="position: absolute;top:0;left:0;width: 100%;height:100%;z-index:2;opacity:0.4;filter: alpha(opacity = 50)"></div>');
                		
        			}else{
        				//alert("error: some problem while sending an email!");
        				errorPopUp("error: some problem while sending an email!");
        			}
        		});
        });
        
        // to send an email remainder to Student.       
        $('.emailNotifStudentSend').click(function() {
        		
        	if($("#emailNotifSub").val().trim() == ""){
        		errorPopUp("Please give a proper Email Subject.");  
        		return false;
        	}else if($("#emailNotifBody").val().trim()== ""){
        		errorPopUp("Please give a proper Email Body.");
        		return false;
        	}
        	
        	
        	var data = {};       	      	 
       		data["studentemailid"] = studentEmail;
       		data["emailsub"] = $("#emailNotifSub").val().trim();
       		data["emailbody"] = $("#emailNotifBody").val().trim();
       		data["sendingfaculty"] = $(".loggedinusername").text();
			$('#cover').show();
			
			$.post('/'+SERVERHOST+'_StudentAppointmentSystem/SendManEmailNotification.php',data,function (data){
			//$.post('/Prod_StudentAppointmentSystem/SendManEmailNotification.php',data,function (data){
				 	$('#cover').hide();
				    if($.trim(data) == "success"){
	    				successPopUp("Email notification sent successfully");
	    				$("#emailNotifSub").val("");
	    				$("#emailNotifBody").val("");
	    			 }else{
	    					//alert("error: some problem while updating the I9 Expiry");
	    					errorPopUp("some problem while sending the email notification.");
	    			 }
				});
        });
        
        
        
        // for admin setting of the start and end date of the semester
        $('.semseladmsett, .yearseladmsett, .oduRRFDatesAdmSett').change(function() {
        	console.log("in the change of sem selection for start date and end date");
        	
        	var curSelAccYear = $(".yearseladmsett").val();
        	var curSelAccSem = $(".semseladmsett").val();
        	$("#startDateAdmSett").removeAttr("max");
    		$("#startDateAdmSett").removeAttr("min"); 			     
     		$("#endDateAdmSett").removeAttr("max");
     		$("#endDateAdmSett").removeAttr("min");
     		
        	var defaultStartDate="";
        	var defaultEndDate="";
        	if(curSelAccSem == "Fall"){
        		defaultStartDate= curSelAccYear.split("-")[0]+"-08-02";
        		defaultEndDate= curSelAccYear.split("-")[0]+"-12-15";
        		$("#startDateAdmSett").attr("min",curSelAccYear.split("-")[0]+"-08-01");
        		$("#startDateAdmSett").attr("max",curSelAccYear.split("-")[0]+"-12-30");    
         		$("#endDateAdmSett").attr("min",curSelAccYear.split("-")[0]+"-08-01");
         		$("#endDateAdmSett").attr("max",curSelAccYear.split("-")[0]+"-12-30"); 
         		
        	}else if(curSelAccSem == "Spring"){
        		defaultStartDate=  curSelAccYear.split("-")[1]+"-01-02";
        		defaultEndDate= curSelAccYear.split("-")[1]+"-04-15";
        		$("#startDateAdmSett").attr("min",curSelAccYear.split("-")[1]+"-01-01");
        		$("#startDateAdmSett").attr("max",curSelAccYear.split("-")[1]+"-05-30");    
         		$("#endDateAdmSett").attr("min",curSelAccYear.split("-")[1]+"-01-01");
         		$("#endDateAdmSett").attr("max",curSelAccYear.split("-")[1]+"-05-30"); 
        	}else{
        		defaultStartDate= curSelAccYear.split("-")[1]+"-05-02";
        		defaultEndDate= curSelAccYear.split("-")[1]+"-07-15";
        		$("#startDateAdmSett").attr("min",curSelAccYear.split("-")[1]+"-05-01");
        		$("#startDateAdmSett").attr("max",curSelAccYear.split("-")[1]+"-07-30");    
         		$("#endDateAdmSett").attr("min",curSelAccYear.split("-")[1]+"-05-01");
         		$("#endDateAdmSett").attr("max",curSelAccYear.split("-")[1]+"-07-30"); 
        	}
        		
	     		
        	$.ajax({
  	          type: "GET",
  	          url: "/"+SERVERHOST+"_StudentAppointmentSystem/updateSettingsAdmin.php?action=6&sem="+curSelAccSem+"&accyear="+curSelAccYear+"&defStartDate="+defaultStartDate+"&defEndDate="+defaultEndDate+"&oduRRF="+$(".oduRRFDatesAdmSett").val(),
  	          dataType: "text",
  	          success: function( data, textStatus, jqXHR) {			  			
  			 	if($.trim(data).split("|")[0] == "Success"){
  			 		if($.trim(data).split("|")[1] == "definsert"){
  			     		$("#startDateAdmSett").val(defaultStartDate);     			     		
  			     		$("#endDateAdmSett").val(defaultEndDate);
  			 		}else{
  			 			$("#startDateAdmSett").val($.trim(data).split("|")[1]);
  			 			//defaultStartDate = $.trim(data).split("|")[1];
  			 			$("#endDateAdmSett").val($.trim(data).split("|")[2]);	
  			 			//defaultEndDate = $.trim(data).split("|")[2];
  			 		}
  			 		
  			 	}else{
  			 		var errMsg = "some problem while getting the start and end dates of the semester details";
  		         	errorPopUp(errMsg);
  			 	}
  	          },
  			  error: function( data, textStatus, jqXHR) {
  	        	$('#cover').hide();
  		      	//alert("error: some problem while getting the project details");
  	         	var errMsg = "some problem while getting the start and end dates of the semester details";
  	         	errorPopUp(errMsg);	 
  		      }
        	}); 
    	
        });
        
        /*$(".oduRRFDatesAdmSett").change(function(){
        	console.log("in the ODU/RF selectiong for date settings");
        	var curSelAccYear = $(".yearseladmsett").val();
        	var curSelAccSem = $(".semseladmsett").val();
        	$("#startDateAdmSett").removeAttr("max");
    		$("#startDateAdmSett").removeAttr("min"); 			     
     		$("#endDateAdmSett").removeAttr("max");
     		$("#endDateAdmSett").removeAttr("min");
     		
        	var defaultStartDate="";
        	var defaultEndDate="";
        	if(curSelAccSem == "Fall"){
        		defaultStartDate= curSelAccYear.split("-")[0]+"-08-02";
        		defaultEndDate= curSelAccYear.split("-")[0]+"-12-15";
        		$("#startDateAdmSett").attr("min",curSelAccYear.split("-")[0]+"-08-01");
        		$("#startDateAdmSett").attr("max",curSelAccYear.split("-")[0]+"-12-30");    
         		$("#endDateAdmSett").attr("min",curSelAccYear.split("-")[0]+"-08-01");
         		$("#endDateAdmSett").attr("max",curSelAccYear.split("-")[0]+"-12-30"); 
         		
        	}else if(curSelAccSem == "Spring"){
        		defaultStartDate=  curSelAccYear.split("-")[1]+"-01-02";
        		defaultEndDate= curSelAccYear.split("-")[1]+"-04-15";
        		$("#startDateAdmSett").attr("min",curSelAccYear.split("-")[1]+"-01-01");
        		$("#startDateAdmSett").attr("max",curSelAccYear.split("-")[1]+"-04-30");    
         		$("#endDateAdmSett").attr("min",curSelAccYear.split("-")[1]+"-01-01");
         		$("#endDateAdmSett").attr("max",curSelAccYear.split("-")[1]+"-04-30"); 
        	}else{
        		defaultStartDate= curSelAccYear.split("-")[1]+"-05-02";
        		defaultEndDate= curSelAccYear.split("-")[1]+"-07-15";
        		$("#startDateAdmSett").attr("min",curSelAccYear.split("-")[1]+"-05-01");
        		$("#startDateAdmSett").attr("max",curSelAccYear.split("-")[1]+"-07-30");    
         		$("#endDateAdmSett").attr("min",curSelAccYear.split("-")[1]+"-05-01");
         		$("#endDateAdmSett").attr("max",curSelAccYear.split("-")[1]+"-07-30"); 
        	}
        		
	     		
        	$.ajax({
  	          type: "GET",
  	          url: "/"+SERVERHOST+"_StudentAppointmentSystem/updateSettingsAdmin.php?action=6&sem="+curSelAccSem+"&accyear="+curSelAccYear+"&defStartDate="+defaultStartDate+"&defEndDate="+defaultEndDate+"&oduRRF="+$(".oduRRFDatesAdmSett").val(),
  	          dataType: "text",
  	          success: function( data, textStatus, jqXHR) {			  			
  			 	if($.trim(data).split("|")[0] == "Success"){
  			 		if($.trim(data).split("|")[1] == "definsert"){
  			     		$("#startDateAdmSett").val(defaultStartDate);     			     		
  			     		$("#endDateAdmSett").val(defaultEndDate);
  			 		}else{
  			 			$("#startDateAdmSett").val($.trim(data).split("|")[1]);
  			 			//defaultStartDate = $.trim(data).split("|")[1];
  			 			$("#endDateAdmSett").val($.trim(data).split("|")[2]);	
  			 			//defaultEndDate = $.trim(data).split("|")[2];
  			 		}
  			 		
  			 	}else{
  			 		var errMsg = "some problem while getting the start and end dates of the semester details";
  		         	errorPopUp(errMsg);
  			 	}
  	          },
  			  error: function( data, textStatus, jqXHR) {
  	        	$('#cover').hide();
  		      	//alert("error: some problem while getting the project details");
  	         	var errMsg = "some problem while getting the start and end dates of the semester details";
  	         	errorPopUp(errMsg);	 
  		      }
        	}); 
        });*/
        
        
        $("#startDateAdmSett").change(function(){
        	$("#endDateAdmSett").attr("min", $(this).val());
        	$(".updateSemDatesButt").show();
        });
        
        $("#endDateAdmSett").change(function(){
        	$("#startDateAdmSett").attr("max", $(this).val());
        	$(".updateSemDatesButt").show();
        });
        
        $(document).on("click",".updateSemDatesButt",function(){	 
        	var currStartDate = $.trim($("#startDateAdmSett").val());
	    	var currEndDate = $.trim($("#endDateAdmSett").val());
	    	if( currStartDate == "" || currEndDate== ""){
	    		//alert("Error: Update the Current Tution with a proper value.");
	    		//return false;
	    		var errMsg = "Update the Semester Start and End Dates properly.";
	    		errorPopUp(errMsg);
	    		return false;
	    	}
	    	
	    	$.ajax({
		          type: "GET",
		          url: "/"+SERVERHOST+"_StudentAppointmentSystem/updateSettingsAdmin.php?action=7&curStartDate="+currStartDate +"&curEndDate="+currEndDate+"&sem="+$(".semseladmsett").val()+"&accyear="+$(".yearseladmsett").val()+"&oduRRF="+$(".oduRRFDatesAdmSett").val(),
		          dataType: "text",
		          success: function( data, textStatus, jqXHR) {		
	    			 $('#cover').hide();
	    			if( $.trim(data) == "success" || $.trim(data)== "insert"){
	    				 var msg = "Semester dates are updates succefully";
		    			 successPopUpWithRD(msg);    				
	    			}else{
	    				 var errMsg = "some problem while updating Semester dates";
			        	  errorPopUp(errMsg);
	    			}
	    			
		          },
	  			  error: function( data, textStatus, jqXHR) {
		        	  $('#cover').hide();
		        	  var errMsg = "some problem while updating Semester dates";
		        	  errorPopUp(errMsg);
	  		      }
	    		}); 
	    });
        
        $(document).on("change", "#startDateIP",function(){
        	$("#endDateIP").attr("min", $(this).val());
       
        });
        
        $(document).on("change","#endDateIP",function(){
        	$("#startDateIP").attr("max", $(this).val());
        	
        });
        
        var curDate = new Date();
        var yearSelOptionsAdmin = "";
        if(curDate.getMonth() < 5){
        	yearSelOptionsAdmin += "<option>"+ (curDate.getFullYear()-1)+"-"+curDate.getFullYear()+"</option>";
        }
        for(var i=0;i<5;i++){
        	yearSelOptionsAdmin += "<option>"+ (curDate.getFullYear()+i) +"-"+(curDate.getFullYear()+i+1) +"</option>";
        }

        $(".yearseladmsett").html(yearSelOptionsAdmin);
        
        $(document).on("change",".yearseladmsett",function(){       	
        	if(parseInt($(this).val().split("-")[0]) == curDate.getFullYear()){
        		// to implement the correct validations 
        		if((curDate.getMonth()+1) <=3 || (curDate.getMonth()+1) >=11  ){
        		//	$(".semseladmsett option[value='Fall']").attr("disabled",true);
        		}else if((curDate.getMonth()+1) >=7 && (curDate.getMonth()+1) <=10){
        			
        		}else{
        			//$(".semseladmsett option[value='Fall']").attr("disabled",true);
        			//$(".semseladmsett option[value='Spring']").attr("disabled",true);
        		}      		
        		//$($(".semseladmsett option[disabled !='disabled']")[0]).attr("selected","selected");      		
        	}else{
        		//$(".semseladmsett option").removeAttr("disabled");
        	}
        });
        
        $(document).on("click",".attachFileExistAppInLaterPeriod",function(){      
        	var fileAttButtClicked = $(this);
        	var recruitmentTr = fileAttButtClicked.parents("tr");
        	var recruitmentId = recruitmentTr.attr("id");
        	var msg = "Please Attach the Appointment Letter For <b>"+ recruitmentTr.find(".stuName").html()+" "+recruitmentTr.find(".stuStartDate").attr("title")+"</b>";
        	
        	msg+= "<br/><br/> <input type='file' class='btn btn-default chooseExistingAppLaterTime' title='attach an appointment letter' /><br/> <input type='submit' recid="+recruitmentId +" class='btn btn-success uploadExistingAppLaterTime' name='Upload' />";
        	existingFileAttachPopUpRD(msg);       	
        });

        
        $(document).on("click",".uploadExistingAppLaterTime",function(){      
        	var recruitmentId = $(this).attr("recid");
        	var fileFormData = new FormData();
			fileFormData.append('filetoUpload', $('.chooseExistingAppLaterTime')[0].files[0]);	
			 fileFormData.append("appointmentID",$.trim(recruitmentId));
			 $.ajax({
	              url: 'uploadExistApmt.php',
	              type: 'POST',
	              data: fileFormData,
	              processData: false,
	              contentType: false,
	              success: function(data) {
	        		 $('#cover').hide();
	                	if($.trim(data) == "success"){	     
	     				    successPopUpWithRD("Existing Appointment Letter uploaded successfully!");
	                	}
						else{
							errorPopUp("error: some problem while uploading the existing Appointment letter!");
						}
	              },
	              error: function(xhr,error){    
	            	  $('#cover').hide();
		    		    errorPopUp("error: some problem while uploading the existing Appointment letter!");
	              }
	          });      
        });

        
        //the following one for providing the editing functionality on importing existing appointments
        //modified-- actions to be done on checkbox click
  	    $(".editAppointment").on("click",function(){
  	    	 ParentTR = $(this).parents("tr");    	    	
  	    	//getAllProjectsByStaff(ParentTR.find(".staName").attr("staffid"));
  	    	// introduce some delay

  	    	//to make any number of appointments to be initiated at once by Faculty -- currently left unimplemented have to revisit this 
  	    		if(inBTWAddition){
  		  			//alert("Please add one Appointment at a time.");
  		  			var errMsg ="Please deal with one Appointment at a time.";
  		  			errorPopUp(errMsg); 		  			
  		  			return false;
  		  		}
  	    		inBTWAddition = true;
  	    		
  	    		// display the other button and hide the edit button
  	    		ParentTR.find(".editAppointment").hide();
  	    		ParentTR.find(".submitEditChanges").show();
  	    		ParentTR.find(".cancelEditAppointment").show();
  	    		 	   		
  	    		//to revert to the state of being unchecked
  	    		var originalTrContent = $(ParentTR).html();
  	    		
  	    		var recId = $(this).parents("tr").attr("id");
  	    		
	    				
	    		//var preStartDate = jQuery.trim(ParentTR.find(".stuStartDate .oSpan").text());    
  	    		var preStartDate = jQuery.trim(ParentTR.find(".stuStartDate input").val()); 	   
  	    		var startDateIPStr='<span class="tSpan"><input type="date" name="startDateIP" class="startDateIPInEdit form-control width_83Per" placeholder="Semester StartDate" required /></span>';
  	    		ParentTR.find(".stuStartDate input").hide(); 	
  	    		//ParentTR.find(".stuStartDate .oSpan").hide(); 	
  	    		ParentTR.find(".stuStartDate").append(startDateIPStr);
  	    		//ParentTR.find(".stuStartDate .startDateIPInEdit").val(preStartDate.split("/")[0]+"-"+preEndDate.split("/")[1]+"-"+preEndDate.split("/")[2]);
  	    		ParentTR.find(".stuStartDate .startDateIPInEdit").val(preStartDate);
  	    		
	    		//var preEndDate = jQuery.trim(ParentTR.find(".stuEndDate .oSpan").text()); 	
  	    		var preEndDate = jQuery.trim(ParentTR.find(".stuEndDate input").val());    	
  	    		var endDateIPStr='<span class="tSpan"><input type="date" name="endDateIP" class="endDateIPInEdit form-control width_83Per" placeholder="semester EndDate" required /></span>';
  	    		ParentTR.find(".stuEndDate input").hide();
  	    		//ParentTR.find(".stuEndDate .oSpan").hide();
  	    		ParentTR.find(".stuEndDate").append(endDateIPStr);
  	    		//ParentTR.find(".stuEndDate .endDateIPInEdit").val(preEndDate.split("/")[0]+"-"+preEndDate.split("/")[1]+"-"+preEndDate.split("/")[2]);
  	    		ParentTR.find(".stuEndDate .endDateIPInEdit").val(preEndDate);
	    		/*var preSem = jQuery.trim(ParentTR.find(".stuSem .oSpan").text()); 	    		
  	    		var semIPStr='<span class="tSpan"><input type="text" name="reSemIP" class="reSemIP form-control width_100Per" placeholder="Semester" required disabled value="'+ nextSemYear[0] +'"/></span>';
  	    		ParentTR.find(".stuSem .oSpan").hide();
  	    		ParentTR.find(".stuSem").append(semIPStr);*/
  	    		
  	    		/*var preYear = jQuery.trim(ParentTR.find(".stuSal .oSpan").text()); 	    		
  	    		var yearIPStr='<span class="tSpan"><input type="number" name="reYearIP" class="reYearIP form-control width_100Per" placeholder="Year" required disabled value="'+ nextSemYear[1] +'"/></span>';
  	    		ParentTR.find(".stuYear .oSpan").hide();
  	    		ParentTR.find(".stuYear").append(yearIPStr);*/
  	    		
  	    		var preSal = jQuery.trim(ParentTR.find(".stuSal .oSpan").text()); 	    		
  	    		var salIPStr='<span class="tSpan"><input type="number" name="salaryIP" class="salaryIP form-control width_100Per" placeholder="Salary" required value="'+ parseInt(preSal) +'"/></span>';
  	    		ParentTR.find(".stuSal .oSpan").hide();
  	    		ParentTR.find(".stuSal").append(salIPStr);
  	    		
  	    		var preHours = jQuery.trim(ParentTR.find(".stuWHours .oSpan").text()); 	    	
  	    		var hoursIPStr='<span class="tSpan"><input type="number" name="hoursIP" class="hoursIP form-control width_100Per" placeholder="Hours" required value="'+ parseInt(preHours) +'"/></span>';
  	    		ParentTR.find(".stuWHours .oSpan").hide();
  	    		ParentTR.find(".stuWHours").append(hoursIPStr);
  	    		
  	    		// these are for the additional requirements of tutuion and fee 
  	    		var preTution = jQuery.trim(ParentTR.find(".stuTWaive .oSpan").text()); 	    	
  	    		var tutionIPStr='<span class="tSpan"><input type="number" name="tutionWaiveIP" class="tutionWaiveIP form-control width_100Per" placeholder="Tution Waive" required value="'+ parseInt(preTution) +'"/></span>';
  	    		ParentTR.find(".stuTWaive .oSpan").hide();
  	    		ParentTR.find(".stuTWaive").append(tutionIPStr);
  	    		
  	    		var preNoOfCredits = jQuery.trim(ParentTR.find(".stuNoOfCredits .oSpan").text()); 	    	
  	    		var creditsIPStr='<span class="tSpan"><input type="number" name="noOfCreditsIP" class="noOfCreditsIP form-control width_100Per" placeholder="No.of Credits" required value="'+ parseInt(preNoOfCredits) +'"/></span>';
  	    		ParentTR.find(".stuNoOfCredits .oSpan").hide();
  	    		ParentTR.find(".stuNoOfCredits").append(creditsIPStr);
  	    		
  	    		
  			    var postSelectStr = '<span class="tSpan"> <select class="form-control postIP" name="postIP"><option value="0">select post</option><option value="GRA">GRA</option><option value="SGRA">SGRA</option><option value="GTA">GTA</option><option value="Grader">Grader</option></select></span>';
  	    		var prePost = jQuery.trim(ParentTR.find(".stuPost .oSpan").text());
  	    		ParentTR.find(".stuPost .oSpan").hide();
  	    		ParentTR.find(".stuPost").append(postSelectStr);
  	    		ParentTR.find(".postIP").val(prePost); 
  	    		
  	    		$staffId = loginUID;
  	    		
  	    		if(isAdmin){
  	    			// here the staffID of the selected staff has to be filled
  	    			$staffId = ParentTR.find(".staName").attr("staffid");
  	    		}    		
  	    		   //to populate all the projects
  	    		//projSelectHelperInEdit(ParentTR,prePost); 
  	    		var projSelectStr = '<span class="tSpan"><select class="form-control projSel" name="projSel"></select></span>';		
	  			var preProjName = jQuery.trim(ParentTR.find(".stuProj .oSpan").text());
	  			var preProjId = jQuery.trim(ParentTR.find(".stuProj").attr("projId"));  		
	  			if(ParentTR.find(".stuProj .projSel").length == 0){
	  				ParentTR.find(".stuProj .oSpan").hide();
		  			ParentTR.find(".stuProj").append(projSelectStr);
		  			preProjVal = preProjName+"-"+preProjId ;
		  			ajaxGetProjByStaffIDAddExistingAppInEdit(ParentTR.find(".staName").attr("staffid"));
		  			ParentTR.find(".projSel").val(preProjName+"-"+preProjId); 
	  			}
  				  var postSelectStr = '<span class="tSpan"> <select class="form-control fundingIP" name="fundingIP"><option value="1">ODU</option><option value="2">ODURF</option></select></span>';
    	    	  var preFundType = jQuery.trim(ParentTR.find(".stuFundingType .oSpan").text());
    	    	  ParentTR.find(".stuFundingType .oSpan").hide();
    	    	  ParentTR.find(".stuFundingType").append(postSelectStr);
    	    	  if(preFundType == "ODU"){
    	    		  ParentTR.find(".fundingIP").val("1");
    	    	  }else{
    	    		  ParentTR.find(".fundingIP").val("2");
    	    	  }
  	    });
  	    
  	    
  	    $(".cancelEditAppointment").on("click",function(){
  	    	 ParentTR = $(this).parents("tr");  	
  	    	inBTWAddition = false;
    		ParentTR.find(".tSpan").remove();
    		
    		ParentTR.find(".oSpan").show();
    		ParentTR.find(".reLeaseOffAdmin i").hide();
    		ParentTR.find(".reLeaseOffAdmin .editAppointment").show();
    		ParentTR.find(".stuStartDate input").hide();
    		ParentTR.find(".stuEndDate input").hide();
    		ParentTR.find(".stuStartDate .adminUpdatedStartDate").show();
    		ParentTR.find(".stuEndDate .adminUpdatedEndDate").show();
    		if(adminType == 1){
    			ParentTR.find(".stuProj .oSpan").hide();
    		}
    		
    		
  	    });
  	    
  	    $(".submitEditChanges").on("click",function(){
  	    	var curRowParent = $(this).parents("tr");  	
  	    	var isExit=false;
  	    	curRowParent.find("input.form-control").each(function(inputEle){				
	  	  	if($(this).val() == ""){
	  	  			//alert("Fill out the field: "+ $(this).attr("placeholder") +" Properly before submitting");
	  	  			errorPopUp("Fill out the field: "+ $(this).attr("placeholder") +" Properly before submitting");
	  	  			isExit = true;
	  	  			return false;
	  	  		}						
	  	  	});			
	  	  	if(isExit){
	  	  		return false;
	  	  	}  	
  	  		  	
	  	  	var curPostVal = curRowParent.find(".postIP").val();	
	  	  	
	  	  	if(curPostVal == "GRA" ||curPostVal == "SGRA"){		
	  	  		if (curRowParent.find(".projSel").val() == "None-0"){
	  	  			//alert("There must be a project assigned to a sudent if he is appointed as GRA, Please choose a project");
	  	  			errorPopUp("There must be a project assigned to a sudent if he is appointed as GRA, Please choose a project");
	  	  			return false;
	  	  		}
	  	  	}
	
	
	  	  	curRowParent.find("select.form-control").each(function(inputEle){				
	  	  		if($(this).val() == "0"){
	  	  			//alert("Fill select an option from the filed: "+ $(this).attr("purpose") +" properly before submitting");
	  	  			errorPopUp("Fill select an option from the filed: "+ $(this).attr("purpose") +" properly before submitting");
	  	  			isExit = true;
	  	  			return false
	  	  		}						
	  	  	});
  	    	
  	    	
  	    	var formdata = curRowParent.find("input").serializeArray();
	  	  	var data = {};
	  	  	$(formdata).each(function(index, obj){
	  	  	    data[obj.name] = obj.value;
	  	  	});
	  	  	
	  	  	 
	  	  	 if(isAdmin){
	  	  		 data["staffIP"]= curRowParent.find(".staName").attr("staffid");
	  	  		 data["extAppImpBy"] = loginUID;
	  	  	 }else{
	  	  		 data["staffIP"]= loginUID;
	  	  	 }
	
	  	  	 data["postIP"]= curRowParent.find(".postIP").val();
	  	  	 
	  	  	 data["fundingIP"]= curRowParent.find(".fundingIP").val();
	  	  	
	  	  	 
	  	  	 var selStartMonth = parseInt(data["startDateIP"].split("-")[1]);
	  	  	 	var selSemester="";
	  	  	 	if(selStartMonth >=7 && selStartMonth <=10 ){
	  	  	 		selSemester= "Fall";
	  	  		  }else if(selStartMonth <=3 || selStartMonth>=11 ){
	  	  			selSemester= "Spring";
	  	  		  }else{
	  	  			selSemester= "Summer";
	  	  		  }	 	 
	  	  	 	
	  	  	 
	  	   	if(parseInt(curRowParent.find(".hoursIP").val()) == 0 || parseInt(curRowParent.find(".hoursIP").val()) < 0 || parseInt(curRowParent.find(".hoursIP").val())>20  ){
	  	  		//alert("A Number of hours of work the student must be asigned is more than 0 and must be less than 20.");
	  	  		if(selSemester == "Summer"){
	  	  			if(parseInt(curRowParent.find(".hoursIP").val())>40){
		  	  			errorPopUp("A Number of hours of work the student must be asigned is less than 40");
			  	  		return false;
	  	  			}
	  	  		}else{
		  	  		errorPopUp("A Number of hours of work the student must be asigned is more than 0 and must be less than 20.");
		  	  		return false;
	  	  		}
	  	  	}

	  	  	 data["semesterIP"]=selSemester; 
	  	  	 // to get the current academic year value
	  	  	 if(selSemester == "Spring"){
	  	  		 data["yearIP"] = (parseInt(data["startDateIP"].split("-")[0])-1)+"-"+(parseInt(data["startDateIP"].split("-")[0]));
	  	  	 }else if (selSemester == "Fall"){
	  	  		 data["yearIP"] = data["startDateIP"].split("-")[0]+"-"+(parseInt(data["startDateIP"].split("-")[0])+1);
	  	  	 }else{
	  	  		 data["yearIP"] = (parseInt(data["startDateIP"].split("-")[0])-1)+"-"+(parseInt(data["startDateIP"].split("-")[0]));
	  	  	 }
	  	  	 
	  	  	 var projIp = "",projId="", projectConfirmStr = "";

	  	  			projIp = curRowParent.find(".projSel").val().split("-")[0];
	
	  	  			projId= curRowParent.find(".projSel").val();
	  	  			
	  	  			if(  curRowParent.find(".projSel").val().split("-")[1]!= "0"){
	  	  				projectConfirmStr = " on a Budget Code "+projIp.toUpperCase();
	  	  			}			
	  	  			
	  	  	 data["projIP"]= projIp.toUpperCase();
	  	  	 data["projID"]= projId;
	  	  	 
	  	  	 data["tutionWaiveIP"]= parseInt(curRowParent.find(".tutionWaiveIP").val());
	  	  	 data["noOfCreditsIP"]= parseInt(curRowParent.find(".noOfCreditsIP").val());
	  	  	 if(projIp == "")
	  	  	 {
	  	  		 //alert("please give a proper name for the project");
	  	  		 errorPopUp("please give a proper name for the project");
	
	  	  		 return;
	  	  	 }
	  	  	 var fundingDet = "";
	  	  	 if(data["fundingIP"] == "1"){
	  	  		 fundingDet = "funded by ODU";
	  	  	 }else{
	  	  		 fundingDet = "funded by ODU Research Fundation";
	  	  	 }
	  	  	data["firstNameIP"] = curRowParent.find(".stuName").text().split(",")[0];
	  	  	data["lastNameIP"] = curRowParent.find(".stuName").text().split(",")[1];
	  	  	data["uinIP"] = curRowParent.find(".stuUIN").text();
	  	  	data["appointmentID"] = curRowParent.attr("id");
	  	  	data["extAppImpBy"] = loginUID;
	  	  	data["recPosID"]= curRowParent.attr("recPosId");
	  	  	
	  	  	
	  	  	// var confirmationStr =""; 
	  	  		// confirmationStr = " Are you sure to edit  the existing  appointment for the student "+ data["firstNameIP"]+" "+data["lastNameIP"]+"("+data["uinIP"]+")";
	  	  		// confirmationStr+= " by prof "+ curRowParent.find(".staName").text()+" as a "+data["postIP"]+ " for "+data["semesterIP"]+" "+data["yearIP"]+projectConfirmStr+" "+fundingDet+ " and pay him "+data["salaryIP"]+" ?";  	 
	  	  		var confirmationStr ="Confirm Changes.";
  	  	 		var proceed = confirm(confirmationStr);
  	  	 		if(proceed){
  	  	 			$.post('/'+SERVERHOST+'_StudentAppointmentSystem/editExistingAppointment.php',data,function (data){
	  	  	 			$('#cover').hide();
	  	  	 			if($.trim(data)== "success"){
	  	  	 			inBTWAddition = false;
  	  	 					successPopUpWithRD("Existing student appointment updated successfully.");
	  	  	 			}else {
	  	  	 				errorPopUp("Some problem while updating the existing student appointment");
	  	  	 			}
  	  	 			});
  	  	 		
  	  	 		}
  	    	
  	    });
  	    
  	  $(document).on("change",".adminUpdatedStuPost",function(){
  		 if($(this).val() == "SGRA"){
  			$(this).parents("tr").find(".adminUpdatedFT").val("1");
  			$(this).parents("tr").find(".stuProj select option[purp='SGRA']").show();
  			$(this).parents("tr").find(".stuProj select option[purp='GRA']").hide(); 
  		 }else{
  			$(this).parents("tr").find(".adminUpdatedFT").val("2");
  			$(this).parents("tr").find(".stuProj select option[purp='SGRA']").hide();
  			$(this).parents("tr").find(".stuProj select option[purp='GRA']").show(); 
  		 }
  	  });
  	  
  	  

  	 $(document).on("change",".adminValidationCloseButt",function(){
  		 window.location.reload();
  	  });
  	  
  	    
 
	});
}


function ajaxUpdateDetByAdmin1SinglePosition(data){
	$('#cover').show();
	$.post('/'+SERVERHOST+'_StudentAppointmentSystem/updateDetByAdmin1.php',data,function (data){
		//$.post('/Prod_StudentAppointmentSystem/updateDetByAdmin1.php',data,function (data){

			 $('#cover').hide();
			    if($.trim(data) == "success"){
			    	parentTR.find(".releaseOffAdmin").attr("isfinanceverified","1");
			    	//parentTrUnderVer.find(".stuTWaive input").attr("disabled",true);
			    	//parentTrUnderVer.find(".stuNoOfCredits input").attr("disabled",true);
			    	parentTR.find(".releaseOffAdmin button").attr("disabled",true);
			    	parentTR.find(".releaseOffAdmin button").attr("title","Funds Availability Verified Success already");
			    	$("#fundsvalidateButt_LPFA").hide();
			    	$(".fundsValidateCB").prop("disabled",true);
    				successPopUp("Funds Availability verification is updated successfully");
    			 }else{
    					//alert("error: some problem while updating the I9 Expiry");
    					errorPopUp("some problem while updating the Funds Availability verification.");
    			 }
		});	
}



function ajaxUpdateDetByAdmin1MultiPosition(dataArray){
	var jsonData = {"data":JSON.stringify({"dataset":dataArray})};
	$('#cover').show();
	$.post('/'+SERVERHOST+'_StudentAppointmentSystem/updateDetByAdmin1MultiPosition.php',jsonData,function (data){
		//$.post('/Prod_StudentAppointmentSystem/updateDetByAdmin1.php',data,function (data){
			 $('#cover').hide();
			    if($.trim(data) == "success"){
			    	
			    	$("."+parentTR.attr("recposid")).each(function(){
			    		parentTR.find(".releaseOffAdmin").attr("isfinanceverified","1");		    	
				    	parentTR.find(".releaseOffAdmin button").attr("disabled",true);
				    	parentTR.find(".releaseOffAdmin button").attr("title","Funds Availability Verified Success already");		
			    	});
			    	
			       	//changed - multiple positions	
			    	/*parentTR.find(".releaseOffAdmin").attr("isfinanceverified","1");		    	
			    	parentTR.find(".releaseOffAdmin button").attr("disabled",true);
			    	parentTR.find(".releaseOffAdmin button").attr("title","Funds Availability Verified Success already");			    	
			    	relatedParentTR.find(".releaseOffAdmin").attr("isfinanceverified","1");			    	
			    	relatedParentTR.find(".releaseOffAdmin button").attr("disabled",true);
			    	relatedParentTR.find(".releaseOffAdmin button").attr("title","Funds Availability Verified Success already");*/

			    	$("#fundsvalidateButt_LPFA").hide();
			    	$(".fundsValidateCB").prop("disabled",true);
			    	successPopUpWithRD("Funds Availability verification is updated successfully");
    				//successPopUp("Funds Availability verification is updated successfully");
    			 }else{
    					//alert("error: some problem while updating the I9 Expiry");
    					errorPopUp("some problem while updating the Funds Availability verification.");
    			 }
		});	
}



function existingFileAttachPopUpRD(msg){
	$('#existAppFileAttModal .modal-body').html("<p>"+msg+"</p>");
	$("#existAppFileAttModal").modal("show");
}

start();


















