<?php 
require_once 'includes/config.php';
require_once 'includes/db_connect.php';
include 'includes/header.php'; 
 

$selected_cat = isset($_GET['cat']) ? mysqli_real_escape_string($conn, $_GET['cat']) : '';
$search_term = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
?>

<style>
    .shop-container { 
        max-width: 95%; 
        margin: 40px auto; 
        padding: 0 40px; 
    }
    
    .shop-header { 
        text-align: center; 
        margin-bottom: 30px; 
    }
    
    .shop-header h1 { 
        font-family: 'Playfair Display', serif; 
        font-size: 2.5rem; 
        color: #d4af37; 
        margin-bottom: 10px; 
    }
    
    .shop-header p { 
        color: #888; 
        font-size: 1rem; 
    }

    /* Category Filter */
    .category-filter-wrapper {
        display: flex; 
        justify-content: center; 
        flex-wrap: wrap; 
        gap: 15px;
        margin: 0 auto 50px auto; 
        padding: 10px 0; 
        max-width: 100%; 
        overflow-x: auto; 
        scrollbar-width: none; 
        -ms-overflow-style: none; 
    }
    
    .category-filter-wrapper::-webkit-scrollbar { 
        display: none; 
    }

    .cat-btn {
        padding: 10px 25px; 
        background: rgba(90, 88, 82, 0.05); 
        color: #ccc;
        text-decoration: none; 
        border-radius: 30px; 
        border: 1px solid #333;
        font-size: 0.9rem; 
        font-weight: 500; 
        transition: 0.3s; 
        white-space: nowrap; 
        flex-shrink: 0; 
    }
    
    .cat-btn:hover, 
    .cat-btn.active { 
        background: #d4af37; 
        color: #000; 
        border-color: #d4af37; 
        font-weight: bold; 
    }

    /* Product Grid */
    .product-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); 
        gap: 24px; 
        align-items: stretch;
    }
    
    .product-card { 
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        width: 100%; 
        max-width: 320px;
        margin: 0 auto;
        min-height: 100%;
        background: #111; 
        border-radius: 15px; 
        overflow: hidden; 
        border: 1px solid #222; 
        transition: transform 0.4s, border-color 0.4s, box-shadow 0.4s; 
        text-align: center; 
        position: relative; 
    }
    
    .product-card:hover { 
        transform: translateY(-10px); 
        border-color: #d4af37; 
        box-shadow: 0 12px 30px rgba(0,0,0,0.45); 
    }
    
    .product-image { 
        position: relative; 
        height: 280px; 
        background: #000; 
        overflow: hidden; 
    }
    
    .product-image img { 
        width: 100%; 
        height: 100%; 
        object-fit: cover; 
        transition: transform 0.5s ease;
    }
    
    .product-card:hover .product-image img { 
        transform: scale(1.08); 
    }

    .card-overlay { 
        position: absolute; 
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0; 
        transition: opacity 0.3s ease; 
        pointer-events: none;
        background: rgba(0,0,0,0.35);
    }
    
    .product-card:hover .card-overlay { 
        opacity: 1; 
    }
    
    .view-btn { 
        cursor: pointer; 
        color: #fff; 
        text-decoration: none; 
        font-size: 0.85rem; 
        background: rgba(0,0,0,0.7); 
        padding: 8px 20px; 
        border-radius: 20px; 
        border: 1px solid #d4af37; 
        transition: background 0.3s, color 0.3s; 
        pointer-events: auto;
    }
    
    .view-btn:hover { 
        background: #d4af37; 
        color: #000; 
    }

    .product-info { 
        padding: 24px; 
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 18px;
        flex: 1;
    }
    
    .product-info h3 { 
        font-family: 'Playfair Display', serif; 
        font-size: 1.35rem; 
        color: #fff; 
        margin-bottom: 5px; 
        line-height: 1.2;
    }
    
    .product-info .cat-name { 
        color: #888; 
        font-size: 0.82rem; 
        text-transform: uppercase; 
        margin-bottom: 10px; 
        letter-spacing: 1px;
    }
    
    .price { 
        color: #d4af37; 
        font-weight: 700; 
        font-size: 1.2rem; 
        margin-bottom: 15px; 
    }

    .add-to-cart-btn { 
        width: 100%; 
        padding: 14px; 
        background: transparent; 
        color: #d4af37; 
        border: 1px solid #d4af37; 
        border-radius: 10px; 
        cursor: pointer; 
        font-weight: 600; 
        transition: 0.3s; 
    }
    
    .add-to-cart-btn:hover { 
        background: #d4af37; 
        color: #000; 
    }

    @media (max-width: 1024px) {
        .product-grid {
            gap: 22px;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }
        .product-card {
            max-width: 300px;
        }
        .product-image {
            height: 260px;
        }
    }

    @media (max-width: 768px) {
        .shop-container {
            padding: 0 24px;
        }
        .product-grid {
            gap: 18px;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
        .product-card {
            max-width: 280px;
        }
        .product-image {
            height: 240px;
        }
        .product-info {
            padding: 20px;
            gap: 14px;
        }
        .shop-header h1 {
            font-size: 2.2rem;
        }
    }

    @media (max-width: 520px) {
        .category-filter-wrapper {
            gap: 10px;
            padding: 8px 0;
        }
        .cat-btn {
            padding: 9px 16px;
            font-size: 0.82rem;
        }
        .product-grid {
            gap: 16px;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        }
        .product-card {
            max-width: 240px;
        }
        .product-image {
            height: 220px;
        }
        .product-info h3 {
            font-size: 1.15rem;
        }
        .product-info .cat-name {
            font-size: 0.78rem;
        }
        .price {
            font-size: 1.05rem;
        }
        .view-btn {
            font-size: 0.8rem;
            padding: 7px 16px;
        }
        .add-to-cart-btn {
            padding: 12px;
        }
    }

    .no-products { 
        color: #888; 
        font-size: 1.1rem; 
        margin-top: 30px; 
        text-align: center; 
        grid-column: 1/-1; 
    }

    /* Highlight CSS for Search */
    .highlight-product { 
        border: 2px solid #d4af37 !important; 
        box-shadow: 0 0 35px rgba(212, 175, 55, 0.9) !important; 
        transform: scale(1.05) !important; 
        transition: all 0.5s ease-in-out; 
        z-index: 10; 
    }

    /* =========================================
       Quick View Modal (Dark Popup Box) Design
       ========================================= */
    .quick-view-modal {
        display: none; 
        position: fixed; 
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 100%;
        background: rgba(0, 0, 0, 0.85); /* Darkens the background */
        z-index: 9999; 
        justify-content: center; 
        align-items: center;
        backdrop-filter: blur(5px); 
        opacity: 0; 
        transition: opacity 0.3s ease;
    }
    
    .quick-view-modal.active { 
        display: flex; 
        opacity: 1; 
    }

    .modal-box {
        background: #0f0f0f; 
        border: 1px solid #d4af37; 
        border-radius: 15px; 
        width: 90%; 
        max-width: 1200px;
        max-height: 90vh; 
        overflow-y: auto; 
        display: flex; 
        flex-wrap: wrap; 
        position: relative;
        box-shadow: 0 20px 50px rgba(0,0,0,0.9); 
        transform: scale(0.9); 
        transition: transform 0.3s ease;
    }
    
    .quick-view-modal.active .modal-box { 
        transform: scale(1); 
    }

    .modal-close-btn { 
        position: absolute; 
        top: 15px; 
        right: 20px; 
        font-size: 2rem; 
        color: #888; 
        cursor: pointer; 
        z-index: 10; 
        transition: 0.3s; 
    }
    
    .modal-close-btn:hover { 
        color: #ff4444; 
        transform: rotate(90deg); 
    }

    /* Left Side: Product Image Box */
    .modal-image-sec { 
        flex: 1; 
        min-width: 300px; 
        background: transparent; 
        display: flex; 
        justify-content: center; /* Center horizontally */
        align-items: center; /* Center vertically */
        padding: 30px; 
        border-right: 1px solid #222; 
    }

    /* Image Sizing within the Modal */
    .modal-image-sec img { 
        width: 100%; 
        max-width: 500px; 
        max-height: 500px; 
        height: auto; 
        object-fit: contain; 
        border-radius: 8px; 
        box-shadow: 0 10px 25px rgba(0,0,0,0.5); /* Shadow to make the image pop */
    }
    
    .modal-info-sec { 
        flex: 1.2; 
        min-width: 300px; 
        padding: 40px; 
        display: flex; 
        flex-direction: column; 
        justify-content: flex-start; 
    }
    
    .modal-cat { 
        color: #888; 
        font-size: 0.8rem; 
        text-transform: uppercase; 
        letter-spacing: 2px; 
        margin-bottom: 5px; 
    }
    
    .modal-title { 
        color: #d4af37; 
        font-family: 'Playfair Display', serif; 
        font-size: 2rem; 
        margin-bottom: 10px; 
        line-height: 1.2; 
    }
    
    .modal-price { 
        color: #fff; 
        font-size: 1.5rem; 
        font-weight: bold; 
        margin-bottom: 20px; 
        padding-bottom: 15px; 
        border-bottom: 1px solid #222; 
    }
    
    /* Description text formatting */
    .modal-desc { 
        color: #bbb; 
        font-size: 0.95rem; 
        line-height: 1.5; 
        margin-bottom: 25px; 
        white-space: pre-wrap; 
    }

    .modal-action { 
        display: flex; 
        gap: 15px; 
        margin-top: auto; 
    }
    
    .modal-qty { 
        background: transparent; 
        border: 1px solid #444; 
        color: #fff; 
        padding: 10px; 
        width: 70px; 
        text-align: center; 
        border-radius: 5px; 
        font-size: 1rem; 
        outline: none; 
    }
    
    .modal-add-btn { 
        flex: 1; 
        background: #d4af37; 
        color: #000; 
        border: none; 
        font-weight: bold; 
        text-transform: uppercase; 
        border-radius: 5px; 
        cursor: pointer; 
        transition: 0.3s; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        gap: 10px; 
    }
    
    .modal-add-btn:hover { 
        background: #fff; 
    }
    
    .modal-box::-webkit-scrollbar { 
        width: 6px; 
    }
    
    .modal-box::-webkit-scrollbar-thumb { 
        background: #d4af37; 
        border-radius: 10px; 
    }

    @media (max-width: 768px) { 
        .modal-image-sec { 
            border-right: none; 
            border-bottom: 1px solid #222; 
            padding: 20px; 
            min-width: auto; 
        } 
        .modal-info-sec { 
            min-width: auto; 
            padding: 20px; 
        } 
        .modal-box { 
            width: min(95vw, 1000px); 
            max-height: 90vh; 
            overflow: auto; 
        } 
        .modal-title { font-size: 1.7rem; } 
        .modal-price { font-size: 1.3rem; }
        .modal-image-sec img { max-height: 60vh; }
    }
</style>

<main class="shop-container">
    <div class="shop-header">
        <h1>Our Exclusive Collection</h1>
        <p>Premium, alcohol-free luxury fragrances crafted for you.</p>
    </div>

    <div class="category-filter-wrapper">
        <a href="shop.php" class="cat-btn <?php echo ($selected_cat == '') ? 'active' : ''; ?>">All Collection</a>
        <?php
        $cat_query = "SELECT * FROM categories ORDER BY name ASC";
        $cat_result = mysqli_query($conn, $cat_query);
        if ($cat_result && mysqli_num_rows($cat_result) > 0) {
            while($cat_row = mysqli_fetch_assoc($cat_result)) {
                $c_name = $cat_row['name'];
                $active_class = ($selected_cat == $c_name) ? 'active' : '';
                echo "<a href='shop.php?cat=$c_name' class='cat-btn $active_class'>$c_name</a>";
            }
        }
        ?>
    </div>

    <div class="product-grid">
        <?php
        if ($selected_cat != '') {
            $sql = "SELECT * FROM products WHERE category = '$selected_cat' ORDER BY id DESC";
        } else {
            $sql = "SELECT * FROM products ORDER BY id DESC";
        }
        
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                
                $highlight_class = "";
                $hide_style = ""; 

                if ($search_term != "") {
                    if (strtolower($row['name']) == $search_term) {
                        $highlight_class = "highlight-product searched-item"; 
                    } else {
                        $hide_style = "display: none;";
                    }
                }
                ?>
                
                <div class="product-card <?php echo $highlight_class; ?>" style="<?php echo $hide_style; ?>">
                    <div class="product-image">
                        <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>">
                        <div class="card-overlay">
                            <button type="button" class="view-btn" 
                                data-id="<?php echo $row['id']; ?>"
                                data-name="<?php echo htmlspecialchars($row['name']); ?>"
                                data-cat="<?php echo htmlspecialchars($row['category']); ?>"
                                data-price="<?php echo number_format($row['price'], 2); ?>"
                                data-rawprice="<?php echo $row['price']; ?>"
                                data-image="<?php echo $row['image_url']; ?>"
                                data-desc="<?php echo htmlspecialchars($row['description']); ?>"
                                onclick="openPopup(this)">
                                View Details
                            </button>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3><?php echo $row['name']; ?></h3>
                        <div class="cat-name"><?php echo isset($row['category']) ? $row['category'] : ''; ?></div>
                        <p class="price">Rs. <?php echo number_format($row['price'], 2); ?></p>
                        
                        <form action="cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
                            <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
                            <button type="submit" name="add_to_cart" class="add-to-cart-btn">
                                <i class="fa-solid fa-cart-plus"></i> Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<div class='no-products'><p>No products found.</p></div>";
        }
        ?>
    </div>
