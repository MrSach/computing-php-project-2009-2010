<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
session_start();
require ('DatabaseConnectPage.php');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>View contacts</title>
<link rel = "stylesheet" href = "stylesheet.css" type = "text/css" >
</head>
<body>
<?php

// The contact aspect of the scheme is large. Of the four contact-related pages, this page is the largest because it handles the different user types, requests for other users, requests from other users, displaying current linked users' information and any indirectly connected users for parents and mentors. Deleting a contact is also used in this page, somewhat similarly to pages such as EditMeetingPage, EditTargetPage and CommentPage.

// Assign variables.
$UserType = $_SESSION['UserType'];
$UserID = $_SESSION['UserID'];
$requesterdetaildisplay = 0;
$requesteddetaildisplay = 0;
echo "<h1>View contacts</h1>";
echo "<hr />";

// Each user type will have different processes.
switch ($UserType) {

// Student case.
	case 1:
	
// Identifies student's ID as a student ID as well as a user ID.
	$StudentID = $UserID;
	
// SQL query for ParentTable, finding the student's parent.
	$parentsql = "SELECT ParentTable.ParentFirstName, ParentTable.ParentSurname, ParentTable.ParentID, ParentTable.ParentEmail, ParentTable.ParentPhoneNumber FROM StudentTable, ParentTable WHERE StudentTable.StudentID = $UserID AND StudentTable.ParentID = ParentTable.ParentID AND StudentTable.ParentID IS NOT NULL";
	$parentquery = mysql_query($parentsql);
	if ($parentquery) {
		$parentarray = mysql_fetch_array($parentquery);
		
// Parent details are only displayed if the parent is found.
		if (($parentarray) && $parentarray[2] != '') {
			$ParentFirstName = $parentarray[0];
			$ParentSurname = $parentarray[1];
			$ParentID = $parentarray[2];
			$ParentEmail = $parentarray[3];
			$ParentPhoneNumber = $parentarray[4];			
			echo "<p>Your parent is " . "$ParentFirstName " . "$ParentSurname. " . "<br />" . "Parent ID = " . "$ParentID. " . "<br />";
			
// Optional details may be displayed if they exist.
			if ((isset($ParentEmail)) && $ParentEmail != '') {
				echo "Parent's email address is  $ParentEmail." . "<br />";
			}
			if ((isset($ParentPhoneNumber)) && $ParentPhoneNumber != '') {
				echo "Parent's phone number is  $ParentPhoneNumber." . "<br />";
			}
			echo "<a href = 'ViewContactPage.php?deleteusertype=2&deleteuserid=$ParentID&StudentID=$UserID'>(Delete contact)</a><br />";
			echo "</p>";
		} else {
		
// This message is displayed in the case no parent is found.
			echo "<p>No parent found. You may not be assigned to a parent. If this is the case, use the 'Search contact' link and follow the page instructions to request your parent user or add them if they have requested you. If you already have a parent entry but this message is displayed, please consult the system administrator.</p>";
		}
	}
	
// MentorTable must also be queried in order to find mentor details.
	$mentorsql = "SELECT MentorTable.MentorFirstName, MentorTable.MentorSurname, MentorTable.MentorID, MentorTable.MentorEmail, MentorTable.MentorPhoneNumber FROM StudentTable, MentorTable WHERE StudentTable.StudentID = $UserID AND StudentTable.MentorID = MentorTable.MentorID AND StudentTable.MentorID IS NOT NULL";
	$mentorquery = mysql_query($mentorsql);
	if ($mentorquery) {
		$mentorarray = mysql_fetch_array($mentorquery);
		
// Mentor details are displayed in the case of a successful search.
		if ($mentorarray && $mentorarray[2] != '') {
			$MentorFirstName = $mentorarray[0];
			$MentorSurname = $mentorarray[1];
			$MentorID = $mentorarray[2];
			$MentorEmail = $mentorarray[3];
			$MentorPhoneNumber = $mentorarray[4];			
			echo "<p>Your mentor is " . "$MentorFirstName " . "$MentorSurname. " . "<br />" . "Mentor ID = " . "$MentorID. " . "<br />";
			
// Optional fields may be displayed.
			if ((isset($MentorEmail)) && $MentorEmail != '') {
				echo "Mentor's email address is  $MentorEmail." . "<br />";
			}
			if ((isset($MentorPhoneNumber)) && $MentorPhoneNumber != '') {
				echo "Mentor's phone number is  $MentorPhoneNumber." . "<br />";
			}
			echo "<a href = 'ViewContactPage.php?deleteusertype=3&deleteuserid=$MentorID&StudentID=$UserID'>(Delete contact)</a><br />";
			echo "</p>";
			echo "<hr />";
		} else {
		
// If no mentor is found then the following message is displayed.
			echo "<p>No mentor found. You may not be assigned to a mentor. If this is the case, use the 'Search contact' link and follow the page instructions to request your mentor user or add them if they have requested you. If you already have a mentor entry but this message is displayed, please consult the system administrator.</p>";
			echo "<hr />";
		}
	}
	break;
	
// Parent case.
	case 2:

// Parent's user ID is assigned a value in the case it may need to be distinguished from other user types.
	$ParentID = $UserID;
	
//SQL query for student table.
		$studentsql = "SELECT StudentFirstName, StudentSurname, StudentID, StudentForm, StudentEmail, StudentPhoneNumber FROM StudentTable WHERE ParentID = '$UserID'";
		$studentvalidationquery = mysql_query($studentsql);
		$studentquery = mysql_query($studentsql);
		if ($studentvalidationquery) {
			if ($studentarray = mysql_fetch_array($studentvalidationquery)) {
			
// Loops are appropriate in the case that a parent may have several students being mentored.
				for ($studentno = 1; $studentno <= ($studentarray = mysql_fetch_array($studentquery)); $studentno++) {
				
// Variables are assigned to then be used and displayed.
					$StudentFirstName = $studentarray[0];
					$StudentSurname =  $studentarray[1];
					$StudentID = $studentarray[2];
					$StudentForm = $studentarray[3];
					$StudentEmail = $studentarray[4];
					$StudentPhoneNumber = $studentarray[5];
					echo "<p>Your student is " . "$StudentFirstName $StudentSurname. " . "<br />" . "StudentID is " . "$StudentID. " . "<br />" . "Student form is " . "$StudentForm. " . "<br />";
					if (isset($StudentEmail) && $StudentEmail != '') {
						echo "Student email is " . "$StudentEmail. " . "<br />";
					}
					if (isset($StudentPhoneNumber) && $StudentPhoneNumber != '') {
						echo "Student phone number is " . "$StudentPhoneNumber. " . "<br />";
					}
					echo "<a href = 'ViewContactPage.php?deleteusertype=2&deleteuserid=$UserID&StudentID=$StudentID'>(Delete contact)</a><br />";
					echo "</p>";					
					
//SQL query for MentorTable; student's ID is used to find mentor's ID and then mentor details using mentor's ID.
					$mentorsql = "SELECT MentorTable.MentorFirstName, MentorTable.MentorSurname, MentorTable.MentorID, MentorTable.MentorEmail, MentorTable.MentorPhoneNumber FROM StudentTable, MentorTable WHERE StudentTable.StudentID = $StudentID AND StudentTable.MentorID IS NOT NULL AND StudentTable.MentorID = MentorTable.MentorID";
					$mentorquery = mysql_query($mentorsql);
					if ($mentorarray = mysql_fetch_array($mentorquery)) {
						$MentorFirstName = $mentorarray[0];
						$MentorSurname = $mentorarray[1];
						$MentorID = $mentorarray[2];
						$MentorEmail = $mentorarray[3];
						$MentorPhoneNumber = $mentorarray[4];
						echo "<p>Their mentor is " . "$MentorFirstName " . "$MentorSurname, " . "<br />" . "Mentor ID = " . "$MentorID. ";
						if ((isset($MentorEmail)) && $MentorEmail != '') {
							echo "Their email address is  $MentorEmail  . ";
						}
						if ((isset($MentorPhoneNumber)) && $MentorPhoneNumber != '') {
							echo "Their phone number is  $MentorPhoneNumber  . ";
						}
						
// Now that mentor details are used they are not needed after this point.
						unset($MentorFirstName);
						unset($MentorSurname);
						unset($MentorID);
						unset($MentorEmail);
						unset($MentorPhoneNumber);
						echo "</p>";
						
// If a mentor for the student is not found, notify parent.
					} else {
						echo "<p>Your student does not have a mentor assigned to them. You may inform them of this.</p>";
					}
					
// After each student their details are unset so that they do not interfere with other processes within the page.
					unset($StudentFirstName);
					unset($StudentSurname);
					unset($StudentID);
					unset($StudentForm);
					unset($StudentEmail);
					unset($StudentPhoneNumber);
					echo "<hr />";
				}
			} else {
			
// If no student was found, display the message below.
				echo "<p>No student found in the database. Please search for your student using the 'Search contact' link or add them if they have requested you.</p>";
			}
		} else {
		
// If the query fails, display the message below.
			echo "<p>No student found in the database. Please search for your student using the 'Search contact' link or add them if they have requested you.</p>";
			echo "<hr />";
		}
	break;
	
// Mentor case.
	case 3:
	
// Identify MentorID as a user ID.
	$MentorID = $UserID;
	
// SQL query for student table
		$studentsql = "SELECT StudentFirstName, StudentSurname, StudentID, StudentForm, StudentEmail, StudentPhoneNumber FROM StudentTable WHERE MentorID = '$UserID'";
		$studentvalidationquery = mysql_query($studentsql);
		$studentquery = mysql_query($studentsql);
		if ($studentvalidationquery) {
			$studentarray = mysql_fetch_array($studentvalidationquery);
			if ($studentarray) {
				for ($studentno = 1; $studentno <= ($studentarray = mysql_fetch_array($studentquery)); $studentno++) {
				
// Loop for each student. Assign variables for each property.
					$StudentFirstName = $studentarray[0];
					$StudentSurname =  $studentarray[1];
					$StudentID = $studentarray[2];
					$StudentForm = $studentarray[3];
					$StudentEmail = $studentarray[4];
					$StudentPhoneNumber = $studentarray[5];
					
// Display student details.
					echo "<p>Your student is " . "$StudentFirstName $StudentSurname. " . "<br />" . "StudentID is " . "$StudentID. " . "<br />" . "Student form is " . "$StudentForm. " . "<br />";
					if (isset($StudentEmail) && $StudentEmail != '') {
						echo "Student email is " . "$StudentEmail. " . "<br />";
					}
					if (isset($StudentPhoneNumber) && $StudentPhoneNumber != '') {
						echo "Student phone number is " . "$StudentPhoneNumber. " . "<br />";
					}
					echo "<a href = 'ViewContactPage.php?deleteusertype=3&deleteuserid=$UserID&StudentID=$StudentID'>(Delete contact)</a><br />";
					echo "</p>";					
					
// SQL query for ParentTable
					$parentsql = "SELECT ParentTable.ParentFirstName, ParentTable.ParentSurname, ParentTable.ParentID, ParentTable.ParentEmail, ParentTable.ParentPhoneNumber FROM StudentTable, ParentTable WHERE StudentTable.StudentID = $StudentID AND StudentTable.ParentID IS NOT NULL AND StudentTable.ParentID = ParentTable.ParentID";
					$parentquery = mysql_query($parentsql);
					if ($parentarray = mysql_fetch_array($parentquery)) {
					
// Fetched information on parents is handled just as with students.
						$ParentFirstName = $parentarray[0];
						$ParentSurname = $parentarray[1];
						$ParentID = $parentarray[2];
						$ParentEmail = $parentarray[3];
						$ParentPhoneNumber = $parentarray[4];
						echo "<p>Their parent is " . "$ParentFirstName " . "$ParentSurname, " . "<br />" . "Parent ID = " . "$ParentID. ";
						if ((isset($ParentEmail)) && $ParentEmail != '') {
							echo "Their email address is  $ParentEmail  . ";
						}
						if ((isset($ParentPhoneNumber)) && $ParentPhoneNumber != '') {
							echo "Their phone number is  $ParentPhoneNumber  . ";
						}
						
// Erase current parent's information to avoid it becoming a problem later.
						unset($ParentFirstName);
						unset($ParentSurname);
						unset($ParentID);
						unset($ParentEmail);
						unset($ParentPhoneNumber);
						echo "</p>";
					} else {
					
// If student has no parent, display the message below.
						echo "<p>Your student does not have a parent assigned to them. You may inform them of this.</p>";
					}
					
// Current student information no longer required in loading the page so it can be erased.
					unset($StudentFirstName);
					unset($StudentSurname);
					unset($StudentID);
					unset($StudentForm);
					unset($StudentEmail);
					unset($StudentPhoneNumber);
					echo "<hr />";
				}
			} else {
			
// If no student was found, display the message below.
				echo "<p>No student found in the database. Please search for your student using the 'Search contact' link or add them if they have requested you.</p>";
			}
		} else {
		
// If the query fails, display the message below.
			echo "<p>No student found in the database. Please search for your student using the 'Search contact' link or add them if they have requested you.</p>";
			echo "<hr />";
		}
	break;
}

