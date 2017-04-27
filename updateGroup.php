<?php

require_once('DBFuncs.php');
$_SESSION['projectTime'] = time();
require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');


$dbh = ConnectDB();


//Ok so we need to check to see what was set.
//If the groupname was set, we need to update the dir name

$groupID = $_SESSION['groupIDEdit'];
echo $groupID;
$oldGroupName = $_SESSION['groupNameEdit'];
$newGroupName = NULL;
$newGroupDesc = NULL;
$newGroupSubject = NULL;
$updateDirCheck = false;
//Clear these when done.

if(isset($_POST['groupName']) && !empty($_POST['groupName'])) {
	$newGroupName = $_POST['groupName'];
	$newGroupName = strip_tags($newGroupName);
        echo "<p> Update Dir is True? </p?";
	$updateDirCheck = true;
}

if(isset($_POST['description']) && !empty($_POST['description'])) {
	$newGroupDesc = $_POST['description'];
	$newGroupDesc = strip_tags($newGroupDesc);
	$newGroupDesc = htmlspecialchars($newGroupDesc,ENT_QUOTES);
}


if(isset($_POST['groupSubject']) && !empty($_POST['groupSubject'])) {
	$newGroupSubject = $_POST['groupSubject'];
}

try {
    $query = 'UPDATE groups SET
	    group_name = COALESCE(:groupName,group_name),
	    group_description = COALESCE(:description,group_description),
	    group_subject = COALESCE(:groupSubject,group_subject)
	    WHERE group_ID = :groupID';

    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':groupName', $newGroupName);
    $stmt->bindParam(':groupSubject', $newGroupSubject);
    $stmt->bindParam(':description', $newGroupDesc);
    $stmt->bindParam(':groupID', $groupID);
    $stmt->execute();
    $updated = $stmt->rowCount();
    echo $updated;
    $stmt = null;
    
    $currGroupName = $oldGroupName;
    $oldDir = "/home/jefferys0/public_html/web/WebSemesterProject/UPLOADED/archive/" .$oldGroupName;
    $updateDir = $oldDir;
    if($updateDirCheck == true){
        $newDirQ = "/~jefferys0/web/WebSemesterProject/UPLOADED/archive/" .$newGroupName;
        $newDirAbs = "/home/jefferys0/public_html/web/WebSemesterProject/UPLOADED/archive/" .$newGroupName;
        $currGroupName = $newGroupName;
        updateGroupDir($oldDir,$newDirQ,$newDirAbs,$groupID);
        $updateDir = $newDirAbs;
    }
    updateImage($updateDir,$groupID, $currGroupName);
    header('Location: http://elvis.rowan.edu/~jefferys0/web/WebSemesterProject/editGroup.php?groupID=' . $groupID);
    exit;
}

catch (PDOException $e) {
        die('PDO Error Updating(): ' . $e->getMessage());
    }



//Update the group directory name
function updateGroupDir($oldDir, $newDirQ, $newDirAbs, $groupID){
    $dbh = ConnectDB();
    $groupImage = getGroupImage($dbh, $groupID);

    $imageID = $groupImage[0]->image_ID;
    $oldImageDir = $groupImage[0]->image_location;
    $newImageDir = $newDirQ . "/" . $groupImage[0]->image_name;

    try{
        $query = "UPDATE images
                SET image_location = :newImageDir
                WHERE image_ID = :imageID";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(":newImageDir", $newImageDir);
        $stmt->bindParam(":imageID", $imageID);
        $stmt->execute();

        $stmt = NULL;
        rename($oldDir,$newDirAbs);
        return $newDir;
    }

    catch(PDOException $e){
        die('PDO Error Updating Image Dir: ' . $e->getMessage());
    }
}


//Update the image if it was set
function updateImage($updateDir,$groupID, $groupName){
    if ($_FILES['groupImage']['error'] == 0) {
        echo "Updating Image";
        //Checking File Type
        $info = getimagesize($_FILES['groupImage']['tmp_name']);
        if ($info === FALSE) {
            header("Location:http://elvis.rowan.edu/~jefferys0/web/WebSemesterProject/error.html?error=GroupImage");
            die("Unable to determine image type of uploaded file");
        }

        if (($info[2] !== IMAGETYPE_BMP) && ($info[2] !== IMAGETYPE_JPEG) 
            && ($info[2] !== IMAGETYPE_PNG)) {
                header("Location: http://elvis.rowan.edu/~jefferys0/web/WebSemesterProject/error.html?error=GroupImage");
                die("Not a bmp/jpeg/png");
        }

        //Image is valid, so now upload and update it. 
        if(!is_uploaded_file($_FILES["groupImage"]["tmp_name"])){
            die("Error in Uploading File");
        }

        $fileName = $_FILES["groupImage"]["name"];
        $targetname = $updateDir . "/" . $fileName;

        if (file_exists($targetname)) {
            $name = $_FILES["groupImage"]["name"];
            $actual_name = pathinfo($name,PATHINFO_FILENAME);
            $original_name = $actual_name;
            $extension = pathinfo($name, PATHINFO_EXTENSION);

            $numFound = 1;
            while(file_exists($updateDir ."/" . $actual_name . "." . $extension))
                {
                    $actual_name = (string)$original_name.$numFound;
                    $name = $actual_name . "." .$extension;
                    $numFound++;
                }
            $targetname = $updateDir . "/" . $name;
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
        $targetname = "/~jefferys0/web/WebSemesterProject/UPLOADED/archive/" . $groupName ."/" . $fileName;
        if(setImageDir($targetname, $fileName,$groupID)){
            return true;
        }
        else{
             return false;
        }

    }//End Main If

    else{
        return false; 
    }
}

function setImageDir($targetname, $fileName,$groupID){

    try {
        $dbh = ConnectDB();
        $imageData = getGroupImage($dbh,$groupID);
        $imageID = $imageData[0]->image_ID;
        echo "<p> Image ID " . $imageID . "</p>";
        $image_query = "UPDATE  images 
                        SET image_name = :fileName, image_location = :targetname 
                        WHERE image_ID = :imageID";
        $stmt        = $dbh->prepare($image_query);
        $stmt->bindParam(':fileName', $fileName);
        $stmt->bindParam(':targetname', $targetname);
        $stmt->bindParam(':imageID', $imageID);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            die("Error in Image Query");
            return false;
        }
    
        $stmt = null;

        $dbh       = ConnectDB();
        $imageData = getImageByDir($dbh, $targetname);
        $image_ID  = $imageData[0]->image_ID;

        $update_query = "UPDATE groups SET image_ID = :image_ID WHERE group_ID = :groupID";

        $stmt = $dbh->prepare($update_query);

        $stmt->bindParam(":image_ID", $image_ID);
        $stmt->bindParam(":groupID", $groupID);
        
         $stmt->execute();

        return true;

    }

    catch (PDOException $e) {
        die("PDOException at setImageDir: " . $e->getMessage());
    }

}
