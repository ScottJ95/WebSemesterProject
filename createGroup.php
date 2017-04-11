<?php

session_start();

$_SESSION['projectTime'] = time();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
 "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>Create New Group</title>
  <meta http-equiv="Content-Type"
        content="application/xhtml+xml; charset=UTF-8" />
  <meta name="Author" content="Scott Jeffery" />

  <link rel="stylesheet" href="tagline.css" />
  <script type="text/javascript" src="./AjaxFunctions.js"></script>
   <script type="text/javascript"
          src="http://code.jquery.com/jquery-1.9.0.min.js"> </script>
</head>

<!-- createGroup.php is the form page for creating a new group.
 Status: Not yet finished -->



<body>


<?php

//TODO: Preprocess description
//TODO: Form checks
//TODO: Get current UserID
//TODO: Page formatting

//SESSION CHECKING

$_SESSION['userID'] = 2;

require_once('DBFuncs.php');
require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');

$dbh = ConnectDB();

//This is test code to get the current session userID.
//TODO: PUT THIS CODE IN DBFUNCS.PHP
if(isset($_SESSION['userID'])){

	$userData = getCurrentUser($dbh, $_SESSION['userID']);
	echo $user->student_ID;
	foreach($userData as $user){
		echo "<p> Hi User Num " . $user->student_id . ", " 
			. $user->username . "</p>";
	}
	//echo "<p> Hi User Num " . $_SESSION['userID'] . "</p> \n";
}
else{
	echo "<p> How did you get here? -LevelLord </p>\n";
}


?>

<script type="text/javascript">

//Check the current group name. This is an AJAX call. 
//I found this source code online at: 
//http://talkerscode.com/webtricks/check%20username%20and%20email%20availability%20from%20database%20using%20ajax.php
//TODO: Have this be in a seperate file?
function checkName(){

	var groupName = $("#groupName").val();

	console.log(groupName); //Debugging. Comment out.

	if(groupName) { //If it's not null, let's check it.
		//Jquery to setup AJAX we can give it a bunch of stuff
		//type: post or get?
		//url: What script do we run?
		//data: What data are we sending?
		//success or failure callbacks
		//This is equivalent to jquery.post(), but this made more sense to me.
		//https://api.jquery.com/jquery.post/
		$.ajax({ 
		   type: 'post',
	           url:  'checkGroup.php',
		   data: {
		   	group_name:groupName,
		   },
		
		   success: function (response) {
			   //Call was successful, so do this function
			   //First, set the name_status html to the response.
			$( '#name_status').html(response);
			//Check the response so we can return the check
			if(response == "OK") {
				return true;
			}

			else {
				return false;
			}
		}
		});//End Ajax
	} //End If

	else //Nothing typed into the group name
	{
		$( '#name_stats').html("");
		alert("Please enter a group name!");
		return false;
	}
}

function checkDescription(){
	var descriptionText = $('#description').val();

	if(descriptionText === ""){
		console.log("I got here");
		alert("Please enter a description");
		return false;
	}
	else{
		return true;
	}

}

function checkForm() {
	if(checkName()) {
		if(checkDescription()){
			return true;
		}
		else{
			return false;
		}
	}
	else {
		return false;
	}

}

function descriptionCount() {
	var descriptionText = $("#description").val();
	$("#description_charCount").html(250 - descriptionText.length);
}

</script>

<h1> Create a New Group </h1>

<p> This is a form that lets you make a new group. 
</p>

<form enctype="multipart/form-data" action="submitGroup.php" method="post" onsubmit = "return checkForm();">
<fieldset>
<legend> Create A New Group </legend>
<table title="Create Group Input">
	<tr>
		<th> Group Name:
		</th>
		<td> <input type="text" name="groupName" id="groupName" onkeyup = "checkName();"/>
		</td>
		<span id= "name_status"></span>
	</tr>
	
	<tr>
		<th> Subject: 
		</th>
		<td> <select name="groupSubject" id="groupSubject"size="1" title="Select Subject">
			<option value = "Calculus">Calculus</option>
			<option value = "Biology">Biology</option>
			<option value = "Chemistry">Chemistry</option>
			<option value = "Physics">Physics</option>
			<option value = "ComputerScience">Computer Science</option>
			<option value = "Psychology">Psychology</option>
			<option value = "History">History</option>
			</select>
		</td>
	</tr>

	<tr>
		<th>Description:
		</th>
		<td><textarea name="description" id="description" cols="50" rows="4" maxlength = "250" onkeyup = "descriptionCount();"  placeholder = "Type Description."></textarea>
		</td>
		<td> <span id="description_charCount"> 250 </span>
		</td>
	</tr>

	<tr>
		<th>Photo:
		</th>
		<td> <input type = "file" name="groupImage" accept = "image/jpg, image/jpeg, image/bmp, image/png"/>
		</td>
	</tr>

	<tr>
		<td>
		</td>
		<td> <input type="submit"/>
		</td>

	</tr>

	</table>
	</fieldset>
	</form>

<p> Here we go bois </p>


<div id="tagline">
 <a href="./hw2-list.html"
    title="Link to homework list">
    Scott J.
 </a>

<span style="float: right;">
<a href="http://validator.w3.org/check/referer">HTML5</a> /
<a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3">
    CSS3 </a>
</span>
</div>

</body>


</html>