// Requests for current user. SQL statement searches RequestTable matching the requested user properties.
$requestedsql = "SELECT RequesterUserType, RequesterUserID FROM RequestTable WHERE RequestedUserType = $UserType AND RequestedUserID = $UserID";
$requestedquery = mysql_query($requestedsql);
if ($requestedquery) {
	for ($requestno = 1; $requestno <=	($requestedarray = mysql_fetch_array($requestedquery)); $requestno++ ) {
		$RequesterUserType = $requestedarray[0];
		$RequesterUserID = $requestedarray[1];

// Different requesters will have different code to be executed.

// If the requester is a student:
		if ($RequesterUserType == 1) {
			$studentsql = "SELECT StudentFirstName, StudentSurname, StudentForm FROM StudentTable WHERE StudentID = $RequesterUserID";
			$studentquery = mysql_query($studentsql);
			if ($studentquery) {
				if ($studentarray = mysql_fetch_array($studentquery)) {		
					$RequesterUserFirstName = $studentarray[0];
					$RequesterUserSurname = $studentarray[1];
					$StudentForm = $studentarray[2];
					$requesteddetaildisplay = 1;
				} else {
					$requesteddetaildisplay = 0;
				}
			} else {
				$requesteddetaildisplay = 0;
			}
			
// If the requester is a parent:
		} else if ($RequesterUserType == 2) {
			$parentsql = "SELECT ParentFirstName, ParentSurname FROM ParentTable WHERE ParentID = $RequesterUserID";
			$parentquery = mysql_query($parentsql);
			if ($parentquery) {
				if ($parentarray = mysql_fetch_array($parentquery)) {
					$RequesterUserFirstName = $parentarray[0];
					$RequesterUserSurname = $parentarray[1];
					$requesteddetaildisplay = 1;
				} else {
				$requesteddetaildisplay = 0;
				}
			} else {
				$requesteddetaildisplay = 0;
			}
			
// If the requester is a mentor:
		} else if ($RequesterUserType == 3) {
			$mentorsql = "SELECT MentorFirstName, MentorSurname FROM MentorTable WHERE MentorID = $RequesterUserID";
			$mentorquery = mysql_query($mentorsql);
			if ($mentorquery) {
				if ($mentorarray = mysql_fetch_array($mentorquery)) {
					$RequesterUserFirstName = $mentorarray[0];
					$RequesterUserSurname = $mentorarray[1];
					$requesteddetaildisplay = 1;
				} else {
					$requesteddetaildisplay = 0;
				}
			} else {
				$requesteddetaildisplay = 0;
			}
		}
		
// Any requests found are detailed here, otherwise nothing is shown.
		if ($requesteddetaildisplay == 1) {
		
// Requester name, type and students' forms are displayed. User may accept or reject request.
			echo "<p>You have a request from " . "$RequesterUserFirstName " . "$RequesterUserSurname.</p>";
			echo "<p>Requester user ID: " . "$RequesterUserID.</p>";
			if ($RequesterUserType == 1) {
				echo "<p>Requester is a student, their form is $StudentForm.</p>";
			} else if ($RequesterUserType == 2) {
				echo "<p>Requester is a parent.</p>";
			} else if ($RequesterUserType == 3) {
				echo "<p>Requester is a mentor.</p>";
			}
			echo "<p><a href = 'AddContactPage.php?RequesterUserType=$RequesterUserType&RequesterUserID=$RequesterUserID'>(Add contact)</a>";
			echo "-----";
			echo "<a href = 'AddRequestPage.php?RequesterUserType=$RequesterUserType&RequesterUserID=$RequesterUserID&action=0'>(Reject contact)</a></p>";
		}
	} 
	if ($requesteddetaildisplay == 1) {
		echo "<hr />";
	}
}

