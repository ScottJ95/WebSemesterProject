function register() {
    var username = document.getElementById("userBox").value;
    var email = document.getElementById("emailBox").value;
    var password = document.getElementById("passwordBox").value;
    var confirmPassword = document.getElementById("confirmPasswordBox").value;

    document.getElementById("passMessage").innerHTML = "";
    document.getElementById("passconfMessage").innerHTML = "";
    document.getElementById("accountregMessage").innerHTML= "";
    if(username=="")
    {
    	document.getElementById("userMessage").innerHTML = "Please enter a valid username";
    }
    else if(email=="")
    {
       	document.getElementById("emailMessage").innerHTML = "Please enter a valid email";
    }
    else if(password=="")
    {
        document.getElementById("passMessage").innerHTML = "Please enter your password";
    }
    else if(confirmPassword=="")
    {
    	document.getElementById("passconfMessage").innerHTML = "Please confirm your password";
    }
    else if(password != confirmPassword)
    {
    	document.getElementById("passconfMessage").innerHTML = "Passwords do not match";
    }
    else
    {
    	$.ajax({ 
    	    type: 'POST',
    	    url:  'DBFuncs.php',
            data: { functionName:'checkUserRegistration',argument:[username,email,password] },
                
            success: function (response) {
                if(response == 0) {
                    document.getElementById("userMessage").innerHTML="Username or Email field contains illegal characters";
                }
	        else if(response == 1) {
                    document.getElementById("userMessage").innerHTML="Username already in use";
	        }
	        else if(response == 2) {
	            document.getElementById("emailMessage").innerHTML="Email is already in use";
	        }
	        else {
	            document.getElementById("accountregMessage").innerHTML="Your account has been registered. Please verify your account by clicking the activation link that has been emailed to you.";
	        }
	    }
	});
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
	        }
                else {
	    	    document.getElementById("userMessage").innerHTML="Username is avaliable";
	        }
	    }
	});
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
     	                document.getElementById("emailMessage").innerHTML="Email is already in use";
	            }      
                    else {
	    	        document.getElementById("emailMessage").innerHTML="Email is valid";      
	            }
	        }            	
            });
        }
        else {
            document.getElementById("emailMessage").innerHTML="Email must be Google or Rowan account";
        }
    }
}


function cancel(){
    window.location.href = "login.html";
}
