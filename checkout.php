<?php
session_start();
include('includes/db_connect.php');
include('includes/header.php');

// Redirect back to Home Page if the cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);

// Calculate the total amount (including quantity)
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += ($item['price'] * $item['quantity']);
}

// Initialize user data (only fetch if logged in)
$user_data = null;
if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];
    $user_res = $conn->query("SELECT * FROM users WHERE id = '$user_id'");
    $user_data = $user_res->fetch_assoc();
} else {
    $user_id = null;
}
?>

<style>
    /* === Premium Checkout CSS === */
    .checkout-container { 
        max-width: 1200px; 
        margin: 60px auto; 
        padding: 0 20px; 
        font-family: 'Montserrat', sans-serif; 
    }
    
    .luxury-title { 
        color: #d4af37; 
        font-family: 'Playfair Display', serif; 
        font-size: 2.8rem; 
        margin-bottom: 50px; 
        text-align: center; 
        text-transform: uppercase; 
        letter-spacing: 2px; 
        position: relative; 
    }
    
    .luxury-title::after { 
        content: ''; 
        display: block; 
        width: 60px; 
        height: 3px; 
        background: #d4af37; 
        margin: 15px auto 0; 
    }
    
    .checkout-wrapper { 
        display: grid; 
        grid-template-columns: 1.2fr 0.8fr; 
        gap: 40px; 
        align-items: start; 
    }

    /* Form & Summary Boxes */
    .checkout-card { 
        background: #0a0a0a; 
        padding: 40px; 
        border-radius: 20px; 
        border: 1px solid #222; 
        box-shadow: 0 15px 40px rgba(0,0,0,0.5); 
        transition: 0.4s ease-in-out; 
    }
    
    .checkout-card:hover { 
        border-color: #d4af37; 
        box-shadow: 0 15px 40px rgba(212, 175, 55, 0.1); 
        transform: translateY(-5px); 
    }
    
    .checkout-card h3 { 
        color: #d4af37; 
        font-family: 'Playfair Display', serif; 
        font-size: 1.6rem; 
        margin-bottom: 25px; 
        border-bottom: 1px solid #222; 
        padding-bottom: 15px; 
    }

    /* Pill Shape Inputs */
    .form-group { 
        margin-bottom: 20px; 
    }
    
    .form-group label { 
        display: block; 
        margin-bottom: 8px; 
        color: #888; 
        font-size: 0.85rem; 
        padding-left: 15px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    
    .checkout-input { 
        width: 100%; 
        padding: 15px 25px; 
        background: #000; 
        border: 1.5px solid #333; 
        color: #fff; 
        border-radius: 50px; 
        outline: none; 
        transition: all 0.3s ease; 
        font-size: 0.95rem; 
        box-sizing: border-box;
        font-family: 'Montserrat', sans-serif;
    }
    
    .checkout-input::placeholder {
        color: #555;
    }
    
    .checkout-input:focus { 
        border-color: #d4af37; 
        box-shadow: 0 0 15px rgba(212, 175, 55, 0.3);
        background: #0a0a0a;
    }
    
    textarea.checkout-input { 
        border-radius: 20px; 
        height: 100px; 
        resize: none; 
        padding: 20px 25px;
    }

    /* === Payment Selection CSS === */
    .payment-section { 
        margin: 30px 0; 
        border: 1px solid #333; 
        border-radius: 15px; 
        overflow: hidden; 
        background: #111; 
    }
    
    .payment-option { 
        display: block; 
        position: relative; 
        padding: 20px 20px 20px 50px; 
        cursor: pointer; 
        border-bottom: 1px solid #333; 
        transition: all 0.3s ease; 
    }
    
    .payment-option:last-child { 
        border-bottom: none; 
    }
    
    .payment-option:hover { 
        background: #1a1a1a; 
    }
    
    .payment-option input { 
        position: absolute; 
        opacity: 0; 
        cursor: pointer; 
    }
    
    .radio-mark { 
        position: absolute; 
        top: 50%; 
        left: 20px; 
        transform: translateY(-50%); 
        height: 20px; 
        width: 20px; 
        background-color: #222; 
        border-radius: 50%; 
        border: 2px solid #555; 
        transition: all 0.3s ease; 
    }
    
    .payment-option input:checked ~ .radio-mark { 
        border-color: #d4af37; 
        background-color: #d4af37;
        box-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
    }
    
    .radio-mark:after { 
        content: ""; 
        position: absolute; 
        display: none; 
        top: 4px; 
        left: 4px; 
        width: 8px; 
        height: 8px; 
        border-radius: 50%; 
        background: #000; 
    }
    
    .payment-option input:checked ~ .radio-mark:after { 
        display: block; 
    }
    
    .payment-text { 
        color: #fff; 
        font-size: 1rem; 
        font-weight: 600; 
        display: flex; 
        align-items: center; 
        gap: 10px; 
    }
    
    .cod-info { 
        display: none; 
        background: #0a0a0a; 
        padding: 15px 20px 15px 50px; 
        border-bottom: 1px solid #333; 
        color: #aaa; 
        font-size: 0.85rem; 
        line-height: 1.5; 
    }
    
    .payment-option input#cod:checked ~ .cod-info { 
        display: block; 
    }
    
    .card-info { 
        display: none; 
        background: rgba(212, 175, 55, 0.08); 
        padding: 15px 20px 15px 50px; 
        border: 1px solid rgba(212, 175, 55, 0.3); 
        border-top: none; 
        color: #d4af37; 
        font-size: 0.85rem; 
        line-height: 1.5; 
        font-weight: 600; 
    }
    
    .payment-option input#card:checked ~ .card-info { 
        display: block; 
    }
    
    .card-icons { 
        display: flex; 
        gap: 10px; 
        margin-top: 5px; 
    }
    
    .card-icons i { 
        font-size: 1.8rem; 
        color: #888; 
    }
    
    .card-icons i.fa-cc-visa { color: #1A1F71; }
    .card-icons i.fa-cc-mastercard { color: #EB001B; }
    .card-icons i.fa-cc-amex { color: #2e77bc; }

    .summary-item { 
        display: flex; 
        justify-content: space-between; 
        align-items: center;
        padding: 15px 0; 
        border-bottom: 1px solid #1a1a1a; 
        color: #ccc; 
    }
    
    .summary-item span b { 
        color: #d4af37; 
        margin-left: 5px; 
    }
    
    .total-row { 
        display: flex; 
        justify-content: space-between; 
        margin-top: 25px; 
        padding: 20px; 
        background: rgba(212, 175, 55, 0.05); 
        border-radius: 15px; 
        border: 1px solid rgba(212, 175, 55, 0.2); 
    }
    
    .total-row span { 
        font-size: 1.3rem; 
        font-weight: bold; 
        color: #fff; 
    }
    
    .total-amount { 
        color: #d4af37 !important; 
    }

    .btn-place-order { 
        width: 100%; 
        padding: 18px; 
        background: #d4af37; 
        color: #000; 
        border: none; 
        border-radius: 50px; 
        font-weight: 700; 
        font-size: 1.1rem; 
        cursor: pointer; 
        transition: all 0.3s ease; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
    }
    
    .btn-place-order:hover { 
        background: #fff; 
        transform: translateY(-3px); 
        box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);
    }
    
    .btn-place-order:active {
        transform: translateY(-1px);
    }
    
    .back-to-cart { 
        display: inline-block; 
        margin-top: 25px; 
        color: #666; 
        text-decoration: none; 
        font-size: 0.85rem; 
        transition: 0.3s; 
    }
    
    .back-to-cart:hover { 
        color: #d4af37; 
    }

    /* Info messages and links */
    p small {
        color: #666;
        font-size: 0.8rem;
    }

    @media (max-width: 992px) { 
        .checkout-wrapper { 
            grid-template-columns: 1fr; 
        } 
        .luxury-title { 
            font-size: 2rem; 
        } 
    }

    @media (max-width: 768px) {
        .checkout-container {
            padding: 0 15px;
        }

        .checkout-card {
            padding: 25px;
        }

        .luxury-title {
            font-size: 1.8rem;
            margin-bottom: 30px;
        }
    }
</style>

<div class="checkout-container">
    <h2 class="luxury-title">Finalize Your Order</h2>
    
    <div class="checkout-wrapper">
        
        <div class="checkout-card">
            <?php if (!$is_logged_in): ?>
                <!-- REGISTRATION FORM FOR NON-LOGGED-IN USERS -->
                <h3><i class="fa-solid fa-user-plus"></i> Create Your Account</h3>
                <p style="color: #888; margin-bottom: 25px; font-size: 0.9rem;">To complete your purchase, please register or log in to your account.</p>
                
                <form id="registrationForm" method="POST" action="">
                    <input type="hidden" name="action" value="register_and_checkout">
                    
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="full_name" class="checkout-input" placeholder="Enter your full name" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="checkout-input" placeholder="Enter your email" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="checkout-input" placeholder="Create a strong password" required>
                        <small style="color: #666; padding-left: 15px; display: block; margin-top: 5px;">Minimum 6 characters</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" class="checkout-input" placeholder="Re-enter your password" required>
                    </div>
                    
                    <button type="button" onclick="proceedToCheckout()" class="btn-place-order" style="margin-top: 20px;">Continue to Shipping & Payment</button>
                    
                    <p style="text-align: center; margin-top: 15px; color: #888; font-size: 0.9rem;">
                        Already have an account? <a href="login.php" style="color: #d4af37; text-decoration: none;">Sign In Here</a>
                    </p>
                </form>
                
            <?php else: ?>
                <!-- SHIPPING & PAYMENT FORM FOR LOGGED-IN USERS -->
                <h3><i class="fa-solid fa-truck-fast"></i> Shipping & Payment</h3>
                
                <form id="checkoutForm" action="process_order.php" method="POST">
                    
                    <input type="hidden" name="place_order" value="1">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="full_name" class="checkout-input" value="<?php echo htmlspecialchars($user_data['full_name']); ?>" placeholder="Enter your full name" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="checkout-input" value="<?php echo htmlspecialchars($user_data['email']); ?>" placeholder="Enter your email" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" class="checkout-input" placeholder="e.g. 077 123 4567" value="<?php echo htmlspecialchars($user_data['phone'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Shipping Address</label>
                        <textarea name="address" class="checkout-input" placeholder="Street, City, Province" required></textarea>
                    </div>
                    
                    <div class="payment-section">
                        
                        <label class="payment-option">
                            <input type="radio" name="payment_method" id="cod" value="COD" checked>
                            <span class="radio-mark"></span>
                            <span class="payment-text">💳 Cash on Delivery</span>
                            
                            <div class="cod-info">
                                Currently we only support <b>Cash on Delivery</b> islands-wide. Your luxury scents will be dispatched once we confirm your order via phone.
                            </div>
                        </label>

                        <label class="payment-option">
                            <input type="radio" name="payment_method" id="card" value="Card">
                            <span class="radio-mark"></span>
                            <div class="payment-text">
                                💳 Pay via Credit / Debit Card
                            </div>
                            <div class="card-icons">
                                <i class="fa-brands fa-cc-visa"></i>
                                <i class="fa-brands fa-cc-mastercard"></i>
                                <i class="fa-brands fa-cc-amex"></i>
                            </div>
                            <div class="card-info">
                                <i class="fa-solid fa-hammer"></i> Coming Soon - This payment option is currently under development. Please use Cash on Delivery for now.
                            </div>
                        </label>

                    </div>

                    <button type="button" onclick="validateAndSubmit()" class="btn-place-order">Complete Purchase</button>
                </form>
                
                <p style="text-align: center; margin-top: 15px; color: #888; font-size: 0.9rem;">
                    <i class="fa-solid fa-lock"></i> Your session is active. <a href="user_logout.php" style="color: #d4af37; text-decoration: none;">Switch Account</a>
                </p>
            <?php endif; ?>
        </div>

        <!-- ORDER SUMMARY (shown for both logged-in and non-logged-in users) -->
        <div class="checkout-card">
            <h3><i class="fa-solid fa-receipt"></i> Order Summary</h3>
            
            <div class="summary-list">
                <?php foreach ($_SESSION['cart'] as $item) { 
                    $subtotal = $item['price'] * $item['quantity'];
                ?>
                    <div class="summary-item">
                        <span><?php echo htmlspecialchars($item['name']); ?> <b>x<?php echo $item['quantity']; ?></b></span>
                        <span style="color: #fff;">Rs. <?php echo number_format($subtotal, 2); ?></span>
                    </div>
                <?php } ?>
            </div>
            
            <div class="total-row">
                <span>Grand Total</span>
                <span class="total-amount">Rs. <?php echo number_format($total, 2); ?></span>
            </div>

            <div style="text-align: center;">
                <a href="cart.php" class="back-to-cart">← Modify Shopping Cart</a>
            </div>
        </div>

    </div>
</div>

<script>
    /**
     * Handles registration and transitions to checkout form
     */
    function proceedToCheckout() {
        const form = document.getElementById('registrationForm');
        const password = form.password.value;
        const confirmPassword = form.confirm_password.value;
        
        // Validate form
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        // Validate password match
        if (password !== confirmPassword) {
            alert("Passwords do not match. Please try again.");
            return;
        }
        
        // Validate password strength
        if (password.length < 6) {
            alert("Password must be at least 6 characters long.");
            return;
        }
        
        // Submit registration
        const formData = new FormData(form);
        fetch('checkout_register.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload page to show checkout form with logged-in user
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    /**
     * Validates the checkout form and ensures a payment method is selected
     * before submitting the form.
     */
    function validateAndSubmit() {
        var form = document.getElementById('checkoutForm');
        var paymentSelected = document.querySelector('input[name="payment_method"]:checked');
        
        // Check HTML5 validation first
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Ensure a payment method is selected
        if (!paymentSelected) {
            alert("Please select a Payment Method (Cash on Delivery or Card) before completing the purchase.");
            return;
        }

        form.submit();
    }
</script>

<?php include('includes/footer.php'); ?>