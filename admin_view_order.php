<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: panel_adma9xKpL2_admin_login.php");
    exit();
}

include('includes/db_connect.php'); 

// Redirect back to Orders page if no ID is present in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: admin_orders.php");
    exit();
}

// Sanitize the ID using intval for security
$order_id = intval($_GET['id']); 

// Fetch main order details from the database
$order_query = $conn->query("SELECT * FROM orders WHERE id = $order_id");
if ($order_query->num_rows == 0) {
    echo "<script>alert('Order not found!'); window.location.href='admin_orders.php';</script>";
    exit();
}
$order = $order_query->fetch_assoc();

// Fetch all items related to this specific order
$items_query = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");

include('includes/header.php'); 
?>

<style>
    /* === Premium Invoice / Order Details CSS === */
    .invoice-container {
        max-width: 770px;
        margin: 60px auto;
        background: #0a0a0a;
        padding: 50px;
        border-radius: 20px;
        border: 1px solid #222;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        font-family: 'Montserrat', sans-serif;
        color: #fff;
    }

    /* Top Actions */
    .top-actions { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 30px; 
    }
    
    .btn-back { 
        color: #888; 
        text-decoration: none; 
        font-size: 0.95rem; 
        transition: 0.3s; 
    }
    
    .btn-back:hover { 
        color: #d4af37; 
    }
    
    .btn-print { 
        background: #d4af37; 
        color: #000; 
        padding: 10px 25px; 
        border-radius: 50px; 
        text-decoration: none; 
        font-weight: bold; 
        font-size: 0.9rem; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
        transition: 0.3s; 
        border: none; 
        cursor: pointer; 
    }
    
    .btn-print:hover { 
        background: #fff; 
        box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3); 
        transform: translateY(-2px); 
    }

    /* Header Section */
    .invoice-header { 
        text-align: center; 
        border-bottom: 1px solid #222; 
        padding-bottom: 30px; 
        margin-bottom: 40px; 
    }
    
    .invoice-header h2 { 
        color: #d4af37; 
        font-family: 'Playfair Display', serif; 
        font-size: 2.5rem; 
        letter-spacing: 2px; 
        text-transform: uppercase; 
        margin: 0 0 10px 0; 
    }
    
    .order-id { 
        font-size: 1.2rem; 
        color: #aaa; 
        letter-spacing: 2px; 
    }

    /* Info Grid (Customer & Payment Info) */
    .info-grid { 
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 40px; 
        margin-bottom: 40px; 
    }
    
    .info-box { 
        background: #111; 
        padding: 25px; 
        border-radius: 15px; 
        border: 1px solid #222; 
    }
    
    .info-box h3 { 
        color: #d4af37; 
        font-size: 1.1rem; 
        margin-bottom: 20px; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
        border-bottom: 1px dashed #333; 
        padding-bottom: 10px; 
    }
    
    .info-line { 
        display: flex; 
        margin-bottom: 12px; 
        font-size: 0.95rem; 
    }
    
    .info-line span:first-child { 
        color: #888; 
        width: 120px; 
        font-weight: 500; 
    }
    
    .info-line span:last-child { 
        color: #ddd; 
        flex: 1; 
        font-weight: 600; 
    }

    /* Itemized Table */
    .invoice-table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-bottom: 30px; 
    }
    
    .invoice-table th { 
        background: rgba(212, 175, 55, 0.1); 
        color: #d4af37; 
        padding: 15px; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
        font-size: 0.85rem; 
        border-bottom: 1px solid #d4af37; 
        text-align: left; 
    }
    
    .invoice-table td { 
        padding: 18px 15px; 
        border-bottom: 1px solid #222; 
        font-size: 0.95rem; 
    }
    
    .invoice-table tbody tr:last-child td { 
        border-bottom: none; 
    }
    
    /* Summary Box */
    .summary-box { 
        float: right; 
        width: 350px; 
        background: #111; 
        padding: 25px; 
        border-radius: 15px; 
        border: 1px solid #333; 
    }
    
    .summary-line { 
        display: flex; 
        justify-content: space-between; 
        margin-bottom: 15px; 
        font-size: 0.95rem; 
        color: #aaa; 
    }
    
    .grand-total { 
        border-top: 1px solid #333; 
        padding-top: 15px; 
        margin-top: 15px; 
        font-size: 1.3rem; 
        color: #d4af37; 
        font-weight: bold; 
    }

    .clearfix::after { 
        content: ""; 
        clear: both; 
        display: table; 
    }

    /* Status Badges */
    .badge { 
        padding: 4px 10px; 
        border-radius: 4px; 
        font-size: 0.75rem; 
        font-weight: bold; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
    }
    
    .b-paid { 
        background: rgba(46, 204, 113, 0.15); 
        color: #2ecc71; 
        border: 1px solid rgba(46, 204, 113, 0.3); 
    }
    
    .b-pending { 
        background: rgba(243, 156, 18, 0.15); 
        color: #f39c12; 
        border: 1px solid rgba(243, 156, 18, 0.3); 
    }
    
    .b-unpaid { 
        background: rgba(231, 76, 60, 0.15); 
        color: #e74c3c; 
        border: 1px solid rgba(231, 76, 60, 0.3); 
    }

    /* === Print CSS === */
    @media print {
        body * { 
            visibility: hidden; 
        }
        
        .invoice-container, 
        .invoice-container * { 
            visibility: visible; 
        }
        
        .invoice-container { 
            position: absolute; 
            left: 0; 
            top: 0; 
            width: 100%; 
            margin: 0; 
            padding: 20px; 
            box-shadow: none; 
            border: none; 
            background: #fff !important; 
            color: #000 !important; 
        }
        
        .top-actions, 
        header, 
        footer { 
            display: none !important; 
        }
        
        /* Adjust colors for paper printing */
        .info-box, 
        .summary-box { 
            background: #f9f9f9 !important; 
            border-color: #ddd !important; 
        }
        
        .invoice-header h2, 
        .info-box h3, 
        .grand-total { 
            color: #000 !important; 
        }
        
        .info-line span:first-child, 
        .summary-line { 
            color: #555 !important; 
        }
        
        .invoice-table th { 
            background: #eee !important; 
            color: #000 !important; 
            border-color: #ccc !important; 
        }
        
        .invoice-table td { 
            border-color: #eee !important; 
            color: #000 !important; 
        }
    }
