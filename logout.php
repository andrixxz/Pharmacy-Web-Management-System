<?php
session_start(); // start the session

// unset all of the session variables and clears all data
$_SESSION = array();

// destroying the session
session_destroy();

// redirecting to the login page
header("Location: login.php");
exit;
?>