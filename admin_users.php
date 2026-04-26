<?php
session_start();
include('includes/db_connect.php');

// --- This is the part that was changed ---
// Checking 'admin_logged_in' here as used in the Header, instead of admin_id
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: panel_adma9xKpL2_admin_login.php");
    exit();
}
// ----------------------------------------

include('includes/header.php');

// Fetch user details from the database
$sql = "SELECT id, full_name, email, phone, secondary_email, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<style>
    /* Center the entire page and prevent overlap with the Header */
    .admin-users-wrapper {
        min-height: 100vh;
        background-color: #000;
        padding: 120px 20px 60px; /* Added 120px top padding to accommodate the fixed Header */
        font-family: 'Montserrat', sans-serif;
    }

    .admin-container {
        max-width: 1100px;
        margin: 0 auto;
    }

    .admin-card {
        background: #0a0a0a;
        border: 1px solid #222;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.5);
    }

    .admin-card h2 {
        color: #d4af37;
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        margin-bottom: 40px;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    /* Premium styling for the table */
    .user-table-wrapper {
        overflow-x: auto;
    }

    .user-table {
        width: 100%;
        border-collapse: collapse;
        color: #ccc;
    }

    .user-table th {
        text-align: left;
        padding: 18px;
        color: #d4af37;
        border-bottom: 2px solid #222;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 1px;
    }

    .user-table td {
        padding: 18px;
        border-bottom: 1px solid #111;
        font-size: 0.9rem;
    }

    .user-table tr:hover {
        background: rgba(212, 175, 55, 0.03);
    }

    .customer-name {
        color: #fff;
        font-weight: 600;
        font-size: 1rem;
    }

    .contact-info i {
        color: #d4af37;
        margin-right: 10px;
        font-size: 0.8rem;
    }

    .badge-date {
        font-size: 0.8rem;
        color: #666;
    }

    .no-data {
        color: #444;
        font-style: italic;
    }
</style>

<div class="admin-users-wrapper">
    <div class="admin-container">
        <div class="admin-card">
            <h2>Registered Customers</h2>
            
            <div class="user-table-wrapper">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Customer Details</th>
                            <th>Secondary Email</th>
                            <th>Phone Number</th>
                            <th>Joined Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0) { ?>
                            <?php while($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td>
                                        <div class="customer-name"><?php echo $row['full_name']; ?></div>
                                        <div style="font-size: 0.8rem; color: #666;"><?php echo $row['email']; ?></div>
                                    </td>
                                    <td>
                                        <?php echo !empty($row['secondary_email']) ? $row['secondary_email'] : '<span class="no-data">N/A</span>'; ?>
                                    </td>
                                    <td class="contact-info">
                                        <?php if(!empty($row['phone'])): ?>
                                            <i class="fa-solid fa-phone"></i> <?php echo $row['phone']; ?>
                                        <?php else: ?>
                                            <span class="no-data">Not Provided</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="badge-date">
                                        <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="4" style="text-align:center; padding: 50px;">No customers registered yet.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>