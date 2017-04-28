<?php 

session_start();

//TODO REMOVE THIS
$_SESSION['userID'] = 1;
require_once('DBFuncs.php');
require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');

$dbh = ConnectDB();

if(!checkSession()){
        header('Location: http://elvis.rowan.edu/~jefferys0/');
        exit;
}

$_SESSION['projectTime'] = time();

$currUser = $_SESSION['userID']

$userData = getUserByID($dbh,$currUser);

if($userData == NULL || $userData[0]->student_ID != $currUser){
        $_SESSION['userID'] = NULL;
        $_SESSION['projectTime'] = NULL;
        echo '<script type="text/javascript">';
        echo 'alert("Either the profile does not exist or something went wrong")';
        echo 'window.location.href = "http://elvis.rowan.edu/~jefferys0/";';
        echo '</script>';
        exit;
}

?>

!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
 "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>Edit Your Profile</title>
  <meta http-equiv="Content-Type"
        content="application/xhtml+xml; charset=UTF-8" />
  <meta name="Author" content="Scott Jeffery" />

  <link rel="stylesheet" href="tagline.css" />
  <script type="text/javascript" src="./AjaxFunctions.js"></script>
   <script type="text/javascript"
          src="http://code.jquery.com/jquery-1.9.0.min.js"> </script>
   <script type="text/javascript" src= "./checkGroupForm.js"></script>
</head>

<body>

<?php

require_once('DBFuncs.php');
require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');

$dbh = ConnectDB();

$currUser = $_SESSION['userID']

$userData = getUserByID($dbh,$currUser);

$userImage = getUserImage($dbh,$currUser);

echo "<h1 id = "editHeader"> Edit Your Profile " .
     $userData[0]->username . "</h1>";
echo "<p> Current Information: </p>\n";

echo "<p> Current User Name: " 
        . $userData[0]->username . "</p>\n";
echo "<p> First Name: " 
        . $userData[0]->fname . "</p>\n";
echo "<p> Last Name: " 
        . $groupData[0]->lname . "</p>\n";
echo "<p> Profile Picture: </p> \n";

    if($userData[0]->image_ID == NULL){
        echo '<img id="userImage" src="./UPLOADED/archive/profile_default.jpg" alt= "Default" style="width:304px;height:228px;">';
    }
    else{
         echo '<img id="userImage" src="'. $userImage[0]->image_location
             .'"alt="' . $userImage[0]->image_name . '" style="width:304px;height:228px;">';
    }
?>

<form enctype="multipart/form-data" action = "updateProfile.php" method="post" onsubmit = "return checkForm(true);">



</form>

</body>

</html>