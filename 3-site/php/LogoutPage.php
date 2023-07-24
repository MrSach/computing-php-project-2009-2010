<?php
session_start();
$_SESSION['LoggedIn'] = false;
session_destroy();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>logging out...</title></title>
</head>
<body>
<?php
if ($_SESSION['LoggedIn'] == false) {
	header("location:LoginPage.php");
} else {
	header("location:LoggedInPage.php");
}
?>
</body>
</html>