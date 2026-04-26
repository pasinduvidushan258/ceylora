<?php 
// 1. Connect to the database and include the header
require_once 'includes/config.php';
require_once 'includes/db_connect.php';

include('includes/header.php'); 
?>



<header class="hero">
    <div class="hero-content">
        <p class="gold-text uppercase tracking-widest">Special Offer</p>
        <h1><span class="gold-text">15% Off</span> Premium Personalized Gifting</h1>
        <p class="sub-text">Premium, alcohol-free home fragrances diffuser in Sri Lanka.</p>
        <a href="shop.php" class="btn-gold">SHOP THE SALE</a>
    </div>
</header>

<div class="tagline-image-wrapper">
    <div class="image-frame">
        <img src="assets/images/සුවද ලොව රස කිරුළ.jpg" alt="සුවද ලොව රස කිරුළ" class="tagline-image">
    </div>
</div>

<?php
// 2. Fetch only the products marked as 'Trending' from the database
// The LIMIT 10 restricts the number of items shown (can be adjusted or removed if needed)
$sql = "SELECT * FROM products WHERE is_trending = 1 LIMIT 10";
$result = $conn->query($sql);
?>

<section class="bestsellers-section" id="shop">
    <div class="section-title-box" style="text-align: center; margin-bottom: 50px;">
        <span style="color: #666; text-transform: uppercase; letter-spacing: 3px; font-size: 0.8rem;">Curated Selection</span>
        <h2 style="font-family: 'Playfair Display', serif; font-size: 3rem; margin-top: 10px;">Trending <span class="gold-text">Bestsellers</span></h2>
    </div>

    <div class="product-grid">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
        ?>
            <div class="product-card">
                <div style="position: absolute; top: 15px; left: 15px; z-index: 5; display: flex; flex-direction: column; gap: 5px;">
                    <?php if($row['is_trending'] == 1): ?>
                        <div class="product-badge" style="background: #d4af37; color: #000; padding: 5px 12px; border-radius: 50px; font-size: 0.65rem; font-weight: 800; letter-spacing: 1px;">TRENDING</div>
                    <?php endif; ?>
                    
                    <?php if($row['is_sale'] == 1): ?>
                        <div class="product-badge" style="background: #ff4444; color: #fff; padding: 5px 12px; border-radius: 50px; font-size: 0.65rem; font-weight: 800; letter-spacing: 1px;">SALE</div>
                    <?php endif; ?>
                </div>
                
                <div class="img-container">
                    <img src="<?php echo $row['image_url']; ?>" alt="Product">
                </div>

                <div class="product-info">
                    <small class="category-text" style="color: #666; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 1px;"><?php echo $row['category']; ?></small>
                    <h3><?php echo $row['name']; ?></h3>
                    <p class="price-text">Rs. <?php echo number_format($row['price'], 2); ?></p>
                    
                    <form action="cart.php" method="POST">
                         <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                         <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
                         <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
                         <button type="submit" name="add_to_cart" class="btn-cart-sm">
                             <i class="fa-solid fa-cart-plus"></i> ADD TO CART
                         </button>
                    </form>
                </div>
            </div>
        <?php
            }
        } else {
            // Display this message if no trending products are found
            echo "<div style='grid-column: 1/-1; text-align: center; padding: 50px; color: #555;'>No trending products selected. Visit admin panel to mark bestsellers!</div>";
        }
        ?>
    </div>
</section>

<?php 
// 3. Include the Footer
include('includes/footer.php'); 
?>