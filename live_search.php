<?php
include('includes/db_connect.php');

if (isset($_GET['q'])) {
    $search = mysqli_real_escape_string($conn, trim($_GET['q']));
    
    // Exit if the search term is empty
    if (empty($search)) {
        exit;
    }

    // Static list of pages (These are also included in the search)
    $site_pages = [
        'about'   => ['title' => 'About Us', 'url' => 'about.php'],
        'contact' => ['title' => 'Contact Us', 'url' => 'contact.php'],
        'service' => ['title' => 'Our Services', 'url' => 'services.php']
    ];

    $pages_html = "";
    // Loop through static pages to find matches
    foreach ($site_pages as $key => $page) {
        if (strpos(strtolower($page['title']), strtolower($search)) !== false || strpos($key, strtolower($search)) !== false) {
            $pages_html .= "<a href='{$page['url']}' class='live-result-item'>
                                <div style='width: 40px; height: 40px; background:#222; border-radius:5px; display:flex; align-items:center; justify-content:center; color:#d4af37;'>
                                    <i class='fa-regular fa-file-lines'></i>
                                </div>
                                <div class='live-result-info'>
                                    <h4>{$page['title']}</h4>
                                    <p style='color: #888; font-weight: normal;'>Page</p>
                                </div>
                            </a>";
        }
    }

    // Search within the Products Database (Limited to 5 for a clean UI)
    $products_html = "";
    $sql = "SELECT * FROM products WHERE name LIKE '%$search%' OR category LIKE '%$search%' LIMIT 5";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $price = number_format($row['price'], 2);
            // Clicking the product redirects to the Shop page with the specific item filtered
            $products_html .= "<a href='shop.php?search=" . urlencode($row['name']) . "' class='live-result-item'>
                                   <img src='{$row['image_url']}' alt='Product Image'>
                                   <div class='live-result-info'>
                                       <h4>{$row['name']}</h4>
                                       <p>Rs. {$price}</p>
                                   </div>
                               </a>";
        }
    }

    // If no results are found in either pages or products
    if (empty($pages_html) && empty($products_html)) {
        echo "<p style='color:#888; font-size:0.85rem; text-align:center; padding: 20px 0;'>No results found for \"{$search}\"</p>";
        exit;
    }

    // Output the results as HTML blocks
    if (!empty($pages_html)) {
        echo "<div class='result-section-title'>Pages</div>";
        echo $pages_html;
    }

    if (!empty($products_html)) {
        echo "<div class='result-section-title'>Products</div>";
        echo $products_html;
    }
}
?>