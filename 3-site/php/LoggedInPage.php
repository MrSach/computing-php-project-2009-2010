<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Mentoring Home</title>
<link rel = "stylesheet" type = "text/css" href = "stylesheet.css">
<?php
require_once("DatabaseConnectPage.php");
session_start();
?>
</head>
<body>
<?php

// Initial variables for loading correct entities for page.
$UserType = $_SESSION['UserType'];
$LoggedIn = $_SESSION['LoggedIn'];

// This code retrieves the user's details for presenting them before displaying them.
$UserLogin = $_SESSION['UserLogin'];
$UserFirstName = $_SESSION['UserFirstName'];
$UserSurname = $_SESSION['UserSurname'];
$UserEmail = $_SESSION['UserEmail'];
$UserPhoneNumber = $_SESSION['UserPhoneNumber'];
$UserID = $_SESSION['UserID'];
$UserTypeName = $_SESSION['UserTypeName'];

// Students also have a form group.
if ($UserType == 1) {
	$StudentForm = $_SESSION['StudentForm'];
}

// Displays the current user's details.
echo "<p>Logged in as " . "$UserLogin" . ".</p>";
echo "<p>Your name is " . "$UserFirstName " . "$UserSurname" . ".</p>";
echo "<p>You are a " . "$UserTypeName" . ".</p>";
echo "<p>Your " . "$UserTypeName" . " user ID is " . "$UserID" . ".</p>";

// If user has an email address and/or a telephone number, they are also displayed to the user.
if ((isset($UserEmail)) && ($UserEmail != '')) {
	echo "<p>Your email address is " . "$UserEmail" . "</p>";
}
if ((isset($UserPhoneNumber)) && ($UserPhoneNumber != '')) {
	echo "<p>Your phone number is " . "$UserPhoneNumber" .  "</p>";
}
if ($UserType == 1) {
	echo "<p>Your form group is " . "$StudentForm." . "</p>";
}

// In the case the user is not logged in they can be notified about this. The session may end without logging the user off.
switch ($LoggedIn) {
	case true: echo "<p>Session logged in </p>"; break;
	case false: echo "<p>Not logged in </p>"; break;
}
echo "<br />";

// Display hyperlinks to other pages.
echo "<p><a href = 'ViewMeetingPage.php'>View meetings</a></p>";
echo "<p><a href = 'ViewContactPage.php'>View contacts</a></p>";
echo "<p><a href = 'LogoutPage.php'>Log out</a></p>";
?>
</body>
</html>