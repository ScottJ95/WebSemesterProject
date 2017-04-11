<?php

//This file is going to be for commonly used DB Functions and for testing. 

//Add to this file as you see fit :)


//Get the current logged in user
//Returns the array containing their information.
function getUserByID($dbh, $userID)
{
	try{
		$user_query = "SELECT student_id,fname,lname,username,email FROM students WHERE student_id = :user_ID";
		$stmt = $dbh-> prepare($user_query);

		$stmt->bindParam(':user_ID', $userID);
		$stmt->execute();
		$userData = $stmt->fetchAll(PDO::FETCH_OBJ);
		$stmt = null;

		return $userData;

	}

	catch(PDOException $e) 
	{
		die('PDO Error in getCurrentUser(): ' . $e->getMessage());
	}
}

//TODO
function getGroupUserList($dbh, $group_ID)
{
 
}


//TODO
function getAllGroups($dbh)
{


}


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

//TODO
function getMatchinGroupNameSubject($dbh, $groupName, $subject)
{

}

//TODO
function getGroupByID($dbh, $groupID)
{

}


//TODO
function getGroupMessageList($dbh, $groupID)
{

}

//TODO
function getCreatedGroups($dbh,$userID)
{


}


?>
