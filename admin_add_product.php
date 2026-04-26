<?php
session_start();

// 1. Connect to the database first
include('includes/db_connect.php'); 

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: panel_adma9xKpL2_admin_login.php");
    exit();
}

// 2. Toggle Trending Status (Includes AJAX logic)
if (isset($_GET['toggle_trending'])) {
    $id = (int)$_GET['toggle_trending']; 
    $conn->query("UPDATE products SET is_trending = 1 - is_trending WHERE id = $id");

    // Stop execution here without refreshing the page if it's an AJAX request
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo "success";
        exit; 
    }

    header("Location: admin_add_product.php");
    exit();
}

$message = "";

// --- 1. Add a new category ---
if (isset($_POST['add_category'])) {
    $cat_name = mysqli_real_escape_string($conn, $_POST['new_category']);
    if (!empty($cat_name)) {
        $sql = "INSERT INTO categories (name) VALUES ('$cat_name')";
        mysqli_query($conn, $sql);
    }
}

// --- 2. Delete a category ---
if (isset($_GET['delete_cat'])) {
    $cat_id = $_GET['delete_cat']; // Note: For future security, consider escaping this value
    mysqli_query($conn, "DELETE FROM categories WHERE id = $cat_id");
    header("Location: admin_add_product.php");
    exit();
}

// --- 3. Delete a product ---
if (isset($_GET['delete'])) {
    $id = $_GET['delete']; // Note: For future security, consider escaping this value
    $sql = "DELETE FROM products WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin_add_product.php?deleted=1");
        exit();
    }
}

// Display delete success message
if (isset($_GET['deleted'])) {
    $message = "<div class='alert alert-orange'><i class='fa-solid fa-trash-can'></i> Product deleted successfully!</div>";
}

// --- 4. Add a new product ---
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    $is_sale = isset($_POST['is_sale']) ? 1 : 0;

    $target_dir = "assets/images/uploads/";
    
    // Create directory if it doesn't exist
    if (!file_exists($target_dir)) { 
        mkdir($target_dir, 0777, true); 
    }

    $image_name = basename($_FILES["image"]["name"]);
    $image_path_in_db = $target_dir . time() . "_" . $image_name;

    // Upload the file and save to database
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_path_in_db)) {
        $sql = "INSERT INTO products (name, category, price, description, image_url, is_sale) 
                VALUES ('$name', '$category', '$price', '$description', '$image_path_in_db', '$is_sale')";
                
        if ($conn->query($sql) === TRUE) {
            $message = "<div class='alert alert-green'><i class='fa-solid fa-check-circle'></i> Product added successfully to inventory!</div>";
        }
    }
}

// === Load the header from here ===
include('includes/header.php'); 

