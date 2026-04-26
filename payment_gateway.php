<?php
session_start();
include('includes/db_connect.php');
include('includes/header.php');

// Retrieve the Order ID and Amount from the URL (Redirect if not present)
if (!isset($_GET['order_id']) || !isset($_GET['amount'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

$order_id = $_GET['order_id'];
$amount = $_GET['amount'];

// Execute this block when the payment form is submitted (Pay Now clicked)
if (isset($_POST['process_payment'])) {
    
    // Generate a dummy Transaction ID (simulating a bank receipt)
    $txn_id = "TXN" . rand(100000, 999999) . "CEY";

    // Update the order status to 'Paid' in the database
    $sql = "UPDATE orders SET payment_status = 'Paid', transaction_id = '$txn_id' WHERE id = '$order_id'";
    
    if (mysqli_query($conn, $sql)) {
        // Payment successful! Clear the cart and redirect to the Success page
        unset($_SESSION['cart']);
        echo "<script>window.location.href='order_success.php?order_id=$order_id';</script>";
        exit();
    } else {
        echo "<script>alert('Payment Failed! Please try again.');</script>";
    }
}
?>

<style>
    /* === Payment Gateway CSS === */
    body { background: #050505; }
    
    .gateway-container {
        max-width: 500px;
        margin: 60px auto;
        background: #0a0a0a;
        padding: 40px;
        border-radius: 20px;
        border: 1px solid #333;
        box-shadow: 0 15px 40px rgba(0,0,0,0.8);
        font-family: 'Montserrat', sans-serif;
        position: relative;
    }

    .secure-header {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #222;
    }

    .secure-header i { 
        color: #28a745; 
        font-size: 2.5rem; 
        margin-bottom: 10px; 
    }
    
    .secure-header h2 { 
        color: #fff; 
        font-size: 1.5rem; 
        margin: 0; 
    }
    
    .secure-header p { 
        color: #888; 
        font-size: 0.85rem; 
        margin-top: 5px; 
    }

    .payment-amount {
        background: rgba(212, 175, 55, 0.1);
        border: 1px dashed #d4af37;
        padding: 20px;
        text-align: center;
        border-radius: 10px;
        margin-bottom: 30px;
    }
    
    .payment-amount span { 
        display: block; 
        color: #888; 
        font-size: 0.9rem; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
    }
    
    .payment-amount h3 { 
        color: #d4af37; 
        font-size: 2.2rem; 
        margin: 10px 0 0 0; 
    }

    /* Card Details Form */
    .form-group { 
        margin-bottom: 20px; 
        position: relative; 
    }
    
    .form-group label { 
        display: block; 
        color: #aaa; 
        font-size: 0.85rem; 
        margin-bottom: 8px; 
    }
    
    .card-input {
        width: 100%; 
        padding: 15px 20px; 
        background: #111; 
        border: 1px solid #333;
        color: #fff; 
        border-radius: 10px; 
        font-size: 1rem; 
        outline: none; 
        transition: 0.3s;
        letter-spacing: 2px;
        box-sizing: border-box;
    }
    
    .card-input:focus { 
        border-color: #d4af37; 
    }
    
    .input-icon { 
        position: absolute; 
        right: 15px; 
        top: 38px; 
        color: #666; 
        font-size: 1.2rem; 
    }

    .grid-2 { 
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 20px; 
    }

    .btn-pay {
        width: 100%; 
        padding: 18px; 
        background: #d4af37; 
        color: #000; 
        border: none;
        border-radius: 10px; 
        font-weight: bold; 
        font-size: 1.1rem; 
        cursor: pointer;
        transition: 0.3s; 
        text-transform: uppercase; 
        letter-spacing: 2px; 
        margin-top: 10px;
    }
    
    .btn-pay:hover { 
        background: #fff; 
        transform: translateY(-3px); 
        box-shadow: 0 10px 20px rgba(0,0,0,0.3); 
    }

    .cancel-link { 
        display: block; 
        text-align: center; 
        color: #666; 
        margin-top: 20px; 
        text-decoration: none; 
        font-size: 0.85rem; 
        transition: 0.3s; 
    }
    
    .cancel-link:hover { 
        color: #ff4444; 
    }

    /* Loading Overlay Animation */
    .loading-overlay {
        display: none; 
        position: absolute; 
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 100%;
        background: rgba(10, 10, 10, 0.95); 
        border-radius: 20px; 
        z-index: 10;
        justify-content: center; 
        align-items: center; 
        flex-direction: column;
    }
    
    .spinner {
        width: 50px; 
        height: 50px; 
        border: 5px solid #333; 
        border-top-color: #d4af37;
        border-radius: 50%; 
        animation: spin 1s linear infinite; 
        margin-bottom: 20px;
    }
    
    .loading-text { 
        color: #fff; 
        font-size: 1.1rem; 
        letter-spacing: 1px; 
    }
    
    @keyframes spin { 
        100% { transform: rotate(360deg); } 
    }
</style>

<div class="gateway-container">
    
    <div class="loading-overlay" id="loadingScreen">
        <div class="spinner"></div>
        <div class="loading-text">Processing Payment...</div>
        <div style="color: #666; font-size: 0.8rem; margin-top: 10px;">Please do not close this window</div>
    </div>

    <div class="secure-header">
        <i class="fa-solid fa-lock"></i>
        <h2>Secure Checkout</h2>
        <p>Your payment is 256-bit encrypted & secure</p>
    </div>

    <div class="payment-amount">
        <span>Amount to Pay</span>
        <h3>Rs. <?php echo number_format($amount, 2); ?></h3>
    </div>

    <form method="POST" id="paymentForm">
        
        <div class="form-group">
            <label>Cardholder Name</label>
            <input type="text" class="card-input" placeholder="JOHN DOE" style="letter-spacing: 1px;" required>
        </div>

        <div class="form-group">
            <label>Card Number</label>
            <input type="text" class="card-input" placeholder="0000 0000 0000 0000" maxlength="19" required>
            <i class="fa-brands fa-cc-visa input-icon"></i>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label>Expiry Date</label>
                <input type="text" class="card-input" placeholder="MM/YY" maxlength="5" required>
            </div>
            <div class="form-group">
                <label>CVV / CVC</label>
                <input type="password" class="card-input" placeholder="•••" maxlength="3" required>
                <i class="fa-solid fa-credit-card input-icon" style="font-size: 1rem;"></i>
            </div>
        </div>

        <button type="button" class="btn-pay" onclick="simulatePayment()">
            <i class="fa-solid fa-shield-halved"></i> Pay Now
        </button>
        
        <input type="hidden" name="process_payment" value="1">
    </form>

    <a href="checkout.php" class="cancel-link">Cancel and return to shop</a>
</div>

<script>
    /**
     * Simulates a payment processing delay before submitting the form.
     * Shows a loading screen for 2 seconds.
     */
    function simulatePayment() {
        var form = document.getElementById('paymentForm');
        
        // HTML5 Validation (Check if required fields are filled)
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Display the Loading Screen
        document.getElementById('loadingScreen').style.display = 'flex';

        // Wait for 2 seconds (2000 milliseconds) then submit the form
        setTimeout(function() {
            form.submit();
        }, 2000);
    }
</script>

<?php include('includes/footer.php'); ?>