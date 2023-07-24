<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Login page</title>
<link rel = "stylesheet" href = "stylesheet.css" type = "text/css" >
</head>
<body>
<?php

// If user is directed back here from an unsuccessful login attempt, display their entered username (if entered).
if (isset($_GET['UserLogin']) && $_GET['UserLogin'] != '') {
	$UserLogin = $_GET['UserLogin'];
	echo "Error - unsuccessful login attempt for username: " . $UserLogin . "<br />";
}
?>

<! HTML section for form >
<h1> Welcome to the Cherwell School Mentoring scheme online.</h1>
<p>Please enter your details.</p>
<form action = 'ProcessLoginPage.php?UserLogin=$UserLogin&UserPassword=$UserPassword&UserType=$UserType' method = 'get'>

<! username variable: UserLogin, the user login name >
<p> username: 
<input type = 'text' name = 'UserLogin' id = 'UserLogin' maxlength = '15'>
</p>

<! password variable: UserPassword, the user login password >
<p> passphrase:
<input type = 'password' name = 'UserPassword' id = 'UserPassword' maxlength = '12'>
</p>

<! user type variable: UserType, the type of user >
<p> user type:
<input type = 'radio' name = 'UserType' value = 1>Student</input>
<input type = 'radio' name = 'UserType' value = 2>Parent</input>
<input type = 'radio' name = 'UserType' value = 3>Mentor</input>
</p>
<input name = 'submit' type = 'submit' id= 'submit' >
</form>
<p>This system is designed to support PHP version 5.2.8 or higher and MYSQL version 5 or higher.</p>
</body>
</html>