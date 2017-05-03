<?php

require_once('DBFuncs.php');
$_SESSION['projectTime'] = time();
require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');

if(!checkSession()){
    exit;
}


$dbh = ConnectDB();


//Ok so we need to check to see what was set.
//If the username was set, we need to update the dir name

$userID = $_SESSION['userID'];
//echo $userID;
$olduserName = $_SESSION['username'];
$newuserName = NULL;
$newfName = NULL;
$newlName = NULL;
$newPassword = NULL;
$newEmail = NULL;
$updateDirCheck = false;

if(isset($_POST['userBox']) && !empty($_POST['userBox'])) {
    $newuserName = $_POST['userBox'];
    $newuserName = strip_tags($newuserName);
    //echo "<p> Update Dir is True? </p?";
    $updateDirCheck = true;
    $newuserName = strtolower($newuserName);
    $_SESSION['username'] = $newuserName;
}

if(isset($_POST['fnameBox']) && !empty($_POST['fnameBox'])) {
    $newfName = $_POST['fnameBox'];
    $newfName = strip_tags($newfName);
    $newfName = htmlspecialchars($newfName,ENT_QUOTES);
}

if(isset($_POST['lnameBox']) && !empty($_POST['lnameBox'])) {
    $newlName = $_POST['lnameBox'];
    $newlName = strip_tags($newlName);
    $newlName = htmlspecialchars($newlName,ENT_QUOTES);
}


if(isset($_POST['passwordBox']) && !empty($_POST['passwordBox'])) {
    $newPassword = $_POST['passwordBox'];
    $newPassword = strip_tags($newPassword);
    $newPassword = htmlspecialchars($newPassword,ENT_QUOTES);
    $newPassword = md5($newPassword);
}


if(isset($_POST['emailBox']) && !empty($_POST['emailBox'])) {
    $newEmail = $_POST['emailBox'];
    $newEmail = strip_tags($newEmail);
    $newEmail = htmlspecialchars($newEmail,ENT_QUOTES);
}

try {
    $query = 'UPDATE students SET
        username = COALESCE(:username,username),
	fname = COALESCE(:fname,fname),
	lname = COALESCE(:lname,lname),
        password = COALESCE(:password, password),
        email = COALESCE(:email, email)
	WHERE student_ID = :userID';

    $stmt = $dbh->prepare($query);

    $stmt->bindParam(':username', $newuserName);
    $stmt->bindParam(':fname', $newfName);
    $stmt->bindParam(':lname', $newlName);
    $stmt->bindParam(':password', $newPassword);
    $stmt->bindParam(':email', $newEmail);
    $stmt->bindParam(':userID', $userID);

    $stmt->execute();

    $updated = $stmt->rowCount();
   // echo $updated;
    $stmt = null;

    $curruserName = $olduserName;
    $oldDir = "/home/jefferys0/public_html/web/WebSemesterProject/UPLOADED/archive/users/" .$olduserName;
    $updateDir = $oldDir;

    if($updateDirCheck == true){
        $newDirQ = "/~jefferys0/web/WebSemesterProject/UPLOADED/archive/users/" .$newuserName;
        $newDirAbs = "/home/jefferys0/public_html/web/WebSemesterProject/UPLOADED/archive/users/" .$newuserName;
        $curruserName = $newuserName;
        $curruserName = strtolower($curruserName);
        updateUserDir($oldDir,$newDirQ,$newDirAbs,$userID);
        $updateDir = $newDirAbs;
    }
    updateImage($updateDir,$userID, $curruserName);
    header('Location: profile.php?userID=' . $userID);
    exit;
}

catch(PDOException $e){
    die('PDO Error Updating User(): ' . $e->getMessage());
}

//Update the user directory name
function updateUserDir($oldDir, $newDirQ, $newDirAbs, $userID){
    $dbh = ConnectDB();
    $userImage = getuserImage($dbh, $userID);

    $imageID = $userImage[0]->image_ID;
    $oldImageDir = $userImage[0]->image_location;
    $newImageDir = $newDirQ . "/" . $userImage[0]->image_name;

    try{
        
        if($imageID != NULL){
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
        else{
            rename($oldDir,$newDirAbs);
            return $newDirAbs;
        }
    }

    catch(PDOException $e){
        die('PDO Error Updating Image Dir: ' . $e->getMessage());
    }
}

function updateImage($updateDir, $userID, $userName){
   if ($_FILES['profileImage']['error'] == 0) {
   	echo "Updating Image";
        //Checking File Type
        $info = getimagesize($_FILES['profileImage']['tmp_name']);
        if ($info === FALSE) {
            echo "That looks like a bad image";
            return false;
        }

        if (($info[2] !== IMAGETYPE_BMP) && ($info[2] !== IMAGETYPE_JPEG) 
            && ($info[2] !== IMAGETYPE_PNG)) {
            echo "That looks like a bad image";
            return false;
        }

        //Image is valid, so now upload and update it. 
        if(!is_uploaded_file($_FILES["profileImage"]["tmp_name"])){
            echo "Image failed to upload";
            return false;
        }

        $fileName = $_FILES["profileImage"]["name"];
        $targetname = $updateDir . "/" . $fileName;

        if (file_exists($targetname)) {
            $name = $_FILES["profileImage"]["name"];
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

         if (copy($_FILES["profileImage"]["tmp_name"], $targetname)) {
            // if we don't do this, the file will be mode 600, owned by
            // www, and so we won't be able to read it ourselves
             chmod($targetname, 0444);
            // but we can't upload another with the same name on top,
            // because it's now read-only
        } else {
            die("Error copying " . $_FILES["profileImage"]["name"]);
        }
        $targetname = "/~jefferys0/web/WebSemesterProject/UPLOADED/archive/users/" . $userName ."/" . $fileName;
        if(setImageDir($targetname, $fileName,$userID)){
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

function setImageDir($targetname, $fileName, $userID){

    try {
        $dbh = ConnectDB();
        $imageData = getUserImage($dbh,$userID);
        $imageID = $imageData[0]->image_ID;
        if($imageID != NULL) {
            //echo "<p> Image ID " . $imageID . "</p>";
            $image_query = "UPDATE  images 
                            SET image_name = :fileName, image_location = :targetname 
                            WHERE image_ID = :imageID";
            $stmt        = $dbh->prepare($image_query);
            $stmt->bindParam(':fileName', $fileName);
            $stmt->bindParam(':targetname', $targetname);
            $stmt->bindParam(':imageID', $imageID);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                echo "Image Query Error";
                return false;
            }
        }

        else{
             //echo "<p> Image ID " . $imageID . "</p>";
            $image_query = "INSERT INTO images (image_name, image_location)
                            VALUES(:fileName, :targetName)"; 
            $stmt        = $dbh->prepare($image_query);
            $stmt->bindParam(':fileName', $fileName);
            $stmt->bindParam(':targetName', $targetname);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                echo "Image Query Error";
                return false;
            }
        }

        $dbh       = ConnectDB();
        $imageData = getImageByDir($dbh, $targetname);
        $image_ID  = $imageData[0]->image_ID;

        $update_query = "UPDATE students SET image_ID = :image_ID 
                        WHERE student_ID = :userID";

        $stmt = $dbh->prepare($update_query);

        $stmt->bindParam(":image_ID", $image_ID);
        $stmt->bindParam(":userID", $userID);
        
        $stmt->execute();

        return true;

    }

    catch (PDOException $e) {
        die("PDOException at setImageDir: " . $e->getMessage());
    }

}
