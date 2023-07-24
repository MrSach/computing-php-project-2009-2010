<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Add meeting</title>
<link rel = "stylesheet" href = "stylesheet.css" type = "text/css" >
<?php
require_once('DatabaseConnectPage.php');
session_start();
?>
</head>
<body>
<?php

// This page is only for mentor access.
$UserType = $_SESSION['UserType'];

// If user is a mentor.
if ($UserType == 3) {

// $action can be 0 or 1, otherwise it is set to 0;
	if (isset($_GET['action'])) {
		$action = $_GET['action'];
	} else {
		$action = 0;
	}
	echo "<h1>Add meeting</h1>";
	
// Checks to see if the date has been set and if the student has been set.
	if ((isset($_GET['day']) && $_GET['day'] != '') && (isset($_GET['month']) && $_GET['month'] != '') && (isset($_GET['StudentID']) && $_GET['StudentID'] != '')) {
	
// Gets the set meeting day and month, retrieves the current year and checks the input day against the actual days in a given month.
		$day = $_GET['day'];
		$month = $_GET['month'];
		$yy = date('Y');
		$maxday = date('t', mktime(0,0,0,$month,1,$yy,0));
		if ($day >= 1 && $day <= $maxday && $day == floor($day)) {
		
// date parameters for meeting date are set.
			$dd = date('d', mktime(0,0,0,$month, $day, $yy, 0));
			$mm = date('m', mktime(0,0,0,$month, $day, $yy, 0));
			$action = 1;
		} else {
			$action = 0;
			echo "<p>Error with date input information. Please enter an appropriate date.</p>";
		}
		if ($action == 1) {
			$currentmonth = date('n');
			
// Checks if academic years match. If not, the year is set accordingly.
			if (($mm >= 1 && $mm <= 6) && ($currentmonth >= 9 && $currentmonth <= 12)) {
			
// When current month is in the range of September to December.
				$yy++;
			} else if (($mm >= 9 && $mm <= 12) && ($currentmonth >= 1 && $currentmonth <= 6)) {
			
// When current month is in the range of January to June.
				$yy--;
			}
			
// Meeting date parameters are concatenated to form the meeting date.
			$MeetingDate = $yy . $mm . $dd;
			
// StudentID is retrieved.
			$StudentID = $_GET['StudentID'];
			
// MentorID is retrieved.
			$UserID = $_SESSION['UserID'];
			
// Checks to see if student is connected with current mentor.
			$confirmsql = "SELECT * FROM StudentTable WHERE MentorID = $UserID AND StudentID = $StudentID";
			$confirmquery = mysql_query($confirmsql);
			if ($confirmquery) {
				if ($confirmarray = mysql_fetch_array($confirmquery)) {

// Adds meeting; additionally a MeetingID is automatically set.
					$addsql = "INSERT INTO MeetingTable (MeetingDate, StudentID, MentorID) VALUES ($MeetingDate, $StudentID, $UserID)";
					$addquery = mysql_query($addsql);
					
// To reduce user effort, the user is redirected to ViewMeetingPage immediately.
					header('location:ViewMeetingPage.php');
					
// Errors based on the mismatch of the student, sql query error and any blank information not set in advance.
				} else {
					echo "<p>Error - could not find matching student. </p>";
					$action = 0;
				}
			} else {
				echo "<p>Error executing sql query.</p>";
				$action = 0;
			}
		}
	} else {
		$action = 0;
		echo "<p>Please enter information in each field and then submit.</p>";
	}
	
// This is set as a separate if statement because $action could be set to 0 in the above code in case of an error.
	if ($action == 0) {
	
// Reset the following fields.
		$StudentID = '';
		$day = '';
		$month = '';
		$maxday = '';
		$dd = '';
		$mm = '';
		$yy = '';
		
// Information is then input into the form.
		$UserID = $_SESSION['UserID'];
		echo "<form action = 'AddMeetingPage.php?day=$day&month=$month&StudentID=$StudentID&action=1' method = 'get'>";
		echo "<p>Meeting date: ";
		echo "day ";
		echo "<input name = 'day' type = 'text' size = '2'  maxlength = '2'>";		
		echo "</input>";
		
// In an academic year, students are in their school year from September until the end of May. They do not finish their courses until some weeks later. Meetings could still take place in June, which is why I have included this month.
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
		$studentsql = "SELECT StudentFirstName, StudentSurname, StudentID, StudentForm FROM StudentTable WHERE MentorID = $UserID";
		$studentquery = mysql_query($studentsql);
		echo "<p>Select your student</p>";
		echo "<select name = 'StudentID'>";
		echo "<option></option>";
		for ($formno = 1; $formno <= ($studentarray = mysql_fetch_array($studentquery)); $formno++) {
			echo "<option value = '$studentarray[2]'>$studentarray[0] $studentarray[1], form $studentarray[3]</option>";
		}
		echo "</select>";
		echo "<p><input name = 'submit' type = 'submit' id='submit'></p>";
		echo "</form>";
		echo "<p><a href = 'ViewMeetingPage.php'>Cancel and view meetings</a></p>";
	}
} else {

// If user is not a mentor, they are redirected to ViewMeetingPage.
	header('location:ViewMeetingPage.php');
}
mysql_close($ConnectServer);
?>
</body>
</html>