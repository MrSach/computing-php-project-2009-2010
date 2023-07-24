<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Search contact</title>
<link rel = "stylesheet" href = "stylesheet.css" type = "text/css" >
<?php
session_start();
require('DatabaseConnectPage.php');
?>
</head>
<body>
<?php

// Set user variables.
$UserType = $_SESSION['UserType'];
$UserID = $_SESSION['UserID'];

// Reset variables not yet used.
$RequestedUserFirstName = '';
$RequestedUserSurname =  '';
$RequestedUserID = 0;
$RequestedUserType = 0;
$StudentForm = '';
$firstnamefind = 0;
$surnamefind = 0;
$idfind = 0;
$dosearch = 1;
echo "<h1>Search contact</h1>";

// Checks which users a student already has.
if ($UserType == 1) {
	$otherusersql = "SELECT ParentID, MentorID FROM StudentTable WHERE StudentID = $UserID";
	$otheruserquery = mysql_query($otherusersql);
	if ($otheruserquery) {
		$otheruserarray = mysql_fetch_array($otheruserquery);
		if ($otheruserarray[0] != '' && ($otheruserarray[0] >=1 && $otheruserarray[0] <= 999) && ($otheruserarray[0] == floor($otheruserarray[0]))) {
			$parentexists = 1;
		} else {
			$parentexists = 0;
		}
		if ($otheruserarray[1] != '' && ($otheruserarray[1] >=1 && $otheruserarray[1] <= 999) && ($otheruserarray[1] == floor($otheruserarray[1]))) {
			$mentorexists = 1;
		} else {
			$mentorexists = 0;
		}
		if ($parentexists == 1 && $mentorexists == 1) {
			$dosearch = 0;
		} else {
			$dosearch = 1;
		}
	}
}

