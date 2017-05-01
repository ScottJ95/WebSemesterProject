/*
 * Ryan Hudson 
 * Javascript functions for registration
 */
function register() {
    var username = document.getElementById("userBox").value;
    var email = document.getElementById("emailBox").value;
    var password = document.getElementById("passwordBox").value;
    var confirmPassword = document.getElementById("confirmPasswordBox").value;

    document.getElementById("passMessage").innerHTML = "";
    document.getElementById("passconfMessage").innerHTML = "";
    document.getElementById("accountregMessage").innerHTML= "";

    //Basic checks for empty fields/mismatched passwords
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
        //POST to DBFuncs
    	$.ajax({ 
    	    type: 'POST',
    	    url:  'DBFuncs.php',
            data: { functionName:'checkUserRegistration',argument:[username,email,password] },
                
            //DBFuncs echos a number based on what happened
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

//Called on key up for the username form field on register.html
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

//Called on key up for the email form field on register.html
function checkEmail()
{
    var email = document.getElementById("emailBox").value;
    var googleEmail = /@gmail.com/;
    var rowanEmail = /@students.rowan.edu/;
    if(email != "") {
        //regex check for gmail or rowan, we restrict to gmail and rowan email for the Google calendar
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

//Just returns user to login page
function cancel(){
    window.location.href = "login.html";
}
