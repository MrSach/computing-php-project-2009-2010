<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>View meetings</title>
<link rel = "stylesheet" href = "stylesheet.css" type = "text/css" >
<?php
require_once('DatabaseConnectPage.php');
session_start();
?>
</head>
<body>
<?php

// For the current user browsing page:
// $UserName = username
// $UserID = user ID within user type
// $UserTypeName = user type (as a name)
// $UserType = user type (as a number)
// $UserFirstName = first name
// $UserSurname = surname
// $LoggedIn = Whether session is set
$LoggedIn = false;
$UserID = $_SESSION['UserID'];
$UserTypeName = $_SESSION['UserTypeName'];
$UserType = $_SESSION['UserType'];
$UserFirstName = $_SESSION['UserFirstName'];
$UserSurname = $_SESSION['UserSurname'];
$LoggedIn = $_SESSION['LoggedIn'];

// The meetings have not been successfully found yet because the necessary details have not been searched.
$meetcheck = 0;
echo "<h1>View meetings</h1><hr />";
switch ($UserType) {

// Student case
	case 1:
	
// Current student is the only student to find meetings for.
	$studentno = 1;
	$totalstudentno = 1;
	$StudentFirstName[$studentno] = $UserFirstName;
	$StudentID[$studentno] = $UserID;
	
// Query student table entry for parent ID using student ID and execute.
	$ParentIDsql = "SELECT StudentTable.ParentID FROM StudentTable WHERE StudentTable.StudentID = $UserID AND ParentID IS NOT NULL";
	$ParentIDquery = mysql_query($ParentIDsql);
	if ($ParentIDquery) {
		if ($ParentIDarray = mysql_fetch_array($ParentIDquery)) {
			$ParentID = $ParentIDarray[0];

	// Query parent table entry for parent first name and surname using parent ID.
			if ($ParentIDarray) {
				$Parentsql = "SELECT ParentTable.ParentFirstName, ParentTable.ParentSurname FROM ParentTable WHERE ParentTable.ParentID = $ParentIDarray[0]";
				$Parentquery = mysql_query($Parentsql);
				
	// If the ID query was successful, fetch the name and ID of the parent.
				if ($Parentquery) {
					if ($Parentarray = mysql_fetch_array($Parentquery)) {
						echo "<p>Your parent's name is $Parentarray[0] $Parentarray[1]" . ". " . "Parent ID is " . "$ParentIDarray[0]" . "." . "</p>";
					} else {
						echo "<p>Error displaying parent's details. Please consult the system administrator.</p>";
					}
				}
			}
		} else {
			echo "<p>No parent found. Please search for one and make a request.</p>";
		}
	}

// Query student table entry for mentor's ID using student id and execute.
	$MentorIDsql = "SELECT StudentTable.MentorID FROM StudentTable WHERE StudentTable.StudentID = $UserID AND MentorID IS NOT NULL";
	$MentorIDquery = mysql_query($MentorIDsql);
	
// Query mentor table entry for mentor first name and surname using mentor's ID.
	if ($MentorIDquery) {
		if ($MentorIDarray = mysql_fetch_array($MentorIDquery)) {
			$MentorID = $MentorIDarray[0];
			$meetcheck = 1;
			if ($MentorIDarray) {
				$Mentorsql = "SELECT MentorTable.MentorFirstName, MentorTable.MentorSurname FROM MentorTable WHERE MentorTable.MentorID = $MentorIDarray[0]";
				$Mentorquery = mysql_query($Mentorsql);

// If the ID query was successful, fetch the name and ID of the mentor. 
				if ($Mentorquery) {
					if ($Mentorarray = mysql_fetch_array($Mentorquery)) {
						echo "<p>Your mentor's name is $Mentorarray[0] $Mentorarray[1]" . ". " . "Mentor ID is " . "$MentorIDarray[0]" . "." . "</p>";
					} else {
						echo "<p>Error displaying mentor's details. Please consult the system administrator.</p>";
					}
				}
			}
		} else {
			echo "<p>No mentor found. Please search for one and make a request.</p>";
		}
	}
	echo "<hr />";
	break;

// Parent case
	case 2:
	$ParentID = $UserID;

// Query student table entry for student first name, surname and ID using parent ID.
	$studentsql = "SELECT StudentTable.StudentFirstName, StudentTable.StudentSurname, StudentTable.StudentID FROM StudentTable WHERE StudentTable.ParentID = $UserID";
	$studentvalidationquery = mysql_query($studentsql);
	if ($studentarray = mysql_fetch_array($studentvalidationquery)) {
		$studentquery = mysql_query($studentsql);
		$meetcheck = 1;
		for ($studentno = 1; $studentno <= ($studentarray = mysql_fetch_array($studentquery)); $studentno++) {
			$StudentFirstName[$studentno] = $studentarray[0];
			$StudentSurname[$studentno] = $studentarray[1];
			$StudentID[$studentno] = $studentarray[2];
			echo "<p>Student: $studentarray[0] $studentarray[1]" . ". " . "Student ID is " . "$studentarray[2]" . "." . "</p>";
			
// Query student table entry for mentor ID using student ID
			$MentorIDsql = "SELECT StudentTable.ParentID FROM StudentTable WHERE StudentTable.StudentID = $studentarray[2]";
			$MentorIDquery = mysql_query($MentorIDsql);
// Query mentor table entry for mentor's first name and surname using mentor ID
			if ($MentorIDquery) {
				if ($MentorIDarray = mysql_fetch_array($MentorIDquery)) {
					$MentorID = $MentorIDarray[0];
					$Mentorsql = "SELECT MentorTable.MentorFirstName, MentorTable.MentorSurname FROM MentorTable WHERE MentorTable.MentorID = $MentorIDarray[0]";
					$Mentorquery = mysql_query($Mentorsql);
					if ($Mentorquery) {
						if ($Mentorarray = mysql_fetch_array($Mentorquery)) {
							echo "<p>Their mentor's name is $Mentorarray[0] " . "$Mentorarray[1]" . ". " . "Mentor ID is " . "$MentorIDarray[0]" . "." . "</p>";
						} else {
							echo "<p>There is an error in displaying the mentor's details. Please notify the system administrator.</p>";
						}
					}
				} else {
					echo "<p>Error retrieving mentor's ID. Please consult the system administrator.</p>";
				}
			} else {
				echo "<p>No mentor found for this student. Please notify the student about this.</p>";
			}
			echo "<br />";
		}
		
// The loop for displaying meetings executes once more than necessary. This code eliminates the additional error which may appear.
		$totalstudentno = $studentno-1;
	} else {
		echo "<p>No student was found. Please search contact for a student.</p>";
	}
	echo "<hr />";
	break;
	case 3:
	$MentorID = $UserID;

// Query student table entry for student's first name, surname and ID using mentor ID
	
	$studentsql = "SELECT StudentTable.StudentFirstName, StudentTable.StudentSurname, StudentTable.StudentID FROM StudentTable WHERE StudentTable.MentorID = $UserID";
	$studentvalidationquery = mysql_query($studentsql);
	if ($studentarray = mysql_fetch_array($studentvalidationquery)) {
		$studentquery = mysql_query($studentsql);
		$meetcheck = 1;
		$studentno = 1;
		for ($studentno = 1; $studentno < ($studentarray = mysql_fetch_array($studentquery)); $studentno++) {
			$StudentFirstName[$studentno] = $studentarray[0];
			$StudentSurname[$studentno] = $studentarray[1];
			$StudentID[$studentno] = $studentarray[2];
			echo "<p>Student: $studentarray[0] $studentarray[1]" . ". " . "Student ID is " . "$studentarray[2]" . "." . "</p>";

// Query student table entry for mentor ID using student's ID.
			$ParentIDsql = "SELECT StudentTable.ParentID FROM StudentTable WHERE StudentTable.StudentID = $studentarray[2]";
			$ParentIDquery = mysql_query($ParentIDsql);

// Query parent table entry for parent's first name and surname using parent's ID.
			if ($ParentIDquery) {
				if ($ParentIDarray = mysql_fetch_array($ParentIDquery)) {
					$ParentID = $ParentIDarray[0];
					$Parentsql = "SELECT ParentTable.ParentFirstName, ParentTable.ParentSurname FROM ParentTable WHERE ParentTable.ParentID = $ParentIDarray[0]";
					$Parentquery = mysql_query($Parentsql);
					if ($Parentquery) {
					
// If a parent is found, display their details.
						if ($Parentarray = mysql_fetch_array($Parentquery)) {
							echo "<p>Their parent's name is $Parentarray[0] " . "$Parentarray[1]" . ". " . "Parent ID is " . "$ParentIDarray[0]" . "." . "</p>";
						} else {
							echo "<p>There is an error in displaying the parent's details. Please notify the system administrator.</p>";
						}
					}
				} else {
					echo "<p>No parent found for this student. Please notify your student about this.</p>";
				}
			}
			echo "<br />";
		}
		$totalstudentno = $studentno-1;
	} else {
		echo "<p>No student was found. Please search contact for a student.</p>";
	}
	echo "<hr />";
	break;
}

