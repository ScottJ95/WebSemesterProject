<?php
session_start();
require_once('DBFuncs.php');

if(!checkSession()){
        header('Location: http://elvis.rowan.edu/~jefferys0/');
        exit;
}



require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');


$dbh = ConnectDB();

//Check to see that the groupName was posted from the previous page.
if (isset($_POST['groupName']) && !empty($_POST['groupName']) && isset($_POST['groupSubject']) && !empty($_POST['groupSubject'])) {
    
    try {
    	$groupName = $_POST['groupName'];
        $subject = $_POST['groupSubject'];

        $query = "select group_ID, group_name,group_subject,group_numUsers,group_description from groups where group_name = :GroupName and group_subject = :Subject";
        $dbh = ConnectDB();
        $stmt = $dbh-> prepare($query);
        $stmt->bindParam(':GroupName', $groupName);
        $stmt->bindParam(':Subject', $subject);
        $stmt->execute();

        $result_array = $stmt->fetchAll(PDO::FETCH_OBJ);
        $groupData = json_encode($result_array);
        $stmt = null;
           
    }
    
    catch (PDOException $e) {
        die('PDO Error Inserting(): ' . $e->getMessage());
    }
    
}

else {
    echo "<p> 404 : Something went wrong </p>\n";
}

function addBelongs($groupName, $studentID)
{
    try {
        $dbh           = ConnectDB();
        $groupData     = getMatchingGroupName($dbh, $groupName);
        $groupID       = $groupData[0]->group_ID;
	$belongs_query = "INSERT INTO belongs (student_ID, group_ID) 
			  VALUES (:studentID, :groupID)";
        $stmt 	       = $dbh->prepare($belongs_query);
        
        $stmt->bindParam(':studentID', $studentID);
        $stmt->bindParam(':groupID', $groupID);
        $stmt->execute();
        
        if ($stmt->rowCount() != 0) {
            //echo "<p> Belongs Added</p>\n";
            return true;
        } else {
            //echo "<p> Shit At Add Belongs</p><\n";
            return false;
        }
    }
    
    catch (PDOException $e) {
        die("Add Belongs Error: " . $e->getMessage());
    }
}

//Upload the group image. 
//Use this again for profile images
function uploadGroupImage($groupName)
{

    if ($_FILES['groupImage']['error'] == 0) {
        
        //Checking File Type
        $info = getimagesize($_FILES['groupImage']['tmp_name']);
        if ($info === FALSE) {
            die("Unable to determine image type of uploaded file");
        }
        
        if (($info[2] !== IMAGETYPE_BMP) && ($info[2] !== IMAGETYPE_JPEG) && ($info[2] !== IMAGETYPE_PNG)) {
            die("Not a bmp/jpeg/png");
        }
        
        //Make the dir
        if (file_exists("./UPLOADED/archive/" . $groupName)) {
        }
        
        else {
            // bug in mkdir() requires you to chmod()
            mkdir("./UPLOADED/archive/" . $groupName, 0777);
            chmod("./UPLOADED/archive/" . $groupName, 0777);
        }
        
        echo "<h2>Copying File And Setting Permission</h2>";
        
        // Make sure it was uploaded
        if (!is_uploaded_file($_FILES["groupImage"]["tmp_name"])) {
		die("Error: " . $_FILES["groupImage"]["name"] ." did not upload.");
        }
        
        
	$targetname = "./UPLOADED/archive/" . $groupName . 
		    "/" . $_FILES["groupImage"]["name"];
	$fileName = $_FILES["groupImage"]["name"];

        if (file_exists($targetname)) {
	    $name = $_FILES["groupImage"]["name"];
	    $actual_name = pathinfo($name,PATHINFO_FILENAME);
	    $original_name = $actual_name;
	    $extension = pathinfo($name, PATHINFO_EXTENSION);
		
	    $numFound = 1;
	    while(file_exists("./UPLOADED/archive/" . $groupName .
		    "/" . $actual_name . "." . $extension)) 
		{
		    $actual_name = (string)$original_name.$numFound;
		    $name = $actual_name . "." .$extension;
		    $numFound++;
		}
	    $targetname = "./UPLOADED/archive/" . $groupName .
		    "/" . $name;
	    $fileName = $name;
	   

        }
        
        if (copy($_FILES["groupImage"]["tmp_name"], $targetname)) {
            // if we don't do this, the file will be mode 600, owned by
            // www, and so we won't be able to read it ourselves
         	chmod($targetname, 0444);
            // but we can't upload another with the same name on top,
            // because it's now read-only
        } else {
            die("Error copying " . $_FILES["groupImage"]["name"]);
		}

        if(setImageDir($targetname, $fileName, $groupName)){
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

//Set up the image in the database
function setImageDir($targetName, $fileName, $groupName)
{
    try {
        $image_query = "INSERT INTO images (image_name, image_location) VALUES (:fileName, :targetName)";
        $dbh         = ConnectDB();
        $stmt        = $dbh->prepare($image_query);
        
        $stmt->bindParam(':fileName', $fileName);
        $stmt->bindParam(':targetName', $targetName);
        
        $stmt->execute();
        
        if ($stmt->rowCount() == 0) {
	    die("Error in Image Query");
            return false;
        }
        
        $stmt = null;
        
        $dbh       = ConnectDB();
        $imageData = getImageByDir($dbh, $targetName);
        $image_ID  = $imageData[0]->image_ID;
        //echo $image_ID;
        //echo $targetName;
        
        $update_query = "UPDATE groups SET image_ID = :image_ID WHERE group_name = :groupName";
        
        $stmt = $dbh->prepare($update_query);
        
        $stmt->bindParam(":image_ID", $image_ID);
        $stmt->bindParam(":groupName", $groupName);
        
        $stmt->execute();
        
        return true;
        
    }
    
    catch (PDOException $e) {
        die("PDOException at setImageDir: " . $e->getMessage());
    }
    
}

function redirectSuccess($imageSucceed) {
    if($imageSucceed == true){
        header('Location: http://elvis.rowan.edu/~jefferys0/web/WebSemesterProject/redirectSuccessTest.php');
    }
    else{
        header('Location: http://elvis.rowan.edu/~jefferys0/web/WebSemesterProject/redirectSuccessTest.php?error="img"');
    }

}

?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>Join a Group</title>
  <meta http-equiv="Content-Type"
        content="application/xhtml+xml; charset=UTF-8" />
  <meta name="Author" content="Jacob Kershaw" />

  <link rel="stylesheet" href="tagline.css" />
  <script type="text/javascript" src="./AjaxFunctions.js"></script>
   <script type="text/javascript"
          src="http://code.jquery.com/jquery-1.9.0.min.js"> </script>
  <script type="text/javascript" src="./checkGroupForm.js"></script>
<link href="thread.css" rel="stylesheet" type="text/css" media="screen">	
</head>

<body onload="showGroups()">
<h1> Join a Group </h1>

<div class="tabcontent" id="groupList">

	<div class = "post">
                    <div class = "userContainer">
                        <div class="userImageChat">
                        </div>
                        <div class="userNameChat">Username Here</div>
                  </div><br>

                    <div class = "messageChat">
                        something something tutor me
                    </div>
                </div>

</div>

</body>
</html>
