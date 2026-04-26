<?php
session_start();
include('includes/db_connect.php');

// Redirect to login page if the user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if Order ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: user_orders.php");
    exit();
}

$order_id = intval($_GET['id']);

// Fetch order details (Checking user_id for security/ownership)
$order_query = $conn->query("SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id");

if ($order_query->num_rows == 0) {
    echo "<script>alert('Order not found or access denied!'); window.location.href='user_orders.php';</script>";
    exit();
}

$order = $order_query->fetch_assoc();
$items_query = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");

include('includes/header.php');
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<style>
    /* === Premium User Invoice UI === */
    .invoice-wrapper {
        max-width: 600px; /* Compact box size as requested */
        margin: 80px auto;
        background: #0a0a0a;
        padding: 50px;
        border-radius: 20px;
        border: 2px solid #222;
        color: #fff;
        font-family: 'Montserrat', sans-serif;
    }

    .invoice-top { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 30px; 
        border-bottom: 1px solid #222; 
        padding-bottom: 20px; 
    }
    
    .invoice-top h2 { 
        font-family: 'Playfair Display', serif; 
        color: #d4af37; 
        text-transform: uppercase; 
        margin: 0; 
    }
    
    .btn-download { 
        background: #d4af37; 
        color: #000; 
        padding: 10px 20px; 
        border-radius: 50px; 
        border: none; 
        font-weight: bold; 
        cursor: pointer; 
        transition: 0.3s; 
        text-transform: uppercase; 
        font-size: 0.8rem; 
    }
    
    .btn-download:hover { 
        background: #fff; 
    }

    .info-section { 
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 30px; 
        margin: 30px 0; 
    }
    
    .info-card { 
        background: #111; 
        padding: 20px; 
        border-radius: 15px; 
        border: 1px solid #222; 
    }
    
    .info-card h4 { 
        color: #d4af37; 
        margin-bottom: 15px; 
        text-transform: uppercase; 
        font-size: 0.9rem; 
        border-bottom: 1px dashed #333; 
        padding-bottom: 10px; 
    }
    
    .info-card p { 
        font-size: 0.85rem; 
        color: #ccc; 
        margin: 8px 0; 
    }
    
    .info-card b { 
        color: #fff; 
    }

    .item-table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-top: 20px; 
    }
    
    .item-table th { 
        text-align: left; 
        padding: 15px; 
        color: #d4af37; 
        border-bottom: 1px solid #d4af37; 
        text-transform: uppercase; 
        font-size: 0.8rem; 
    }
    
    .item-table td { 
        padding: 15px; 
        border-bottom: 1px solid #222; 
        font-size: 0.9rem; 
    }

    .summary-area { 
        float: right; 
        width: 300px; 
        margin-top: 30px; 
        background: #111; 
        padding: 20px; 
        border-radius: 15px; 
        border: 1px solid #222; 
    }
    
    .summary-line { 
        display: flex; 
        justify-content: space-between; 
        margin-bottom: 10px; 
        color: #888; 
        font-size: 0.9rem; 
    }
    
    .grand-total { 
        border-top: 1px solid #333; 
        padding-top: 10px; 
        margin-top: 10px; 
        color: #d4af37; 
        font-weight: bold; 
        font-size: 1.1rem; 
    }

    /* === PDF Download Mode (Light Theme for Printing) === */
    .pdf-white { 
        background: #fff !important; 
        color: #000 !important; 
        padding: 40px !important; 
        width: 800px !important; 
    }
    
    .pdf-white h2, .pdf-white h4, .pdf-white b { 
        color: #000 !important; 
    }
    
    .pdf-white .info-card { 
        background: #f9f9f9 !important; 
        border: 1px solid #ddd !important; 
    }
    
    .pdf-white .item-table th { 
        background: #eee !important; 
        color: #000 !important; 
        border-bottom: 2px solid #000 !important; 
    }
    
    .pdf-white .item-table td { 
        color: #000 !important; 
        border-bottom: 1px solid #eee !important; 
    }
    
    .pdf-white .summary-area { 
        background: #f9f9f9 !important; 
        border: 1px solid #ddd !important; 
    }
    
    .pdf-white .grand-total { 
        color: #000 !important; 
        border-top: 2px solid #000 !important; 
    }
</style>

<div class="invoice-wrapper" id="orderInvoice">
    <div class="invoice-top" id="pdfHide">
        <h2>Order Details</h2>
        <button onclick="generatePDF()" class="btn-download">
            <i class="fa-solid fa-file-pdf"></i> Download Invoice
        </button>
    </div>

    <div style="text-align: center; margin-bottom: 30px;">
        <p style="color: #888; margin: 0;">Order Reference: <b>#<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></b></p>
        <p style="color: #666; font-size: 0.8rem;">Placed on: <?php echo date('F d, Y - h:i A', strtotime($order['order_date'])); ?></p>
    </div>

    <div class="info-section">
        <div class="info-card">
            <h4>Shipping Address</h4>
            <p><b>Name:</b> <?php echo $order['full_name']; ?></p>
            <p><b>Phone:</b> <?php echo $order['phone']; ?></p>
            <p><b>Address:</b> <?php echo $order['address']; ?></p>
        </div>
        <div class="info-card">
            <h4>Order Status</h4>
            <p><b>Payment:</b> <?php echo $order['payment_status']; ?></p>
            <p><b>Method:</b> <?php echo $order['payment_method']; ?></p>
            <p><b>Delivery:</b> <span style="color: #d4af37;"><?php echo $order['status']; ?></span></p>
        </div>
    </div>

    <table class="item-table">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Price</th>
                <th>Qty</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $sub = 0;
            while($item = $items_query->fetch_assoc()) { 
                $line = $item['price'] * $item['quantity'];
                $sub += $line;
            ?>
                <tr>
                    <td><?php echo $item['product_name']; ?></td>
                    <td>Rs. <?php echo number_format($item['price'], 2); ?></td>
                    <td>x<?php echo $item['quantity']; ?></td>
                    <td style="text-align: right;">Rs. <?php echo number_format($line, 2); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div style="overflow: hidden;">
        <div class="summary-area">
            <div class="summary-line">
                <span>Subtotal</span> 
                <span>Rs. <?php echo number_format($sub, 2); ?></span>
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

<script>
    /**
     * Converts the HTML invoice into a downloadable PDF
     */
    function generatePDF() {
        const element = document.getElementById('orderInvoice');
        const hideMe = document.getElementById('pdfHide');
        
        // Hide the download button during PDF generation
        hideMe.style.display = 'none';
        element.classList.add('pdf-white');

        const opt = {
            margin: 0.3,
            filename: 'Ceylora_Invoice_#<?php echo $order['id']; ?>.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, backgroundColor: '#ffffff' },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        };

        // Execute PDF generation
        html2pdf().set(opt).from(element).save().then(() => {
            // Restore UI after download is complete
            element.classList.remove('pdf-white');
            hideMe.style.display = 'flex';
        });
    }
</script>

<?php include('includes/footer.php'); ?>