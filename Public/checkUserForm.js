function checkProfile() {
   
    var password = document.getElementById("passwordBox").value;
    var confirmPassword = document.getElementById("confirmPasswordBox").value; 
    console.log(confirmPassword);
    document.getElementById("passMessage").innerHTML = "";
    document.getElementById("passconfMessage").innerHTML = "";
    if(checkUsername() && checkEmail()){ 
        if(password != confirmPassword)
        {
    	    document.getElementById("passconfMessage").innerHTML = "Passwords do not match";
            return false;
        }
        else
        {
            return true;
        }
    }
    else{
        return false;
    }
}

function checkUsername()
{
    var username = document.getElementById("userBox").value;
    if(username != "") {
    	$.ajax({
  	    type: 'POST',
       	    url:  'DBFuncs.php',
       	    data: { functionName:'checkUsernameReg',argument:[username] },
	    success: function (response){
 	        if(response == 0) {
	    	    document.getElementById("userMessage").innerHTML="Username is already in use";
                    return false;
	        }
                else {
	    	    document.getElementById("userMessage").innerHTML="Username is avaliable";
                    return true;
	        }
	    }
	});
    }
    else{
        return true;
    }
}

function checkEmail()
{
    var email = document.getElementById("emailBox").value;
    var googleEmail = /@gmail.com/;
    var rowanEmail = /@students.rowan.edu/;
    if(email != "") {
	if(googleEmail.test(email) == true || rowanEmail.test(email) == true){
            $.ajax({
                type: 'POST',
	        url:  'DBFuncs.php',
                data: { functionName:'checkEmailReg',argument:[email] },
                success: function (response){
                    if(response == 0) {
     	                document.getElementById("emailMessage").innerHTML="  Email is already in use";
                        return false;
	            }      
                    else {
	    	        document.getElementById("emailMessage").innerHTML="  Email is valid";    
                        return true;  
	            }
	        }            	
            });
        }
        else {
            document.getElementById("emailMessage").innerHTML="  Email must be Google or Rowan account";
            return false;
        }
    }
    else{
        return true;
    }
}
