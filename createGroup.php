<?php
session_start();
require_once('DBFuncs.php');

$_SESSION['userID'] = 1;

if(!checkSession()){
        header('Location: http://elvis.rowan.edu/~jefferys0/');
        exit;
}

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
  <script type="text/javascript" src="./checkGroupForm.js"></script>
</head>

<!-- createGroup.php is the form page for creating a new group.
 Status: Not yet finished -->



<body>


<?php

require_once('DBFuncs.php');
require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');

$dbh = ConnectDB();


?>


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
