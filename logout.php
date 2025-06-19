<?php
session_start();
session_destroy(); // Ends the session
header("Location: Homeindex.php"); // Redirect to homepage
exit;
?>