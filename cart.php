<?php
session_start();
include('includes/db_connect.php');

// --- 1. Logic for adding an item to the cart ---
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the product is already in the basket
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += 1; // Increase quantity by 1 if already exists
            $found = true;
            break;
        }
    }

    // Add as a new item if it's not in the basket
    if (!$found) {
        $_SESSION['cart'][] = array(
            'id'       => $product_id,
            'name'     => $product_name,
            'price'    => $product_price,
            'quantity' => 1 // Initial quantity is 1
        );
    }
    
    header("Location: cart.php");
    exit();
}

// --- 2. Logic for the + and - buttons ---
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];

    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['id'] == $id) {
            if ($action == 'increase') {
                $item['quantity'] += 1;
            } elseif ($action == 'decrease') {
                $item['quantity'] -= 1;
                // Remove the item from the basket if quantity drops to 0
                if ($item['quantity'] <= 0) {
                    unset($_SESSION['cart'][$key]);
                }
            } elseif ($action == 'remove') {
                unset($_SESSION['cart'][$key]); // Completely remove when the delete icon is clicked
            }
            break;
        }
    }
    
    // Re-index the array to prevent gaps
    $_SESSION['cart'] = array_values($_SESSION['cart']); 
    header("Location: cart.php");
    exit();
}

// --- 3. Logic for clearing the entire cart ---
if (isset($_GET['clear'])) {
    unset($_SESSION['cart']);
    header("Location: cart.php");
    exit();
}

include('includes/header.php');
?>

<style>
    /* --- Premium Cart CSS --- */
    .cart-container {
        max-width: 1100px;
        margin: 60px auto;
        padding: 40px;
        background: #0a0a0a;
        border-radius: 15px;
        border: 1px solid #222;
        color: #fff;
    }
    
    .cart-title {
        color: #d4af37;
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        margin-bottom: 30px;
        text-align: center;
        border-bottom: 1px solid #222;
        padding-bottom: 20px;
    }

    .cart-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }
    
    .cart-table th {
        color: #888;
        text-transform: uppercase;
        font-size: 0.9rem;
        padding: 15px 10px;
        border-bottom: 1px solid #333;
        text-align: left;
    }
    
    .cart-table td {
        padding: 20px 10px;
        border-bottom: 1px solid #222;
        font-size: 1.1rem;
        vertical-align: middle;
    }

    /* --- Quantity Box Design --- */
    .qty-box {
        display: inline-flex;
        align-items: center;
        background: #111;
        border: 1px solid #333;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .qty-btn {
        background: transparent;
        color: #ccc;
        padding: 8px 15px;
        text-decoration: none;
        font-size: 1.2rem;
        transition: 0.3s;
    }
    
    .qty-btn:hover { 
        background: #d4af37; 
        color: #000; 
        font-weight: bold; 
    }
    
    .qty-val {
        padding: 8px 20px;
        color: #fff;
        font-weight: bold;
        border-left: 1px solid #333;
        border-right: 1px solid #333;
    }

    /* --- Trash / Delete Button --- */
    .btn-remove {
        color: #ff4444;
        text-decoration: none;
        margin-left: 20px;
        font-size: 1.2rem;
        transition: 0.3s;
        display: inline-block;
    }
    
    .btn-remove:hover { 
        color: #fff; 
        transform: scale(1.1); 
    }

    .total-section {
        background: #111;
        padding: 30px;
        border-radius: 10px;
        border: 1px solid #d4af37;
        text-align: right;
        margin-bottom: 30px;
    }
    
    .total-section p { 
        color: #888; 
        margin-bottom: 5px; 
        text-transform: uppercase; 
        font-size: 0.9rem; 
    }
    
    .total-section h3 { 
        color: #d4af37; 
        font-size: 2rem; 
        font-family: 'Playfair Display', serif; 
    }

    .cart-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .btn-continue {
        color: #ccc;
        text-decoration: none;
        padding: 12px 25px;
        border: 1px solid #444;
        border-radius: 30px;
        transition: 0.3s;
    }
    
    .btn-continue:hover { 
        background: #333; 
        color: #fff; 
    }

    .btn-checkout {
        background: #d4af37;
        color: #000;
        text-decoration: none;
        padding: 15px 40px;
        border-radius: 30px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.3s;
    }
    
    .btn-checkout:hover { 
        background: #fff; 
        transform: translateY(-2px); 
        box-shadow: 0 5px 15px rgba(255,255,255,0.2); 
    }

    .clear-link {
        color: #ff4444;
        text-decoration: none;
        font-size: 0.9rem;
        transition: 0.3s;
    }
    
    .clear-link:hover { 
        text-decoration: underline; 
    }
</style>

<div class="cart-container">
    <h2 class="cart-title">Your Shopping Cart</h2>
    
    <table class="cart-table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th style="text-align: center;">Quantity</th>
                <th style="text-align: right;">Total Price</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total = 0;
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    // Multiply by quantity to get the subtotal
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
            ?>
                <tr>
                    <td>
                        <?php echo $item['name']; ?><br>
                        <span style="font-size: 0.85rem; color: #888;">Unit Price: Rs. <?php echo number_format($item['price'], 2); ?></span>
                    </td>
                    
                    <td style="text-align: center;">
                        <div class="qty-box">
                            <a href="cart.php?action=decrease&id=<?php echo $item['id']; ?>" class="qty-btn">-</a>
                            <span class="qty-val"><?php echo $item['quantity']; ?></span>
                            <a href="cart.php?action=increase&id=<?php echo $item['id']; ?>" class="qty-btn">+</a>
                        </div>
                    </td>
                    
                    <td style="text-align: right;">
                        <span style="color: #d4af37; font-weight: bold;">Rs. <?php echo number_format($subtotal, 2); ?></span>
                        <a href="cart.php?action=remove&id=<?php echo $item['id']; ?>" class="btn-remove" title="Remove Item"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='3' style='text-align:center; padding: 50px; color: #666; font-size: 1.2rem;'>Your luxury cart is empty. Time to find your signature scent!</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php if ($total > 0) { ?>
        <div class="total-section">
            <p>Order Total</p>
            <h3>Rs. <?php echo number_format($total, 2); ?></h3>
        </div>

        <div class="cart-actions">
            <a href="shop.php" class="btn-continue">← Continue Shopping</a>
            <a href="checkout.php" class="btn-checkout">PROCEED TO CHECKOUT</a>
            <a href="cart.php?clear=1" class="clear-link" onclick="return confirm('Do you want to clear your entire cart?')">Clear Cart</a>
        </div>
    <?php } else { ?>
        <div style="text-align: center; margin-top: 30px;">
            <a href="shop.php" class="btn-checkout" style="display: inline-block;">EXPLORE SHOP</a>
        </div>
    <?php } ?>
</div>

<?php include('includes/footer.php'); ?>