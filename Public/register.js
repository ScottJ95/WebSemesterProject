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

function cancel(){
	window.location.href = "login.html";
}
