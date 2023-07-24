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

// For accepting a request only, no action variable is required.

// Get current user's type and ID.
$RequestedUserType = $_SESSION['UserType'];
$RequestedUserID = $_SESSION['UserID'];

// Get requester's type and ID.
$RequesterUserType = $_GET['RequesterUserType'];
$RequesterUserID = $_GET['RequesterUserID'];

// Check if request still exists in table.
$requestsql = "SELECT * FROM RequestTable WHERE RequesterUserType = $RequesterUserType AND RequesterUserID = $RequesterUserID AND RequestedUserType = $RequestedUserType AND RequestedUserID = $RequestedUserID";
$requestquery = mysql_query($requestsql);
if ($requestquery) {
	$requestarray = mysql_fetch_array($requestquery);
	if ($requestarray){
	
// If current user is a parent, insert parent's ID into requesting student's entry.
		if ($RequestedUserType == 2) {
			$addsql = "UPDATE StudentTable SET ParentID = $RequestedUserID WHERE StudentID = $RequesterUserID";
			$addquery = mysql_query($addsql);
			
// If current user is a mentor, insert mentor's ID into requesting student's entry.
		} else if ($RequestedUserType == 3) {
			$addsql = "UPDATE StudentTable SET MentorID = $RequestedUserID WHERE StudentID = $RequesterUserID";
			$addquery = mysql_query($addsql);
			
// If current user is a student, insert requester's ID into student's requester user type's entry.
		} else if ($RequestedUserType == 1) {
			$addsql1 = "UPDATE StudentTable SET ";
			if ($RequesterUserType == 2) {
				$addsql2 = "ParentID ";
			} else if ($RequesterUserType == 3) {
				$addsql2 = "MentorID ";
			}
			$addsql3 = "= $RequesterUserID WHERE StudentID = $RequestedUserID";
			$addsql = $addsql1 . $addsql2 . $addsql3;
			$addquery = mysql_query($addsql);;
		}
		
// Remove requests for particular student's ID and any requests with other users of the same type as the current requester. Redirect back to ViewContactPage afterwards.
		if ($RequestedUserType == 1) {
			$removerequestsql1 = "DELETE FROM RequestTable WHERE RequestedUserType = 1 AND RequestedUserID = $RequestedUserID AND RequesterUserType = $RequesterUserType";
			$removequery1 = mysql_query($removerequestsql1);
			$removerequestsql2 = "DELETE FROM RequestTable WHERE RequesterUserType = 1 AND RequesterUserID = $RequestedUserID AND RequestedUserType = $RequesterUserType";
			$removequery2 = mysql_query($removerequestsql2);
			header('location: ViewContactPage.php');
		} else if ($RequestedUserType == 2 || $RequestedUserType == 3) {
			$removerequestsql1 = "DELETE FROM RequestTable WHERE RequesterUserType = 1 AND RequesterUserID = $RequesterUserID AND RequestedUserType = $RequestedUserType";
			$removequery1 = mysql_query($removerequestsql1);
			$removerequestsql2 = "DELETE FROM RequestTable WHERE RequestedUserType = 1 AND RequestedUserID = $RequesterUserID AND RequesterUserType = $RequestedUserType";
			$removequery2 = mysql_query($removerequestsql2);
			header('location: ViewContactPage.php');
		}
	} else {
	
// Redirect user to ViewContactPage if no request retrieved.
		header('location: ViewContactPage.php');
	}
}
?>
</body>
</html>