<?php
session_start();
include('includes/db_connect.php');

// Redirect the user to the login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
include('includes/header.php');

// Fetch orders only for the currently logged-in user
// Note: Ensure your 'orders' table contains the 'user_id' column
$sql = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY order_date DESC";
$result = $conn->query($sql);
?>

<style>
    /* === Premium User Orders UI === */
    .my-orders-section {
        max-width: 1200px;
        margin: 100px auto;
        padding: 0 20px;
        font-family: 'Montserrat', sans-serif;
        color: #fff;
    }

    .page-header {
        text-align: center;
        margin-bottom: 60px;
    }

    .page-header h2 {
        color: #d4af37;
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .page-header p {
        color: #888;
        font-size: 1rem;
        margin-top: 10px;
    }

    /* Order Table / List Style */
    .orders-wrapper {
        background: rgba(10, 10, 10, 0.6);
        border: 1px solid #222;
        border-radius: 20px;
        overflow: hidden;
        backdrop-filter: blur(10px);
    }

    .order-row {
        display: grid;
        grid-template-columns: 1.5fr 2fr 1.5fr 1.5fr 1.5fr;
        padding: 25px;
        border-bottom: 1px solid #222;
        align-items: center;
        transition: 0.3s;
    }

    .order-row:last-child { 
        border-bottom: none; 
    }

    .order-row:hover {
        background: rgba(212, 175, 55, 0.03);
    }

    .order-header-row {
        background: rgba(212, 175, 55, 0.05);
        color: #d4af37;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
    }

    /* Details Styling */
    .order-id { 
        font-weight: bold; 
        color: #fff; 
    }
    
    .order-date { 
        color: #888; 
        font-size: 0.9rem; 
    }
    
    .order-total { 
        color: #d4af37; 
        font-weight: bold; 
        font-size: 1.1rem; 
    }

    /* Status Badges */
    .badge {
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: bold;
        text-transform: uppercase;
        display: inline-block;
        text-align: center;
        min-width: 100px;
    }

    /* Payment Status Badges */
    .pay-paid { 
        background: rgba(46, 204, 113, 0.1); 
        color: #2ecc71; 
        border: 1px solid rgba(46, 204, 113, 0.3); 
    }
    
    .pay-pending { 
        background: rgba(243, 156, 18, 0.1); 
        color: #f39c12; 
        border: 1px solid rgba(243, 156, 18, 0.3); 
    }
    
    /* Delivery Status Colors */
    .status-shipped { color: #3498db; }
    .status-delivered { color: #2ecc71; }
    .status-processing { color: #d4af37; }

    /* Action Button */
    .btn-details {
        background: transparent;
        color: #d4af37;
        border: 1px solid #d4af37;
        padding: 10px 20px;
        border-radius: 50px;
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: bold;
        text-transform: uppercase;
        transition: 0.3s;
        text-align: center;
    }

    .btn-details:hover {
        background: #d4af37;
        color: #000;
        box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
    }

    /* Empty State */
    .empty-orders {
        text-align: center;
        padding: 80px 0;
    }
    
    .empty-orders i { 
        font-size: 4rem; 
        color: #333; 
        margin-bottom: 20px; 
    }

    /* Mobile Responsive Adjustments */
    @media (max-width: 768px) {
        .order-header-row { display: none; }
        .order-row { 
            grid-template-columns: 1fr; 
            text-align: center; 
            gap: 15px;
            padding: 30px;
        }
    }
</style>

<div class="my-orders-section">
    <div class="page-header">
        <h2>My Orders</h2>
        <p>Track your luxury fragrance journey with Ceylora</p>
    </div>

    <div class="orders-wrapper">
        <div class="order-row order-header-row">
            <div>Order ID</div>
            <div>Date Placed</div>
            <div style="text-align: center;">Payment</div>
            <div style="text-align: center;">Delivery</div>
            <div style="text-align: center;">Action</div>
        </div>

        <?php if ($result->num_rows > 0) { ?>
            <?php while($row = $result->fetch_assoc()) { 
                // Determine CSS class based on payment status
                $pay_class = ($row['payment_status'] == 'Paid') ? 'pay-paid' : 'pay-pending';
                
                // Determine color class based on delivery status
                $status_color = "";
                if($row['status'] == 'Shipped') $status_color = "status-shipped";
                elseif($row['status'] == 'Delivered') $status_color = "status-delivered";
                else $status_color = "status-processing";
            ?>
                <div class="order-row">
                    <div class="order-id">#<?php echo str_pad($row['id'], 5, '0', STR_PAD_LEFT); ?></div>
                    
                    <div class="order-date">
                        <i class="fa-regular fa-calendar-days" style="margin-right: 8px;"></i>
                        <?php echo date('F d, Y', strtotime($row['order_date'])); ?>
                    </div>
                    
                    <div style="text-align: center;">
                        <span class="badge <?php echo $pay_class; ?>">
                            <?php echo $row['payment_status']; ?>
                        </span>
                    </div>

                    <div style="text-align: center; font-weight: bold;" class="<?php echo $status_color; ?>">
                        <i class="fa-solid fa-circle" style="font-size: 0.5rem; margin-right: 5px;"></i>
                        <?php echo $row['status']; ?>
                    </div>

                    <div style="text-align: center;">
                        <a href="view_my_order.php?id=<?php echo $row['id']; ?>" class="btn-details">
                            View Details
                        </a>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="empty-orders">
                <i class="fa-solid fa-box-open"></i>
                <h3>No orders found yet.</h3>
                <p>Your luxury collection is waiting for its first addition.</p>
                <a href="shop.php" class="btn-details" style="display: inline-block; margin-top: 20px;">Go to Shop</a>
            </div>
        <?php } ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>