<?php
session_start();
include('includes/header.php');

// Retrieve the Order ID from the URL (to display it if it exists)
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;
?>

<style>
    /* === Premium Success Page CSS === */
    .success-container {
        min-height: 80vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 60px 20px;
        background: #050505; /* Deep Black Background */
    }

    .success-card {
        background: #0a0a0a;
        max-width: 700px;
        width: 100%;
        padding: 60px 40px;
        border-radius: 25px; /* Rounded corners */
        border: 1px solid #222;
        text-align: center;
        box-shadow: 0 20px 50px rgba(0,0,0,0.7);
        transition: 0.4s ease-in-out;
    }

    /* Hover effect to lift the card */
    .success-card:hover {
        transform: translateY(-10px);
        border-color: #d4af37;
        box-shadow: 0 20px 50px rgba(212, 175, 55, 0.1);
    }

    /* Success Icon Animation */
    .success-icon {
        font-size: 100px;
        color: #d4af37;
        margin-bottom: 30px;
        animation: scaleIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    @keyframes scaleIn {
        from { transform: scale(0); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .success-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.8rem;
        color: #d4af37;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 3px;
    }

    /* Stylish box for displaying the Order ID */
    .order-id-box {
        display: inline-block;
        background: rgba(212, 175, 55, 0.1);
        border: 1px dashed #d4af37;
        padding: 10px 20px;
        border-radius: 10px;
        color: #fff;
        font-family: 'Montserrat', sans-serif;
        font-size: 1.1rem;
        margin-bottom: 25px;
        letter-spacing: 1px;
    }
    
    .order-id-box span { 
        color: #d4af37; 
        font-weight: bold; 
    }

    .success-message {
        font-size: 1.1rem;
        color: #aaa;
        max-width: 500px;
        margin: 0 auto 40px auto;
        line-height: 1.8;
    }

    /* Pill Shape Continue Button */
    .btn-continue {
        display: inline-block;
        padding: 18px 50px;
        background: #d4af37;
        color: #000;
        text-decoration: none;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 2px;
        border-radius: 50px; /* Pill Shape */
        transition: 0.3s ease;
        box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2);
    }

    .btn-continue:hover {
        background: #fff;
        transform: scale(1.05);
        box-shadow: 0 15px 30px rgba(255,255,255,0.1);
    }

    .order-hint {
        margin-top: 30px;
        font-size: 0.85rem;
        color: #555;
        border-top: 1px solid #1a1a1a;
        padding-top: 20px;
    }
</style>

<div class="success-container">
    <div class="success-card">
        
        <div class="success-icon">
            <i class="fa-solid fa-circle-check"></i>
        </div>

        <h1 class="success-title">Order Confirmed</h1>
        
        <?php if($order_id): ?>
            <div class="order-id-box">
                Order Reference: <span>#<?php echo str_pad($order_id, 4, '0', STR_PAD_LEFT); ?></span>
            </div>
        <?php endif; ?>

        <p class="success-message">
            Thank you for choosing <b>CEYLORA Signature</b>. Your luxury fragrance journey has begun! <br><br>
            Our team will contact you shortly via phone to verify your shipping details.
        </p>

        <div class="action-box">
            <a href="index.php" class="btn-continue">
                <i class="fa-solid fa-bag-shopping" style="margin-right: 10px;"></i> CONTINUE SHOPPING
            </a>
        </div>

        <div class="order-hint">
            <p><i class="fa-solid fa-clock"></i> Most orders are delivered within 3-5 working days island-wide.</p>
        </div>
        
    </div>
</div>

<?php include('includes/footer.php'); ?>