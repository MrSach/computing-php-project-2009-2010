<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Edit target</title>
<link rel = "stylesheet" href = "stylesheet.css" type = "text/css" >
<?php
require_once ('DatabaseConnectPage.php');
session_start();
?>
</head>
<body>
<?php

// User type retrieved.
$UserType = $_SESSION['UserType'];

// User must be a mentor to access this page.
if ($UserType == 3) {

// Details of meeting retrieved.
	$MeetingID = $_GET['MeetingID'];
	$UserID = $_SESSION['UserID'];

// Checks if mentor and meeting match.
	$validatemeetingsql = "SELECT * FROM MeetingTable WHERE MentorID = $UserID AND MeetingID = $MeetingID";
	$validatemeetingquery = mysql_query($validatemeetingsql);
	if ($validatemeetingarray = mysql_fetch_array($validatemeetingquery)) {
		$TargetID = $_GET['TargetID'];
		$targetsql = "SELECT TargetUserType, UserTarget, DateDue, StudentComment, ParentComment, MentorComment, TargetMetYet FROM TargetTable WHERE TargetID = $TargetID";
		$targetquery = mysql_query($targetsql);
		if ($targetarray = mysql_fetch_array($targetquery)) {
			$action = $_GET['action'];
			$TargetUserType = $targetarray[0];
			$UserTarget = addslashes($targetarray[1]);
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
			
// When the information is being transferred into the validaiton procedure.
			if ($action == 1) {
			
// Each field required must not be left blank.
				if ( (isset($_GET['TargetUserType']) && ( ($_GET['TargetUserType'] == 1) || ($_GET['TargetUserType'] == 2) || ($_GET['TargetUserType'] == 3) ) ) && (addslashes(isset($_GET['UserTarget'])) && (addslashes($_GET['UserTarget']) != '') ) && (isset($_GET['day']) && ($_GET['day'] != '') ) && (isset($_GET['month']) && ($_GET['month'] != '') ) ) {
					$TargetUserType = $_GET['TargetUserType'];
					$UserTarget = addslashes($_GET['UserTarget']);
					$day = $_GET['day'];
					$month = $_GET['month'];
					$TargetMetYet = $_GET['TargetMetYet'];
					if (addslashes(isset($_GET['MentorComment']))) {
						$MentorComment = addslashes($_GET['MentorComment']);
					} else {
						$MentorComment = '';
					}
					$yyyy = date('Y');
					$maxday = date('t', mktime(0,0,0,$month,1,$yyyy,0));
					
// Checks if given day fits within set month.
					if ($day >= 1 && $day <= $maxday && $day == floor($day)) {
						$dd = date('d', mktime(0,0,0,$month, $day, $yyyy, 0));
						$mm = date('m', mktime(0,0,0,$month, $day, $yyyy, 0));
						$currentmonth = date('n');
						$currentday = date('j');
						
// Due date must not be earlier in the academic year than the current day.
						if ( ( ($mm >= 9 && $mm <= 12 && $currentmonth >= 9 && $currentmonth <= 12) || ($mm >= 1 && $mm <= 6 && $currentmonth >= 9 && $currentmonth <= 12) && $mm > $currentmonth) || ($mm >= 1 && $mm <= 6 && $currentmonth >= 1 && $currentmonth <= 6 && $mm > $currentmonth) || ($mm == $currentmonth && $day >= $currentday ) ) {
							if (($mm >= 1 && $mm <= 6) && ($currentmonth >= 9 && $currentmonth <= 12)) {
								$yyyy++;
							}
							
// Prepares target information for inserting into query, which is then used to modify target details.
							$DateDue = $yyyy . $mm . $dd;
							$editsql = "UPDATE TargetTable SET TargetUserType = $TargetUserType, UserTarget = '$UserTarget', DateDue = $DateDue, MentorComment = '$MentorComment', TargetMetYet = $TargetMetYet, MeetingID = $MeetingID WHERE TargetID = $TargetID";
							$editquery = mysql_query($editsql);
							echo "<p>Target edited.</p>";
							echo "<p><a href = 'ViewTargetPage.php?MeetingID=$MeetingID'>Go back - View targets</a></p>";
							header("location:ViewTargetPage.php?MeetingID=$MeetingID");
						
// User will need to enter new details again if date set was invalid or if not all fields were correctly entered in.
						} else {
							$action = 0;
							echo "<p>Cannot set a due date earlier than today.</p>";
						}
					} else {
						$action = 0;
						echo "<p>Error with date input information. Please enter an appropriate date.</p>";
					}
				} else {
					$action = 0;
					echo "<p>Please enter data in required fields (user, target and date due)</p>";
				}
			}

// User is prompted with input boxes to alter the current information, just as they would with adding a meeting.
			if ($action == 0) {
				$currentday = '';
				$currentmonth = '';
				$day = '';
				$month = '';
				$yyyy = '';
				$mm = '';
				$dd = '';
				$displayparent = 0;
				echo "<form action = 'EditTargetPage.php?TargetUserType=$TargetUserType&UserTarget=$UserTarget&MentorComment=$MentorComment&day=$day&month=$month&TargetMetYet=$TargetMetYet&MeetingID=$MeetingID&TargetID=$TargetID&action=1' method = 'get'>";
				$studentidsql = "SELECT StudentID FROM MeetingTable WHERE MeetingID = $MeetingID";
				$studentidquery = mysql_query($studentidsql);
				$studentidarray = mysql_fetch_array($studentidquery);
				$studentsql = "SELECT StudentFirstName, StudentSurname, StudentForm FROM StudentTable WHERE StudentID = $studentidarray[0]";
				$studentquery = mysql_query($studentsql);
				if ($studentarray = mysql_fetch_array($studentquery)) {
					$parentidsql = "SELECT ParentID FROM StudentTable WHERE StudentID = $studentidarray[0]";
					$parentidquery = mysql_query($parentidsql);
					if ($parentidarray = mysql_fetch_array($parentidquery)) {
						$parentsql = "SELECT ParentFirstName, ParentSurname FROM ParentTable WHERE ParentID = $parentidarray[0]";
						$parentquery = mysql_query($parentsql);
						if ($parentarray = mysql_fetch_array($parentquery)) {
							$displayparent = 1;
						}
					}
				}
				echo "<p>Which of the users is this target set for? ";
				echo "<select name = 'TargetUserType'>";
				echo "<option></option>";
				echo "<option value = '1'";
				if ($TargetUserType == 1) {
				echo " SELECTED";
				}
				echo ">$studentarray[0] $studentarray[1], form $studentarray[2], ID $studentidarray[0]</option>, student";
				if ($displayparent == 1) {
					echo "<option value = '2'";
					if ($TargetUserType == 2) {
					echo " SELECTED";
					}
				echo ">$parentarray[0] $parentarray[1], ID $parentidarray[0], parent</option>";
				}
				echo "<option value = '3'";
				if ($TargetUserType == 3) {
				echo " SELECTED";
				}
				echo ">Myself, mentor</option>";
				echo "</select></p>";
				echo "<p>User target: ";
				echo "<input name = 'UserTarget' type = 'text' value = '$UserTarget'>";
				echo "</input></p>";
				echo "<p>Student's comment: " . $StudentComment . "</p>";
				echo "<p>Parent's comment: " . $ParentComment . "</p>";
				echo "<p>Comment: ";
				echo "<input name = 'MentorComment' type = 'text' value = '$MentorComment'>";	
				echo "</input></p>";
				$yyyy = "$DateDue[0]" . "$DateDue[1]" . "$DateDue[2]" . "$DateDue[3]";
				$mm = "$DateDue[5]" . "$DateDue[6]";
				$dd = "$DateDue[8]" . "$DateDue[9]";
				echo "<p>Date target due: ";
				echo "day ";
				echo "<input name = 'day' type = 'text' size = '2'  maxlength = '2' value = '$dd'>";
				echo "</input>";
				echo "month ";
				echo "<select name = 'month'>";
				echo "<option></option>";
				echo "<option value = '9'";
				if ($mm == 9) {
					echo " SELECTED";
				}
				echo ">September</option>";
				echo "<option value = '10'";
				if ($mm == 10) {
					echo " SELECTED";
				}
				echo ">October</option>";
				echo "<option value = '11'";
				if ($mm == 11) {
					echo " SELECTED";
				}
				echo ">November</option>";
				echo "<option value = '12'";
				if ($mm == 12) {
					echo " SELECTED";
				}
				echo ">December</option>";
				echo "<option value = '1'";
				if ($mm == 1) {
					echo " SELECTED";
				}
				echo ">January</option>";
				echo "<option value = '2'";
				if ($mm == 2) {
					echo " SELECTED";
				}
				echo ">February</option>";
				echo "<option value = '3'";
				if ($mm == 3) {
					echo " SELECTED";
				}
				echo ">March</option>";
				echo "<option value = '4'";
				if ($mm == 4) {
					echo " SELECTED";
				}
				echo ">April</option>";
				echo "<option value = '5'";
				if ($mm == 5) {
					echo " SELECTED";
				}
				echo ">May</option>";
				echo "<option value = '6'";
				if ($mm == 6) {
					echo " SELECTED";
				}
				echo ">June</option>";
				echo "</p></select>";
				
				echo "<p>Target met?";
				echo "<input type='radio' name='TargetMetYet' value=1";
				if ($TargetMetYet == 1) {
					echo " checked='checked'";
				}
				echo ">Yes</input>";
				echo "<input type='radio' name='TargetMetYet' value=0";
				if ($TargetMetYet == 0) {
					echo " checked='checked'";
				}
				echo ">No</input></p>";
				
				echo "<input name = 'MeetingID' type = 'hidden' value = '$MeetingID'>";
				echo "<input name = 'action' type = 'hidden' value = '1'>";
				echo "<input name = 'TargetID' type = 'hidden' value = '$TargetID'>";
				echo "<p><input name = 'submit' type = 'submit' id='submit'></p>";
				echo "</form>";
				echo "<p><a href = 'ViewTargetPage.php?MeetingID=$MeetingID'>Cancel and view targets</a></p>";
			}
			
// Errors are the result of an incorrect target ID, meeting ID, or that the user is not a mentor.
		} else {
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