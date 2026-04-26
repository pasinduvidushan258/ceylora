<?php
/**
 * Logout Process
 * Clears all user session data and redirects to the home page.
 */

// 1. Initialize the session to access session data
session_start(); 

// 2. Remove all session variables
session_unset(); 

// 3. Destroy the entire session from the server
session_destroy(); 

// 4. Redirect the user back to the Home page after logging out
header("Location: index.php"); 
exit();
?>