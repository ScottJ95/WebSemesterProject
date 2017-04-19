<?php
session_start();

$query = "SELECT email FROM students WHERE username = " . $_SESSION['username'] . ""; //current email of the logged in user we are loading the calendar for
$email = mysql_query($query);
$userInfo = explode("@", $email); //separate the id from the full email string
$dom = new domDocument; //document we will adjust
$dom->loadHTML('<iframe id="calendar" src="" style="border: 0" width="1400px" height="1050px" frameborder="0" scrolling="no"></iframe>');//the section of html we are loading in

$element = $dom->getElementsById( 'calendar' );//get the loaded html snipped

if($element instanceof DOMNodeList){
	foreach($element as $domElement){//verify that the retrieved element is still valid
        $domElement->setAttribute('src', 'https://calendar.google.com/calendar/embed?src=' + $userInfo[0] + '%40gmail.com&ctz=America/New_York');//add the googleCalendar embed code to load correct calendar
	}
}
?>