<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Add target</title>
<link rel = "stylesheet" href = "stylesheet.css" type = "text/css" >
<?php
require_once ('DatabaseConnectPage.php');
session_start();
?>
</head>
<body>
<?php

// Retrieve the user type
$UserType = $_SESSION['UserType'];

// User type must be a mentor, otherwise the user may not enter beyond this point.
if ($UserType == 3) {

// Targets created will be connected to current meeting, so meeting ID is required.
	$MeetingID = $_GET['MeetingID'];
	
// Each page must directly address the issue of security, hence why the user ID is useful.
	$UserID = $_SESSION['UserID'];
	$validatemeetingsql = "SELECT * FROM MeetingTable WHERE MentorID = $UserID AND MeetingID = $MeetingID";
	$validatemeetingquery = mysql_query($validatemeetingsql);
	if ($validatemeetingarray = mysql_fetch_array($validatemeetingquery)) {
		$action = $_GET['action'];
		
// Target details will undergo checking before adding if $action is set to 1.
		if ($action == 1) {
		
// Checks if each required property has actually been set.
			if ( (isset($_GET['TargetUserType']) && ( ($_GET['TargetUserType'] == 1) || ($_GET['TargetUserType'] == 2) || ($_GET['TargetUserType'] == 3) ) ) && (addslashes(isset($_GET['UserTarget'])) && (addslashes($_GET['UserTarget']) != '') ) && (isset($_GET['day']) && ($_GET['day'] != '') ) && (isset($_GET['month']) && ($_GET['month'] != '') ) ) {
				$TargetUserType = $_GET['TargetUserType'];
				$UserTarget = addslashes($_GET['UserTarget']);
				$day = $_GET['day'];
				$month = $_GET['month'];
				if (addslashes(isset($_GET['MentorComment']))) {
					$MentorComment = addslashes($_GET['MentorComment']);
				} else {
					$MentorComment = '';
				}
				$yyyy = date('Y');
				$maxday = date('t', mktime(0,0,0,$month,1,$yyyy,0));
				if ($day >= 1 && $day <= $maxday && $day == floor($day)) {
					$dd = date('d', mktime(0,0,0,$month, $day, $yyyy, 0));
					$mm = date('m', mktime(0,0,0,$month, $day, $yyyy, 0));
					$currentmonth = date('n');
					$currentday = date('j');
					
// Proceeds if either: both months are in the last 4 months in the calendar but current month is earlier than scheduled month; current month is before end of calendar year, but scheduled month is after the start of new calendar year; both months after new calendar year, but current month before scheduled month; both dates are in the same month and the deadline is later in the month than the current day. The aim of this is so that targets need to be set in advance, not after the deadline.
					if ( ( ($mm >= 9 && $mm <= 12 && $currentmonth >= 9 && $currentmonth <= 12) || ($mm >= 1 && $mm <= 6 && $currentmonth >= 9 && $currentmonth <= 12) && $mm > $currentmonth) || ($mm >= 1 && $mm <= 6 && $currentmonth >= 1 && $currentmonth <= 6 && $mm > $currentmonth) || ($mm == $currentmonth && $day >= $currentday ) ) {
					
// Academic years overlap over two Gregorian years so if mentor sets a target in any month between September and December which is to be met between January and June, the year is incremented by one. This helps with ordering targets by date.
						if (($mm >= 1 && $mm <= 6) && ($currentmonth >= 9 && $currentmonth <= 12)) {
							$yyyy++;
						}
						
// Properties are prepared for being inserted into new target.
						$DateDue = $yyyy . $mm . $dd;
						$addsql = "INSERT INTO TargetTable (TargetUserType, UserTarget, DateDue, MentorComment, TargetMetYet, MeetingID) VALUES ($TargetUserType, '$UserTarget', $DateDue, '$MentorComment', 0, $MeetingID)";
						$addquery = mysql_query($addsql);
						echo "<p><a href = 'ViewTargetPage.php?MeetingID=$MeetingID'>Go back - View targets</a></p>";
						header("location:ViewTargetPage.php?MeetingID=$MeetingID");
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

// By default, the action is to enter details for the new target.
		if ($action == 0) {

// Set any variables not used at this point to null. This eliminates error messages being reported for any unassigned variables.
			$currentday = '';
			$currentmonth = '';
			$TargetUserType = '';
			$UserTarget = '';
			$DateDue = '';
			$StudentComment = '';
			$ParentComment = '';
			$MentorComment = '';
			$TargetMetYet = '';
			$TargetID = '';
			$day = '';
			$month = '';
			$maxday = '';
			$dd = '';
			$mm = '';
			$yy = '';
			$displayparent = 0;
			
// Begin form.
			echo "<form action = 'AddTargetPage.php?TargetUserType=$TargetUserType&UserTarget=$UserTarget&MentorComment=$MentorComment&day=$day&month=$month&MeetingID=$MeetingID&action=1' method = 'get'>";
			
// Search for student. SQL queries are used instead of session variables because this method responds immediately to updates, for example, if erroneous details have been rectified then they should display correctly.
			$studentidsql = "SELECT StudentID FROM MeetingTable WHERE MeetingID = $MeetingID";
			$studentidquery = mysql_query($studentidsql);
			$studentidarray = mysql_fetch_array($studentidquery);
			$studentsql = "SELECT StudentFirstName, StudentSurname, StudentForm FROM StudentTable WHERE StudentID = $studentidarray[0]";
			$studentquery = mysql_query($studentsql);
			if ($studentarray = mysql_fetch_array($studentquery)) {
			
// Parent search, similar to the code used in ViewMeetingPage and ViewContactPage, because without a student there would be no use of a parent.
				$parentidsql = "SELECT ParentID FROM StudentTable WHERE StudentId = $studentidarray[0]";
				$parentidquery = mysql_query($parentidsql);
				if ($parentidarray = mysql_fetch_array($parentidquery)) {
					$parentsql = "SELECT ParentFirstName, ParentSurname FROM ParentTable WHERE ParentID = $parentidarray[0]";
					$parentquery = mysql_query($parentsql);
					if ($parentarray = mysql_fetch_array($parentquery)) {
						$displayparent = 1;
					}
				}
			}
			
// Selection of target's user display.
			echo "<p>Which of the users is this target set for? ";
			echo "<select name = 'TargetUserType'>";
			echo "<option></option>";
			echo "<option value = '1'>$studentarray[0] $studentarray[1], form $studentarray[2], ID $studentidarray[0], student</option>";
			if ($displayparent == 1) {
				echo "<option value = '2'>$parentarray[0] $parentarray[1], ID $parentidarray[0], parent</option>";
			}
			echo "<option value = '3'>Myself, mentor</option>";
			echo "</select></p>";
			
// Input boxes for inserting text for target and mentor comment.
			echo "<p>User target: ";
			echo "<input name = 'UserTarget' type = 'text'>";		
			echo "</input></p>";
			echo "<p>Comment: ";
			echo "<input name = 'MentorComment' type = 'text'>";		
			echo "</input></p>";
			
// Date input - day and month.
			echo "<p>Date target due: ";
			echo "day ";
			echo "<input name = 'day' type = 'text' size = '2'  maxlength = '2'>";		
			echo "</input>";
			echo "month ";
			echo "<select name = 'month'>";
			echo "<option></option>";
			echo "<option value = '9'>September</option>";
			echo "<option value = '10'>October</option>";
			echo "<option value = '11'>November</option>";
			echo "<option value = '12'>December</option>";
			echo "<option value = '1'>January</option>";
			echo "<option value = '2'>February</option>";
			echo "<option value = '3'>March</option>";
			echo "<option value = '4'>April</option>";
			echo "<option value = '5'>May</option>";
			echo "<option value = '6'>June</option>";
			echo "</select></p>";
			echo "<input name = 'MeetingID' type = 'hidden' value = '$MeetingID'>";
			echo "<input name = 'action' type = 'hidden' value = '1'>";
			echo "<p><input name = 'submit' type = 'submit' id='submit'></p>";
			echo "</form>";
			echo "<p><a href = 'ViewTargetPage.php?MeetingID=$MeetingID'>Cancel and view targets</a></p>";
		}
	} else {
	
// Errors for when the wrong meeting ID has been input and if user is not a mentor.
		header("location:ViewTargetPage.php?MeetingID=$MeetingID");
	}
} else {
	header("location:ViewTargetPage.php?MeetingID=$MeetingID");
}
?>
</body>
</html>