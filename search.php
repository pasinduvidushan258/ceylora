<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('includes/db_connect.php');
include('includes/header.php');

$search_keyword = "";
$products_found = false;
$pages_found = [];

// List of main static pages on the site
$site_pages = [
    'about'    => ['title' => 'About Us', 'url' => 'about.php', 'desc' => 'Learn more about Ceylora Signature and our luxury fragrances.'],
    'contact'  => ['title' => 'Contact Us', 'url' => 'contact.php', 'desc' => 'Get in touch with us for any inquiries.'],
    'service'  => ['title' => 'Our Services', 'url' => 'services.php', 'desc' => 'Explore the exclusive services we offer.'],
    'shipping' => ['title' => 'Shipping Policy', 'url' => '#', 'onclick' => "openModal('shippingModal')", 'desc' => 'Information about our delivery and shipping processes.'],
    'return'   => ['title' => 'Return & Refund Policy', 'url' => '#', 'onclick' => "openModal('returnModal')", 'desc' => 'Details about returns and refunds.'],
    'home'     => ['title' => 'Home Page', 'url' => 'index.php', 'desc' => 'Return to the Ceylora main page.']
];

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_keyword = strtolower(trim($_GET['search']));
    $safe_search = mysqli_real_escape_string($conn, $search_keyword);

    // 1. Search within static pages
    foreach ($site_pages as $key => $page) {
        if (strpos(strtolower($page['title']), $search_keyword) !== false || strpos($key, $search_keyword) !== false) {
            $pages_found[] = $page;
        }
    }

    // 2. Search within products in the database
    $sql = "SELECT * FROM products WHERE name LIKE '%$safe_search%' OR category LIKE '%$safe_search%' OR description LIKE '%$safe_search%' ORDER BY id DESC";
    $product_results = $conn->query($sql);
    
    if ($product_results && $product_results->num_rows > 0) {
        $products_found = true;
    }
}
?>

<style>
    .search-results-container { 
        max-width: 1200px; 
        margin: 60px auto; 
        padding: 0 20px; 
        color: #fff; 
        min-height: 50vh; 
    }
    
    .luxury-title { 
        color: #d4af37; 
        font-family: 'Playfair Display', serif; 
        font-size: 2.2rem; 
        margin-bottom: 40px; 
        text-transform: uppercase; 
        letter-spacing: 2px; 
        text-align: center; 
    }
    
    .section-title { 
        color: #fff; 
        font-family: 'Playfair Display', serif; 
        font-size: 1.5rem; 
        border-bottom: 1px solid #333; 
        padding-bottom: 10px; 
        margin-bottom: 25px; 
        margin-top: 40px; 
    }
    
    /* Page Results Style */
    .page-result-card { 
        background: #111; 
        border: 1px solid #222; 
        border-left: 3px solid #d4af37; 
        padding: 20px; 
        margin-bottom: 15px; 
        border-radius: 8px; 
        transition: 0.3s; 
    }
    
    .page-result-card:hover { 
        border-color: #d4af37; 
        transform: translateX(5px); 
    }
    
    .page-result-card h3 { 
        color: #d4af37; 
        margin-bottom: 8px; 
        font-size: 1.2rem; 
    }
    
    .page-result-card p { 
        color: #888; 
        font-size: 0.9rem; 
        margin-bottom: 15px; 
    }
    
    .page-result-card a { 
        color: #000; 
        background: #d4af37; 
        padding: 8px 15px; 
        text-decoration: none; 
        border-radius: 5px; 
        font-weight: bold; 
        font-size: 0.8rem; 
        text-transform: uppercase; 
    }
    
    .page-result-card a:hover { 
        background: #fff; 
    }

    /* Product Grid Style (Similar to the Shop page) */
    .product-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); 
        gap: 30px; 
    }
    
    .product-card { 
        background: #0a0a0a; 
        border: 1px solid #222; 
        border-radius: 15px; 
        padding: 20px; 
        text-align: center; 
        transition: 0.3s; 
    }
    
    .product-card:hover { 
        border-color: #d4af37; 
        transform: translateY(-5px); 
        box-shadow: 0 10px 20px rgba(212, 175, 55, 0.1); 
    }
    
    .product-card img { 
        width: 100%; 
        height: 250px; 
        object-fit: cover; 
        border-radius: 10px; 
        margin-bottom: 15px; 
    }
    
    .product-card h3 { 
        color: #fff; 
        font-size: 1.1rem; 
        margin-bottom: 10px; 
    }
    
    .product-card .price { 
        color: #d4af37; 
        font-weight: bold; 
        margin-bottom: 15px; 
    }
    
    .btn-cart { 
        display: inline-block; 
        width: 100%; 
        padding: 12px; 
        background: transparent; 
        color: #d4af37; 
        border: 1px solid #d4af37; 
        border-radius: 5px; 
        text-decoration: none; 
        font-weight: bold; 
        transition: 0.3s; 
    }
    
    .btn-cart:hover { 
        background: #d4af37; 
        color: #000; 
    }

    .no-results { 
        text-align: center; 
        padding: 50px 20px; 
        background: #111; 
        border-radius: 15px; 
        border: 1px dashed #444; 
    }
    
    .no-results i { 
        font-size: 3rem; 
        color: #444; 
        margin-bottom: 15px; 
    }
    
    .no-results p { 
        color: #888; 
        font-size: 1.1rem; 
    }
</style>

<div class="search-results-container">
    <h2 class="luxury-title">
        <?php if(!empty($search_keyword)): ?>
            Search Results for: <span style="color: #fff;">"<?php echo htmlspecialchars($_GET['search']); ?>"</span>
        <?php else: ?>
            Search Our Store
        <?php endif; ?>
    </h2>

    <?php if(!empty($search_keyword)): ?>
        
        <?php if(count($pages_found) > 0): ?>
            <h3 class="section-title">Pages Found</h3>
            <div class="page-results">
                <?php foreach($pages_found as $page): ?>
                    <div class="page-result-card">
                        <h3><?php echo $page['title']; ?></h3>
                        <p><?php echo $page['desc']; ?></p>
                        <?php if(isset($page['onclick'])): ?>
                            <a href="<?php echo $page['url']; ?>" onclick="<?php echo $page['onclick']; ?>; return false;">View Detail</a>
                        <?php else: ?>
                            <a href="<?php echo $page['url']; ?>">Visit Page</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if($products_found): ?>
            <h3 class="section-title">Products Found</h3>
            <div class="product-grid">
                <?php while($row = $product_results->fetch_assoc()): ?>
                    <div class="product-card">
                        <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>">
                        <h3><?php echo $row['name']; ?></h3>
                        <p class="price">Rs. <?php echo number_format($row['price'], 2); ?></p>
                        <a href="shop.php" class="btn-cart">View in Shop</a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <?php if(count($pages_found) == 0 && !$products_found): ?>
            <div class="no-results">
                <i class="fa-solid fa-magnifying-glass-minus"></i>
                <h3 style="color: #d4af37; margin-bottom: 10px;">No Results Found</h3>
                <p>We couldn't find anything matching "<?php echo htmlspecialchars($_GET['search']); ?>". <br>Try checking your spelling or use different keywords.</p>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="no-results">
            <i class="fa-solid fa-magnifying-glass"></i>
            <p>Type something in the search bar above to find products and pages.</p>
        </div>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>