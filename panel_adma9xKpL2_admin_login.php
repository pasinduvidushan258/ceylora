<?php
session_start();
include('includes/db_connect.php');

// If an admin is already logged in, redirect directly to the dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_orders.php");
    exit();
}

// Handle login form submission
if (isset($_POST['login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username='$user' AND password='$pass'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_orders.php");
        exit();
    } else {
        $error = "Invalid Username or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | CEYLORA</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* === Premium Login CSS === */
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body {
            background: #050505;
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #fff;
        }

        /* The Login Card */
        .login-box {
            background: #0a0a0a;
            padding: 50px 40px;
            border-radius: 20px; /* Rounded corners */
            border: 1px solid #222;
            box-shadow: 0 15px 40px rgba(0,0,0,0.8);
            width: 100%;
            max-width: 420px;
            text-align: center;
            transition: 0.4s ease-in-out;
        }

        /* Card Hover Effect */
        .login-box:hover {
            border-color: #d4af37;
            box-shadow: 0 15px 40px rgba(212, 175, 55, 0.15); /* Golden Glow */
            transform: translateY(-5px); /* Lift Up */
        }

        .login-title {
            font-family: 'Playfair Display', serif;
            color: #d4af37;
            font-size: 2.2rem;
            margin-bottom: 5px;
            letter-spacing: 2px;
        }

        .login-subtitle {
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        /* Pill Shape Inputs */
        .form-group { 
            margin-bottom: 20px; 
            position: relative; 
        }
        
        .form-group i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }
        
        .login-input {
            width: 100%;
            padding: 15px 20px 15px 45px; /* Space for icon */
            background: #000;
            border: 1px solid #333;
            color: #fff;
            border-radius: 50px; /* Pill Shape */
            font-family: 'Montserrat', sans-serif;
            font-size: 0.95rem;
            outline: none;
            transition: 0.3s;
        }
        
        .login-input:focus {
            border-color: #d4af37;
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.2);
        }
        
        .login-input:focus + i { 
            color: #d4af37;  /* Icon turns gold on focus */
        } 

        /* Pill Shape Button */
        .login-btn {
            width: 100%;
            padding: 15px;
            background: #d4af37;
            color: #000;
            border: none;
            border-radius: 50px; /* Pill Shape */
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
            margin-top: 10px;
        }
        
        .login-btn:hover {
            background: #fff;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2);
        }

        /* Error Message Styling */
        .error-alert {
            background: rgba(255, 68, 68, 0.1);
            color: #ff4444;
            border: 1px solid #ff4444;
            padding: 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .back-link {
            display: inline-block;
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
</head>
<body>

    <div class="login-box">
        <h2 class="login-title">CEYLORA</h2>
        <p class="login-subtitle">Admin Portal Access</p>
        
        <?php if(isset($error)): ?>
            <div class="error-alert">
                <i class="fa-solid fa-triangle-exclamation"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <i class="fa-solid fa-user"></i>
                <input type="text" name="username" class="login-input" placeholder="Username" required>
            </div>
            
            <div class="form-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" class="login-input" placeholder="Password" required>
            </div>
            
            <button type="submit" name="login" class="login-btn">Secure Login</button>
        </form>
        
        <a href="index.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Return to Store</a>
    </div>

</body>
</html>