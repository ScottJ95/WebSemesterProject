<!doctype html>
<?php
require_once('DBFuncs.php');
require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');
if(checkSession()){
    header('Location: http://elvis.rowan.edu/~jefferys0/web/WebSemesterProject/main.php'); 
}

?>

<html>
<head>
<meta charset="utf-8">
<title>Login</title>
<link href="login.css" rel="stylesheet" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="login.js"></script>
</head>



<body>
<div class = "container"> 
<div class = "infoText"> Project Reddit 2.0 </div>
<div class = "textBoxField"><input class = "inputBox" id="emailBox" type="email" placeholder="Username"></div><br>
<div class = "textBoxField"><input class = "inputBox" id="passwordBox" type="password" placeholder="Password"></div><br>
<div class = "validate" id = "popup"></div>

<div class = "buttonContainer">
	<button onclick="login()" class = "homebuttons"  id = "btnLogin">LOGIN</button>
	<button onclick="newUser()" class = "homebuttons"  id = "newUser">NEW USER?</button>
	<button onclick="forgotPassword()" class = "homebuttons"  id = "forgotPass">FORGOT PASSWORD</button>
</div>
</div>
</body>
</html>
