<?php
session_start();

// Destroy all session data to log the user out
session_destroy(); 

// Redirect the user back to the home page after logout
header("Location: index.php"); 
exit();
?>