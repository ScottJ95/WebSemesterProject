<?php
require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');


//This file is going to be for commonly used DB Functions and for testing. 

//Add to this file as you see fit :)

//Checks to see if there is a currently active session
function checkSession(){
    if(isset($_SESSION['userID'])){
            //$userData = getUserByID($dbh, $_SESSION['userID']);
            //echo $userData[0]->student_ID;
            //foreach($userData as $user){
            //echo "<p> Hi User Num " . $user->student_ID . ", "
            //. $user->username . "</p>";
            //	}
	    //echo "<p> Hi User Num " . $_SESSION['userID'] . "</p> \n";
	    return true;
	}
	else{
	    return false;
	}	

}


//Select Functions



//Get the current logged in user
//Returns the array containing their information.
function getUserByID($dbh, $userID)
{
    try {
	$user_query = "SELECT student_ID,fname,lname,username,email,image_ID FROM students WHERE student_id = :user_ID";
	$stmt = $dbh-> prepare($user_query);

	$stmt->bindParam(':user_ID', $userID);
	$stmt->execute();
	$userData = $stmt->fetchAll(PDO::FETCH_OBJ);
	$stmt = null;

	return $userData;

	}

	catch(PDOException $e) 	{
		die('PDO Error in getCurrentUser(): ' . $e->getMessage());
	}
}

function getUserImage($dbh, $userID)
{

    try{
        $image_query = "SELECT * FROM images join students using(image_ID) where student_ID = :userID";
        $stmt = $dbh-> prepare($image_query);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
        $imageData = $stmt->fetchAll(PDO::FETCH_OBJ);
        $stmt = null;
        return $imageData;

    }

    catch(PDOException $e)
    {
        die('PDO Error in getUserInfoThroughEmail(): ' . $e->getMessage());
    }


}

//TODO TEST
function joinGroup($dbh, $userID, $groupID) {
    try{
        $query = "UPDATE groups 
                  SET group_numUsers = group_numUsers + 1
                  WHERE group_ID = :groupID";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':groupID', $groupID);
        $stmt->execute();
        $stmt = null;
        $dbh = ConnectDB();
        
        $query = "INSERT INTO belongs (student_ID, group_ID)
                  VALUES (:userID, :groupID)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':userID', $userID);
        $stmt->bindParam(':groupID', $groupID);
        $stmt->execute();
    }

    catch(PDOException $e){
        die('PDO Error in joinGroup(): ' . $e->getMessage());
    }
}

//UserID Leaves GroupID
//TODO TEST
function leaveGroup($dbh, $userID, $groupID){
    try{
        $query = "UPDATE groups 
                  SET group_numUsers = group_numUsers - 1
                  WHERE group_ID = :groupID";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':groupID', $groupID);
        $stmt->execute();
        $stmt = null;
        $dbh = ConnectDB();

        $query = "DELETE FROM belongs 
                  WHERE student_ID = :userID";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
    }

    catch(PDOException $e){
        die('PDO Error in leaveGroup(): ' . $e->getMessage());
    }

}
//Check to see if a user belongs in a group
function checkBelongs($dbh, $userID, $groupID) {

    try{
        $query = "SELECT * FROM belongs 
                 WHERE student_ID = :userID AND group_ID = :groupID";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':userID', $userID);
        $stmt->bindParam(':groupID', $groupID);
        $stmt->execute();

        $belongsData = $stmt->fetchAll(PDO::FETCH_OBJ);
        if($belongsData[0]->student_ID == $userID && $belongsData[0]->group_ID == $groupID){
            return true;
        }
        else{
            return false;
        }
        }

    catch(PDOException $e) {
        die("Error with Check Belongs " . $e->getMessage());
    }
        
}
//Get the list of users that belong to a group.
function getGroupUserList($dbh, $groupID)
{
    try {
	$user_query = "SELECT student_ID,fname,lname,username,email FROM students " 
			. "JOIN belongs USING (group_ID) JOIN groups USING (student_ID) "
    			. "WHERE group_ID = :groupID";

	$stmt = $dbh->prepare($user_query);
	$stmt->bindParam(':group_ID', $groupID);
	$stmt->execute();
	$userList = $stmt->fetchAll(PDO::FETCH_OBJ);
	$stmt = null;
	return $userData;
		
	}

	catch(PDOException $e) 
	{
		die('PDO Error in groupUserList: ' . $e->getMessage());
	}	
}


//Get the list of all groups
function getAllGroups($dbh)
{
    try {
	$group_query = "SELECT * FROM groups";
	$stmt = $dbh->prepare($group_query);
	$stmt->execute();
	$groupData = $stmt->fetchAll(PDO::FETCH_OBJ);
	$stmt = null;

	return $groupData;
        }

        catch(PDOException $e)
        {
                die('PDO Error in    : ' . $e->getMessage());
        }

}

