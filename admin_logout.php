<?php
// 1. Initialize the session to access the current session data
session_start();

// 2. Destroy all active session data (Logs the admin out)
session_destroy();

// 3. Redirect the user back to the secure login page
header("Location: panel_adma9xKpL2_admin_login.php");
?>