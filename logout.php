<?php
session_start();

// Saari session data delete karo
$_SESSION = [];

// Session destroy
session_destroy();

// Redirect to home
header("Location: index.php");
exit();
?>
