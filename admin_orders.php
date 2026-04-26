<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: panel_adma9xKpL2_admin_login.php");
    exit();
}

include('includes/db_connect.php'); 

// --- 1. Update Admin Note and Order Delivery Status ---
if (isset($_POST['update_order'])) {
    $id = $_POST['order_id'];
    $note = mysqli_real_escape_string($conn, $_POST['admin_note']);
    $order_status = mysqli_real_escape_string($conn, $_POST['order_status']);
    
    $conn->query("UPDATE orders SET admin_note = '$note', status = '$order_status' WHERE id = $id");
    
    header("Location: admin_orders.php");
    exit();
}

include('includes/header.php'); 

// --- 2. SQL Query to fetch order data ---
$sql = "SELECT o.*, 
               GROUP_CONCAT(CONCAT(oi.product_name, ' <strong style=\"color:#d4af37; font-size:0.9rem;\">x', oi.quantity, '</strong>') SEPARATOR '<br><br>') as items, 
               GROUP_CONCAT((oi.price * oi.quantity) SEPARATOR '<br><br>') as item_prices
        FROM orders o
        LEFT JOIN order_items oi ON o.id = oi.order_id
        GROUP BY o.id
        ORDER BY o.order_date DESC";

$result = $conn->query($sql);
?>

<style>
    /* === Premium Order Management CSS === */
    .admin-orders-container { 
        max-width: 1400px; 
        margin: 0 auto; 
        padding: 80px 20px; 
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

    /* Table Modernization */
    .premium-table { 
        width: 100%; 
        border-collapse: separate; 
        border-spacing: 0 15px; 
        font-size: 0.95rem; 
        color: #fff; 
    }
    
    .premium-table thead th { 
        color: #d4af37; 
        font-family: 'Playfair Display', serif; 
        font-weight: bold; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
        padding: 15px; 
        border-bottom: 2px solid #333; 
        text-align: left; 
    }
    
    .premium-table tbody tr { 
        background: #0a0a0a; 
        transition: all 0.3s ease-in-out; 
        box-shadow: 0 5px 15px rgba(0,0,0,0.3); 
    }
    
    .premium-table tbody tr:hover { 
        transform: translateY(-7px); 
        background: #111; 
        border-color: #d4af37; 
        box-shadow: 0 15px 35px rgba(212, 175, 55, 0.15); 
        z-index: 10; 
        position: relative; 
    }
    
    .premium-table td, 
    .premium-table th { 
        padding: 25px 20px; 
        vertical-align: top; 
        border: none; 
    }
    
    .premium-table tbody tr td:first-child { 
        border-radius: 15px 0 0 15px; 
        padding-left: 30px; 
    }
    
    .premium-table tbody tr td:last-child { 
        border-radius: 0 15px 15px 0; 
        padding-right: 30px; 
    }

    /* Details styling */
    .customer-name { 
        color: #fff; 
        font-size: 1.15rem; 
        font-weight: 600; 
        margin-bottom: 5px; 
        text-transform: capitalize; 
    }
    
    .customer-phone { 
        color: #aaa; 
        font-size: 0.85rem; 
        margin-top: 5px; 
    }
    
    .customer-phone i { 
        color: #d4af37; 
        margin-right: 8px; 
        font-size: 0.8rem; 
        width: 15px; 
        text-align: center; 
    }
    
    .order-date-badge { 
        color: #888; 
        background: rgba(255,255,255,0.05); 
        padding: 5px 10px; 
        border-radius: 4px; 
        font-size: 0.8rem; 
        display: inline-block; 
        margin-top: 10px;
    }

    /* === Payment Badges === */
    .pay-method-badge { 
        display: inline-block; 
        padding: 4px 10px; 
        border-radius: 4px; 
        font-size: 0.75rem; 
        font-weight: bold; 
        margin-top: 10px; 
        text-transform: uppercase; 
        letter-spacing: 1px;
    }
    
    .method-cod { 
        background: rgba(46, 204, 113, 0.15); 
        color: #2ecc71; 
        border: 1px solid rgba(46, 204, 113, 0.3); 
    }
    
    .method-card { 
        background: rgba(52, 152, 219, 0.15); 
        color: #3498db; 
        border: 1px solid rgba(52, 152, 219, 0.3); 
    }

    .bill-pill { 
        display: inline-block; 
        background: rgba(212, 175, 55, 0.1); 
        color: #d4af37; 
        padding: 10px 25px; 
        border-radius: 50px; 
        font-weight: bold; 
        font-size: 1.2rem; 
        border: 1px solid rgba(212, 175, 55, 0.3); 
    }

    /* === Form & Note Box === */
    .note-form { 
        display: flex; 
        flex-direction: column; 
        gap: 10px; 
    }
    
    .status-select { 
        background: #111; 
        color: #fff; 
        border: 1px solid #333; 
        padding: 8px 12px; 
        border-radius: 8px; 
        font-size: 0.85rem; 
        outline: none; 
        transition: 0.3s; 
        cursor: pointer; 
    }
    
    .status-select:focus { 
        border-color: #d4af37; 
    }
    
    .note-textarea { 
        background: #000; 
        color: #fff; 
        border: 1px solid #333; 
        padding: 12px; 
        border-radius: 12px; 
        width: 100%; 
        font-size: 0.85rem; 
        resize: vertical; 
        min-height: 60px; 
        transition: 0.3s; 
    }
    
    .note-textarea:focus { 
        border-color: #d4af37; 
        outline: none; 
        box-shadow: 0 0 10px rgba(212, 175, 55, 0.1); 
    }
    
    .note-submit-btn { 
        background: #d4af37; 
        color: #000; 
        border: none; 
        cursor: pointer; 
        padding: 8px 20px; 
        border-radius: 50px; 
        font-weight: bold; 
        font-size: 0.8rem; 
        transition: 0.3s; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
    }
    
    .note-submit-btn:hover { 
        background: #fff; 
        transform: scale(1.05); 
    }

    /* === View Details Button === */
    .btn-view {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: transparent;
        color: #d4af37;
        border: 1px solid #d4af37;
        padding: 10px 20px;
        border-radius: 50px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: bold;
        transition: 0.3s ease-in-out;
        text-transform: uppercase;
        letter-spacing: 1px;
        white-space: nowrap;
    }
    
    .btn-view:hover {
        background: #d4af37;
        color: #000;
        box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);
        transform: translateY(-3px);
    }

    @media (max-width: 992px) {
        .premium-table thead { display: none; }
        .premium-table tbody tr td { display: block; text-align: left; padding: 15px; border-radius: 15px !important; }
        .customer-details { padding-left: 20px !important; }
        .btn-view { width: 100%; justify-content: center; margin-top: 15px; }
    }
