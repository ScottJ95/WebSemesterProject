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
<title>Chats</title>
<link href="thread.css" rel="stylesheet" type="text/css" media="screen">
<link href="Chats.css" rel="stylesheet" type="text/css" media="screen">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="Chats.js"></script>
</head>


<body onload="getUserID();">
<div class = "container"> 
	<div class = "left">
    	<div class = "userImage" id = "userImage"> </div>
        <div id = "userName">  </div>
        <button class="navButton" id="home" onclick="main();">Home</button>
    	<button class="navButton" id="profile" onclick="editProfile();">Profile</button>
    	<button class="navButton" id="me" onclick="editGroup();">Edit Group</button>
    	<button class="navButton" id="signOut" onclick="logout();">Log Out</button>
    </div>
    <div class = "center">
    	<div id = "groupContainer" class="style-5" style="overflow-y: scroll;">    
                </div>
    		<div id ="newMessage"> 
              	<textarea id="inputMessage" class="inputMessage"> </textarea>
    		 </div>
       <div class = "buttonContainer">
                <button id="btnSend" class="btn btn-action" onClick="submitMessage();">Send</button>
                <button id="btnAttach" class="btn btn-action">Attach</button>
       
       </div>
    </div>
    <div class = "right">
    
    <div class = "members"> 
    	<div class = "groupImage" id = "groupImage"></div>
    	<div class = "memberText">MEMBERS</div>
        <div class = "memberCount"> Total members: <label id = "numMem"></label>
        </div>
        <div class = "memberContainer">
        	<ul id = "memberList">
            </ul>
        </div>
    </div>

        </div>

    </div>
     <div class = "calendarSpace">
     	
        <iframe onload = "getSource();" id = "calendar">
        </iframe>
    </div>
</body>
</html>
