<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) { 
    header("Location: panel_adma9xKpL2_admin_login.php"); 
    exit(); 
}

include('includes/db_connect.php');

// Get the ID from the URL
if (!isset($_GET['id'])) {
    header("Location: admin_add_product.php");
    exit();
}
$id = (int)$_GET['id'];

// Fetch existing product details from the database
$result = $conn->query("SELECT * FROM products WHERE id = $id");
if ($result->num_rows == 0) {
    header("Location: admin_add_product.php");
    exit();
}
$product = $result->fetch_assoc();

$message = "";

// Handle form submission for updating the product
if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = $_POST['price'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $is_sale = isset($_POST['is_sale']) ? 1 : 0;
    $is_trending = isset($_POST['is_trending']) ? 1 : 0;
    
    // Base SQL update query
    $sql = "UPDATE products SET name='$name', category='$category', price='$price', description='$description', is_sale='$is_sale', is_trending='$is_trending'";

    // Handle image upload if a new image is provided
    if ($_FILES['image']['name'] != "") {
        $target_dir = "assets/images/uploads/";
        
        if (!file_exists($target_dir)) { 
            mkdir($target_dir, 0777, true); 
        }

        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . time() . "_" . $image_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $sql .= ", image_url='$target_file'";
        }
    }

    $sql .= " WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Display a styled success message
        $message = "<div class='alert success'><i class='fa-solid fa-circle-check'></i> Product updated successfully!</div>";
        
        // Refresh the product data to display updated values on the page
        $result = $conn->query("SELECT * FROM products WHERE id = $id");
        $product = $result->fetch_assoc();
    } else {
        $message = "<div class='alert error'>Error: " . $conn->error . "</div>";
    }
}

include('includes/header.php');
?>

<style>
    /* === Premium Edit Portal CSS === */
    .edit-container { 
        max-width: 850px; 
        margin: 60px auto; 
        padding: 0 20px; 
        font-family: 'Montserrat', sans-serif; 
        color: #fff; 
    }
    
    .luxury-title { 
        color: #d4af37; 
        font-family: 'Playfair Display', serif; 
        font-size: 2.5rem; 
        text-align: center; 
        text-transform: uppercase; 
        letter-spacing: 2px; 
        margin-bottom: 40px; 
        position: relative; 
    }
    
    .luxury-title::after { 
        content: ''; 
        display: block; 
        width: 50px; 
        height: 2px; 
        background: #d4af37; 
        margin: 15px auto 0; 
    }

    .admin-box { 
        background: #0a0a0a; 
        padding: 50px 40px; 
        border: 1px solid #222; 
        border-radius: 25px; 
        box-shadow: 0 20px 50px rgba(0,0,0,0.8); 
        transition: 0.4s; 
    }
    
    .admin-box:hover { 
        border-color: #d4af37; 
        box-shadow: 0 20px 50px rgba(212, 175, 55, 0.1); 
    }

    .form-group { 
        margin-bottom: 25px; 
    }
    
    .form-group label { 
        display: block; 
        margin-bottom: 10px; 
        color: #aaa; 
        font-size: 0.85rem; 
        font-weight: 500; 
        padding-left: 5px; 
    }
    
    input[type="text"], 
    input[type="number"], 
    select, 
    textarea { 
        width: 100%; 
        padding: 15px 20px; 
        background: #000; 
        border: 1px solid #333; 
        color: #fff; 
        border-radius: 12px; 
        outline: none; 
        transition: 0.3s; 
        font-size: 1rem; 
        box-sizing: border-box;
    }
    
    textarea { 
        height: 120px; 
        resize: none; 
    }
    
    input:focus, 
    select:focus, 
    textarea:focus { 
        border-color: #d4af37; 
        box-shadow: 0 0 15px rgba(212, 175, 55, 0.1); 
    }

    /* Checkbox Group */
    .checkbox-group { 
        display: flex; 
        gap: 30px; 
        margin: 25px 0; 
        background: #111; 
        padding: 20px; 
        border-radius: 15px; 
        border: 1px solid #222; 
    }
    
    .check-item { 
        display: flex; 
        align-items: center; 
        gap: 10px; 
        cursor: pointer; 
        color: #d4af37; 
        font-weight: bold; 
        font-size: 0.9rem; 
    }
    
    .check-item input { 
        width: 18px; 
        height: 18px; 
        accent-color: #d4af37; 
        cursor: pointer; 
    }

    .btn-update { 
        width: 100%; 
        padding: 18px; 
        background: #d4af37; 
        color: black; 
        border: none; 
        border-radius: 50px; 
        font-weight: bold; 
        cursor: pointer; 
        text-transform: uppercase; 
        transition: 0.4s; 
        font-size: 1rem; 
        letter-spacing: 1px; 
    }
    
    .btn-update:hover { 
        background: #fff; 
        transform: translateY(-3px); 
        box-shadow: 0 10px 20px rgba(0,0,0,0.4); 
    }

    .current-img { 
        margin-bottom: 30px; 
        text-align: center; 
    }
    
    .current-img img { 
        width: 160px; 
        height: 160px; 
        object-fit: cover; 
        border-radius: 20px; 
        border: 2px solid #d4af37; 
        padding: 5px; 
        background: #000; 
    }

    .back-link { 
        display: block; 
        text-align: center; 
        margin-top: 30px; 
        color: #666; 
        text-decoration: none; 
        font-size: 0.9rem; 
        transition: 0.3s; 
    }
    
    .back-link:hover { 
        color: #d4af37; 
    }

    /* Alerts */
    .alert { 
        padding: 15px; 
        border-radius: 12px; 
        text-align: center; 
        margin-bottom: 25px; 
        font-weight: bold; 
    }
    
    .success { 
        background: rgba(0, 255, 0, 0.1); 
        color: #00ff00; 
        border: 1px solid #00ff00; 
    }
    
    .error { 
        background: rgba(255, 0, 0, 0.1); 
        color: #ff4444; 
        border: 1px solid #ff4444; 
    }