// Requests that the user themselves will also be displayed.
$requestersql = "SELECT RequestedUserType, RequestedUserID FROM RequestTable WHERE RequesterUserType = $UserType AND RequesterUserID = $UserID";
$requesterquery = mysql_query($requestersql);
if ($requesterquery) {
	for ($requestno = 1; $requestno <=	($requesterarray = mysql_fetch_array($requesterquery)); $requestno++ ) {
		$RequestedUserType = $requesterarray[0];
		$RequestedUserID = $requesterarray[1];
		if ($RequestedUserType == 1) {
			$studentsql = "SELECT StudentFirstName, StudentSurname, StudentForm FROM StudentTable WHERE StudentID = $RequestedUserID";
			$studentquery = mysql_query($studentsql);
			if ($studentquery) {
				if ($studentarray = mysql_fetch_array($studentquery)) {		
					$RequestedUserFirstName = $studentarray[0];
					$RequestedUserSurname = $studentarray[1];
					$StudentForm = $studentarray[2];
					$requesterdetaildisplay = 1;
				} else {
					$requesterdetaildisplay = 0;
				}
			} else {
				$requesterdetaildisplay = 0;
			}
		} else if ($RequestedUserType == 2) {
			$parentsql = "SELECT ParentFirstName, ParentSurname FROM ParentTable WHERE ParentID = $RequestedUserID";
			$parentquery = mysql_query($parentsql);
			if ($parentquery) {
				if ($parentarray = mysql_fetch_array($parentquery)) {
					$RequestedUserFirstName = $parentarray[0];
					$RequestedUserSurname = $parentarray[1];
					$requesterdetaildisplay = 1;
				} else {
				$requesterdetaildisplay = 0;
				}
			} else {
				$requesterdetaildisplay = 0;
			}
		} else if ($RequestedUserType == 3) {
			$mentorsql = "SELECT MentorFirstName, MentorSurname FROM MentorTable WHERE MentorID = $RequestedUserID";
			$mentorquery = mysql_query($mentorsql);
			if ($mentorquery) {
				if ($mentorarray = mysql_fetch_array($mentorquery)) {
					$RequestedUserFirstName = $mentorarray[0];
					$RequestedUserSurname = $mentorarray[1];
					$requesterdetaildisplay = 1;
				} else {
				$requesterdetaildisplay = 0;
				}
			} else {
				$requesterdetaildisplay = 0;
			}
		}
		if ($requesterdetaildisplay == 1) {
		
// Requested user's name and ID are shown. Requested student's form group is also displayed.
			echo "<p>You have requested the user " . "$RequestedUserFirstName " . "$RequestedUserSurname.</p>";
			echo "<p>Requested user ID: " . "$RequestedUserID.</p>";
			if ($RequestedUserType == 1) {
				echo "<p>Requested user is a student, their form is $StudentForm.</p>";
			} else if ($RequestedUserType == 2) {
				echo "<p>Requested user is a parent.</p>";
			} else if ($RequestedUserType == 3) {
				echo "<p>Requested user is a mentor.</p>";
			}
			echo "<p><a href = 'AddRequestPage.php?RequestedUserType=$RequestedUserType&RequestedUserID=$RequestedUserID&action=2'>(Cancel request)</a></p>";
		} else {
			$requesterdetaildisplay = 0;
		}
	}
	if ($requesterdetaildisplay == 1) {
		echo "<hr />";
	}
}

