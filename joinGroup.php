<?php
session_start();
require_once('DBFuncs.php');



if(!checkSession()){
        header('Location: http://elvis.rowan.edu/~jefferys0/');
        exit;
}


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
 "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>Join a Group</title>
  <meta http-equiv="Content-Type"
        content="application/xhtml+xml; charset=UTF-8" />
  <meta name="Author" content="Jacob Kershaw" />

  <script type="text/javascript" src="./AjaxFunctions.js"></script>
   <script type="text/javascript"
	  src="http://code.jquery.com/jquery-1.9.0.min.js"> </script>
  <script type="text/javascript" src="./checkGroupForm.js"></script>
<link href="thread.css" rel="stylesheet" type="text/css" media="screen">   


</head>




<body>


<?php

require_once('DBFuncs.php');
require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');

$dbh = ConnectDB();


?>


<h1> Join a Group </h1>

<p> This is a form that lets you join a group. 
</p>

<form enctype="multipart/form-data" action="Main.php" method="post" onsubmit = "return checkJoinForm();">
<fieldset>
<legend> Search For A Group </legend>
<table title="Create Group Input" id= "createGroupTable">
    <tr>
        <th> Group Name:
        </th>
        <td> <input type="text" name="groupName" id="groupName"/>
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
        <td>
        </td>
	<td> <input type="submit"/>
	<input type="button" name="Cancel" value="Cancel" onClick="window.location='Main.php';" />
	</td>

    </tr>

    </table>
    </fieldset>
    </form>




</body>
</html>
