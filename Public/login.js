function login() {
	var email = document.getElementById("emailBox").value;
	var password = document.getElementById("passwordBox").value;

	if(email=="")
	{
		document.getElementById("popup").innerHTML = "Please enter a valid email";
	}
	if(password=="")
	{
		document.getElementById("popup").innerHTML = "Please enter your password";
	}



	
}
