<?php
session_start();
include('includes/db_connect.php');

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- 1. Sanitize and validate input ---
    $full_name = trim(mysqli_real_escape_string($conn, $_POST['full_name']));
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($full_name) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit();
    }
    
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters.']);
        exit();
    }
    
    if ($password !== $confirm_password) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
        exit();
    }
    
    // --- 2. Check if email already exists ---
    $check_query = "SELECT id FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($result) > 0) {
        echo json_encode(['success' => false, 'message' => 'This email is already registered. Please use another email or sign in.']);
        exit();
    }
    
    // --- 3. Hash password and insert new user ---
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $insert_query = "INSERT INTO users (full_name, email, password, created_at) 
                     VALUES ('$full_name', '$email', '$hashed_password', NOW())";
    
    if (mysqli_query($conn, $insert_query)) {
        
        // --- 4. Get the newly created user ID ---
        $new_user_id = mysqli_insert_id($conn);
        
        // --- 5. Set session variables to keep user logged in ---
        $_SESSION['user_id'] = $new_user_id;
        $_SESSION['user_name'] = $full_name;
        $_SESSION['user_email'] = $email;
        
        // --- 6. Return success response ---
        echo json_encode([
            'success' => true,
            'message' => 'Account created successfully! Proceeding to checkout...',
            'user_id' => $new_user_id
        ]);
        
    } else {
        echo json_encode(['success' => false, 'message' => 'Error creating account: ' . mysqli_error($conn)]);
    }
    
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

mysqli_close($conn);
?>