</style>

<div class="admin-orders-container">
    <h2 class="luxury-title">Order Management</h2>
    
    <table class="premium-table">
        <thead>
            <tr>
                <th>Customer Details</th>
                <th>Order Items</th>
                <th style="text-align: center;">Payment</th>
                <th style="text-align: center;">Total Bill</th>
                <th>Status & Notes</th>
                <th style="text-align: center;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()) { 
                $method_class = ($row['payment_method'] == 'Card') ? 'method-card' : 'method-cod';
                $method_icon = ($row['payment_method'] == 'Card') ? '<i class="fa-regular fa-credit-card"></i> Card' : '<i class="fa-solid fa-money-bill-wave"></i> COD';
            ?>
                <tr>
                    <td class="customer-details">
                        <div class="customer-name"><?php echo $row['full_name']; ?></div>
                        
                        <div class="customer-phone"><i class="fa-solid fa-envelope"></i> <?php echo $row['email']; ?></div>
                        <div class="customer-phone"><i class="fa-solid fa-phone"></i> <?php echo $row['phone']; ?></div>
                        <div class="customer-phone"><i class="fa-solid fa-location-dot"></i> <?php echo $row['address']; ?></div>
                        
                        <small class="order-date-badge"><?php echo date('M d, h:i A', strtotime($row['order_date'])); ?></small><br>
                        <span class="pay-method-badge <?php echo $method_class; ?>"><?php echo $method_icon; ?></span>
                    </td>
                    
                    <td>
                        <div style="line-height: 1.6;">
                            <span><?php echo $row['items'] ? $row['items'] : 'No items found'; ?></span>
                        </div>
                    </td>
                    
                    <td style="text-align: center; vertical-align: middle;">
                        <div style="font-weight: bold; font-size: 0.95rem;">
                            <?php 
                                if (isset($row['payment_status'])) {
                                    if ($row['payment_status'] == 'Paid') {
                                        echo '<span style="color: #2ecc71;">🟢 Paid</span>';
                                    } elseif ($row['payment_status'] == 'Pending') {
                                        echo '<span style="color: #f39c12;">🟡 Pending</span>';
                                    } else {
                                        echo '<span style="color: #e74c3c;">🔴 Unpaid</span>';
                                    }
                                }
                            ?>
                        </div>
                        
                        <?php if(!empty($row['transaction_id'])) { ?>
                            <div style="font-size: 0.75rem; color: #888; margin-top: 10px; font-weight: normal; background: rgba(255,255,255,0.05); padding: 5px; border-radius: 4px;">
                                TXN: <?php echo $row['transaction_id']; ?>
                            </div>
                        <?php } ?>
                    </td>
                    
                    <td style="text-align: center; vertical-align: middle;">
                        <div class="bill-pill">Rs. <?php echo number_format($row['total_amount'], 2); ?></div>
                    </td>
                    
                    <td>
                        <form action="admin_orders.php" method="POST" class="note-form">
                            <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                            
                            <select name="order_status" class="status-select">
                                <option value="Pending" <?php if(isset($row['status']) && $row['status'] == 'Pending') echo 'selected'; ?>>📦 Pending</option>
                                <option value="Processing" <?php if(isset($row['status']) && $row['status'] == 'Processing') echo 'selected'; ?>>⚙️ Processing</option>
                                <option value="Shipped" <?php if(isset($row['status']) && $row['status'] == 'Shipped') echo 'selected'; ?>>🚚 Shipped</option>
                                <option value="Delivered" <?php if(isset($row['status']) && $row['status'] == 'Delivered') echo 'selected'; ?>>✅ Delivered</option>
                                <option value="Cancelled" <?php if(isset($row['status']) && $row['status'] == 'Cancelled') echo 'selected'; ?>>❌ Cancelled</option>
                            </select>

                            <textarea name="admin_note" placeholder="Add tracking ID or notes..." class="note-textarea"><?php echo $row['admin_note']; ?></textarea>
                            <button type="submit" name="update_order" class="note-submit-btn">Update Order</button>
                        </form>
                    </td>
                    
                    <td style="text-align: center; vertical-align: middle;">
                        <a href="admin_view_order.php?id=<?php echo $row['id']; ?>" class="btn-view" title="View Full Invoice">
                            <i class="fa-solid fa-eye"></i> Details
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>