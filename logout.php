<?php
include("include/db.php");
include("include/sessions.php");

// Initialize session
session_start();

// Destroy the session
$_SESSION["User_Id"] = null;
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
?>
