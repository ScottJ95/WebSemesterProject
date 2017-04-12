<?php 
	session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
 "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>Checking Upload Test</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta name="Author" content="Scott Jeffery" />
  <meta name="generator" content="Vim" />

</head>
<body>

<!-- Scott: THIS SCRIPT CURRENTLY SERVES AS A TESTING GROUNDS
     WHEN I AM READY, I WILL MAKE THIS SCRIPT ACT ACCORDINGLY. -->


<h1> Checking The Query and the File Upload </h1>

<?php

//TODO THIS FILE SHOULD SPIT OUT AN ALET BOX BASED ON WHAT HAPPENED
//TODO THIS FILE SHOULD REDIRECT THE USER WHEN PROCESSED 

require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');
require_once('DBFuncs.php');

if (isset($_SESSION['userID'])) {
    echo "<p> Hi User Num " . $_SESSION['userID'] . "</p> \n";
} else {
    echo "<p> How did you get here? -LevelLord </p>\n";
}

//require_once('DBFuncs.php');

$dbh = ConnectDB();


//Check to see that the groupName was posted from the previous page.
if (isset($_POST['groupName']) && !empty($_POST['groupName'])) {
    
    echo "<p>Adding" . $_POST['groupName'] . "to DB\n";
    
    try {
	    $query = 'INSERT INTO groups (group_name,group_subject,group_description,creator_ID) ' 
		    . 'VALUES (:groupName, :groupSubject, :description, :creatorID)';
	    $stmt  = $dbh->prepare($query);

	//TODO: PREPROCESS COMMENTS AND GROUPNAMES 
	//TODO: ADD CREATOR INTO BELONG
	//TODO: ADD IMAGE INTO IMAGES/GROUP IMAGE COLUMN
		
        $groupName    = $_POST['groupName'];
        $groupSubject = $_POST['groupSubject'];
	$description  = $_POST['description'];
	//$date = time();
	echo "<p> " . $groupName . ", " . $groupSubject . ", " . $description . "</p>\n";
	$creatorID = $_SESSION['userID'];
	echo "<p> " . $creatorID . "</p>\n";

        
        $stmt->bindParam(':groupName', $groupName);
        $stmt->bindParam(':groupSubject', $groupSubject);
	$stmt->bindParam(':description', $description);
	$stmt->bindParam(':creatorID', $creatorID);
        $stmt->execute();
        $inserted = $stmt->rowCount();
        
        //$stmt         = null;
        $groupCreated = false;
        
        if ($inserted == 0) {
            //header("Location: http://elvis.rowan.edu/~jefferys0/web/WebSemesterProject/createGroup.php");
            echo "Query Failed... but that's ok for now\n";
        } else {
            $groupCreated = true;
	    echo "Query succeded!?!?!?!?\n";
	    if(addBelongs($groupName, $creatorID)){
		    echo "Belongs Added";
		    uploadGroupImage();
	    }

        }
        
    }
    
    catch (PDOException $e) {
        die('PDO Error Inserting(): ' . $e->getMessage());
    }
    
    //TODO: HAVE THIS LINE MOVED TO UNDER GROUP CREATED SO THAT THE FILE UPLOADS ONLY IF THE GROUP WAS CREATED.
    
    /*if (uploadImage()) {
        echo "<p> FILE WAS UPLOADED!!!! </p>";
    } else {
        echo "<p> SHIT </p>";
    }*/
    
} 

else {
    echo "<p> No Insert </p>\n";
   }

function addBelongs($groupName, $studentID)
{
	try{
	$dbh = ConnectDB();
	$groupData = getMatchingGroupName($dbh, $groupName);
	$groupID = $groupData[0]->group_ID;
	$belongs_query = "INSERT INTO belongs (student_ID, group_ID) VALUES (:studentID, :groupID)";
	$stmt = $dbh->prepare($belongs_query);

	$stmt->bindParam(':studentID', $studentID);
	$stmt->bindParam(':groupID', $groupID);
	$stmt->execute();
	
	if($stmt->rowCount() != 0){
		echo "<p> Belongs Added</p>\n";
		return true;
	}
	else{
		echo"<p> Shit At Add Belongs</p><\n";
		return false;
	}
	}

	catch(PDOException $e){
		die("Add Belongs Error: " .$e->getMessage());
	}
}