// Deleting contacts. A separate page was unnecessary. The contacts section is not as significant as the meetings section.
if (isset($_GET['deleteuserid']) && (isset($_GET['deleteusertype']) && isset($_GET['StudentID']) ) ) {
	$deleteuserid = $_GET['deleteuserid'];
	$deleteusertype = $_GET['deleteusertype'];
	$StudentID = $_GET['StudentID'];

// Queries are executed to verify that the user to delete from is the current user.
	$confirmsql1 = "SELECT * FROM StudentTable WHERE StudentID = $StudentID AND ";
	if ($deleteusertype == 2) {
		$confirmsql2 = "ParentID = $deleteuserid";
	} else if ($deleteusertype == 3) {
		$confirmsql2 = "MentorID = $deleteuserid";
	}
	$confirmsql = $confirmsql1 . $confirmsql2;
	$confirmquery = mysql_query($confirmsql);
	if ($confirmarray = mysql_fetch_array($confirmquery)) {
		$deletesql1 = "UPDATE StudentTable SET ";
		if ($deleteusertype == 2) {
			$deletesql2 = "ParentID";
		} else if ($deleteusertype == 3) {
			$deletesql2 = "MentorID";
		}
		$deletesql3 = " = NULL WHERE StudentID = $StudentID";
		$deletesql = $deletesql1 . $deletesql2 . $deletesql3;
		$deletequery = mysql_query($deletesql);
		header("location:ViewContactPage.php");
	} else {
		header("location:ViewContactPage.php");
	}
}
echo "<p><a href = 'SearchContactPage.php'>Search contact</a><br/></p>";
echo "<p><a href = 'LoggedInPage.php'>Go to main page</a></p>";
?>
</body>
</html>