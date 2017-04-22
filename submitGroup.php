<?php
session_start();
require_once('DBFuncs.php');

if(!checkSession()){
        header('Location: http://elvis.rowan.edu/~jefferys0/');
        exit;
}

$_SESSION['projectTime'] = time();


require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');

if (isset($_SESSION['userID'])) {
    echo "<p> Hi User Num " . $_SESSION['userID'] . "</p> \n";
} else {
    echo "<p> How did you get here? -LevelLord </p>\n";
}

$dbh = ConnectDB();



//Check to see that the groupName was posted from the previous page.
if (isset($_POST['groupName']) && !empty($_POST['groupName'])) {
    
    //echo "<p>Adding" . $_POST['groupName'] . "to DB\n";
    
    try {
	    $query = 'INSERT INTO groups (group_name,group_subject,group_description,creator_ID) ' 
		    . 'VALUES (:groupName, :groupSubject, :description, :creatorID)';
	    $stmt  = $dbh->prepare($query);

		
		$groupName    = $_POST['groupName'];
		$groupName = strip_tags($groupName);
		$groupName = htmlspecialchars($groupName, ENT_QUOTES);

		$groupSubject = $_POST['groupSubject'];

		$description  = $_POST['description'];
		$description = strip_tags($description);
		$description = htmlspecialchars($description, ENT_QUOTES);

		//echo "<p> " . $groupName . ", " . $groupSubject . ", " . $description . "</p>\n";
		$creatorID = $_SESSION['userID'];
		//echo "<p> " . $creatorID . "</p>\n";

		$query = 'INSERT INTO groups (group_name,group_subject,group_description,creator_ID) ' 
			. 'VALUES (:groupName, :groupSubject, :description, :creatorID)';
        $stmt  = $dbh->prepare($query);
        
	$groupName = $_POST['groupName'];
	$groupName = strip_tags($groupName);
        //echo $groupName;
        $groupSubject = $_POST['groupSubject'];
        $description  = $_POST['description'];
        $description  = strip_tags($description);
        $description  = htmlspecialchars($description, ENT_QUOTES);
        //echo $description;
	
        echo "<p> " . $groupName . ", " . $groupSubject . ", " . $description . "</p>\n";
        $creatorID = $_SESSION['userID'];
        echo "<p> " . $creatorID . "</p>\n";
    
        $stmt->bindParam(':groupName', $groupName);
        $stmt->bindParam(':groupSubject', $groupSubject);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':creatorID', $creatorID);
        $stmt->execute();
        $inserted = $stmt->rowCount();
        
        $stmt         = null;
        $groupCreated = false;
        
        if ($inserted == 0) {
            header("Location: http://elvis.rowan.edu/~jefferys0/web/WebSemesterProject/error.html?error=InsertGroup");
			exit;
        } else {
            $groupCreated = true;
            if (addBelongs($groupName, $creatorID)) {
		    if(uploadGroupImage($groupName)){
			    		//TODO: THIS NEEDS TO REDIRECT TO THE GROUP'S PAGE
					header("Location: http://elvis.rowan.edu/~jefferys0/web/WebSemesterProject/redirectSuccessTest.php");
				}
				else{
					header("Location: http://elvis.rowan.edu/~jefferys0/web/WebSemesterProject/redirectSuccessTest.php?error=GroupImage");
				}
            }
            
        }
        
    }
    
    catch (PDOException $e) {
        die('PDO Error Inserting(): ' . $e->getMessage());
    }
    
}

else {
    echo "<p> No Insert </p>\n";
}

function addBelongs($groupName, $studentID)
{
    try {
        $dbh           = ConnectDB();
        $groupData     = getMatchingGroupName($dbh, $groupName);
        $groupID       = $groupData[0]->group_ID;
		$belongs_query = "INSERT INTO belongs (student_ID, group_ID) 
						  VALUES (:studentID, :groupID)";
        $stmt          = $dbh->prepare($belongs_query);
        
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
				header("Location: http://elvis.rowan.edu/~jefferys0/web
						/WebSemesterProject/error.html?error=GroupImage");
            die("Unable to determine image type of uploaded file");
        }
        
        if (($info[2] !== IMAGETYPE_BMP) && ($info[2] !== IMAGETYPE_JPEG) && ($info[2] !== IMAGETYPE_PNG)) {
				header("Location: http://elvis.rowan.edu/~jefferys0/web
						/WebSemesterProject/error.html?error=GroupImage");
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
				header("Location: http://elvis.rowan.edu/~jefferys0/web
						/WebSemesterProject/error.html?error=FileUpload");
				die("Error: " . $_FILES["groupImage"]["name"] . 
					" did not upload.");
        }
        
        
	$targetname = "./UPLOADED/archive/" . $groupName . 
		    "/" . $_FILES["groupImage"]["name"];


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

        }
        
        if (copy($_FILES["groupImage"]["tmp_name"], $targetname)) {
            // if we don't do this, the file will be mode 600, owned by
            // www, and so we won't be able to read it ourselves
         	chmod($targetname, 0444);
            // but we can't upload another with the same name on top,
            // because it's now read-only
        } else {
			header("Location: http://elvis.rowan.edu/~jefferys0/web/WebSemesterProject/error.html?error=FileCopy");
            die("Error copying " . $_FILES["groupImage"]["name"]);
		}

        if(setImageDir($targetname, $_FILES["groupImage"]["name"], $groupName)){
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
			header("Location: http://elvis.rowan.edu/~jefferys0/web/WebSemesterProject/error.html?error=ImageQuery");
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

?>
