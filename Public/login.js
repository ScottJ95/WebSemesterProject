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
	
}
