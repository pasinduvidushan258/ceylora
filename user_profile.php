<?php
session_start();
include('includes/db_connect.php');

// Redirect the user to the login page if they are not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_msg = "";
$error_msg = "";

// 1. Fetch existing user details from the database
// Note: Ensure your 'users' table contains 'phone' and 'secondary_email' columns
$user_query = $conn->query("SELECT * FROM users WHERE id = '$user_id'");
$user = $user_query->fetch_assoc();

// 2. Handle Profile Update Request
if (isset($_POST['update_profile'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $secondary_email = mysqli_real_escape_string($conn, $_POST['secondary_email']);

    // === Email & Phone Format Validation ===
    if (!empty($secondary_email) && !filter_var($secondary_email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Please enter a valid format for secondary email.";
    } elseif (!empty($phone) && !preg_match('/^[0-9]{10}+$/', $phone)) {
        $error_msg = "Please enter a valid 10-digit phone number.";
    } else {
        // Proceed with the update if validation passes
        $update_sql = "UPDATE users SET 
                        full_name = '$full_name', 
                        phone = '$phone', 
                        secondary_email = '$secondary_email' 
                        WHERE id = '$user_id'";
        
        if ($conn->query($update_sql)) {
            $_SESSION['user_name'] = $full_name; // Update the session variable as well
            $success_msg = "Profile updated successfully!";
            header("Refresh:2"); 
        } else {
            $error_msg = "Error updating profile.";
        }
    }
}

include('includes/header.php');
?>

<style>
    /* === Premium User Profile UI === */
    .profile-section {
        max-width: 800px;
        margin: 100px auto;
        padding: 0 20px;
        font-family: 'Montserrat', sans-serif;
        color: #fff;
    }

    .profile-card {
        background: #0a0a0a;
        padding: 40px;
        border-radius: 20px;
        border: 1px solid #222;
        box-shadow: 0 15px 40px rgba(0,0,0,0.5);
    }

    .profile-card h2 {
        color: #d4af37;
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        margin-bottom: 30px;
        text-align: center;
        text-transform: uppercase;
    }

    .form-group { 
        margin-bottom: 25px; 
    }
    
    .form-group label { 
        display: block; 
        margin-bottom: 10px; 
        color: #888; 
        font-size: 0.9rem; 
        padding-left: 15px; 
    }
    
    .profile-input {
        width: 100%;
        padding: 15px 25px;
        background: #000;
        border: 1px solid #333;
        color: #fff;
        border-radius: 50px;
        outline: none;
        transition: 0.3s;
        box-sizing: border-box;
    }

    .profile-input:focus { 
        border-color: #d4af37; 
        box-shadow: 0 0 10px rgba(212, 175, 55, 0.2); 
    }

    /* Styling for non-editable fields */
    .readonly-input {
        background: #111;
        color: #555;
        border-color: #222;
        cursor: not-allowed;
    }

    .btn-update {
        width: 100%;
        padding: 15px;
        background: #d4af37;
        color: #000;
        border: none;
        border-radius: 50px;
        font-weight: bold;
        text-transform: uppercase;
        cursor: pointer;
        transition: 0.3s;
        margin-top: 10px;
        letter-spacing: 1px;
    }

    .btn-update:hover { 
        background: #fff; 
        transform: translateY(-3px); 
    }

    .alert { 
        padding: 15px; 
        border-radius: 10px; 
        margin-bottom: 20px; 
        text-align: center; 
        font-size: 0.9rem; 
    }
    
    .alert-success { 
        background: rgba(46, 204, 113, 0.1); 
        color: #2ecc71; 
        border: 1px solid #2ecc71; 
    }
    
    .alert-error { 
        background: rgba(231, 76, 60, 0.1); 
        color: #e74c3c; 
        border: 1px solid #e74c3c; 
    }

    .change-pw-link {
        display: block;
        text-align: center;
        margin-top: 25px;
        color: #666;
        text-decoration: none;
        font-size: 0.85rem;
        transition: 0.3s;
    }
    
    .change-pw-link:hover { 
        color: #d4af37; 
    }
</style>

<div class="profile-section">
    <div class="profile-card">
        <h2>Account Settings</h2>

        <?php if($success_msg): ?>
            <div class='alert alert-success'><?php echo $success_msg; ?></div>
        <?php endif; ?>
        
        <?php if($error_msg): ?>
            <div class='alert alert-error'><?php echo $error_msg; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" class="profile-input" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
            </div>

            <div class="form-group">
                <label>Primary Email (Cannot be changed)</label>
                <input type="email" class="profile-input readonly-input" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
            </div>

            <div class="form-group">
                <label>Secondary Email (Optional)</label>
                <input type="email" name="secondary_email" class="profile-input" value="<?php echo isset($user['secondary_email']) ? htmlspecialchars($user['secondary_email']) : ''; ?>" placeholder="Add an alternative email">
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" class="profile-input" value="<?php echo isset($user['phone']) ? htmlspecialchars($user['phone']) : ''; ?>" placeholder="e.g. 0771234567" required>
            </div>

            <div class="form-group">
                <label>Member Since</label>
                <input type="text" class="profile-input readonly-input" value="<?php echo date('F d, Y', strtotime($user['created_at'])); ?>" readonly>
            </div>

            <button type="submit" name="update_profile" class="btn-update">Save Changes</button>
        </form>

        <a href="change_password.php" class="change-pw-link">
            <i class="fa-solid fa-lock"></i> Want to change your password?
        </a>
    </div>
</div>

<?php include('includes/footer.php'); ?>