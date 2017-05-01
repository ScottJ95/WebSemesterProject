function login() {
	var username = document.getElementById("emailBox").value;
	var password = document.getElementById("passwordBox").value;

	if(username=="")
	{
		document.getElementById("popup").innerHTML = "Please enter a valid email";
	}
	else if(password=="")
	{
		document.getElementById("popup").innerHTML = "Please enter your password";
	}
	else
	{
                $.ajax({ 
                   type: 'POST',
                   url:  'DBFuncs.php',
                   data: { functionName:'checkUsername',argument:[username,password] },
                
                   success: function (response) {
			   if(response == 0){
				document.getElementById("popup").innerHTML="Username or password is incorrect.";
			   }
			   else if(response == 1)
			   {
				   document.getElementById("popup").innerHTML="Log In";

				  //Set Session var
				window.location.href = "Main.php";
			   }
			   else //Password needs to be changed
			   {	
				window.location.href = "newpassword.html";
			   }
		   }
         
		});
	

	}

}

function newUser(){
	window.location.href = "register.html";
}

function forgotPassword(){
	window.location.href = "comfirmEmail.html";	
}

function comfirmCancel(){
	window.location.href = "login.html";
}

function comfirmSend(){

	var email = document.getElementById("emailBox").value;

        if(email=="")
        {
                document.getElementById("popup").innerHTML = "Please enter your email";
        }
	else
	{
		$.ajax({
                   type: 'POST',
                   url:  'DBFuncs.php',
                   data: {functionName:'checkEmail',argument:[email,'PASSWORD']},

                   success: function (response) {
                           if(response == 0){
                                document.getElementById("popup").innerHTML="Email cannot be found.";
                                }
                           else if(response == 1)
                           {
				   document.getElementById("popup").innerHTML="Email has been sent.";
                           }
			   else{
				document.getElementById("popup").innerHTML="Please wait 5 minutes to reset your password.";
			   }

                   }

                });
	}
}
function passwordChange(){
	var password1 = document.getElementById("passwordBox").value;
        var password2 = document.getElementById("confirmPasswordBox").value;

        if(password1=="")
        {
                document.getElementById("popup").innerHTML = "Please enter your new password";
        }
        else if(password2=="")
        {
                document.getElementById("popup").innerHTML = "Please confirm your password";
        }
	else if(password1!=password2)
	{
		document.getElementById("popup").innerHTML = "Passwords dont match please enter the same password in both feilds";
	}
        else
        {
                $.ajax({
                   type: 'POST',
                   url:  'DBFuncs.php',
                   data: { functionName:'changePassword',argument:password1 },

                   success: function (response) {
                           if(response != 1){
                                document.getElementById("popup").innerHTML="Something wrong happened";
                           }
                           else
                           {
                                   document.getElementById("popup").innerHTML="Password has changed. Logging in now.";
                           	window.location.href = "Main.html";
			   }
			}
                });

        }
}

function passwordCancel(){
	window.location.href = "login.html";
}

