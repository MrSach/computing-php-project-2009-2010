<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Processing...</title>
<link rel = "stylesheet" href = "stylesheet.css" type = "text/css" >
<?php
session_start();
require('DatabaseConnectPage.php');
?>
</head>
<body>
<?php

// Like many other pages, this page uses different actions. The action is retrieved.
$action = $_GET['action'];

// action values 1 and 2 are for when the current user made a request.
if ($action == 1 || $action == 2) {
	$RequesterUserType = $_SESSION['UserType'];
	$RequesterUserID = $_SESSION['UserID'];
	$RequestedUserType = $_GET['RequestedUserType'];
	$RequestedUserID = $_GET['RequestedUserID'];
	
// action value 0 is when the current user was requested by another user.
} else if ($action == 0) {
	$RequestedUserType = $_SESSION['UserType'];
	$RequestedUserID = $_SESSION['UserID'];
	$RequesterUserType = $_GET['RequesterUserType'];
	$RequesterUserID = $_GET['RequesterUserID'];
}

// Action to take when current user has just made a request for another user. This requires a check to see if the student involved already has a parent/mentor. This is necessary to prevent requests from being made after a student has a connection with a user of that user type.
if ($action == 1) {
	if ($RequesterUserType == 1 && $RequestedUserType == 2) {
		$confirmsql = "SELECT * FROM StudentTable WHERE StudentID = $RequesterUserID AND ParentID IS NULL";
	} else if ($RequesterUserType == 2 && $RequestedUserType == 1) {
		$confirmsql = "SELECT * FROM StudentTable WHERE StudentID = $RequestedUserID AND ParentID IS NULL";
	} else if ($RequesterUserType == 1 && $RequestedUserType == 3) {
		$confirmsql = "SELECT * FROM StudentTable WHERE StudentID = $RequesterUserID AND MentorID IS NULL";
	} else if ($RequesterUserType == 3 && $RequestedUserType == 1) {
		$confirmsql = "SELECT * FROM StudentTable WHERE StudentID = $RequestedUserID AND MentorID IS NULL";
	}

// SQL query formed and executed.
	$confirmquery = mysql_query($confirmsql);
	$confirmarray = mysql_fetch_array($confirmquery);
	if ($confirmquery) {
		if ($confirmarray) {

// Checks to see if existing request to be made already exists.
			$checkexistingsql = "SELECT * FROM RequestTable WHERE RequestedUserType = '$RequestedUserType' AND RequestedUserID = '$RequestedUserID' AND RequesterUserType = '$RequesterUserType' AND RequesterUserID = '$RequesterUserID'";
			$checkexistingquery = mysql_query($checkexistingsql);
			if ($checkexistingquery) {
				if ($checkexistingarray = mysql_fetch_array($checkexistingquery)) {
				
// No changes are made and user is redirected back to ViewContactPage if ssame request already made.
					header('location:ViewContactPage.php');
				} else {
				
// If no request exists, create the request and redirect the user to ViewContactPage.
					$addsql = "INSERT INTO RequestTable (RequesterUserType, RequesterUserID, RequestedUserType, RequestedUserID) VALUES ('$RequesterUserType', '$RequesterUserID', '$RequestedUserType', '$RequestedUserID')";
					$addquery = mysql_query($addsql);
					header('location:ViewContactPage.php');
				}
			} else {
				header('location:ViewContactPage.php');
			}
		} else {
			header('location:ViewContactPage.php');
		}
	}
	
// For cancelling a request which the current user made.
} else if ($action == 2) {

// Removing the request for a parent/mentor.
	if ($RequesterUserType == 1) {
		$removerequestsql = "DELETE FROM RequestTable WHERE RequesterUserType = 1 AND RequesterUserID = $RequesterUserID AND RequestedUserType = $RequestedUserType AND RequestedUserID = $RequestedUserID";
		$removequery = mysql_query($removerequestsql);
		header('location:ViewContactPage.php');
		
// Removing the request for a student.
	} else if ($RequesterUserType == 2 || $RequesterUserType == 3) {
		$removerequestsql = "DELETE FROM RequestTable WHERE RequestedUserType = 1 AND RequestedUserID = $RequestedUserID AND RequesterUserType = $RequesterUserType AND RequesterUserID = $RequesterUserID";
		$removequery = mysql_query($removerequestsql);
		header('location:ViewContactPage.php');
	}

// For rejecting a request another user made for them.
} else if ($action == 0) {

// For rejecting a parent/mentor request.
	if ($RequestedUserType == 1) {
		$removerequestsql = "DELETE FROM RequestTable WHERE RequestedUserType = 1 AND RequestedUserID = $RequestedUserID AND RequesterUserType = $RequesterUserType AND RequesterUserID = $RequesterUserID";
		$removequery = mysql_query($removerequestsql);
		header('location:ViewContactPage.php');
		
// For rejecting a student request.
	} else if ($RequestedUserType == 2 || $RequestedUserType == 3) {
		$removerequestsql = "DELETE FROM RequestTable WHERE  RequesterUserType = 1 AND RequesterUserID = $RequesterUserID AND RequestedUserType = $RequestedUserType AND RequestedUserID = $RequestedUserID";
		$removequery = mysql_query($removerequestsql);
		header('location:ViewContactPage.php');
	}
} else {

// If no action specified, the user must still be able to navigate to discover the error.
	header('location:ViewContactPage.php');
}
?>
</body>
</html>