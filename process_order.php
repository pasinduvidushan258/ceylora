<?php
session_start();
include('includes/db_connect.php');

if (isset($_POST['place_order'])) {
    
    // 1. Sanitize customer data for security
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    // --- Capture user_id sent from the Checkout form ---
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']); 
    
    // Capture the selected Payment Method (COD or Card)
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']); 
    
    // 2. Calculate the Grand Total accurately (including Quantity)
    $total = 0;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += ($item['price'] * $item['quantity']); 
        }
    } else {
        header("Location: shop.php");
        exit();
    }

    // 3. Determine Payment Status
    // If COD, set as "Pending" until delivery. If Card, set as "Unpaid" until payment is complete.
    $payment_status = ($payment_method == 'COD') ? 'Pending' : 'Unpaid';

    // 4. Insert the main order into the 'orders' table (along with user_id)
    $sql = "INSERT INTO orders (user_id, full_name, email, address, phone, total_amount, payment_method, payment_status) 
            VALUES ('$user_id', '$full_name', '$email', '$address', '$phone', '$total', '$payment_method', '$payment_status')";

    if ($conn->query($sql) === TRUE) {
        
        // Retrieve the newly generated Order ID
        $order_id = $conn->insert_id; 

        // 5. Insert every item from the Cart into the 'order_items' table
        foreach ($_SESSION['cart'] as $item) {
            $p_name = mysqli_real_escape_string($conn, $item['name']);
            $p_price = $item['price'];
            $p_qty = $item['quantity']; 
            
            $item_sql = "INSERT INTO order_items (order_id, product_name, quantity, price) 
                         VALUES ('$order_id', '$p_name', '$p_qty', '$p_price')";
            $conn->query($item_sql);
        }

        // 6. Redirect based on the chosen Payment Method
        if ($payment_method == 'COD') {
            
            // If Cash on Delivery: Order is confirmed. Clear the cart and redirect to the Success page.
            unset($_SESSION['cart']);
            header("Location: order_success.php?order_id=$order_id");
            exit();

        } else if ($payment_method == 'Card') {
            
            // If Card Payment: Redirect to the Payment Gateway with the Order ID and Amount.
            header("Location: payment_gateway.php?order_id=$order_id&amount=$total");
            exit();

        }

    } else {
        echo "Error Processing Order: " . $conn->error;
    }
} else {
    header("Location: index.php");
    exit();
}
?>