<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Add/edit/delete comment</title>
<link rel = "stylesheet" href = "stylesheet.css" type = "text/css" >
<?php
session_start();
require('DatabaseConnectPage.php');
?>
</head>
<body>
<?php

// Before the user is allowed to alter the state of the comment field, they must be validated and then assigned the field based on their user type.
$UserType = $_SESSION['UserType'];
$UserID = $_SESSION['UserID'];
$TargetID = $_GET['TargetID'];
$MeetingID = $_GET['MeetingID'];
$targetlinked = 0;
$confirmsql1 = "SELECT * FROM MeetingTable WHERE MeetingID = $MeetingID";
if ($UserType == 1) {
	$confirmsql2 = " AND StudentID = $UserID";
	$confirmsql = $confirmsql1 . $confirmsql2;
	$confirmquery = mysql_query($confirmsql);
	if ($confirmarray = mysql_fetch_array($confirmquery)) {
		$targetlinked = 1;
	}
} else if ($UserType == 2) {
		$studentsql = "SELECT StudentID FROM StudentTable WHERE ParentID = $UserID";
		$studentquery = mysql_query($studentsql);
		for ($studentno = 1; $studentno < ($studentarray = mysql_fetch_array($studentquery)); $studentno++) {
			$StudentID = $studentarray[0];
			$confirmsql2 = " AND StudentID = $StudentID";
			$confirmsql = $confirmsql1 . $confirmsql2;
			$confirmquery = mysql_query($confirmsql);
			if ($confirmarray = mysql_fetch_array($confirmquery)) {
				$targetlinked = 1;
			}
		}
} else if ($UserType == 3) {
	$confirmsql2 = " AND MentorID = $UserID";
	$confirmsql = $confirmsql1 . $confirmsql2;
	$confirmquery = mysql_query($confirmsql);
	if ($confirmarray = mysql_fetch_array($confirmquery)) {
		$targetlinked = 1;
	}
}

// When a result is returned from any of the above queries, the user may change their comment.
if ($targetlinked == 1) {
	$findsql = "SELECT StudentComment, ParentComment, MentorComment FROM TargetTable WHERE TargetID = $TargetID";
	$findquery = mysql_query($findsql);
	if ($findarray = mysql_fetch_array($findquery)) {
	
// $action is used here to verify which action should be taken - add/edit, delete or prepare comment?
		$action = $_GET['action'];
		
// For adding and editing comments.
		if ($action == 1) {
			$UserComment = $_GET['UserComment'];
			$commentsql1 = "UPDATE TargetTable SET ";
			if ($UserType == 1) {
				$commentsql2 = "StudentComment ";
			} else if ($UserType == 2) {
				$commentsql2 = "ParentComment ";
			} else if ($UserType == 3) {
				$commentsql2 = "MentorComment ";
			}
			$commentsql3 = "= '$UserComment' WHERE TargetID = $TargetID";
			$commentsql = $commentsql1 . $commentsql2 . $commentsql3;
			$commentquery = mysql_query($commentsql);
			header("location:ViewTargetPage.php?MeetingID=$MeetingID");
			
// Deleting comments.
		} else if ($action == 2) {
			 $commentsql1 = "UPDATE TargetTable SET ";
			if ($UserType == 1) {
				$commentsql2 = "StudentComment ";
			} else if ($UserType == 2) {
				$commentsql2 = "ParentComment ";
			} else if ($UserType == 3) {
				$commentsql2 = "MentorComment ";
			}
			$commentsql3 = "= NULL WHERE TargetID = $TargetID";
			$commentsql = $commentsql1 . $commentsql2 . $commentsql3;
			$commentquery = mysql_query($commentsql);
			header("location:ViewTargetPage.php?MeetingID=$MeetingID");
		
// Preparing a comment before writing it to the table.
		} else if ($action == 3) {
			if ($UserType == 1) {
				$UserComment = addslashes($findarray[0]);
			} else if ($UserType == 2) {
				$UserComment = addslashes($findarray[1]);
			} else if ($UserType == 3) {
				$UserComment = addslashes($findarray[2]);
			}
			echo "<form action = 'CommentPage.php?MeetingID=$MeetingID&TargetID=$TargetID&UserComment=$UserComment&action=1' method = 'get'>";
			echo "<p>Comment: ";
			
// Input comment. If comment already exists, place it here to edit.
			echo "<input name = 'UserComment' id = 'UserComment' type = 'text' value = '$UserComment'>";
			echo "</input";
			echo "</p>";
			
// Automatically carry MeetingID, TargetID and assign $action to the page after submission.
			echo "<input name = 'MeetingID' id = 'MeetingID' type = 'hidden' value = '$MeetingID'></input>";
			echo "<input name = 'TargetID' id = 'TargetID' type = 'hidden' value = '$TargetID'></input>";
			echo "<input name = 'action' type = 'hidden' value = '1'></input>";
			echo "<p><input name = 'submit' type = 'submit' id = 'submit' value = 'Add/edit comment'></p>";
			echo "</form>";
			echo '';
			echo "<p><a href = 'CommentPage.php?MeetingID=$MeetingID&TargetID=$TargetID&UserComment=$UserComment&action=2'>Delete comment</a></p>";
			echo "<p><a href = 'ViewTargetPage.php?MeetingID=$MeetingID'>Cancel and view targets</a></p>";
		} else {
			header('location:ViewTargetPage.php?MeetingID=$MeetingID');
		}

// Query failure would be very abnormal, but it could happen if the user is missing key information (i.e. if they prefer to use an address bar directly when browsing). User must be connected with the target, otherwise they are redirected back to ViewTargetPage.
	} else {
		header('location:ViewTargetPage.php?MeetingID=$MeetingID');
	}
} else {
	header('location:ViewTargetPage.php?MeetingID=$MeetingID');
}
?>
</body>
</html>