function uploadGroupImage()
{
	$groupName = $_POST["groupName"];

    if (isset($_FILES["groupImage"]) && !empty($_FILES["groupImage"])) {
        
        echo "<p> Oh look, the file was set </p>\n";
        
        echo "<p> Checking File Type </p>\n";
        // never assume the upload succeeded
        if ($_FILES['groupImage']['error'] !== UPLOAD_ERR_OK) {
            echo "<p> SHIT SOMETHING WENT WRONG </p> \n";
            die("Upload failed with error code " . $_FILES['file']['error']);
        }
        
        $info = getimagesize($_FILES['groupImage']['tmp_name']);
        if ($info === FALSE) {
            echo "<p> SHIT IMAGE TYPE ERROR </p> \n";
            die("Unable to determine image type of uploaded file");
        }
        
        if (($info[2] !== IMAGETYPE_BMP) && ($info[2] !== IMAGETYPE_JPEG) && ($info[2] !== IMAGETYPE_PNG)) {
            echo "<p> HEY!!! THAT'S NOT AN IMAGE!!!!! D:< </p> \n";
            die("Not a bmp/jpeg/png");
        }
        
        echo "<p> Ok we passed the test. Let's make the dir... </p> \n";
        
        
        if (file_exists("./UPLOADED/archive/" . $groupName)) {
		echo "I see it already exists; you've uploaded before.</p>";
		//TODO: This should do something based on the page.
        }
        
        else {
            // bug in mkdir() requires you to chmod()
            mkdir("./UPLOADED/archive/" . $groupName, 0777);
            chmod("./UPLOADED/archive/" . $groupName, 0777);
            echo "done.</p>";
        }
        
        echo "<h2>Copying File And Setting Permission</h2>";
        
        // Make sure it was uploaded
        if (!is_uploaded_file($_FILES["groupImage"]["tmp_name"])) {
            echo "<pre>\n";
           // print_r($_FILES["userfile"]);
            //echo "</pre>";
            die("Error: " . $_FILES["groupImage"]["name"] . " did not upload.");
        }
        
        
        $targetname = "./UPLOADED/archive/" . $groupName . "/" .  $_FILES["groupImage"]["name"];
        
        if (file_exists($targetname)) {
		echo "<p>Already uploaded one with this name.  I'm confused.</p>";
		return false;
        }
        
        else {
            if (copy($_FILES["groupImage"]["tmp_name"], $targetname)) {
                // if we don't do this, the file will be mode 600, owned by
                // www, and so we won't be able to read it ourselves
                chmod($targetname, 0444);
                // but we can't upload another with the same name on top,
                // because it's now read-only
            } else {
                die("Error copying " . $_FILES["groupImage"]["name"]);
            }
	}
	setImageDir($targetname, $_FILES["groupImage"]["name"], $groupName);
        return true;
        
    }
	
	else{
		echo "<p> File is not set </p>";
		return false;
	}
}


function setImageDir($targetName, $fileName, $groupName ) 
{
	try{
		$image_query = "INSERT INTO images (image_name, image_location) VALUES (:fileName, :targetName)";
		$dbh = ConnectDB();
		$stmt = $dbh->prepare($image_query);

		$stmt->bindParam(':fileName', $fileName);
		$stmt->bindParam(':targetName', $targetName);

		$stmt->execute();

		if($stmt->rowCount() == 0){
			echo"<p> ERROR AT IMAGE DIR <\p>";
			return false;
		}

		$stmt = null;

		$dbh = ConnectDB();
		$imageData = getImageByDir($dbh, $targetName);
		$image_ID = $imageData[0]->image_ID;
		echo $image_ID;
		echo $targetName;

		$update_query = "UPDATE groups SET image_ID = :image_ID WHERE group_name = :groupName";

		$stmt = $dbh->prepare($update_query);

		$stmt->bindParam(":image_ID", $image_ID);
		$stmt->bindParam(":groupName", $groupName);

		$stmt->execute();

		return true;	

	}

	catch(PDOException $e) {

		die("PDOException at setImageDir: " . $e->getMessage());
	}

}

?>
