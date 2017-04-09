<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
 "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>Create New Group</title>
  <meta http-equiv="Content-Type"
        content="application/xhtml+xml; charset=UTF-8" />
  <meta name="Author" content="Scott Jeffery" />

  <link rel="stylesheet" href="tagline.css" />
</head>

<body>

<?php

require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');

//require_once('DBFuncs.php');

$dbh = ConnectDB();



if( isset($_POST['groupName']) && !empty($_POST['groupName'])) {
        echo "<p>Adding" . $_POST['groupName'] . "to DB\n";
        try {
                $query = 'INSERT INTO groups (groupName,groupSubject,description) ' .
                        'VALUES (:groupName, :groupSubject, :description)';
                $stmt = $dbh->prepare($query);

                $groupName = $_POST['groupname'];
                $groupSubject = $_POST['groupSubject'];
                $description = $_POST['description'];

                $stmt->bindParam(':groupName', $groupName);
                $stmt->bindParam (':groupSubject', $groupSubject);
                $stmt->bindParam(':description', $description);
                $stmt->execute();
                $inserted = $stmt->rowCount();

                $stmt = null;

                echo "<p> Inserted $inserted record(s).<p>\n";
                }
        catch(PDOException $e) {
                die('PDO Error Inserting(): ' . $e->getMEssage());
        }
}
else{
        echo "<p> No Insert </p>\n";
}

?>


<h1> Create a New Group </h1>

<p> This is a form that lets you make a new group. 
</p>

<form action="createGroup.php" method="post">
<table title="Create Group Input">
	<tr>
		<th> Group Name:
		</th>
		<td> <input type="text" name="groupName" id="groupName"/>
		</td>
	</tr>
	
	<tr>
		<th> Subject: 
		</th>
		<td> <select name="groupSubject" id="groupSubject"size="1" title="Select Subject">
			<option value = "Calculus">Calculus</option>
			<option value = "Biology">Biology</option>
			<option value = "Chemistry">Chemistry</option>
			<option value = "Physics">Physics</option>
			<option value = "ComputerScience">Computer Science</option>
			<option value = "Psychology">Psychology</option>
			<option value = "History">History</option>
			</select>
		</td>
	</tr>

	<tr>
		<th>Description:
		</th>
		<td><textarea name="description" id="description" cols="50" rows="4">(Type Description.)</textarea>
		</td>
	</tr>

	<tr>
		<th>Photo:
		</th>
		<td><textarea name="derp" cols="50" rows="4">This is where you would upload a photo.</textarea>
		</td>
	</tr>

	<tr>
		<td>
		</td>
		<td> <input type="submit"/>
		</td>

	</tr>

	</table>
	</form>

<p> Here we go bois </p>


<div id="tagline">
 <a href="./hw2-list.html"
    title="Link to homework list">
    Scott J.
 </a>

<span style="float: right;">
<a href="http://validator.w3.org/check/referer">HTML5</a> /
<a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3">
    CSS3 </a>
</span>
</div>

</body>


</html>