// Fetch all products for the inventory table
$all_products = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<style>
    /* All previous styles are kept exactly as they were, just formatted for readability */
    .admin-container { 
        max-width: 1300px; 
        margin: 60px auto; 
        padding: 0 20px; 
        font-family: 'Montserrat', sans-serif; 
        color: #fff; 
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
    .admin-main-grid { 
        display: grid; 
        grid-template-columns: 1.5fr 1fr; 
        gap: 40px; 
        align-items: start; 
        margin-bottom: 60px; 
    }
    .admin-box { 
        background: #0a0a0a; 
        padding: 40px; 
        border: 1px solid #222; 
        border-radius: 20px; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
    }
    .admin-box h3 { 
        color: #d4af37; 
        font-family: 'Playfair Display', serif; 
        font-size: 1.8rem; 
        margin-bottom: 25px; 
        border-bottom: 1px solid #222; 
        padding-bottom: 15px; 
    }
    .form-group { 
        margin-bottom: 20px; 
    }
    .form-group label { 
        display: block; 
        margin-bottom: 8px; 
        color: #aaa; 
        font-size: 0.9rem; 
        font-weight: 500; 
    }
    input[type="text"], 
    input[type="number"], 
    input[type="file"], 
    select, 
    textarea { 
        width: 100%; 
        padding: 15px; 
        background: #000; 
        border: 1px solid #333; 
        color: #fff; 
        border-radius: 12px; 
        outline: none; 
        transition: 0.3s; 
        font-size: 0.95rem; 
    }
    textarea { 
        resize: vertical; 
        min-height: 100px; 
    }
    input:focus, 
    select:focus, 
    textarea:focus { 
        border-color: #d4af37; 
        box-shadow: 0 0 10px rgba(212, 175, 55, 0.1); 
    }
    .checkbox-container { 
        display: flex; 
        align-items: center; 
        gap: 10px; 
        cursor: pointer; 
        color: #d4af37; 
        font-weight: bold; 
    }
    .checkbox-container input { 
        width: auto; 
        transform: scale(1.2); 
        accent-color: #d4af37; 
    }
    .btn-primary { 
        background: #d4af37; 
        color: #000; 
        border: none; 
        padding: 15px; 
        font-size: 1rem; 
        font-weight: bold; 
        cursor: pointer; 
        text-transform: uppercase; 
        width: 100%; 
        border-radius: 50px; 
        transition: 0.3s; 
        letter-spacing: 1px; 
        margin-top: 10px; 
    }
    .btn-primary:hover { 
        background: #fff; 
        transform: translateY(-3px); 
        box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2); 
    }
    .cat-add-flex { 
        display: flex; 
        gap: 15px; 
        margin-bottom: 30px; 
    }
    .btn-add-cat { 
        background: #d4af37; 
        color: #000; 
        border: none; 
        padding: 0 25px; 
        border-radius: 12px; 
        font-weight: bold; 
        cursor: pointer; 
        transition: 0.3s; 
    }
    .btn-add-cat:hover { 
        background: #fff; 
    }
    .cat-item { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        background: #111; 
        padding: 15px 20px; 
        border-radius: 12px; 
        margin-bottom: 12px; 
        border: 1px solid #222; 
        transition: 0.3s; 
    }
    .cat-item:hover { 
        border-color: #d4af37; 
        transform: translateX(5px); 
    }
    .cat-del-btn { 
        color: #ff4444; 
        text-decoration: none; 
        font-size: 1.2rem; 
        display: flex; 
        justify-content: center; 
        align-items: center; 
        width: 30px; 
        height: 30px; 
        border-radius: 50%; 
        background: rgba(255,68,68,0.1); 
        transition: 0.3s; 
    }
    .cat-del-btn:hover { 
        background: #ff4444; 
        color: #fff; 
        transform: rotate(90deg); 
    }
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
        transform: translateY(-5px); 
        background: #111; 
        border-color: #d4af37; 
        box-shadow: 0 15px 35px rgba(212, 175, 55, 0.15); 
    }
    .premium-table td { 
        padding: 20px; 
        vertical-align: middle; 
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
    .inventory-img { 
        width: 60px; 
        height: 60px; 
        object-fit: cover; 
        border-radius: 10px; 
        border: 1px solid #333; 
    }
    .action-flex { 
        display: flex; 
        justify-content: center; 
        gap: 15px; 
        align-items: center; 
    }
    .btn-edit { 
        color: #d4af37; 
        background: rgba(212, 175, 55, 0.1); 
        width: 40px; 
        height: 40px; 
        display: inline-flex; 
        justify-content: center; 
        align-items: center; 
        border-radius: 50%; 
        text-decoration: none; 
        transition: 0.3s; 
    }
    .btn-edit:hover { 
        background: #d4af37; 
        color: #000; 
        transform: scale(1.1); 
    }
    .btn-trash { 
        color: #ff4444; 
        background: rgba(255, 68, 68, 0.1); 
        width: 40px; 
        height: 40px; 
        display: inline-flex; 
        justify-content: center; 
        align-items: center; 
        border-radius: 50%; 
        text-decoration: none; 
        transition: 0.3s; 
    }
    .btn-trash:hover { 
        background: #ff4444; 
        color: #fff; 
        transform: scale(1.1) rotate(15deg); 
    }
    .alert { 
        padding: 15px; 
        margin-bottom: 30px; 
        border-radius: 10px; 
        text-align: center; 
        font-weight: bold; 
    }
    .alert-green { 
        background: rgba(0, 255, 0, 0.1); 
        color: #00ff00; 
        border: 1px solid #00ff00; 
    }
    .alert-orange { 
        background: rgba(255, 68, 68, 0.1); 
        color: #ff4444; 
        border: 1px solid #ff4444; 
    }

    @media (max-width: 992px) {
        .admin-main-grid { grid-template-columns: 1fr; }
        .premium-table thead { display: none; }
        .premium-table tbody tr td { display: block; text-align: left; padding: 15px; border-radius: 15px !important; }
        .action-flex { justify-content: flex-start; margin-top: 10px; }
    }
</style>

<div class="admin-container">
    <h2 class="luxury-title">Product Portal</h2>
    <?php echo $message; ?>

    <div class="admin-main-grid">
        <div class="admin-box">
            <h3>Add New Fragrance</h3>
            <form action="admin_add_product.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="name" required placeholder="e.g. Royal Oud">
                </div>
                
                <div style="display: flex; gap: 20px;">
                    <div class="form-group" style="flex: 1;">
                        <label>Category</label>
                        <select name="category" required>
                            <option value="">-- Select Category --</option>
                            <?php
                            $cat_res = $conn->query("SELECT * FROM categories ORDER BY name ASC");
                            while($c = $cat_res->fetch_assoc()) {
                                echo "<option value='".$c['name']."'>".$c['name']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Price (Rs.)</label>
                        <input type="number" name="price" required placeholder="0.00">
                    </div>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" required placeholder="Brief details about the fragrance notes..."></textarea>
                </div>

                <div class="form-group">
                    <label>Product Image</label>
                    <input type="file" name="image" required style="padding: 10px;">
                </div>

                <div class="form-group">
                    <label class="checkbox-container">
                        <input type="checkbox" name="is_sale"> Mark as 'On Sale' / 'Special'
                    </label>
                </div>

                <button type="submit" name="submit" class="btn-primary">
                    <i class="fa-solid fa-cloud-arrow-up"></i> UPLOAD PRODUCT
                </button>
            </form>
        </div>

        <div class="admin-box">
            <h3>Manage Categories</h3>
            <form action="admin_add_product.php" method="POST" class="cat-add-flex">
                <input type="text" name="new_category" placeholder="e.g. Floral, Woody" required style="flex: 1;">
                <button type="submit" name="add_category" class="btn-add-cat">ADD</button>
            </form>

            <div class="category-list">
                <?php
                $cat_list = $conn->query("SELECT * FROM categories ORDER BY name ASC");
                if($cat_list->num_rows > 0) {
                    while($cl = $cat_list->fetch_assoc()) {
                        echo "<div class='cat-item'>
                                <span style='font-weight: 500;'>".$cl['name']."</span>
                                <a href='admin_add_product.php?delete_cat=".$cl['id']."' class='cat-del-btn' onclick='return confirm(\"Delete this category?\")' title='Delete Category'>&times;</a>
                              </div>";
                    }
                } else {
                    echo "<p style='color:#555; text-align:center;'>No categories added yet.</p>";
                }
                ?>
            </div>
        </div>
    </div>

    <h3 class="luxury-title" style="font-size: 2rem; margin-top: 80px;">Existing Inventory</h3>
    <table class="premium-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Product Details</th>
                <th>Category</th>
                <th>Price</th>
                <th style="text-align: center;">Trending Status</th> 
                <th style="text-align: center;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $all_products->fetch_assoc()) { ?>
            <tr>
                <td><img src="<?php echo $row['image_url']; ?>" class="inventory-img"></td>
                <td>
                    <strong><?php echo $row['name']; ?></strong><br>
                    <?php if($row['is_sale']) { 
                        echo "<span class='badge sale' style='color: #ff4444; font-size: 0.8rem; font-weight: bold; border: 1px solid #ff4444; padding: 2px 5px; border-radius: 4px;'>SALE</span>"; 
                    } ?>
                </td>
                <td><?php echo $row['category']; ?></td>
                <td>Rs. <?php echo number_format($row['price'], 2); ?></td>
        
                <td style="text-align: center;">
                    <button type="button" onclick="toggleTrending(this, <?php echo $row['id']; ?>)" 
                        style="cursor: pointer; padding: 8px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; transition: 0.3s; border: 1px solid #333;
                        background: <?php echo $row['is_trending'] ? '#d4af37' : '#222'; ?>; 
                        color: <?php echo $row['is_trending'] ? '#000' : '#888'; ?>;">
                        <?php echo $row['is_trending'] ? '★ TRENDING' : '☆ MARK AS TRENDING'; ?>
                    </button>
                </td>

                <td style="text-align: center;">
                    <div class="action-flex">
                        <a href="admin_edit_product.php?id=<?php echo $row['id']; ?>" class="btn-edit"><i class="fa-solid fa-pen"></i></a>
                        <a href="admin_add_product.php?delete=<?php echo $row['id']; ?>" class="btn-trash" onclick="return confirm('Delete Product?')"><i class="fa-solid fa-trash-can"></i></a>
                    </div>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
/**
 * Toggles the trending status of a product using AJAX
 * @param {HTMLElement} btn - The button element clicked
 * @param {number} id - The ID of the product
 */
function toggleTrending(btn, id) {
    fetch('admin_add_product.php?toggle_trending=' + id, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.text())
    .then(data => {
        if(data === "success") {
            if (btn.innerText.includes('★')) {
                btn.innerText = '☆ MARK AS TRENDING';
                btn.style.background = '#222';
                btn.style.color = '#888';
            } else {
                btn.innerText = '★ TRENDING';
                btn.style.background = '#d4af37';
                btn.style.color = '#000';
            }
        }
    });
}
</script>

<?php include('includes/footer.php'); ?>