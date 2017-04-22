function register() {
	var username = document.getElementById("userBox").value;
	var email = document.getElementById("emailBox").value;
	var password = document.getElementById("passwordBox").value;
	var confirmPassword = document.getElementById("confirmPasswordBox").value;

	if(username=="")
	{
		document.getElementById("popup").innerHTML = "Please enter a valid username";
	}
        else if(email=="")
        {
                document.getElementById("popup").innerHTML = "Please enter a valid email";
        }
        else if(password=="")
        {
                document.getElementById("popup").innerHTML = "Please enter your password";
        }
	else if(confirmPassword=="")
	{
		document.getElementById("popup").innerHTML = "Please confirm your password";
	}
	else if(password != confirmPassword)
	{
		document.getElementById("popup").innerHTML = "Passwords do not match";
	}
	else
	{
                $.ajax({ 
                   type: 'POST',
                   url:  'DBFuncs.php',
                   data: { functionName:'checkUserRegistration',argument:[username,email,password] },
                
                   success: function (response) {
			   
			   if(response == 0) {
				   document.getElementById("popup").innerHTML="Username already in use";
		   	   }
			   else if(response == 1) {
				   document.getElementById("popup").innerHTML="Email is already in use";
			   }
			   else {
				   document.getElementById("popup").innerHTML="Account registered";
			   }
		   }
         
		});
	

	}

}

checkUsername()
{
	var username = document.getElementById("userBox").value;
	if(username != "") {
		$.ajax({
        	    type: 'POST',
        	    url:  'DBFuncs.php',
        	    data: { functionName:'checkUsernameReg',argument:[username] },
		    success: function (response){
 			if(response == 0) {
				document.getElementById("popup").innerHTML="Username is already in use";
			}
			else {
				document.getElementById("popup").innerHTML="Username is avaliable";
			}
		    }
		});

	}
}

checkEmail()
{
        var email = document.getElementById("emailBox").value;
	var googleEmail = /@gmail.com/;
	var rowanEmail = /@students.rowan.edu/;
        if(email != "") {
		if(googleEmail.test(email) == true && rowanEmail.test(email) == true){
                	$.ajax({
                    	    type: 'POST',
                   	    url:  'DBFuncs.php',
                    	    data: { functionName:'checkEmailReg',argument:[email] },
                            success: function (response){
                                if(response == 0) {
                                    document.getElementById("popup").innerHTML="Email is already in use";
                                }
                                else {
                                    document.getElementById("popup").innerHTML="Email is valid";
                                }
                            }
                    	});
		}
		else {
		    document.getElementById("popup").innerHTML="Email is valid";
		}
	}
}


function cancel(){
	window.location.href = "login.html";
}
