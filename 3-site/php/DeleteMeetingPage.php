<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Delete meeting</title>
<link rel = "stylesheet" href = "stylesheet.css" type = "text/css" >
<?php
require_once('DatabaseConnectPage.php');
session_start();
?>
</head>
<body>
<?php

// Retrieve type of user - only mentors are permitted.
$UserType = $_SESSION['UserType'];

// A meeting ID is also required in order to delete a meeting.
if ($UserType == 3 && (isset($_GET['MeetingID']) && $_GET['MeetingID'] != '') ) {
	if (isset($_GET['action']) ) {
		$action = $_GET['action'];
	}
	echo "<h1>Delete meeting</h1>";
	
// If meeting requested to be deleted.
	if ($action == 1) {
	
// Assign mentor's ID and meeting ID to variables within the page.
		$UserID = $_SESSION['UserID'];
		$MeetingID = $_GET['MeetingID'];
		$confirmsql = "SELECT * FROM MeetingTable WHERE MentorID = $UserID AND MeetingID = $MeetingID";
		$confirmquery = mysql_query($confirmsql);
		if ($confirmquery) {
			if ($confirmarray = mysql_fetch_array($confirmquery)) {
			
// Deletes current meeting.
				$deletesql = "DELETE FROM MeetingTable WHERE MeetingID = $MeetingID";
				$deletequery = mysql_query($deletesql);
				echo "<p>Meeting successfully deleted.</p>";
				
// Redirect user to ViewMeetingPage.
				header('location:ViewMeetingPage.php');
			} else {
			
// Any errors which occur are listed here, including when a student has been mismatched and if the sql query cannot be executed because of incorrect/missing information.
				echo "<p>Error - could not find matching student. </p>";
				$action = 0;
			}
		} else {
			echo "<p>Error executing sql query - please check you have input details correctly.</p>";
			$action = 0;
		}
		if ($action == 0) {
			$day = '';
			$month = '';
			$StudentID = '';
			$MeetingID = '';
			$UserID = '';
		}
	}
	
// By default, the user is prompted with the meeting details before it is deleted. This is to confirm that this meeting is what the mentor wishes to delete.
	if ($action == 0) {
	
// Retrieve information about the meeting.
		$UserID = $_SESSION['UserID'];
		$MeetingID = $_GET['MeetingID'];
		$fetchsql = "SELECT StudentID, MeetingDate FROM MeetingTable WHERE MentorID = $UserID AND MeetingID = $MeetingID";
		$fetchquery = mysql_query($fetchsql);
		if ($fetcharray = mysql_fetch_array($fetchquery)) {
			$MeetingDate = $fetcharray[1];
			$StudentID = $fetcharray[0];
			$yyyy = "$MeetingDate[0]" . "$MeetingDate[1]" . "$MeetingDate[2]" . "$MeetingDate[3]";
			$mm = "$MeetingDate[5]" . "$MeetingDate[6]";
			$dd = "$MeetingDate[8]" . "$MeetingDate[9]";
			$datetime = date('l d F Y', mktime(0,0,0, $mm, $dd, $yyyy, 0));
			echo "<p>Current meeting date is " . $datetime . "</p>";
			echo "<form action = 'DeleteMeetingPage.php?MeetingID=$MeetingID&action=1' method = 'get'>";
			$studentsql1 = "SELECT StudentID FROM MeetingTable WHERE MentorID = $UserID AND MeetingID = $MeetingID";
			$studentquery1 = mysql_query($studentsql1);
			$studentarray = mysql_fetch_array($studentquery1);
			$StudentID = $studentarray[0];
			$studentsql2 = "SELECT StudentTable.StudentFirstName, StudentTable.StudentSurname, StudentTable.StudentID, StudentTable.StudentForm FROM StudentTable WHERE StudentID = $StudentID";
			$studentquery2 = mysql_query($studentsql2);
			if ($studentarray = mysql_fetch_array($studentquery2)) {
				echo "<p>Current student is " . "$studentarray[0] $studentarray[1], form $studentarray[3], ID $studentarray[2].</p>";
				$StudentID = $studentarray[2];
			}
			echo "<p>Current meeting ID: $MeetingID</p>";
			echo "<p><input name = 'MeetingID' type = 'hidden' value='$MeetingID'></p>";
			echo "<p><input name = 'action' type = 'hidden' value='1'></p>";
			echo "<p><input name = 'submit' type = 'submit' id='submit'></p>";
			echo "</form>";
		} else {
		
// In the case of a meeting mismatch, the error message is reported to the user.
			echo "<p>Your ID and the meeting ID do not match. Please use the user interface to perform tasks.</p>";
		}
		echo "<p><a href = 'ViewMeetingPage.php'>Cancel and view meetings</a></p>";
	}
} else {

// If the user is not a mentor, redirect them back to ViewMeetingPage.
	header('location:ViewMeetingPage.php');
}
mysql_close($ConnectServer);
?>
</body>
</html>