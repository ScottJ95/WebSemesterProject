<?php

//This file is going to be for commonly used DB Functions and for testing. 

//Add to this file as you see fit :)


//Select Functions

//Get the current logged in user
//Returns the array containing their information.
function getUserByID($dbh, $userID)
{
	try {
		$user_query = "SELECT student_ID,fname,lname,username,email FROM students WHERE student_id = :user_ID";
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

//TODO: SEE IF WE CAN USE COALESCE HERE
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
function getMatchinGroupNameSubject($dbh, $groupName, $subject)
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

//Check to see if someone has a account
function getUserInfoThroughEmail($dbh, $userEmail)
{
	try{
                $user_query = "SELECT student_id,fname,lname,username,email FROM students WHERE email = :userEmail";
                $stmt = $dbh-> prepare($user_query);

                $stmt->bindParam(':userEmail', $userEmail);
                $stmt->execute();
                $userData = $stmt->fetchAll(PDO::FETCH_OBJ);
                $stmt = null;

                return $userData;

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


?>
