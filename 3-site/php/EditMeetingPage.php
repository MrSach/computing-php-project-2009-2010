<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Edit meeting</title>
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

// If user is a mentor and a value for $MeetingID is specified.
if ($UserType == 3 && (isset($_GET['MeetingID']) && $_GET['MeetingID'] != '') ) {
	if (isset($_GET['action']) ) {
		$action = $_GET['action'];
	}
	echo "<h1>Edit meeting</h1>";
	
// Checks to see if the date has been set and if the student has been set.
	if ($action == 1) {
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

// MeetingID is retrieved.
				$MeetingID = $_GET['MeetingID'];
				
// Checks to see if student is connected with current mentor.
				$confirmsql = "SELECT * FROM StudentTable WHERE MentorID = $UserID AND StudentID = $StudentID";
				$confirmquery = mysql_query($confirmsql);
				if ($confirmquery) {
					if ($confirmarray = mysql_fetch_array($confirmquery)) {
						$editsql = "UPDATE MeetingTable SET MeetingDate = $MeetingDate, StudentID = $StudentID, MentorID = $UserID WHERE MeetingID = $MeetingID";
						$editquery = mysql_query($editsql);
						
// To reduce user effort, the user is redirected to ViewMeetingPage immediately.
						header('location:ViewMeetingPage.php');
					} else {
						echo "<p>Error - could not find matching student.</p>";
						$action = 0;
					}
					
// Errors based on the mismatch of the student, sql query error and any blank information not set in advance.
				} else {
					echo "<p>Error executing sql query - please check you have input details correctly.</p>";
					$action = 0;
				}
			}
		} else {
			$action = 0;
			echo "<p>Please enter information in each field and then submit.</p>";
		}
	}
	
// This is set as a separate if statement because $action could be set to 0 in the above code in case of an error.
	if ($action == 0) {
	
// Reset the following fields.
		$day = '';
		$month = '';
		$action = '';
		$StudentID = '';
		$MeetingID = '';
		$maxday = '';
		$dd = '';
		$mm = '';
		$yy = '';
		
// Information is then input into the form.
		$UserID = $_SESSION['UserID'];
		$MeetingID = $_GET['MeetingID'];
		$fetchsql = "SELECT StudentID, MeetingDate FROM MeetingTable WHERE MentorID = $UserID AND MeetingID = $MeetingID";
		$fetchquery = mysql_query($fetchsql);
		if ($fetcharray = mysql_fetch_array($fetchquery)) {
			$MeetingDate = $fetcharray[1];
			$StudentID = $fetcharray[0];
			$yyyy = "$MeetingDate[0]" . "$MeetingDate[1]" . "$MeetingDate[2]" . "$MeetingDate[3]";
			$mm = "$MeetingDate[5]" . "$MeetingDate[6]";
			$dd = "$MeetingDate[8]" . "$MeetingDate[9]";
			$datetime = date('l d F Y', mktime(0,0,0, $mm, $dd, $yyyy, 0));
			echo "<p>Current meeting date is " . $datetime . "</p>";
			echo "<form action = 'EditMeetingPage.php?day=$day&month=$month&studentid=$StudentID&MeetingID=$MeetingID&action=1' method = 'get'>";
			echo "<p>Meeting date: ";
			echo "day ";
			echo "<input name = 'day' type = 'text' size = '2'  maxlength = '2' value = '$dd'>";
			echo "</input>";
			
// In an academic year, students are in their school year from September until the end of May. They do not finish their courses until some weeks later. Meetings could still take place in June, which is why I have included this month.
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
			$studentsql1 = "SELECT StudentID FROM MeetingTable WHERE MentorID = $UserID AND MeetingID = $MeetingID";
			$studentquery1 = mysql_query($studentsql1);
			$studentarray = mysql_fetch_array($studentquery1);
			$StudentID = $studentarray[0];
			$studentsql2 = "SELECT StudentTable.StudentFirstName, StudentTable.StudentSurname, StudentTable.StudentID, StudentTable.StudentForm FROM StudentTable WHERE StudentID = $StudentID";
			$studentquery2 = mysql_query($studentsql2);
			if ($studentarray = mysql_fetch_array($studentquery2)) {
				echo "<p>Current student is " . "$studentarray[0] $studentarray[1], form $studentarray[3], ID $studentarray[2].</p>";
				$StudentID = $studentarray[2];
			}
			echo "<p>Select your student</p>";
			$studentsql = "SELECT StudentFirstName, StudentSurname, StudentID, StudentForm FROM StudentTable WHERE MentorID = $UserID";
			$studentquery = mysql_query($studentsql);
			echo "<select name = 'StudentID'>";
			echo "<option></option>";
			for ($formno = 1; $formno <= ($studentarray = mysql_fetch_array($studentquery)); $formno++) {
				echo "<option value = '$studentarray[2]'";
				if ($studentarray[2] == $StudentID) {
					echo " SELECTED";
				}
			echo ">$studentarray[0] $studentarray[1], form $studentarray[3]</option>";
			}
			echo "</select>";
			echo "<p>Current meeting ID: $MeetingID</p>";
			echo "<p><input name = 'MeetingID' type = 'hidden' value='$MeetingID'></p>";
			echo "<p><input name = 'action' type = 'hidden' value='1'></p>";
			echo "<p><input name = 'submit' type = 'submit' id='submit'></p>";
			echo "</form>";
		} else {
			echo "<p>The meeting ID and your ID do not match. Please use the user interface to perform tasks.</p>";
		}
		echo "<p><a href = 'ViewMeetingPage.php'>Cancel and view meetings</a>";
	}
} else {

// If user is not a mentor, they are redirected to ViewMeetingPage.
	header('location:ViewMeetingPage.php');
}
mysql_close($ConnectServer);
?>
</body>
</html>