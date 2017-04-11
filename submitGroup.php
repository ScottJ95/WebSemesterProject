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

//TODO THIS FILE SHOULD NOT SPIT OUT ANY HTML 
//TODO THIS FILE SHOULD REDIRECT THE USER WHEN PROCESSED 

require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');

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
        $query = 'INSERT INTO groups (groupName,groupSubject,description) ' . 'VALUES (:groupName, :groupSubject, :description)';
        $stmt  = $dbh->prepare($query);

	//TODO: PREPROCESS COMMENTS AND GROUPNAMES AND STUFF
		
        $groupName    = $_POST['groupname'];
        $groupSubject = $_POST['groupSubject'];
	$description  = $_POST['description'];


        
        $stmt->bindParam(':groupName', $groupName);
        $stmt->bindParam(':groupSubject', $groupSubject);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        $inserted = $stmt->rowCount();
        
        $stmt         = null;
        $groupCreated = false;
        
        if (inserted == 0) {
            //header("Location: http://elvis.rowan.edu/~jefferys0/web/WebSemesterProject/createGroup.php");
            echo "Query Failed... but that's ok for now\n";
        } else {
            $groupCreated = true;
            echo "Query succeded!?!?!?!?\n";
        }
        
    }
    
    catch (PDOException $e) {
        die('PDO Error Inserting(): ' . $e->getMessage());
    }
    
    //TODO: HAVE THIS LINE MOVED TO UNDER GROUP CREATED SO THAT THE FILE UPLOADS ONLY IF THE GROUP WAS CREATED.
    
    if (uploadImage()) {
        echo "<p> FILE WAS UPLOADED!!!! </p>";
    } else {
        echo "<p> SHIT </p>";
    }
    
} else {
    echo "<p> No Insert </p>\n";
}

function uploadImage()
{
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
        
        
        if (file_exists("./UPLOADED/archive/dummyDir")) {
		echo "I see it already exists; you've uploaded before.</p>";
		//TODO: This should do something based on the page.
        }
        
        else {
            // bug in mkdir() requires you to chmod()
            mkdir("./UPLOADED/archive/dummyDir", 0777);
            chmod("./UPLOADED/archive/dummyDir", 0777);
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
        
        
        $targetname = "./UPLOADED/archive/dummyDir/" . $_FILES["groupImage"]["name"];
        
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
        return true;
        
    }
	
	else{
		echo "<p> File is not set </p>";
		return false;
	}
}

?>