//Get a group based on their name.
function getMatchingGroupName($dbh, $groupName)
{	
    try{
	$group_query = "SELECT * FROM groups WHERE group_name = :groupName";
	$stmt = $dbh->prepare($group_query);

	$stmt->bindParam(":groupName", $groupName);

	$stmt->execute();

	$groupData = $stmt->fetchAll(PDO::FETCH_OBJ);
	$stmt = null;

	return $groupData;
	}

	catch(PDOException $e){
	    die("PDOException at getMatchingGroupName" . $e->getMessage());
	}
}

//Get a list of groups based on their subject.
function getMatchingGroupNameSubject($dbh, $groupName, $subject)
{
    try {
	$group_query = "SELECT * FROM groups WHERE group_name = :groupName AND group_subject = :subject";
	$stmt = $dbh->prepare($group_query);

	$stmt->bindParam(":groupName", $groupName);
	$stmt->bindParam(":subject", $subject);

	$stmt->execute();

	$groupData = $stmt->fetchAll(PDO::FETCH_OBJ);

	return $groupData;
        }

        catch(PDOException $e)
        {
                die('PDO Error in getMatchingGroupSubject: ' . $e->getMessage());
        }

}

//get a group by their ID
function getGroupByID($dbh, $groupID)
{
    try {
	$group_query = "SELECT * FROM groups WHERE group_ID = :groupID";
	$stmt = $dbh->prepare($group_query);

	$stmt->bindParam(":groupID", $groupID);
	$stmt->execute();

	$groupData = $stmt->fetchAll(PDO::FETCH_OBJ);

	return $groupData;
        }

        catch(PDOException $e)
        {
                die('PDO Error in getGroupByID: ' . $e->getMessage());
        }

}


//Get a group's messages by their ID
function getGroupMessageList($dbh, $groupID)
{
    try {
	$message_query = "SELECT * FROM messages WHERE group_ID = :groupID";
	$stmt = $dbh->prepare($message_query);

	$stmt->bindParam(":groupID", $groupID);
	$stmt->execute();

	$messageData = $stmt->fetchAll(PDO::FETCH_OBJ);

	return $messageData;
    }

    catch(PDOException $e)
    {
        die('PDO Error in getGroupMessages: ' . $e->getMessage());
    }

}

//Get all the groups userID has created
function getCreatedGroups($dbh, $userID)
{
    try {
	$group_query = "SELECT * FROM groups WHERE creator_ID = :userID";

	$stmt = $dbh->prepare($group_query);
	$stmt->bindParam(":userID", $userID);
	$stmt->execute();

	$groupData = $stmt->fetchALL(PDO::FETCH_OBJ);

	return $groupData;

    }

    catch(PDOException $e)
    {
        die('PDO Error in    : ' . $e->getMessage());
    }
	
}

function checkCreator($dbh, $userID, $groupID) {
	
    try {
        $group_query = "SELECT * FROM groups WHERE creator_ID = :userID AND group_ID = :groupID";

        $stmt = $dbh->prepare($group_query);
	$stmt->bindParam(":userID", $userID);
	$stmt->bindParam(":groupID", $groupID);
        $stmt->execute();

        $groupData = $stmt->fetchALL(PDO::FETCH_OBJ);

        return $groupData;

    }

    catch(PDOException $e)
    {
        die('PDO Error in    : ' . $e->getMessage());
    }


}

//Check to see if someone has a account
function checkUsername()
{
    $dbh = ConnectDB();
    $username = $_POST['argument'][0];
    $password = $_POST['argument'][1];
    $user_query = "SELECT student_id,fname,lname,username,email 
                    FROM students 
                    WHERE username = :userName and password = :passWord";
    $stmt = $dbh-> prepare($user_query);
    $stmt->bindParam(':userName', $username);
    $stmt->bindParam(':passWord', $password);
    $stmt->execute();

    if ($stmt -> rowCount() == 0) {
            echo "0";
    }
    else{
	$user_query = "SELECT change_password 
                    FROM students 
                    WHERE change_password = 1 and username = :userName";
	$stmt = $dbh-> prepare($user_query);
	$stmt->bindParam(':userName', $username);
        $stmt->execute();
	if ($stmt -> rowCount() == 0) {
		$_SESSION['username'] = $username;
                echo "1";
	}
	else
	{
	    $_SESSION['username'] = $username;
            echo "2";
	}
    }
    exit();
}

function changePassword()
{
    $dbh = ConnectDB();
    $username = $_SESSION['username'];
    $password = $_POST['argument'];
    $user_query = "UPDATE students SET password = :Password,change_password = 0 WHERE username = :userName;";
    $stmt = $dbh-> prepare($user_query);
    $stmt->bindParam(':userName', $username);
    $stmt->bindParam(':Password', $password);
    $stmt->execute();
    echo "1";
    exit();
}

