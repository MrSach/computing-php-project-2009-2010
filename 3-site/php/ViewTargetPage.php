<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>View targets</title>
<link rel = "stylesheet" href = "stylesheet.css" type = "text/css" >
<?php
require_once ('DatabaseConnectPage.php');
session_start();
?>
</head>
<body>
<?php

// $UserID is the ID of current user.
// $UserType is the type of user which the current user is.
$UserID = $_SESSION['UserID'];
$UserType = $_SESSION['UserType'];
$userlinked = 0;

// Looks to see if a meeting ID is set. If so, proceed to obtain it and retrieve stored variable from previous page.
if (isset($_GET['MeetingID']) && $_GET['MeetingID'] != '') {
	$MeetingID = $_GET['MeetingID'];
	echo "<h1>View targets</h1><hr />";
	switch ($UserType) {
		case 1:
			$confirmsql = "SELECT * FROM MeetingTable WHERE StudentID = $UserID AND MeetingID = $MeetingID";
			$confirmquery = mysql_query($confirmsql);
			if ($confirmarray = mysql_fetch_array($confirmquery)) {
				$userlinked = 1;
				$StudentID = $UserID;
			} else {
				$userlinked = 0;
			}
		break;
		case 2:
			$studentsql = "SELECT StudentID FROM StudentTable WHERE ParentID = $UserID";
			$studentquery = mysql_query($studentsql);
			for ($studentno = 1; $studentno <= ($studentarray = mysql_fetch_array($studentquery)); $studentno++) {
				$StudentID = $studentarray[0];
				$confirmsql = "SELECT * FROM MeetingTable WHERE StudentID = '$StudentID' AND MeetingID = '$MeetingID'";
				$confirmquery = mysql_query($confirmsql);
				if ($confirmarray = mysql_fetch_array($confirmquery)) {
					$userlinked = 1;
					$StudentID = $studentarray[0];
				}
			}
		break;
		case 3:
			$confirmsql = "SELECT * FROM MeetingTable WHERE MentorID = $UserID AND MeetingID = $MeetingID";
			$confirmquery = mysql_query($confirmsql);
			if ($confirmarray = mysql_fetch_array($confirmquery)) {
				$userlinked = 1;
				$MentorID = $UserID;
			} else {
				$userlinked = 0;
			}
		break;
	}
	
// After the user has been recognised as valid to view this meeting, they are notified that they are valid and is shown the meeting ID.
	if ($userlinked == 1) {
		echo "<p>Meeting ID: $MeetingID </p>";
		echo "<p>You are connected to this meeting.</p>";
		
// Begin target queries.
		$targetsql = "SELECT TargetUserType, UserTarget, DateDue, StudentComment, ParentComment, MentorComment, TargetMetYet, TargetID FROM TargetTable WHERE MeetingID = $MeetingID ORDER BY DateDue";
		$targetqueryvalidation = mysql_query($targetsql);
		if ($targetarray = mysql_fetch_array($targetqueryvalidation)) {
			$targetquery = mysql_query($targetsql);
			
// In the case targets are retrieved.
			echo "<p>Targets for this meeting:</p>";
			echo "<table border = 3px>";
			echo "<tr> <td><p>User type</p></td> <td><p>User's target</p></td> <td><p>Date due</p></td> <td><p>Student comment</p></td> <td><p>Parent comment</p></td> <td><p>Mentor comment</p></td> <td><p>Target met?</p></td> <td><p>Target ID</p></td> </tr>";
			for ($targetno = 1; $targetno <= ($targetarray = mysql_fetch_array($targetquery)); $targetno++ ) {
				$DateDue = $targetarray[2];
				$yyyy = "$DateDue[0]" . "$DateDue[1]" . "$DateDue[2]" . "$DateDue[3]";
				$mm = "$DateDue[5]" . "$DateDue[6]";
				$dd = "$DateDue[8]" . "$DateDue[9]";
				$datetime = date('l j F', mktime(0,0,0, $mm, $dd, $yyyy, 0));
				echo "<tr> <td><p>";
				if ($targetarray[0] == 1) {
					$TargetUserName = "Student";
				} else if ($targetarray[0] == 2) {
					$TargetUserName = "Parent";
				} else if ($targetarray[0] == 3) {
					$TargetUserName = "Mentor";
				}
				echo "$TargetUserName</p></td> <td><p>$targetarray[1]</p></td> <td><p>";
				echo "$datetime";
				
// Each individual user may alter their own comments, but not those of other users.
				echo "</p></td> <td><p>$targetarray[3]";
				if ($UserType == 1) {
					echo " <p><a href = 'CommentPage.php?MeetingID=$MeetingID&TargetID=$targetarray[7]&action=3'>(add/edit comment)</a></p> <p><a href = 'CommentPage.php?MeetingID=$MeetingID&TargetID=$targetarray[7]&action=2'>(delete comment)</a></p>";
				}
				echo "</p></td> <td><p>$targetarray[4]";
				if ($UserType == 2) {
					echo " <p><a href = 'CommentPage.php?MeetingID=$MeetingID&TargetID=$targetarray[7]&action=3'>(add/edit comment)</a></p> <p><a href = 'CommentPage.php?MeetingID=$MeetingID&TargetID=$targetarray[7]&action=2'>(delete comment)</a></p>";
				}
				echo "</p></td> <td><p>$targetarray[5]";
				if ($UserType == 3) {
					echo " <p><a href = 'CommentPage.php?MeetingID=$MeetingID&TargetID=$targetarray[7]&action=3'>(add/edit comment)</a></p> <p><a href = 'CommentPage.php?MeetingID=$MeetingID&TargetID=$targetarray[7]&action=2'>(delete comment)</a></p>";
				}
				echo "</p></td> <td><p>";
				if ($targetarray[6] == 1) {
					echo "Yes";
				} else if ($targetarray[6] == 0) {
					echo "No";
				}
				echo "</p></td> <td><p>$targetarray[7]</p></td>";
				if ($UserType == 3) {
					echo " <td><p><a href = 'EditTargetPage.php?TargetID=$targetarray[7]&MeetingID=$MeetingID&action=0'>Edit</a></p></td> <td><p><a href = 'DeleteTargetPage.php?TargetID=$targetarray[7]&MeetingID=$MeetingID&action=0'>Delete</p></td>";
				echo "</tr>";
				}
			}
			echo "</table>";
			
// Allow the mentor to add a target if they desire.
			if ($UserType == 3) {
				echo "<p><a href = 'AddTargetPage.php?MeetingID=$MeetingID&action=0'>Add target to this meeting</a></p>";
			}
		} else {
			echo "<p>There are currently no targets for this meeting.</p>";
			if ($UserType == 3) {
				echo "<p>You can ";
				echo "<a href = 'AddTargetPage.php?MeetingID=$MeetingID&action=0'>add a target</a>";
				echo " to this meeting.</p>";
			}
		}
	} else {
	
// If a user has linked to a page without an appropriate meeting ID they are redirected back to ViewMeetingPage.
		header('location:ViewMeetingPage.php');
	}
} else {
	header('location:ViewMeetingPage.php');
}
echo "<p><a href = 'ViewMeetingPage.php'>View meetings</a></p>";
?>
</body>
</html>