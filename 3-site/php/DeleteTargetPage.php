<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Delete target</title>
<link rel = "stylesheet" href = "stylesheet.css" type = "text/css" >
<?php
require_once ('DatabaseConnectPage.php');
session_start();
?>
</head>
<body>
<?php

// Retrieves user type.
$UserType = $_SESSION['UserType'];

// Page contents only accessible to mentors.
if ($UserType == 3) {

// Primary key of meeting is required before user can be verified.
	$MeetingID = $_GET['MeetingID'];
	
// User is checked if they match with the target's meeting.
	$UserID = $_SESSION['UserID'];
	$validatemeetingsql = "SELECT * FROM MeetingTable WHERE MentorID = $UserID AND MeetingID = $MeetingID";
	$validatemeetingquery = mysql_query($validatemeetingsql);
	if ($validatemeetingarray = mysql_fetch_array($validatemeetingquery)) {
	
// Target ID is required before target can be deleted.
		$TargetID = $_GET['TargetID'];
		$targetsql = "SELECT TargetUserType, UserTarget, DateDue, StudentComment, ParentComment, MentorComment, TargetMetYet FROM TargetTable WHERE TargetID = $TargetID";
		$targetquery = mysql_query($targetsql);
		if ($targetarray = mysql_fetch_array($targetquery)) {
		
// Target information is retrieved and may be used in displaying details before it is actually deleted.
			$action = $_GET['action'];
			
// Deleting the target is an SQL query.
			if ($action == 1) {
				$deletesql = "DELETE FROM TargetTable WHERE TargetID = $TargetID";
				$deletequery = mysql_query($deletesql);
				header("location:ViewTargetPage.php?MeetingID=$MeetingID");
			}
			if ($action == 0) {
			
// Variables are set before they can be used.
				$currentday = '';
				$currentmonth = '';
				$day = '';
				$month = '';
				$yyyy = '';
				$mm = '';
				$dd = '';
				$displayparent = 0;
				$TargetUserType = $targetarray[0];
				$UserTarget = $targetarray[1];
				$DateDue = $targetarray[2];
				if ($targetarray[3] != '') {
					$StudentComment = $targetarray[3];
				} else {
					$StudentComment = '';
				}
				if ($targetarray[4] != '') {
					$ParentComment = $targetarray[4];
				} else {
					$ParentComment = '';
				}
				if ($targetarray[5] != '') {
					$MentorComment = $targetarray[5];
				} else {
					$MentorComment = '';
				}
				$TargetMetYet = $targetarray[6];

// Here a form is used. There is no significant difference in this case to using a hyperlink. The structure of AddMeetingPage, EditMeetingPage, DeleteMeetingPage, AddTargetPage, EditTargetPage and DeleteTargetPage all have a similar basis.
				echo "<form action = 'DeleteTargetPage.php?MeetingID=$MeetingID&TargetID=$TargetID&action=1' method = 'get'>";
				
// Target's information is displayed to user.
				echo "<p>Target for user: ";
				if ($TargetUserType == 1) {
					echo "student";
				} else if ($TargetUserType == 2) {
					echo "parent";
				} else if ($TargetUserType == 3) {
					echo "mentor";
				}
				echo "</p>";
				echo "<p>Target: " . $UserTarget . "</p>";
				echo "<p>Student comment: " . $StudentComment . "</p>";
				echo "<p>Parent comment: " . $ParentComment . "</p>";
				echo "<p>Mentor comment: " . $MentorComment . "</p>";
				$yyyy = "$DateDue[0]" . "$DateDue[1]" . "$DateDue[2]" . "$DateDue[3]";
				$mm = "$DateDue[5]" . "$DateDue[6]";
				$dd = "$DateDue[8]" . "$DateDue[9]";
				$datetime = date('l j F', mktime(0,0,0, $mm, $dd, $yyyy, 0));
				echo "<p>Date target due: ";
				echo "$datetime</p>";
				echo "<p>Target Met: ";
				if ($TargetMetYet == 1) {
					echo "Yes";
				} else if ($TargetMetYet == 0) {
					echo "No";
				}
				echo "</p>";
				echo "<input name = 'MeetingID' type = 'hidden' value = '$MeetingID'>";
				echo "<input name = 'action' type = 'hidden' value = '1'>";
				echo "<input name = 'TargetID' type = 'hidden' value = '$TargetID'>";
				echo "<p><input name = 'submit' type = 'submit' id='submit'></p>";
				echo "</form>";
				echo "<p><a href = 'ViewTargetPage.php?MeetingID=$MeetingID'>Cancel and view targets</a></p>";
			}
		} else {
		
// User must be connected with meeting target belongs to and must be a mentor, otherwise they are redirected back to ViewTagretPage.
			header("location:ViewTargetPage.php?MeetingID=$MeetingID");
		}
	} else {
		header("location:ViewTargetPage.php?MeetingID=$MeetingID");
	}
} else {
	header("location:ViewTargetPage.php?MeetingID=$MeetingID");
}
?>
</body>
</html>