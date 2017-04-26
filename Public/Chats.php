//Send new message into database
function submitNewMessage($group_ID, $student_ID, $message_date, $message_body)
{
    try {
        $message_query = "INSERT INTO messages (group_ID, student_ID, message_date, message_body) VALUES (:group_ID, :student_ID, :message_date, :message_body)";
        $dbh         = ConnectDB();
        $stmt        = $dbh->prepare($message_query);
        
        $stmt->bindParam(':group_ID', $group_ID);
        $stmt->bindParam(':student_ID', $student_ID);
        $stmt->bindParam(':message_date', $message_date);
        $stmt->bindParam(':message_body', $message_body);        
        $stmt->execute();
	}
}

//send reply to database
function submitNewMessage($group_ID, $student_ID, $message_date, $message_body, $reply_ID)
{
    try {
        $message_query = "INSERT INTO messages (group_ID, student_ID, message_date, message_body, reply_ID) VALUES (:group_ID, :student_ID, :message_date, :message_body)";
        $dbh         = ConnectDB();
        $stmt        = $dbh->prepare($message_query);
        
        $stmt->bindParam(':group_ID', $group_ID);
        $stmt->bindParam(':student_ID', $student_ID);
        $stmt->bindParam(':message_date', $message_date);
        $stmt->bindParam(':message_body', $message_body);        
		$stmt->bindParam(':reply_ID', $reply_ID);
        $stmt->execute();
	}
}