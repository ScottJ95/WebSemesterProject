<!doctype html>
<?php
require_once('DBFuncs.php');
require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');

if(!checkSession()){
    exit;
}
?>


<html>
<head>
<meta charset="utf-8">
<title>Reddit 2.0</title>
<link href="thread.css" rel="stylesheet" type="text/css" media="screen">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="Main.js"></script>
</head>
<body onload="start();">
<div class = "container"> 
	<div class = "left">
    		<div class = "userImage" id = "userImage"> </div>
        	<div id = "userName"> Your Username Here</div>
        	<button class="navButton" id="home" onclick="main();">Home</button><br>
    		<button class="navButton" id="profile" onclick="editProfile();">Profile</button>
    		<button class="navButton" id="me">SomethingElseHere</button>
    		<button class="navButton" id="signOut" onclick="logout();">Log Out</button>

    	</div>
    <div class = "realcenter">
    	<div class = "groupContainer" id = "groups">
		<h1 style="color:white; text-align:center; font-family:Avenir;">Groups</h1>
		<button class="tabButton" id="create" onclick="createGroup();">Create a group</button>
		<button class="tabButton" id="join" onclick="joinGroup();">Join a group</button>
		<br>
		<div class="tab">
		<button class="tabButton" id="all" onclick="Groups(event,'0');">All</button>
		<button class="tabButton" id="created" onclick="Groups(event,'2');">Created</button>
		<button class="tabButton" id="In" onclick="Groups(event,'1');">In</button>
		</div>
		<div class="tabcontent  style-5" id="groupList">


		</div>

	</div> 
    </div>


</div>
</body>
</html>
