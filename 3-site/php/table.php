<?php

require('DatabaseConnectPage.php');

$sql = "SELECT StudentLogin, StudentPassword, StudentID, StudentFirstName, StudentSurname, StudentEmail, StudentPhoneNumber, StudentForm, ParentID, MentorID FROM StudentTable";
$query = mysql_query($sql);

echo "StudentTable<br />";
echo "<table border = '2px'>";

echo "<tr> <td>Login</td> <td>Password</td> <td>ID</td> <td>First Name</td> <td>Surname</td> <td>Email</td> <td>Tel</td> <td>Form</td> <td>Parent ID</td> <td>Mentor ID</td> </tr> </tr>";

for ($a = 1; $a <= ($array = mysql_fetch_array($query)); $a++) {
	echo "<tr> <td>$array[0]</td> <td>$array[1]</td> <td>$array[2]</td> <td>$array[3]</td> <td>$array[4]</td> <td>$array[5]</td> <td>$array[6]</td> <td>$array[7]</td> <td>$array[8]</td> <td>$array[9]</td> </tr>";
}
echo "</table>";

echo "<br />";

$sql = "SELECT ParentLogin, ParentPassword, ParentID, ParentFirstName, ParentSurname, ParentEmail, ParentPhoneNumber FROM ParentTable";
$query = mysql_query($sql);

echo "ParentTable<br />";
echo "<table border = '2px'>";

echo "<tr> <td>Login</td> <td>Password</td> <td>ID</td> <td>First Name</td> <td>Surname</td> <td>Email</td> <td>Tel</td> </tr> </tr>";

for ($a = 1; $a <= ($array = mysql_fetch_array($query)); $a++) {
	echo "<tr> <td>$array[0]</td> <td>$array[1]</td> <td>$array[2]</td> <td>$array[3]</td> <td>$array[4]</td> <td>$array[5]</td> <td>$array[6]</td> </tr>";
}
echo "</table>";

echo "<br />";

$sql = "SELECT MentorLogin, MentorPassword, MentorID, MentorFirstName, MentorSurname, MentorEmail, MentorPhoneNumber FROM MentorTable";
$query = mysql_query($sql);

echo "MentorTable<br />";
echo "<table border = '2px'>";

echo "<tr> <td>Login</td> <td>Password</td> <td>ID</td> <td>First Name</td> <td>Surname</td> <td>Email</td> <td>Tel</td> </tr>";

for ($a = 1; $a <= ($array = mysql_fetch_array($query)); $a++) {
	echo "<tr> <td>$array[0]</td> <td>$array[1]</td> <td>$array[2]</td> <td>$array[3]</td> <td>$array[4]</td> <td>$array[5]</td> <td>$array[6]</td> </tr>";
}
echo "</table>";

echo "<br />";

$sql = "SELECT MeetingID, MeetingDate, StudentID, MentorID FROM MeetingTable";
$query = mysql_query($sql);

echo "MeetingTable<br />";
echo "<table border = '2px'>";

echo "<tr> <td>Meeting ID</td> <td>Meeting Date</td> <td>Student ID</td> <td>Mentor ID</td> </tr>";

for ($a = 1; $a <= ($array = mysql_fetch_array($query)); $a++) {
	echo "<tr> <td>$array[0]</td> <td>$array[1]</td> <td>$array[2]</td> <td>$array[3]</td> </tr>";
}
echo "</table>";

echo "<br />";

$sql = "SELECT MeetingID, TargetID, StudentTarget, MentorTarget, DateDue, StudentComment, ParentComment, MentorComment, TargetMetYet FROM TargetTable";
$query = mysql_query($sql);

echo "TargetTable<br />";
echo "<table border = '2px'>";

echo "<tr> <td>Meeting ID</td> <td>Target ID</td> <td>Student Target</td> <td>Mentor Target</td> <td>Date Due</td> <td>Student Comment</td> <td>Parent Comment</td> <td>Mentor Comment</td> <td>Target met?</td> </tr>";

for ($a = 1; $a <= ($array = mysql_fetch_array($query)); $a++) {
	echo "<tr> <td>$array[0]</td> <td>$array[1]</td> <td>$array[2]</td> <td>$array[3]</td> <td>$array[4]</td> <td>$array[5]</td> <td>$array[6]</td> <td>$array[7]</td> <td>$array[8]</td></tr>";
}
echo "</table>";

echo "<br />";

$sql = "SELECT MeetingID, FileID, FileName, FileExtension, FileSize, FileResourceLocation, UserType, UserID FROM FileTable";
$query = mysql_query($sql);

echo "FileTable<br />";
echo "<table border = '2px'>";

echo "<tr> <td>Meeting ID</td> <td>File ID</td> <td>File Name</td> <td>File Extension</td> <td>File Size</td> <td>File Resource Location</td> <td>User Type - Uploader</td> <td>User ID - Uploader</td> </tr>";

for ($a = 1; $a <= ($array = mysql_fetch_array($query)); $a++) {
	echo "<tr> <td>$array[0]</td> <td>$array[1]</td> <td>$array[2]</td> <td>$array[3]</td> <td>$array[4]</td> <td>$array[5]</td> <td>$array[6]</td> <td>$array[7]</td> </tr>";
}
echo "</table>";

echo "<br />";

$sql = "SELECT RequestID, RequesterUsertype, RequesterUserID, RequestedUserType, RequestedUserID FROM RequestTable";
$query = mysql_query($sql);

echo "RequestTable<br />";
echo "<table border = '2px'>";

echo "<tr> <td>Request ID</td> <td>Requester User Type</td> <td>Requester User ID</td> <td>Requested User Type</td> <td>Requested User ID</td> </tr>";

for ($a = 1; $a <= ($array = mysql_fetch_array($query)); $a++) {
	echo "<tr> <td>$array[0]</td> <td>$array[1]</td> <td>$array[2]</td> <td>$array[3]</td> <td>$array[4]</td> </tr>";
}
echo "</table>";

echo "<br />";

?>