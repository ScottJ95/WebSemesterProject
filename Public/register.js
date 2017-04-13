function register() {
	var username = document.getElementById("userBox").value;
	var email = document.getElementById("emailBox".value;
	var password = document.getElementById("passwordBox").value;
	var confirmPassword = document.getElementById("confirmPasswordBox").value;

	if(username=="")
	{
		document.getElementById("popup").innerHTML = "Please enter a valid username";
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
	else
	{
                $.ajax({ 
                   type: 'POST',
                   url:  'DBFuncs.php',
                   data: { functionName:'checkUseRegistration',argument:[username] },
                
                   success: function (response) {
			   if(response == 0){
				document.getElementById("popup").innerHTML="Username is avaliable";
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

function cancel(){
	window.location.href = "login.html";
}
