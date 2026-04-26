<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) { 
    header("Location: panel_adma9xKpL2_admin_login.php"); 
    exit(); 
}

include('includes/db_connect.php');
include('includes/header.php');

$message = "";

// Handle password change request
if (isset($_POST['change_pass'])) {
    $current_pass = $_POST['current_pass'];
    $new_pass     = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    // The username 'admin' here must exactly match the one in your database
    $check = $conn->query("SELECT * FROM admin WHERE username='admin' AND password='$current_pass'");
    
    if ($check->num_rows == 1) {
        if ($new_pass === $confirm_pass) {
            $update = $conn->query("UPDATE admin SET password='$new_pass' WHERE username='admin'");
            $message = "<div class='alert success'><i class='fa-solid fa-circle-check'></i> Password changed successfully!</div>";
        } else {
            $message = "<div class='alert error'><i class='fa-solid fa-circle-xmark'></i> New passwords do not match!</div>";
        }
    } else {
        $message = "<div class='alert error'><i class='fa-solid fa-circle-exclamation'></i> Current password is incorrect!</div>";
    }
}
?>

<style>
    /* === Premium Change Password CSS === */
    .change-pass-container {
        max-width: 550px;
        margin: 80px auto;
        padding: 20px;
        font-family: 'Montserrat', sans-serif;
    }

    .luxury-title {
        color: #d4af37;
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        margin-bottom: 40px;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 2px;
        position: relative;
    }

    .luxury-title::after {
        content: ''; 
        display: block; 
        width: 50px; 
        height: 2px; 
        background: #d4af37; 
        margin: 15px auto 0;
    }

    .custom-admin-box {
        background: #0a0a0a;
        padding: 50px 40px;
        border: 1px solid #222;
        border-radius: 20px; /* Rounded corners */
        box-shadow: 0 15px 40px rgba(0,0,0,0.8);
        transition: 0.4s ease-in-out;
    }

    /* Hover effect to lift the box */
    .custom-admin-box:hover {
        transform: translateY(-8px);
        border-color: #d4af37;
        box-shadow: 0 20px 50px rgba(212, 175, 55, 0.15);
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        margin-bottom: 10px;
        color: #aaa;
        font-size: 0.85rem;
        font-weight: 500;
        padding-left: 15px;
    }

    /* Pill-shape Inputs */
    .custom-admin-box input[type="password"] {
        width: 100%;
        padding: 15px 25px;
        background: #000;
        border: 1px solid #333;
        color: white;
        border-radius: 50px; /* Pill Shape */
        font-size: 1rem;
        outline: none;
        transition: 0.3s;
        box-sizing: border-box;
    }

    .custom-admin-box input[type="password"]:focus {
        border-color: #d4af37;
        box-shadow: 0 0 12px rgba(212, 175, 55, 0.2);
    }

    /* Pill-shape Submit Button */
    .submit-btn {
        width: 100%;
        background: #d4af37;
        color: black;
        border: none;
        padding: 16px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        border-radius: 50px; /* Pill Shape */
        transition: 0.3s;
        margin-top: 15px;
    }

    .submit-btn:hover {
        background: #fff;
        transform: scale(1.02);
        box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    }

    /* Alert Styling */
    .alert {
        padding: 15px;
        margin-bottom: 30px;
        text-align: center;
        border-radius: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 0.9rem;
    }

    .success { 
        background: rgba(0, 255, 0, 0.1); 
        color: #00ff00; 
        border: 1px solid #00ff00; 
    }

    .error { 
        background: rgba(255, 68, 68, 0.1); 
        color: #ff4444; 
        border: 1px solid #ff4444; 
    }

    .back-dashboard {
        display: inline-block;
        margin-top: 35px;
        color: #666;
        text-decoration: none;
        font-size: 0.85rem;
        transition: 0.3s;
    }

    .back-dashboard:hover { 
        color: #d4af37; 
    }
</style>

<div class="change-pass-container">
    <h2 class="luxury-title">Security Settings</h2>
    
    <?php echo $message; ?>
    
    <div class="custom-admin-box">
        <form method="POST">
            <div class="form-group">
                <label><i class="fa-solid fa-key" style="margin-right: 5px;"></i> Current Password</label>
                <input type="password" name="current_pass" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-lock-open" style="margin-right: 5px;"></i> New Password</label>
                <input type="password" name="new_pass" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-shield-check" style="margin-right: 5px;"></i> Confirm New Password</label>
                <input type="password" name="confirm_pass" placeholder="••••••••" required>
            </div>

            <button type="submit" name="change_pass" class="submit-btn">Update Admin Access</button>
        </form>
    </div>

    <div style="text-align: center;">
        <a href="admin_orders.php" class="back-dashboard">← Return to Admin Dashboard</a>
    </div>
</div>

<?php include('includes/footer.php'); ?>