<?php 

//Checks to see if a group already exists in the DB.

require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');

$dbh = connectDB();

if(isset($_POST['group_name'])) {

	try {
		$query = 'SELECT group_name FROM groups where group_name = :group_name';

		$stmt = $dbh -> prepare($query);

		$groupName = $_POST['group_name'];
		$groupName = strip_tags($groupName);

		$stmt->bindParam(':group_name', $groupName);

		$stmt -> execute();

		if ($stmt -> rowCount() != 0) {
			echo "Group Name Already Exists";
		}
		else{
			echo "Group Name is Available!";
		}

		exit();
	}

	catch(PDOException $e) {
		echo "Something went wrong";
		die ('PDO Error Getting(): ' . $e->getMessage());
	}
}
