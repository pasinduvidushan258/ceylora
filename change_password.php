<?php
session_start();

// If the user is not logged in, redirect directly to the login page (for security)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db_connect.php'; 

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = $_SESSION['user_id'];

    if ($new_password !== $confirm_password) {
        $error = "New passwords do not match!";
    } else {
        $query = "SELECT password FROM users WHERE id='$user_id'";
        $result = mysqli_query($conn, $query);
        $user = mysqli_fetch_assoc($result);

        // Verifying the hashed password
        if (password_verify($current_password, $user['password'])) {
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE users SET password='$new_hashed_password' WHERE id='$user_id'";
            
            if (mysqli_query($conn, $update_query)) {
                $success = "Password changed successfully!";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        } else {
            $error = "Current password is incorrect!";
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<style>
    /* === Ceylora Premium Dark UI for Change Password === */
    .pw-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        padding: 60px 20px;
        font-family: 'Montserrat', sans-serif;
    }

    .pw-card {
        background: #0a0a0a; /* Premium Dark Background */
        width: 100%;
        max-width: 500px;
        padding: 45px;
        border-radius: 20px;
        border: 1px solid #222;
        box-shadow: 0 20px 50px rgba(0,0,0,0.6);
        transition: 0.3s;
    }

    .pw-card:hover {
        border-color: #d4af37;
        box-shadow: 0 20px 50px rgba(212, 175, 55, 0.1);
    }

    .pw-card h2 {
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        margin-bottom: 10px;
        color: #d4af37; /* Gold Title */
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .pw-card p {
        text-align: center;
        color: #888;
        margin-bottom: 35px;
        font-size: 0.9rem;
        line-height: 1.6;
    }

    .input-group { 
        margin-bottom: 25px; 
    }
    
    .input-group label { 
        display: block; 
        font-size: 0.85rem; 
        color: #aaa; 
        margin-bottom: 10px; 
        font-weight: 500;
        padding-left: 15px;
    }

    .input-group input { 
        width: 100%; 
        padding: 15px 25px; 
        border: 1px solid #333 !important; 
        border-radius: 50px; /* Pill Shape */
        font-size: 0.95rem; 
        outline: none; 
        transition: 0.3s;
        background-color: #000 !important;
        color: #fff !important;
    }

    .input-group input:focus { 
        border-color: #d4af37 !important; 
        box-shadow: 0 0 15px rgba(212, 175, 55, 0.2); 
    }

    /* Gold Update Button */
    .btn-update {
        width: 100%; 
        padding: 16px; 
        background: #d4af37; 
        color: #000;
        border: none; 
        border-radius: 50px; 
        font-size: 1rem; 
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer; 
        transition: 0.3s; 
        margin-top: 15px;
    }

    .btn-update:hover { 
        background: #fff; 
        transform: translateY(-3px); 
        box-shadow: 0 10px 20px rgba(0,0,0,0.4);
    }

    /* Custom Alerts */
    .alert { 
        padding: 15px; 
        border-radius: 12px; 
        margin-bottom: 25px; 
        text-align: center; 
        font-size: 0.9rem; 
    }
    
    .error { 
        background: rgba(231, 76, 60, 0.1); 
        color: #e74c3c; 
        border: 1px solid rgba(231, 76, 60, 0.3); 
    }
    
    .success { 
        background: rgba(46, 204, 113, 0.1); 
        color: #2ecc71; 
        border: 1px solid rgba(46, 204, 113, 0.3); 
    }

    .back-link {
        display: block;
        text-align: center;
        margin-top: 25px;
        color: #666;
        text-decoration: none;
        font-size: 0.85rem;
        transition: 0.3s;
    }
    
    .back-link:hover { 
        color: #d4af37; 
    }
</style>

<div class="pw-wrapper">
    <div class="pw-card">
        <h2>Security</h2>
        <p>Update your password regularly to keep your luxury collection safe.</p>

        <?php if($error): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="input-group">
                <label>Current Password</label>
                <input type="password" name="current_password" required>
            </div>
            
            <div class="input-group">
                <label>New Password</label>
                <input type="password" name="new_password" required>
            </div>

            <div class="input-group">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn-update">Save New Password</button>
        </form>

        <a href="user_profile.php" class="back-link">← Back to Profile Settings</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>