// Checks for meetings if there is a student-mentor connection.
if ($meetcheck == 1) {

// Loops for each user, but designed for parents and mentors in the case several students have been found.
	for ($studentno = 1; $studentno <= $totalstudentno; $studentno++) {
		echo "<p>$StudentFirstName[$studentno]" . "'s meetings:<br />";
		$meetsql = "SELECT MeetingTable.MeetingDate, MeetingTable.StudentID, MeetingTable.MentorID, MeetingTable.MeetingID FROM MeetingTable WHERE MeetingTable.StudentID = $StudentID[$studentno] ORDER BY MeetingDate";
		$meetqueryvalidate = mysql_query($meetsql);
		if ($meetqueryvalidate) {
			if ($meetarray = mysql_fetch_array($meetqueryvalidate)) {
				$meetquery = mysql_query($meetsql);	

// Meetings displayed if any results returned.				
				echo "<table border = '1'>";
				echo "<tr><td><p>Date</p></td> <td><p>Student ID</p></td> <td><p>Mentor ID</p></td> <td><p>Meeting ID</p></td> </tr>";
				for ($meetno = 1; $meetno <= ($meetarray = mysql_fetch_array($meetquery)); $meetno++) {

// Assigns each individual meeting property.
					$MeetingID = $meetarray[3];
					$MeetingDate = $meetarray[0];
					$yyyy = "$MeetingDate[0]" . "$MeetingDate[1]" . "$MeetingDate[2]" . "$MeetingDate[3]";
					$mm = "$MeetingDate[5]" . "$MeetingDate[6]";
					$dd = "$MeetingDate[8]" . "$MeetingDate[9]";
					$datetime = date('l j F', mktime(0,0,0, $mm, $dd, $yyyy, 0));
					echo "<tr><td><p>$datetime</p></td> <td><p>$meetarray[1]</p></td> <td><p>$meetarray[2]</p></td> <td><p>$meetarray[3]</p></td>";			
					echo "<td><p><a href = 'ViewTargetPage.php?MeetingID=$meetarray[3]'>View</a></p></td>";
					if ($UserType == 3) {
						echo "<td><p><a href = 'EditMeetingPage.php?MeetingID=$meetarray[3]&action=0'>Edit</a></p></td>";
						echo "<td><p><a href = 'DeleteMeetingPage.php?MeetingID=$meetarray[3]&action=0'>Delete</a></p></td>";
					}
					echo "</tr>";
				}
				echo "</table>";
			} else {

// In the case no meetings are found which are relevant to user.
				echo "<p>There are currently no meetings.</p>";
				if ($UserType == 3) {
					echo "<p>You can add a meeting for this student by clicking the 'Add meeting' link.</p>";
				}
			}
		} else {
			echo "<p>There is an error in retrieving table information.<br /></p>";
		}
	echo "<hr />";
	}

// Mentors may add a meeting at any time.
	if ($UserType == 3) {
		echo "<p><a href = 'AddMeetingPage.php?action=0'>Add meeting</a> </stfont> </p>";
	}
}

// Hyperlink back to LoggedInPage.
echo "<p><a href = 'LoggedInPage.php'>Homepage</a> </stfont> </p>";
mysql_close($ConnectServer);
?>
</body>
</html>