// If the user is a student and has both a parent and a mentor assigned to them, only display a message and a hyperlink to the previous page, otherwise continue below.
if ($UserType == 1 && $dosearch == 0) {
	echo "<p>You already have a parent and a mentor assigned to you. You may only have one parent and one mentor at any one time. Please delete your parent/mentor association if you wish to add a new contact.</p>";
} else {

// Students may only search for parents or mentors of the user type they are not connected with.
	if ($UserType == 1) {
		echo "<p>Search a ";
		if (!$parentexists) {
			echo "parent ";
			if (!$mentorexists) {
				echo "or mentor";
			}
		} else if (!$mentorexists) {
			echo "mentor";
		}
		echo "</p>";
	} else if ($UserType == 2 || $UserType == 3) {
		echo "<p>Search a student</p>";
	}

// Students may have either a parent or a mentor to search, but that is the only major distinction.
	if ($UserType == 1) {
		echo "<form action = 'SearchContactPage.php?RequestedUserFirstName=$RequestedUserFirstName&RequestedUserSurname=$RequestedUserSurname&RequestedUserID=$RequestedUserID&RequestedUserType=$RequestedUserType&firstnamefind=$firstnamefind&surnamefind=$surnamefind&idfind=$idfind' method = 'get'>";
		
// Parents and mentors may only search students, but students have a form group.
	} else if ($UserType == 2 || $UserType == 3) {
		echo "<form action = 'SearchContactPage.php?RequestedUserFirstName=$RequestedUserFirstName&RequestedUserSurname=$RequestedUserSurname&RequestedUserID=$RequestedUserID&StudentForm=$StudentForm&RequestedUserType=$RequestedUserType&firstnamefind=$firstnamefind&surnamefind=$surnamefind&idfind=$idfind' method = 'get'>";
	}
	
// Display menu.
	echo "<table>";
	echo "<tr>";
	echo "<td>";
	
// First name may be searched using text forming the beginning of the name, the end of the name or in part of the name. Default selection is that the name contains what is input.
	echo "<p>First Name:</p>";
	echo "<p>Begins with:<input type='radio' name='firstnamefind' value=1></input> <br />";
	echo "Ends with:<input type='radio' name='firstnamefind' value=2></input><br />";
	echo "Contains:<input type='radio' name='firstnamefind' value=3 checked='checked'></input></p>";
	echo "<input type = 'text' name = 'RequestedUserFirstName' id = 'RequestedUserFirstName' maxlength = '15'>";
	echo "</input>";
	echo "</td>";
	echo "<td>";
	
// Surname may be searched using text forming the beginning of the name, the end of the name or in part of the name. Default selection is that the name contains what is input.
	echo "<p>Surname:</p>";
	echo "<p>Begins with:<input type='radio' name='surnamefind' value=1></input> <br />";
	echo "Ends with:<input type='radio' name='surnamefind' value=2></input> <br />";
	echo "Contains:<input type='radio' name='surnamefind' value=3 checked='checked'></input></p>";
	echo "<input type = 'text' name = 'RequestedUserSurname' id = 'RequestedUserSurname' maxlength = '20'>";
	echo "</input>";
	echo "</td>";
	echo "<td>";

// ID is searched either exactly matching the input ID or any number containing the ID (default).
	echo "<p>ID:</p>";
	echo "<p>Is exactly:<input type='radio' name='idfind' value=1></input><br />";
	echo "Contains:<input type='radio' name='idfind' value=2 checked='checked'></input></p> ";
	echo "<p><input type = 'int' name = 'RequestedUserID' id = 'RequestedUserID' size = '3' maxlength = '3' ></p>";
	echo "</td>";
	echo "</tr>";
	echo "</table><p>";

// If the user is a student, enable them to search set user types.
	if ($UserType == 1) {
		if ($parentexists && (!($mentorexists))) {
			echo "<input type = 'hidden' name = 'RequestedUserType' value = 3></input>";
		} else if ((!($parentexists)) && $mentorexists) {
			echo "<input type = 'hidden' name = 'RequestedUserType' value = 2></input>";
		} else if (!($parentexists) && (!($mentorexists))) {
			echo "<input type='radio' name='RequestedUserType' value=2>Parent</input>";
			echo "<input type='radio' name='RequestedUserType' value=3>Mentor</input>";
		}
		echo "</p>";

// Parents and mentors have similar searches for students. They may search for students in specific forms.
	} else if ($UserType == 2 || $UserType == 3) {
		echo "Student form: ";
		echo "<input type = 'hidden' name = 'RequestedUserType' value = 1></input>";
		$studentformsql = "SELECT DISTINCT StudentForm FROM StudentTable";
		$studentformquery = mysql_query($studentformsql);
		echo "<select name = 'StudentForm'>";
		echo "<option></option>";
		for ($formno = 1; $formno <= ($studentformarray = mysql_fetch_array($studentformquery)); $formno++) {
			echo "<option>$studentformarray[0]</option>";
		}
		echo "</select></p>";
	}
	echo "<input name = 'submit' type = 'submit' id='submit' >";
	echo "</form><br />";
	
// Once the details are input, retrieve the information.
	if (isset($_GET['RequestedUserType'])) {
		$RequestedUserType = $_GET['RequestedUserType'];
		
// Some fields may be left blank. If so, they will be ignored in the search. If all fields are blank then search all entries (students will only appear if they do not already have the same type of user as the user searching for them).
		if (isset($_GET['RequestedUserFirstName'])) {
			$RequestedUserFirstName = $_GET['RequestedUserFirstName'];
		} else {
			$RequestedUserFirstName = '';
		}
		if (isset($_GET['RequestedUserSurname'])) {
			$RequestedUserSurname = $_GET['RequestedUserSurname'];
		} else {
			$RequestedUserSurname = '';
		}
		if (isset($_GET['RequestedUserID'])) {
			$RequestedUserID = $_GET['RequestedUserID'];
		} else {
			$RequestedUserID = '';
		}
		if ($UserType == 2 || $UserType == 3) {
			if (isset($_GET['StudentForm'])) {
				$StudentForm = $_GET['StudentForm'];
			} else {
				$StudentForm = '';
			}
		}
		$firstnamefind = $_GET['firstnamefind'];
		$surnamefind = $_GET['surnamefind'];
		$idfind = $_GET['idfind'];
		switch ($RequestedUserType) {
		
// Searching a student.
			case 1:
				if ($UserType == 2 || $UserType == 3) {
					$studentsql1 = "SELECT StudentFirstName, StudentSurname, StudentID, StudentForm, ParentID, MentorID FROM StudentTable WHERE StudentFirstName LIKE ";
					if ($firstnamefind == 1) {
						$studentsql2 = "'$RequestedUserFirstName%'"; 
					} else if ($firstnamefind == 2) {
						$studentsql2 = "'%$RequestedUserFirstName'";
					} else if ($firstnamefind == 3) {
						$studentsql2 = "'%$RequestedUserFirstName%'";
					}
					$studentsql3 = "AND StudentSurname LIKE ";
					if ($surnamefind == 1) {
						$studentsql4 = "'$RequestedUserSurname%' ";
					} else if ($surnamefind == 2) {
						$studentsql4 = "'%$RequestedUserSurname' ";
					} else if ($surnamefind == 3) {
						$studentsql4 = "'%$RequestedUserSurname%' ";
					}
					$studentsql5 = "AND StudentID LIKE ";
					if ($idfind == 1) {
						$studentsql6 = "'$RequestedUserID'";
					} else if ($idfind == 2) {
						$studentsql6 = "'%$RequestedUserID%' ";
					}
					if ($StudentForm == '') {
						$studentsql7 = "";
					} else {
						$studentsql7 = "AND StudentForm = '$StudentForm' ";
					}
					if ($UserType == 2) {
						$studentsql8 = "AND ParentID IS NULL";
					} else if ($UserType == 3) {
						$studentsql8 = "AND MentorID IS NULL";
					}
					$studentsql = $studentsql1 . $studentsql2 . $studentsql3 . $studentsql4 . $studentsql5 . $studentsql6 . $studentsql7 . $studentsql8;
					$studentqueryvalidation = mysql_query($studentsql);
					if ($studentqueryvalidation) {
						$studentquery = mysql_query($studentsql);
						$studentarray = mysql_fetch_array($studentqueryvalidation);
						if ($studentarray) {
							echo "<table border = '5px'>";
							echo "<tr> <td><p>Student first name</p></td> <td><p>Student surname</p></td> <td><p>Student ID</p></td> <td><p>Student form</p></td> <td></td> </tr>";
							for ($studentno = 1; $studentno <= ($studentarray = mysql_fetch_array($studentquery)); $studentno++) {
								echo "<tr> <td><p>$studentarray[0]</p></td> <td><p>$studentarray[1]</p></td> <td><p>$studentarray[2]</p></td> <td><p>$studentarray[3]</p></td> <td><p><a href = 'AddRequestPage.php?RequestedUserID=$studentarray[2]&RequestedUserType=$RequestedUserType&action=1'>Add request</a></p></td> </tr>";
							}
							echo "</table>";
						} else {
							echo "<p>Query found no results. Please alter your search</p>";
						}
					} else {
						echo "<p>Query not searched. Check your input details.</p>";
					}
				}
			break;
			
// Searching for a parent.
			case 2:
				if ($UserType == 1) {
					$parentsql1 = "SELECT ParentTable.ParentFirstName, ParentTable.ParentSurname, ParentTable.ParentID FROM ParentTable WHERE ParentTable.ParentFirstName LIKE";
					if ($firstnamefind == 1) {
						$parentsql2 = "'$RequestedUserFirstName%'"; 
					} else if ($firstnamefind == 2) {
						$parentsql2 = "'%$RequestedUserFirstName'";
					} else if ($firstnamefind == 3) {
						$parentsql2 = "'%$RequestedUserFirstName%'";
					}
					$parentsql3 = "AND ParentTable.ParentSurname LIKE ";
					if ($surnamefind == 1) {
						$parentsql4 = "'$RequestedUserSurname%' ";
					} else if ($surnamefind == 2) {
						$parentsql4 = "'%$RequestedUserSurname' ";
					} else if ($surnamefind == 3) {
						$parentsql4 = "'%$RequestedUserSurname%' ";
					}
					$parentsql5 = "AND ParentTable.ParentID LIKE ";
					if ($idfind == 1) {
						$parentsql6 = "'$RequestedUserID' ";
					} else if ($idfind == 2) {
						$parentsql6 = "'%$RequestedUserID%' ";
					}
					$parentsql = $parentsql1 . $parentsql2 . $parentsql3 . $parentsql4 . $parentsql5 . $parentsql6;
					$parentqueryvalidation = mysql_query($parentsql);
					if ($parentqueryvalidation) {
						$parentquery = mysql_query($parentsql);
						$parentarray = mysql_fetch_array($parentqueryvalidation);
						if ($parentarray) {
							echo "<table border = '5px'>";
							echo "<tr> <td><p>Parent first name</p></td> <td><p>Parent surname</p></td> <td><p>Parent ID</p></td> <td></td> </tr>";
							for ($parentno = 1; $parentno <= ($parentarray = mysql_fetch_array($parentquery)); $parentno++) {
								echo "<tr> <td><p>$parentarray[0]</p></td> <td><p>$parentarray[1]</p></td> <td><p>$parentarray[2]</p></td> <td><p><a href = 'AddRequestPage.php?RequestedUserID=$parentarray[2]&RequestedUserType=$RequestedUserType&action=1'>Add request</a></p></td> </tr>";
							}
							echo "</table>";
						} else {
							echo "<p>Query found no results. Please alter your search.</p>";
						}
					} else {
						echo "<p>Query not searched. Check your input details.</p>";
					}
				}
			break;
			
// Searching a mentor.
			case 3:
				if ($UserType == 1) {
					$mentorsql1 = "SELECT MentorTable.MentorFirstName, MentorTable.MentorSurname, MentorTable.MentorID FROM MentorTable WHERE MentorTable.MentorFirstName LIKE";
					if ($firstnamefind == 1) {
						$mentorsql2 = "'$RequestedUserFirstName%'"; 
					} else if ($firstnamefind == 2) {
						$mentorsql2 = "'%$RequestedUserFirstName'";
					} else if ($firstnamefind == 3) {
						$mentorsql2 = "'%$RequestedUserFirstName%'";
					}
					$mentorsql3 = "AND MentorTable.MentorSurname LIKE ";
					if ($surnamefind == 1) {
						$mentorsql4 = "'$RequestedUserSurname%' ";
					} else if ($surnamefind == 2) {
						$mentorsql4 = "'%$RequestedUserSurname' ";
					} else if ($surnamefind == 3) {
						$mentorsql4 = "'%$RequestedUserSurname%' ";
					}
					$mentorsql5 = "AND MentorTable.MentorID LIKE ";
					if ($idfind == 1) {
						$mentorsql6 = "'$RequestedUserID' ";
					} else if ($idfind == 2) {
						$mentorsql6 = "'%$RequestedUserID%' ";
					}
					$mentorsql = $mentorsql1 . $mentorsql2 . $mentorsql3 . $mentorsql4 . $mentorsql5 . $mentorsql6;
					$mentorqueryvalidation = mysql_query($mentorsql);
					if ($mentorqueryvalidation) {
						$mentorquery = mysql_query($mentorsql);
						$mentorarray = mysql_fetch_array($mentorqueryvalidation);
						if ($mentorarray) {
							echo "<table border = '5px'>";
							echo "<tr> <td><p>Mentor first name</p></td> <td><p>Mentor surname</p></td> <td><p>Mentor ID</p></td> <td></td> </tr>";
							for ($mentorno = 1; $mentorno <= ($mentorarray = mysql_fetch_array($mentorquery)); $mentorno++) {
								echo "<tr> <td><p>$mentorarray[0]</p></td> <td><p>$mentorarray[1]</p></td> <td><p>$mentorarray[2]</p></td> <td><a href = 'AddRequestPage.php?RequestedUserID=$mentorarray[2]&RequestedUserType=$RequestedUserType&action=1'>Add request</a></p></td> </tr>";
							}
							echo "</table>";
						} else {
							echo "<p>Query found no results. Please alter your search.</p>";
						}
					} else {
						echo "<p>Query not searched. Check your input details.</p>";
					}
				}
			break;
		}
	} else {
	
// This message is displayed when the search has not been executed.
		echo "<p>Please select your search criteria. By default, if no fields have entered data all of the possible users are displayed.</p>";
	}
}
echo "<p><a href = 'ViewContactPage.php'>View contacts</a></p>";
echo "<br />";
?>
</body>
</html>