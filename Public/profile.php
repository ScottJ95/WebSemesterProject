<?php

session_start();

require_once('DBFuncs.php');
require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');

$dbh = ConnectDB();

if(!checkSession()){
        exit;
}

$_SESSION['projectTime'] = time();

$userID= $_SESSION['userID'];
$queryID = $_GET['userID'];

if(!isset($_GET['userID'])){
    echo "Hi";
    echo '<script type="text/javascript">';
    echo 'alert("Either the profile does not exist or something went wrong");';
    echo 'window.history.back();';
    echo '</script>';
    exit;
}

$userData = getUserByID($dbh,$queryID);

if($userData == NULL || $userData[0]->student_ID != $queryID){
    echo '<script type="text/javascript">';
    echo 'alert("Either the profile does not exist or something went wrong");';
    echo 'window.history.back();';
    echo '</script>';
    exit;
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
 "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>User Profile Page</title>
  <meta http-equiv="Content-Type"
        content="application/xhtml+xml; charset=UTF-8" />
  <meta name="Author" content="Scott Jeffery" />

  <link rel="stylesheet" href="thread.css" />
  <script type="text/javascript" src="./AjaxFunctions.js"></script>
   <script type="text/javascript"
          src="http://code.jquery.com/jquery-1.9.0.min.js"> </script>
   <script type="text/javascript" src= "./checkUserForm.js"></script>
</head>

<body>

<?php

require_once('DBFuncs.php');
require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');

$dbh = ConnectDB();

$userID= $_SESSION['userID'];
$queryID = $_GET['userID'];
$enableEdit = true;

if($userID != $queryID){
  $enableEdit = false;
}

else if($userID ==1){
    $enableEdit = true;
}

$userData = getUserByID($dbh,$queryID);

$userImage = getUserImage($dbh,$queryID);
echo '<div class="left">';

echo '<h1 id = "editHeader"> User Profile: ' .
     $userData[0]->username . "</h1>";
echo "<p> Current Information: </p>\n";

echo "<p> Current User Name: "
        . $userData[0]->username . "</p>\n";
echo "<p> First Name: "
        . $userData[0]->fname . "</p>\n";
echo "<p> Last Name: "
        . $userData[0]->lname . "</p>\n";
echo "<p> Email: " 
        . $userData[0]->email . "</p>\n";
echo "<p> Profile Picture: </p> \n";

    if($userData[0]->image_ID == NULL){
        echo '<img id="userImage" src="./defaultIcon.svg" alt= "Default" style="width:300px;height:300px;">';
    }
    else{
         echo '<img id="userImage" src="'. $userImage[0]->image_location
             .'"alt="' . $userImage[0]->image_name . '" style="width:300px;height:300px;">';
    }

echo '</div>';
if($enableEdit){

echo '<div id="center"> <form enctype="multipart/form-data" action="updateProfile.php" method="post" 
        onsubmit = "return checkProfile();">

<fieldset>
<legend> Edit Your Profile Info </legend>

<table title = "Edit Profile" id="editProfileTable">
    <tr>
        <th>User Name:
        </th>
            <td> <input type = "text" name="userBox"
                id="userBox" onkeyup="checkUsername();"/>
            </td>
        <span id="userMessage"></span>
      </tr>

      <tr>
        <th>First Name:
        </th>
            <td> <input type = "text" name="fnameBox"
                id="fnameBox"/>
            </td>
      </tr>

      <tr>
        <th>Last Name:
        </th>
            <td> <input type = "text" name="lnameBox"
                id="lnameBox"/>
            </td>
      </tr>

      <tr>
        <th> Email:  
        </th>
        <td><input type = "text" name="emailBox" 
              id="emailBox" onkeyup="checkEmail();"/>
        </td>
        <span id="emailMessage"></span>
      </tr>

      <tr>
        <th>Change Password:
        </th>
            <td><input type="password" name="passwordBox"
                id="passwordBox"/>
            </td>
            <span id="passMessage"></span>
        </tr>

      <tr>
        <th> Confirm Change Password:
        </th>
             <td><input type="password" name="confirmPasswordBox"
                id="confirmPasswordBox"/>
            </td>
            <span id="passconfMessage"></span>
        </td>
      </tr>

      <tr>
        <th>Profile Photo:
        </th>
        <td> <input type = "file" name="profileImage" accept = "image/jpg, image/jpeg, image/bmp, image/png"/>
        </td>
    </tr>

    <tr>
        <td>
        </td>
        <td> <input type="submit"/>
        </td>

    </tr>


  </table></div>';
}


?>



</body>

</html>

