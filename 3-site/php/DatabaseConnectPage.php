<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
</head>
<body>
<?php

// Define the server, login username and password for the server login.
$DatabaseHost = "localhost";
$DatabaseUser = "sachasimon";
$DatabasePassword = "qp12zm";
$ConnectServer = mysql_connect($DatabaseHost,$DatabaseUser,$DatabasePassword);

// Error reported if not connected to server.
if ($ConnectServer) {
} else {
	die("error - " . mysql_error());
	echo "<br />";
}
$DatabaseName = "sachasimon";
mysql_select_db($DatabaseName);
?>
</body>
</html>