</style>

<div class="invoice-container">
    
    <div class="top-actions">
        <a href="admin_orders.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back to Orders</a>
        <button onclick="window.print()" class="btn-print"><i class="fa-solid fa-print"></i> Print Invoice</button>
    </div>

    <div class="invoice-header">
        <h2>Order Details</h2>
        <div class="order-id">REFERENCE: #<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></div>
        <div style="color: #666; font-size: 0.85rem; margin-top: 10px;">
            Placed on: <?php echo date('F d, Y - h:i A', strtotime($order['order_date'])); ?>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-box">
            <h3><i class="fa-solid fa-user"></i> Customer Information</h3>
            <div class="info-line"><span>Name:</span> <span><?php echo $order['full_name']; ?></span></div>
            <div class="info-line"><span>Email:</span> <span><?php echo $order['email']; ?></span></div>
            <div class="info-line"><span>Phone:</span> <span><?php echo $order['phone']; ?></span></div>
            <div class="info-line"><span>Address:</span> <span><?php echo $order['address']; ?></span></div>
        </div>

        <div class="info-box">
            <h3><i class="fa-solid fa-truck-fast"></i> Order Information</h3>
            
            <div class="info-line">
                <span>Method:</span> 
                <span><?php echo ($order['payment_method'] == 'Card') ? '<i class="fa-regular fa-credit-card"></i> Card' : '<i class="fa-solid fa-money-bill-wave"></i> Cash on Delivery'; ?></span>
            </div>
            
            <div class="info-line">
                <span>Payment:</span> 
                <span>
                    <?php 
                        if($order['payment_status'] == 'Paid') echo '<span class="badge b-paid">Paid</span>';
                        elseif($order['payment_status'] == 'Pending') echo '<span class="badge b-pending">Pending</span>';
                        else echo '<span class="badge b-unpaid">Failed/Unpaid</span>';
                    ?>
                </span>
            </div>

            <div class="info-line">
                <span>Delivery:</span> 
                <span style="color: #d4af37;"><?php echo $order['status']; ?></span>
            </div>

            <?php if(!empty($order['transaction_id'])) { ?>
                <div class="info-line"><span>TXN ID:</span> <span><?php echo $order['transaction_id']; ?></span></div>
            <?php } ?>
            
            <?php if(!empty($order['admin_note'])) { ?>
                <div class="info-line" style="margin-top: 15px; padding-top: 15px; border-top: 1px dashed #333;">
                    <span>Notes:</span> <span style="color: #aaa; font-style: italic;"><?php echo $order['admin_note']; ?></span>
                </div>
            <?php } ?>
        </div>
    </div>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>Item Description</th>
                <th style="text-align: center;">Unit Price</th>
                <th style="text-align: center;">Quantity</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $calculated_subtotal = 0;
            while($item = $items_query->fetch_assoc()) { 
                $item_total = $item['price'] * $item['quantity'];
                $calculated_subtotal += $item_total;
            ?>
                <tr>
                    <td><strong><?php echo $item['product_name']; ?></strong></td>
                    <td style="text-align: center; color: #aaa;">Rs. <?php echo number_format($item['price'], 2); ?></td>
                    <td style="text-align: center; color: #d4af37; font-weight: bold;">x<?php echo $item['quantity']; ?></td>
                    <td style="text-align: right; color: #fff;">Rs. <?php echo number_format($item_total, 2); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="clearfix">
        <div class="summary-box">
            <div class="summary-line">
                <span>Subtotal</span>
                <span>Rs. <?php echo number_format($calculated_subtotal, 2); ?></span>
            </div>
            <div class="summary-line">
                <span>Shipping</span>
                <span>Free</span>
            </div>
            <div class="summary-line grand-total">
                <span>Grand Total</span>
                <span>Rs. <?php echo number_format($order['total_amount'], 2); ?></span>
            </div>
        </div>
    </div>

</div>

<?php include('includes/footer.php'); ?>