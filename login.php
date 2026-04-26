<?php
session_start();

// Make sure your database connection file is linked correctly here!
include 'includes/db_connect.php'; 

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Handle User Registration (SIGN UP)
    if (isset($_POST['action']) && $_POST['action'] == 'register') {
        $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];

        // Check if the email is already registered in the database
        $check_query = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($result) > 0) {
            $error = "This email is already registered! Please Sign In.";
        } else {
            // Hash the password for security before saving it
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_query = "INSERT INTO users (full_name, email, password) VALUES ('$full_name', '$email', '$hashed_password')";
            
            if (mysqli_query($conn, $insert_query)) {
                $success = "Account created successfully! You can now Sign In.";
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
    } 
    
    // 2. Handle User Login (SIGN IN)
    elseif (isset($_POST['action']) && $_POST['action'] == 'login') {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];

        $query = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            // Verify if the entered password matches the hashed password in the DB
            if (password_verify($password, $user['password'])) {
                // Set session variables upon successful login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_email'] = $user['email'];
                
                header("Location: index.php"); // Redirect to the Home page after login
                exit();
            } else {
                $error = "Incorrect password! Please try again.";
            }
        } else {
            $error = "No account found with this email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login or Sign Up | CEYLORA</title>
    
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    
    <a href="index.php" class="back-home"><i class="fa-solid fa-arrow-left"></i> Back to Home</a>

    <div class="auth-wrapper">
        <div class="auth-container">
            
            <div class="auth-form-box">
                <div class="auth-logo">
                    <h2 style="font-family: 'Playfair Display', serif; color: #111;">
                        CEYLORA <span style="color: #d4af37;">Signature</span>
                    </h2>
                </div>

                <?php if($error): ?>
                    <div class="alert error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if($success): ?>
                    <div class="alert success"><?php echo $success; ?></div>
                <?php endif; ?>

                <div id="login-form" class="form-section">
                    <h3>Welcome Back!</h3>
                    <p>Please enter your details to sign in.</p>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="login">
                        
                        <div class="input-group">
                            <label>Email Address</label>
                            <input type="email" name="email" placeholder="example@gmail.com" required>
                        </div>
                        
                        <div class="input-group">
                            <label>Password</label>
                            <input type="password" name="password" placeholder="••••••••" required>
                        </div>
                        
                        <button type="submit" class="btn-auth">Sign In</button>
                    </form>
                    
                    <p class="switch-form">Don't have an account? <span onclick="toggleForm('register')">Sign Up here</span></p>
                </div>

                <div id="register-form" class="form-section" style="display: none;">
                    <h3>Create an Account</h3>
                    <p>Sign up to get exclusive offers and luxury scents.</p>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="register">
                        
                        <div class="input-group">
                            <label>Full Name</label>
                            <input type="text" name="full_name" placeholder="Amelie Laurent" required>
                        </div>
                        
                        <div class="input-group">
                            <label>Email Address</label>
                            <input type="email" name="email" placeholder="amelie@gmail.com" required>
                        </div>
                        
                        <div class="input-group">
                            <label>Password</label>
                            <input type="password" name="password" placeholder="••••••••" required>
                        </div>
                        
                        <button type="submit" class="btn-auth">Sign Up</button>
                    </form>
                    
                    <p class="switch-form">Already have an account? <span onclick="toggleForm('login')">Sign In</span></p>
                </div>
            </div>

            <div class="auth-image-box">
                <img src="assets/images/user_login.png" alt="Ceylora Perfume Setup">
            </div>

        </div>
    </div>

    <script>
        /**
         * Toggles the visibility between the Login and Registration forms
         * @param {string} formType - The target form to display ('register' or 'login')
         */
        function toggleForm(formType) {
            if (formType === 'register') {
                document.getElementById('login-form').style.display = 'none';
                document.getElementById('register-form').style.display = 'block';
            } else {
                document.getElementById('register-form').style.display = 'none';
                document.getElementById('login-form').style.display = 'block';
            }
        }
    </script>
</body>
</html>