function checkEmail()
{
    $dbh = ConnectDB();
    $email = $_POST['argument'][0];
    $user_query = "SELECT change_password_time FROM students WHERE email = :Email";
    $stmt = $dbh-> prepare($user_query);
    $stmt->bindParam(':Email', $email);
    $stmt->execute();
	
    if ($stmt -> rowCount() == 0) {
        echo "0";
    }
    else{	
	$user_query = "SELECT change_password_time 
                    FROM students 
                    WHERE email = :Email and TIMESTAMPDIFF(MINUTE, change_password_time, now()) > 5;";
        $stmt = $dbh-> prepare($user_query);
        $stmt->bindParam(':Email', $email);
        $stmt->execute();
	if ($stmt -> rowCount() == 0){
	    echo "2";
	}
	else
	{
	    $password = $_POST['argument'][1];
	    $to      = $email;
	    $subject = 'Reddit 2.0 - Password Reset';
	    $message = 'New password is : '. $password;
	    mail($to, $subject, $message);

	    $user_query = "UPDATE students 
                            SET password = :Password,change_password_time = now(),change_password = 1 
                            WHERE email = :Email;";
	    $stmt = $dbh-> prepare($user_query);
	    $stmt->bindParam(':Email', $email);
	    $stmt->bindParam(':Password', $password);
	    $stmt->execute();
	    echo "1";
	}
    }
    exit();	
}

function getGroupImage($dbh, $groupID)
{

    try{
        $image_query = "SELECT * FROM images join groups using(image_ID) where group_ID = :groupID";
        $stmt = $dbh-> prepare($image_query);
        $stmt->bindParam(':groupID', $groupID);
        $stmt->execute();
        $imageData = $stmt->fetchAll(PDO::FETCH_OBJ);
	$stmt = null;
        return $imageData;

    }

    catch(PDOException $e)
    {
        die('PDO Error in getUserInfoThroughEmail(): ' . $e->getMessage());
    }


}



function getImageByDir($dbh, $dir) 
{

	try{
                $image_query = "SELECT image_ID FROM images where image_location = :dir";
                $stmt = $dbh-> prepare($image_query);

                $stmt->bindParam(':dir', $dir);
                $stmt->execute();
                $imageData = $stmt->fetchAll(PDO::FETCH_OBJ);
                $stmt = null;

                return $imageData;

        }

        catch(PDOException $e)
        {
                die('PDO Error in getUserInfoThroughEmail(): ' . $e->getMessage());
        }

}
function checkUserRegistration()
{
    $dbh = ConnectDB();
    $username = $_POST['argument'][0];
    $email = $_POST['argument'][1];
    $password = $_POST['argument'][2];

    $name_query = "SELECT username FROM students WHERE username = :userName";
    $stmt = $dbh-> prepare($name_query);
    $stmt->bindParam(':userName', $username);
    $stmt->execute();

    if ($stmt -> rowCount() == 0) {
        $email_query = "SELECT email FROM students WHERE email = :email";
        $stmt = $dbh-> prepare($email_query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
	if ($stmt -> rowCount() == 0) {
		$reg_query = "INSERT INTO students (username, password, email) VALUES(:userName, :password, :email)";
		$stmt = $dbh-> prepare($reg_query);
		$stmt->bindParam(':userName', $username);
		$stmt->bindParam(':password', $password);
                $stmt->bindParam(':email', $email);
		$stmt->execute();
		//Registration was successful.
                echo "2";
        }
        else
	{
		//If the email already exists;.
                echo "1";
        }
 
	}
	else{
		//If the username already exists.
		echo "0";
	}
        exit();
}

function checkUsernameReg()
{
        $dbh = ConnectDB();
        $username = $_POST['argument'][0];
        $name_query = "SELECT username FROM students WHERE username = :userName";
        $stmt = $dbh-> prepare($name_query);
        $stmt->bindParam(':userName', $username);
        $stmt->execute();
	if ($stmt -> rowCount() == 0) {
		echo "1";
	}
	else {
		echo "0";
	}
}

function checkEmailReg()
{
        $dbh = ConnectDB();
        $email = $_POST['argument'][0];
        $name_query = "SELECT email FROM students WHERE email = :Email";
        $stmt = $dbh-> prepare($name_query);
        $stmt->bindParam('Email', $email);
        $stmt->execute();
        if ($stmt -> rowCount() == 0) {
                echo "1";
        }
        else {
                echo "0";
        }
}


session_start();
switch($_POST['functionName']) {
	case 'checkEmail':
		checkEmail();
		break;
	case 'checkUsername':
		checkUsername();
		break;			
	case 'changePassword':
		changePassword();
		break;
	case 'checkUserRegistration':
		checkUserRegistration();
		break;
}

?>