</main>

<div id="quickViewModal" class="quick-view-modal">
    <div class="modal-box">
        <span class="modal-close-btn" onclick="closePopup()">&times;</span>
        
        <div class="modal-image-sec">
            <img id="qv-image" src="" alt="Product">
        </div>

        <div class="modal-info-sec">
            <span id="qv-category" class="modal-cat"></span>
            <h2 id="qv-title" class="modal-title"></h2>
            <div id="qv-price" class="modal-price"></div>
            
            <div id="qv-desc" class="modal-desc"></div>

            <form action="cart.php" method="POST" class="modal-action">
                <input type="hidden" id="qv-id" name="product_id" value="">
                <input type="hidden" id="qv-form-name" name="product_name" value="">
                <input type="hidden" id="qv-form-price" name="product_price" value="">
                
                <input type="number" name="quantity" value="1" min="1" class="modal-qty">
                <button type="submit" name="add_to_cart" class="modal-add-btn">
                    <i class="fa-solid fa-cart-shopping"></i> Add to Cart
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // 1. Opens the Quick View Modal and populates data when "View Details" is clicked
    function openPopup(button) {
        document.getElementById('qv-id').value = button.getAttribute('data-id');
        document.getElementById('qv-title').innerText = button.getAttribute('data-name');
        document.getElementById('qv-form-name').value = button.getAttribute('data-name');
        document.getElementById('qv-category').innerText = button.getAttribute('data-cat');
        document.getElementById('qv-price').innerText = "Rs. " + button.getAttribute('data-price');
        document.getElementById('qv-form-price').value = button.getAttribute('data-rawprice');
        document.getElementById('qv-image').src = button.getAttribute('data-image');
        
        // Preserve line breaks in the description
        document.getElementById('qv-desc').innerHTML = button.getAttribute('data-desc').replace(/\n/g, "<br>");

        document.getElementById('quickViewModal').classList.add('active');
    }

    // 2. Closes the Quick View Modal
    function closePopup() {
        document.getElementById('quickViewModal').classList.remove('active');
    }

    // 3. Closes the modal if clicked outside (dark area) AND removes search highlights
    window.addEventListener('click', function(e) {
        let modal = document.getElementById('quickViewModal');
        let searchedItem = document.querySelector('.searched-item');

        // A) Close modal if clicked outside the content box
        if (e.target === modal) {
            closePopup();
        }

        // B) If a searched item is highlighted, clicking anywhere else will reveal all other products
        if (searchedItem && !searchedItem.contains(e.target) && !modal.contains(e.target) && !e.target.classList.contains('view-btn')) {
            searchedItem.classList.remove('highlight-product', 'searched-item');
            
            let allProducts = document.querySelectorAll('.product-card');
            allProducts.forEach(function(item) {
                item.style.display = 'block'; 
                item.animate([{opacity: 0}, {opacity: 1}], {duration: 600, fill: 'forwards'});
            });
            
            // Clean the URL by removing the search parameter
            const url = new URL(window.location);
            url.searchParams.delete('search');
            window.history.replaceState({}, document.title, url);
        }
    });
</script>

<?php include 'includes/footer.php'; ?>