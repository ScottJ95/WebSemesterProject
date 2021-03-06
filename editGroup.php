<?php 

session_start();

require_once('DBFuncs.php');
require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');

$dbh = ConnectDB();

if(!checkSession()){
        exit;
}

$_SESSION['projectTime'] = time();

if(!isset($_GET['groupID'])) {
	echo '<script type="text/javascript">'; 
	echo 'alert("Group not Specified");'; 
	echo 'window.history.back();';
	echo '</script>';
	exit;
}

$groupData = getGroupbyID($dbh,$_GET['groupID']);

if($groupData == NULL){
	echo '<script type="text/javascript">';
        echo 'alert("Group does not exist");';
        echo 'window.history.back();';
        echo '</script>';
        exit;
}

if($_SESSION['userID'] == 1){
        echo 'Welcome Admin';
}

else if(!checkCreator($dbh, $_SESSION['userID'], $_GET['groupID'])){
	echo '<script type="text/javascript">';
        echo 'alert("You are not the creator of this group");';
        echo 'window.history.back;';
        echo '</script>';
	exit;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
 "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>Edit Your Group</title>
  <meta http-equiv="Content-Type"
        content="application/xhtml+xml; charset=UTF-8" />
  <meta name="Author" content="Scott Jeffery" />

  <link rel="stylesheet" href="profile.css" />
  <script type="text/javascript" src="./AjaxFunctions.js"></script>
   <script type="text/javascript"
	  src="http://code.jquery.com/jquery-1.9.0.min.js"> </script>
   <script type="text/javascript" src= "./checkGroupEdit.js"></script>
</head>

<body>

<script type="text/javascript">
function main(){
       var groupURL = urlSample = window.location.href.split('?')[1];
       window.location.href = "Chats.php?"+groupURL; 
}


</script>

<?php

require_once('DBFuncs.php');
require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');

$dbh = ConnectDB();

//This is test code to get the current session userID.
if(isset($_SESSION['userID']) && isset($_GET['groupID']))

{
    $_SESSION['groupIDEdit'] = $_GET['groupID'];
    $userData = getUserByID($dbh, $_SESSION['userID']);
    $groupData = getGroupByID($dbh, $_GET['groupID']);
    $groupImage = getGroupImage($dbh, $groupData[0]->group_ID);

    echo '<h1 class = "header" id = "header"> Edit Group ' . $groupData[0]->group_name . '</h1>';
    echo '<div class = "container">';
    echo '<div class="left">';
    echo '<button class="navButton" id="home" onclick="main();">Return</button>';
    echo "<p> Current Information: </p>\n";
    echo "<p> Current Group Name: " 
	. $groupData[0]->group_name . "</p>\n";

    $_SESSION['groupNameEdit'] = $groupData[0]->group_name;

    echo "<p> Current Group Subject: " 
	. $groupData[0]->group_subject . "</p>\n";
    echo "<p> Current Group Description: " 
	. $groupData[0]->group_description . "</p>\n";
    echo "<p> Current Group Image: </p> \n";

    if($groupData[0]->image_ID == NULL){
        echo '<img id="groupImage" src="/~jefferys0/web/WebSemesterProject/defaultIcon.svg"' .   
            'alt= "Default" style="width:304px;height:228px;">';
    }
    else{
         echo '<img id="groupImage" src="'. $groupImage[0]->image_location .
	     '"alt="' . $groupImage[0]->image_name . '" style="width:304px;height:228px;">';
    }
	echo '</div>';
}
else{
    echo "<p> How did you get here? -LevelLord </p>\n";
}
?>
<div class = "center" id="center">
<form enctype="multipart/form-data" action = "updateGroup.php" method="post" onsubmit = "return checkForm(true);">

<fieldset style=" margin:auto; margin-top:300px; width:500px">
<legend> Edit Your Group Info </legend>

<table title = "Edit Group Input" id="editGroupTable">
    <tr>
	<th> Group Name:
	</th>	
	    <td> <input type = "text" name="groupName" 
		id="groupName" onkeyup="checkName(true);"/>
	    </td>
	<span id="name_status"></span>
	</tr>

	<tr> 
	<th> Subject: 
	</th> 

	<td><select name="groupSubject" id="groupSubject"size="1" title="Select Subject">
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
		<td><textarea name="description" 
			id="description" cols="50" rows="4" 
			maxlength = "250" 
			onkeyup = "descriptionCount();"  
			placeholder = "Type Description."></textarea>
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
    <tr>
        <td>
        </td>
        <td> <button onclick="deleteGroupCheck()";>Delete Group </button>

    </table>
    </fieldset>
	</form>
</div>
 

</body>

</html>
