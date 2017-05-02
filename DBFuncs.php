<?php
require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');


//This file is going to be for commonly used DB Functions and for testing. 

//Add to this file as you see fit :)

//Checks to see if there is a currently active session
function checkSession(){
    if(isset($_SESSION['userID'])){
	    return true;
	}
    else
        {
            //TODO: CHANGE TO INDEX.PHP
	   header('Location: http://elvis.rowan.edu/~jefferys0/web/WebSemesterProject/login.php'); 
           return false;
	}	

}


//Get the current group's creator
//Returns the current group's creator's email address.
function getCreatorEmail() {
	
    try {
		$dbh = ConnectDB();
		$studentID = $_POST['argument'][0];
        $group_query = "SELECT email FROM students WHERE student_ID = :studentID"; 
        // get creator ID
        $stmt = $dbh->prepare($group_query);
		$stmt->bindParam(":studentID", $studentID);
        $stmt->execute();

        $userID = $stmt->fetchALL(PDO::FETCH_OBJ); // creator ID now
		$creatorAddress = $userID[0]->email;
		//$creatorEmail = explode("@", $creatorAddress);
        echo $creatorAddress;//[0];

    }

    catch(PDOException $e)
    {
        die('PDO Error in    : ' . $e->getCreatorEmail());
    }

}

function addMessage() {
    try{
	$dbh = ConnectDB();	
	$groupID = $_POST['argument'][0];
	$userID = $_POST['argument'][1];
	$body = $_POST['argument'][2];				
        $query = "INSERT INTO messages (group_ID, student_ID, message_body)
                  VALUES (:groupID, :userID, :body)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':userID', $userID);
        $stmt->bindParam(':groupID', $groupID);
        $stmt->bindParam(':body', $body);
        $stmt->execute();
    }
		
    catch(PDOException $e){
        die('PDO Error in joinGroup(): ' . $e->getMessage());
    }
}
//Get the current group's creator
//Returns the current group's creator's email address.
function getCreator() {
	
    try {
	$dbh = ConnectDB();
	$groupID = $_POST['argument'][0];		
        $group_query = "SELECT creator_ID FROM groups WHERE group_ID = :groupID";
        $stmt = $dbh->prepare($group_query);
		$stmt->bindParam(":groupID", $groupID);
        $stmt->execute();
        $groupIDReturn = $stmt->fetchALL(PDO::FETCH_OBJ);
		$creatorID = $groupIDReturn[0]->creator_ID;
        echo $creatorID;

    }

    catch(PDOException $e)
    {
        die('PDO Error in    : ' . $e->getCreator());
    }


}

//Get the ID of student from a messageID,
//Returns that user's ID
function getUserIDFromMessageID($message_ID)
{
    try {
	$user_query = "SELECT student_ID FROM messages WHERE message_ID = :message_ID";
	$stmt = $dbh-> prepare($user_query);

	$stmt->bindParam(':message_ID', $message_ID);
	$stmt->execute();
	$userEmail = $stmt->fetchAll(PDO::FETCH_OBJ);
	$stmt = null;

	return $userEmail[0];

	}

	catch(PDOException $e) 	{
		die('PDO Error in getCurrentUser(): ' . $e->getEmailCurrentUser());
	}
}

//Get the current logged in user,
//Returns that user's email address.
function getEmailCurrentUser($dbh, $userID)
{
    try {
	$user_query = "SELECT email FROM students WHERE student_id = :user_ID";
	$stmt = $dbh-> prepare($user_query);

	$stmt->bindParam(':user_ID', $userID);
	$stmt->execute();
	$userEmail = $stmt->fetchAll(PDO::FETCH_OBJ);
	$stmt = null;

	return $userEmail[0];

	}

	catch(PDOException $e) 	{
		die('PDO Error in getCurrentUser(): ' . $e->getEmailCurrentUser());
	}
}