</style>

<div class="edit-container">
    <h2 class="luxury-title">Edit Fragrance</h2>
    
    <?php echo $message; ?>

    <div class="admin-box">
        <form action="" method="POST" enctype="multipart/form-data">
            
            <div class="current-img">
                <label style="color: #888; display: block; margin-bottom: 10px;">Current Display Image</label>
                <img src="<?php echo $product['image_url']; ?>" alt="Product Image">
            </div>

            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Category</label>
                    <select name="category" required>
                        <?php
                        $cat_res = $conn->query("SELECT * FROM categories ORDER BY name ASC");
                        while($c = $cat_res->fetch_assoc()) {
                            $selected = ($c['name'] == $product['category']) ? "selected" : "";
                            echo "<option value='".$c['name']."' $selected>".$c['name']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Price (Rs.)</label>
                    <input type="number" name="price" value="<?php echo $product['price']; ?>" step="0.01" required>
                </div>
            </div>

            <div class="form-group">
                <label>Description (Fragrance Notes & Details)</label>
                <textarea name="description" required><?php echo $product['description']; ?></textarea>
            </div>

            <div class="form-group">
                <label>Change Image (Leave blank to keep current)</label>
                <input type="file" name="image" style="padding: 10px; background: transparent; border: 1px dashed #444;">
            </div>

            <div class="checkbox-group">
                <label class="check-item">
                    <input type="checkbox" name="is_sale" <?php echo ($product['is_sale'] == 1) ? 'checked' : ''; ?>> ON SALE
                </label>
                <label class="check-item">
                    <input type="checkbox" name="is_trending" <?php echo ($product['is_trending'] == 1) ? 'checked' : ''; ?>> TRENDING BESTSELLER
                </label>
            </div>

            <button type="submit" name="update" class="btn-update">
                <i class="fa-solid fa-arrows-rotate"></i> Update Product Details
            </button>
            
            <a href="admin_add_product.php" class="back-link">← Return to Inventory</a>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>