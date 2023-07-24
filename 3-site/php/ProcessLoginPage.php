<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Processing...</title>
<link rel = "stylesheet" href = "stylesheet.css" type = "text/css" >
<?php
require_once('DatabaseConnectPage.php');
session_start();
?>
</head>
<body>
<?php

// This page has no user interface. It is merely a page to process the login and find a match with existing records in the respective user table.

// Checks if login, password and user type are set. If so, set global variables for each. If not, return to LoginPage.
if (isset($_GET['UserLogin'])) {
	$UserLogin = $_GET['UserLogin'];
	if (isset($_GET['UserPassword'])) {
		$UserPassword = $_GET['UserPassword'];
		if (isset($_GET['UserType'])) {
			$UserType = $_GET['UserType'];
		} else {
			header("location:login1.php?UserLogin=$UserLogin");
		}
	} else if (isset($_GET['UserLogin'])) {
		header("location:login1.php?UserLogin=$UserLogin");
	}
} else {
	header("location:login1.php");
}

// Individual user types have their own tables and must have separate SQL queries.
switch ($UserType) {

// Student case
	case 1:
	$logsql = "SELECT `StudentFirstName`, `StudentSurname`, `StudentID`, `StudentForm`, `StudentEmail`, `StudentPhoneNumber` FROM `StudentTable` WHERE `StudentLogin` = '$UserLogin' AND `StudentPassword` = '$UserPassword' ";
	$logsqlquery = mysql_query($logsql);
	$logsqlarray = mysql_fetch_array($logsqlquery);
	break;

// Parent case
	case 2:
	$logsql = "SELECT ParentFirstName, ParentSurname, ParentID, ParentEmail, ParentPhoneNumber FROM ParentTable WHERE ParentLogin = '$UserLogin' AND ParentPassword = '$UserPassword' ";
	$logsqlquery = mysql_query($logsql);
	$logsqlarray = mysql_fetch_array($logsqlquery);
	break;

// Mentor case
	case 3:
	$logsql = "SELECT MentorFirstName, MentorSurname, MentorID, MentorEmail, MentorPhoneNumber FROM MentorTable WHERE MentorLogin = '$UserLogin' AND MentorPassword = '$UserPassword' ";
	$logsqlquery = mysql_query($logsql);
	$logsqlarray = mysql_fetch_array($logsqlquery);
	break;
}

// Following code is executed if any entry is found in database table. Different commands are executed for each category of user.
if ($logsqlarray) {
	switch($UserType) {
	
// Student case
		case 1:

// Global variables are set from the array.
		$StudentLogin = "$UserLogin";
		$StudentFirstName = "$logsqlarray[0]";
		$StudentSurname = "$logsqlarray[1]";
		$StudentID = "$logsqlarray[2]";
		$StudentForm = "$logsqlarray[3]";
		$StudentEmail= "$logsqlarray[4]";
		$StudentPhoneNumber= "$logsqlarray[5]";
		
// User type labelled for the user.
		$UserTypeName = "student";

// Session variables are set so that current information may be retrieved in other pages in the same session.
		$_SESSION['StudentLogin'] = $StudentLogin;
		$_SESSION['StudentFirstName'] = $StudentFirstName;
		$_SESSION['StudentSurname'] = $StudentSurname;
		$_SESSION['StudentForm'] = $StudentForm;
		$_SESSION['StudentID'] = $StudentID;
		$_SESSION['StudentEmail'] = $StudentEmail;
		$_SESSION['StudentPhoneNumber'] = $StudentPhoneNumber;
		$_SESSION['UserID'] = $StudentID;
		$_SESSION['UserLogin'] = $_SESSION['StudentLogin'];
		$_SESSION['UserFirstName'] = $_SESSION['StudentFirstName'];
		$_SESSION['UserSurname'] = $_SESSION['StudentSurname'];
		$_SESSION['UserEmail'] = $_SESSION['StudentEmail'];
		$_SESSION['UserPhoneNumber'] = $_SESSION['StudentPhoneNumber'];
		break;
		
// Parent case
		case 2:
		
// Global variables are set from the array.
		$ParentLogin = "$UserLogin";
		$ParentFirstName = "$logsqlarray[0]";
		$ParentSurname = "$logsqlarray[1]";
		$ParentID = "$logsqlarray[2]";
		$ParentEmail = "$logsqlarray[3]";
		$ParentPhoneNumber = "$logsqlarray[4]";

// User type labelled for the user.
		$UserTypeName = "parent";
		
// Session variables set so that current information may be retrieved in other pages in the same session.
		$_SESSION['ParentLogin'] = $ParentLogin;
		$_SESSION['ParentFirstName'] = $ParentFirstName;
		$_SESSION['ParentSurname'] = $ParentSurname;
		$_SESSION['ParentID'] = $ParentID;
		$_SESSION['ParentEmail'] = $ParentPhoneNumber;
		$_SESSION['ParentPhoneNumber'] = $ParentPhoneNumber;
		$_SESSION['UserID'] = $ParentID;
		$_SESSION['UserLogin'] = $_SESSION['ParentLogin'];
		$_SESSION['UserFirstName'] = $_SESSION['ParentFirstName'];
		$_SESSION['UserSurname'] = $_SESSION['ParentSurname'];
		$_SESSION['UserEmail'] = $_SESSION['ParentEmail'];
		$_SESSION['UserPhoneNumber'] = $_SESSION['ParentPhoneNumber'];
		break;
		
// Mentor case
		case 3:
		
// Global variables are set from the array.
		$MentorLogin = "$UserLogin";
		$MentorFirstName = "$logsqlarray[0]";
		$MentorSurname = "$logsqlarray[1]";
		$MentorID = "$logsqlarray[2]";
		$MentorEmail = "$logsqlarray[3]";
		$MentorPhoneNumber = "$logsqlarray[4]";
		
// User type labelled for the user.
		$UserTypeName = "mentor";
		
// Session variables set so that current information may be retrieved in other pages in the same session.
		$_SESSION['MentorLogin'] = $MentorLogin;
		$_SESSION['MentorFirstName'] = $MentorFirstName;
		$_SESSION['MentorSurname'] = $MentorSurname;
		$_SESSION['MentorID'] = $MentorID;
		$_SESSION['MentorEmail'] = $MentorEmail;
		$_SESSION['MentorPhoneNumber'] = $MentorPhoneNumber;
		$_SESSION['UserID'] = $MentorID;
		$_SESSION['UserLogin'] = $_SESSION['MentorLogin'];
		$_SESSION['UserFirstName'] = $_SESSION['MentorFirstName'];
		$_SESSION['UserSurname'] = $_SESSION['MentorSurname'];
		$_SESSION['UserEmail'] = $_SESSION['MentorEmail'];
		$_SESSION['UserPhoneNumber'] = $_SESSION['MentorPhoneNumber'];
		break;
	}
	
// Sets variables to identify that session is expected on further pages. The user type, used in decision rules in most of the pages, is most used throughout the project, along with the user ID.
	$_SESSION['LoggedIn'] = true;
	$_SESSION['UserTypeName'] = $UserTypeName;
	$_SESSION['UserType'] = $UserType;
	
// Go to LoggedInPage
	header("location:LoggedInPage.php");

// If no match is found with the database, the session is not logged in and user is redirected back to the login page.
	
} else {
	$_SESSION['LoggedIn'] = false;
	if (isset($_GET['UserLogin'])) {
		header("location:LoginPage.php?UserLogin=$UserLogin");
	} else {
		header("location:LoginPage.php");
	}
}
?>
</body>
</html>