//Select Functions
//Get the current logged in user
//Returns the array containing their information.
function getUserByID($dbh, $userID)
{
    try {
	$user_query = "SELECT student_ID,fname,lname,username,email,image_ID 
                      FROM students 
                      WHERE student_id = :user_ID";
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
        $dbh = ConnectDB();
        $image_query = "SELECT * FROM images 
                        JOIN students USING(image_ID) 
                        WHERE student_ID = :userID";
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

function getUserImageAjax(){

 try{
        $dbh = ConnectDB();
        $userID = $_SESSION['userID'];
        $image_query = "SELECT * FROM images 
                        JOIN students USING(image_ID) 
                        WHERE student_ID = :userID";
        $stmt = $dbh-> prepare($image_query);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
        $imageData = $stmt->fetchAll(PDO::FETCH_OBJ);
        $stmt = null;
        echo $imageData[0]->image_location;

    }

    catch(PDOException $e)
    {
        die('PDO Error in getUserInfoThroughEmail(): ' . $e->getMessage());
    }

}


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
function leaveAGroup(){

	$userID = $_SESSION['userID'];
	$groupID = $_POST['argument'];
    $dbh = ConnectDB();	
    try{
        $query = "UPDATE groups 
                  SET group_numUsers = group_numUsers - 1
                  WHERE group_ID = :groupID";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':groupID', $groupID);
        $stmt->execute();
        $stmt = null;

        $query = "DELETE FROM belongs 
                  WHERE student_ID = :userID and group_ID = :groupID";
        $stmt = $dbh->prepare($query);
	$stmt->bindParam(':userID', $userID);
	$stmt->bindParam(':groupID', $groupID);
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
function getGroupUserList()
{
    try {
    $dbh = ConnectDB();
    $groupID = $_POST['argument'][0];

    $user_query = "SELECT student_ID,fname,lname,username,email FROM students 
                  JOIN belongs USING(student_ID) 
                  WHERE group_ID = :groupID";

    $stmt = $dbh->prepare($user_query);
    $stmt->bindParam(':groupID', $groupID);
    $stmt->execute();
    $userList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $users = json_encode($userList);
    echo $users;

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
	$group_query = "SELECT * FROM groups 
                        WHERE group_name = :groupName AND group_subject = :subject";
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
function getGroupMessageList()
{
    try {
    $dbh = ConnectDB();
    $groupID = $_POST['argument'][0];		
	$message_query = "SELECT t1.message_body, t2.username,t2.student_ID 
                          FROM messages t1 
                          INNER JOIN students t2 ON t1.student_ID = t2.student_ID 
                          WHERE group_ID = :groupID";
	$stmt = $dbh->prepare($message_query);

	$stmt->bindParam(":groupID", $groupID);
	$stmt->execute();

	$messageData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $messages = json_encode($messageData);
	echo $messages;
    }

    catch(PDOException $e)
    {
        die('PDO Error in getGroupMessages: ' . $e->getMessage());
    }

}

function getImageWithID()
{
	try {
	$dbh = ConnectDB();	
	$groupID = $_POST['argument'];	
        $group_query = "SELECT t1.student_ID, t2.image_location FROM students t1 
                        INNER JOIN images t2 ON t1.image_ID = t2.image_ID 
                        INNER JOIN belongs t3 ON t3.student_ID = t1.student_ID 
                        INNER JOIN groups t4 ON t4.group_ID = t3.group_ID 
                        WHERE t4.group_ID = :groupID";
        $stmt = $dbh->prepare($group_query);

        $stmt->bindParam(":groupID", $groupID);
        $stmt->execute();

        $result_array = $stmt->fetchAll(PDO::FETCH_OBJ);
	$groupData = json_encode($result_array);
        echo $groupData;
        }

        catch(PDOException $e)
        {
                die('PDO Error in getGroupByID: ' . $e->getMessage());
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
        $group_query = "SELECT * FROM groups 
                        WHERE creator_ID = :userID AND group_ID = :groupID";

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
    $stmt->bindParam(':passWord', md5($password));
    $stmt->execute();
	
    if ($stmt -> rowCount() == 0) {
            echo "0";
    }
    else{
	$studentData = $stmt->fetchAll(PDO::FETCH_OBJ);
	    
	$user_query = "SELECT change_password
                    FROM students 
                    WHERE change_password = 1 and username = :userName";
	$stmt = $dbh-> prepare($user_query);
	$stmt->bindParam(':userName', $username);
	$stmt->execute();

	if ($stmt -> rowCount() == 0) {
		$_SESSION['username'] = $username;
		$_SESSION['userID'] = $studentData[0] -> student_id;
                echo "1";
	}
	else
	{
		$_SESSION['username'] = $username;
		$_SESSION['userID'] = $studentData[0] -> student_id;
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
    $user_query = "UPDATE students SET password = :Password,change_password = 0 
                    WHERE username = :userName;";
    $stmt = $dbh-> prepare($user_query);
    $stmt->bindParam(':userName', $username);
    $stmt->bindParam(':Password', md5($password));
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
                    WHERE email = :Email 
                    AND TIMESTAMPDIFF(MINUTE, change_password_time, now()) > 5;";
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
	    $stmt->bindParam(':Password', md5($password));
	    $stmt->execute();
	    echo "1";
	}
    }
    exit();	
}

function getGroupImage($dbh, $groupID)
{

    try{
        $image_query = "SELECT * FROM images 
                        JOIN groups USING(image_ID) WHERE group_ID = :groupID";
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

function getGroupImageAjax(){
	try{
        $dbh = ConnectDB();
        $image_query = "SELECT * FROM images 
                        JOIN groups USING(image_ID) 
                        WHERE group_ID = :groupID";
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

//Checks if the username and email enterd are unique and adds them to the DB
function checkUserRegistration()
{
    $dbh = ConnectDB();
    $username = $_POST['argument'][0];
    $email = $_POST['argument'][1];
    $password = $_POST['argument'][2];


    if($username != strip_tags($username) || $email != strip_tags($email)) {
        // contains HTML, dont allow registration.
        echo "0";
    }

    else {
        $name_query = "SELECT username FROM students WHERE username = :userName";
        $stmt = $dbh-> prepare($name_query);
        $stmt->bindParam(':userName', $username);
        $stmt->execute();

        if ($stmt -> rowCount() == 0) {
            //Username was unique
            $email_query = "SELECT email FROM students WHERE email = :email";      
            $stmt = $dbh-> prepare($email_query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

	    if ($stmt -> rowCount() == 0) {
                //Email was unique
                $hash = md5( rand(0,1000) );
	        $reg_query = "INSERT INTO students (username, password, email, hash_link) 
                            VALUES(:userName, :password, :email, :hash)";
	        $stmt = $dbh-> prepare($reg_query);
	        $stmt->bindParam(':userName', $username);
	        $stmt->bindParam(':password', md5($password));
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':hash', $hash);
	        $stmt->execute();
                //Account added to DB

                //Send verification email
                $to      = $email;
                $subject = 'Verify Account';
                $message = '
                Thanks for signing up!
                Your account has been created, please click the following link to active your account:
                http://elvis.rowan.edu/~hudson37/web/WebSemesterProject/verify.php?email='.$email.'&hash='.$hash.'
                ';
                     
                $headers = 'From:noreply@website.com' . "\r\n"; // Set from headers
                mail($to, $subject, $message, $headers); // Send our email

                //Create directory for users files
                mkdir("/home/jefferys0/public_html/web/WebSemesterProject/UPLOADED/archive/users/". $username, 0777);
                chmod("/home/jefferys0/public_html/web/WebSemesterProject/UPLOADED/archive/users/". $username, 0777);
	    
                //Registration was successful.
                echo "3";
            }

            else {
	        //If the email already exists;.
                echo "2";
            }
        }
        else {
    	    //If the username already exists.
	    echo "1";
        }
    }
    exit();
}

//Updates for the username formbox on key up wheather the username is avaliable or not.
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

//Updates for the email formbox on key up wheather the email is avaliable or not.
function checkEmailReg()
{
    $dbh = ConnectDB();
    $email = $_POST['argument'][0];
    $name_query = "SELECT email FROM students WHERE email = :email";
    $stmt = $dbh-> prepare($name_query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    if ($stmt -> rowCount() == 0) {
        echo "1";
    }
    else {
        echo "0";
    }
}

function joinGroups()
{
    if(checkSession()) {
	$groupName = $_POST['argument'][0];
	$subject = $_POST['argument'][1];

	$query = "SELECT group_ID,group_name,group_subject,group_numUsers,group_description 
                FROM groups 
                WHERE group_name = :GroupName AND group_subject = :Subject";
	$dbh = ConnectDB();
        $stmt = $dbh-> prepare($query);
	$stmt->bindParam(':GroupName', $groupName);
	$stmt->bindParam(':Subject', $subject);
	$stmt->execute();
	
	$result_array = $stmt->fetchAll(PDO::FETCH_OBJ);
        $groupData = json_encode($result_array);
        $stmt = null;
        echo $groupData;

     }
     else
	echo 0;

}

function getUsernameFromID(){
    try {
	$dbh = ConnectDB();
	$studentID = $_POST['argument'][0];		
        $userID_query = "SELECT username FROM students 
                        WHERE student_ID = :studentID";
        $stmt = $dbh->prepare($userID_query);
		$stmt->bindParam(":studentID", $studentID);
        $stmt->execute();
        $usernameReturn = $stmt->fetchALL(PDO::FETCH_OBJ);
		$NameofUser = $usernameReturn[0]->username;
        echo $NameofUser;

    }

    catch(PDOException $e)
    {
        die('PDO Error in    : ' . $e->getCreator());
    }


}	

function joinTheGroup(){

    $groupName = $_POST['argument'][0];
    $subject = $_POST['argument'][1];	
    if(checkSession()){
        $subject = str_replace(' ','',$subject);
	$query = "SELECT group_ID, group_name,group_subject,group_numUsers,group_description 
                FROM groups WHERE group_name = :GroupName AND group_subject = :Subject";
        $dbh = ConnectDB();
        $stmt = $dbh-> prepare($query);
        $stmt->bindParam(':GroupName', $groupName);
        $stmt->bindParam(':Subject', $subject);
        $stmt->execute();
	
	if ($stmt -> rowCount() == 0) {
            echo 0;
    	}
	else{

	   $result_array = $stmt->fetchAll(PDO::FETCH_OBJ);
	   $groupID = $result_array[0] -> group_ID;
	   $studentID = $_SESSION['userID'];
	   
	   $query = "SELECT * FROM belongs 
                    WHERE student_ID = :StudentID AND group_ID = :GroupID";
           $dbh = ConnectDB();
           $stmt = $dbh-> prepare($query);
           $stmt->bindParam(':GroupID', $groupID);
           $stmt->bindParam(':StudentID', $studentID);
           $stmt->execute();

	   if ($stmt -> rowCount() != 0) {
            echo 3;
	   }
	   else
	   {
	   
	   $query = "INSERT INTO belongs (student_ID,group_ID) 
                    VALUES (:StudentID,:GroupID)";
	   $dbh = ConnectDB();
           $stmt = $dbh-> prepare($query);
	   $stmt->bindParam(':GroupID', $groupID);
           $stmt->bindParam(':StudentID', $studentID);
	   $stmt->execute();


	    $query = "UPDATE groups SET group_numUsers = group_numUsers+'1' 
                      WHERE group_ID = :GroupID";
           $dbh = ConnectDB();
           $stmt = $dbh-> prepare($query);
           $stmt->bindParam(':GroupID', $groupID);
           $stmt->execute();

	   echo 1;
	   
	    }
        }
    }
    else
    {
	echo  2;	
    }
}



function getGroups()
{

    if(checkSession())
    {

    $case = $_POST['argument'][0];//0-all, 1-in, 2-created
    switch($case){
    case 0:
	$query = "SELECT group_name, t1.group_numUsers, t1.image_ID,t1.group_description, t1.group_ID 
                FROM groups t1 
		INNER JOIN belongs t2 ON t1.group_ID = t2.group_ID
		INNER JOIN students t3 ON t2.student_ID = t3.student_ID 
		WHERE t3.username = :Username
		ORDER BY group_name";
	break;
    case 1:
	$query = "SELECT group_name, t1.group_numUsers, t1.image_ID,t1.group_description, t1.group_ID 
                FROM groups t1 
                INNER JOIN belongs t2 ON t1.group_ID = t2.group_ID 
                INNER JOIN students t3 ON t2.student_ID = t3.student_ID 
                WHERE t3.username = :Username AND t3.student_ID != t1.creator_ID
                ORDER BY group_name";
	break;
    case 2:
	$query = "SELECT group_name, t1.group_numUsers, t2.image_ID,t1.group_description, t1.group_ID 
                FROM groups t1 
                INNER JOIN students t2 ON t1.creator_ID = t2.student_ID 
                WHERE t2.username = :Username
                ORDER BY group_name";
            break;
    }


    $dbh = ConnectDB();	
    $username = $_SESSION['username'];
    $stmt = $dbh-> prepare($query);
    $stmt->bindParam(':Username', $username);
    $stmt->execute();

    $result_array = $stmt->fetchAll(PDO::FETCH_OBJ);
    $groupData = json_encode($result_array);
    $stmt = null;
    echo $groupData;
    }
    else
	echo 0;
}

function deleteGroup(){
   if(isset($_SESSION['groupIDEdit']) && !empty($_SESSION['groupIDEdit'])){
    try{
        $query = "DELETE FROM groups WHERE group_ID = :groupID";
        $groupID = $_SESSION['groupIDEdit'];
        $_SESSION['groupIDEdit'] = NULL;
        $_SESSION['groupNameEdit'] = NULL;
        
        $dbh = ConnectDB();
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':groupID', $groupID);
        $stmt->execute();
        $deleted = $stmt->rowCount();
        if($deleted == 0){
        echo "Deleted 0";
        exit();
        }

        echo 0;
        exit();
        }

    catch(PDOException $e){
        die("Wtf??") . $e->getMessage();
    }
}
    else{
        print "Error";
        exit();
    }

}




function getSessionVar(){
	echo $_SESSION['username'];
}
function getSessionUserID(){
	echo $_SESSION['userID'];
}
function sessionOff(){
	$_SESSION['username'] = NULL;
	$_SESSION['userID'] = NULL;
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
    case 'getGroups':
	getGroups();
	break;
    case 'checkUserRegistration':
	checkUserRegistration();
	break;
    case 'checkUsernameReg':
	checkUsernameReg();
	break;
    case 'checkEmailReg':
        checkEmailReg();
        break;
    case 'getSessionVar':
	getSessionVar();
	break;
    case 'sessionOff':
	sessionOff();
	break;
    case 'joinGroups':
	joinGroups();
	break;
    case 'deleteGroup':
        deleteGroup();
        break;
    case 'getCreator':
        getCreator();
        break;
    case 'getCreatorEmail':
        getCreatorEmail();
        break;	
    case 'getSessionUserID':
	getSessionUserID();
	break;
    case 'addMessage':
	addMessage();
	break;	
    case 'getGroupUserList':
	getGroupUserList();
	break;	
    case 'leaveAGroup':
	leaveAGroup();
	break;
    case 'getSessionUserID':
	getSessionUserID();
	break;
    case 'addMessage':
	addMessage();
	break;	
    case 'getGroupUserList':
	getGroupUserList();
	break;
    case 'getGroupMessageList';		
	getGroupMessageList();
	break;	 	
    case 'getUserImageAjax':
        getUserImageAjax();
        break;
    case 'getUsernameFromID':
        getUsernameFromID();
	break;
    case 'joinTheGroup':
	joinTheGroup();
	break;	    
    case 'getImageWithID':
	getImageWithID();
	break;    
}

?>
