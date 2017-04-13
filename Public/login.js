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
			   else
			   {
				   document.getElementById("popup").innerHTML="Log In";
				  //Set Session var
				//Send to main menu
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
                   data: {functionName:'checkEmail',argument:email},

                   success: function (response) {
                           if(response == 0){
                                document.getElementById("popup").innerHTML="Email cannot be found.";
                                }
                           else
                           {
				   document.getElementById("popup").innerHTML="Email has been sent.";

				   //Send Email
                           }
                   }

                });
	}
}
function passwordChange(){

}

function passwordCancel(){
	window.location.href = "login.html";
}

