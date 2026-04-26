<?php 
// Ensure session is started before accessing session variables
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEYLORA | Luxury Fragrances</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@300;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    
    <?php 
    // Dynamically load page-specific styles based on current file
    $current_page = basename($_SERVER['PHP_SELF']);
    if($current_page == 'checkout.php'): ?>
        <link rel="stylesheet" href="assets/css/checkout.css">
    <?php elseif($current_page == 'cart.php'): ?>
        <link rel="stylesheet" href="assets/css/cart.css">
    <?php elseif($current_page == 'index.php'): ?>
        <link rel="stylesheet" href="assets/css/tagline-section.css">
    <?php endif; ?>
    
    <style>
        /* Container for user avatar and dropdown */
        .user-menu-container {
            position: relative;
            display: inline-block;
        }

        /* User avatar styling */
        .user-avatar {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 38px !important;
            height: 38px !important;
            background-color: #d4af37 !important;
            color: #000 !important;
            border-radius: 50% !important;
            font-weight: 700 !important;
            font-size: 1.2rem !important;
            text-decoration: none !important;
            cursor: pointer;
            transition: all 0.3s ease !important;
            margin: 0 15px !important;
        }
        
        /* Hover effect for avatar */
        .user-avatar:hover {
            transform: scale(1.1) !important;
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.5) !important;
        }
        
        /* Profile dropdown container */
        .profile-dropdown {
            display: none; 
            position: absolute;
            top: 55px;
            right: 0;
            background-color: #1a1a1a;
            min-width: 250px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
            border: 1px solid #333;
            z-index: 1000;
            overflow: hidden;
            animation: fadeInDown 0.3s ease;
        }
        
        /* Visible state for dropdown */
        .profile-dropdown.show {
            display: block;
        }

        /* Dropdown animation */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Header section inside dropdown */
        .dropdown-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #333;
        }

        /* Large avatar inside dropdown */
        .dropdown-header .big-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #d4af37;
            color: #000;
            font-size: 2rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
        }

        /* User name styling */
        .dropdown-header h4 {
            color: #fff;
            margin: 0 0 5px;
            font-family: 'Montserrat', sans-serif;
            font-size: 1.1rem;
        }

        /* User email styling */
        .dropdown-header p {
            color: #888;
            font-size: 0.85rem;
            margin: 0;
            word-break: break-all;
        }

        /* Navigation links inside dropdown */
        .dropdown-links {
            padding: 10px 0;
        }

        .dropdown-links a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #ccc;
            text-decoration: none;
            font-size: 0.95rem;
            transition: 0.2s;
        }

        /* Icon styling inside dropdown links */
        .dropdown-links a i {
            margin-right: 15px;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        /* Hover effect for dropdown links */
        .dropdown-links a:hover {
            background-color: #2a2a2a;
            color: #d4af37;
        }
        
        /* Logout link hover override */
        .dropdown-links a.logout-link:hover {
            color: #ff4444;
        }
    </style>
</head>
<body>

<header class="main-header">
    <div class="header-container">
        
        <div class="logo">
            <a href="index.php">
                <img src="assets/images/logo.png" alt="CEYLORA Logo">
            </a>
        </div>

        <nav class="nav-bar" id="navBar">
            <div class="mobile-menu-close" id="menuClose">
                <i class="fa-solid fa-xmark"></i>
            </div>
            
            <ul class="nav-links">
                <li><a href="index.php" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Home</a></li>
                <li><a href="shop.php" class="<?php echo ($current_page == 'shop.php') ? 'active' : ''; ?>">Shop</a></li>

                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <li><a href="admin_orders.php" class="<?php echo ($current_page == 'admin_orders.php') ? 'active' : ''; ?>" style="color: #ff4444; font-weight: 600;">Orders</a></li>
                    <li><a href="admin_add_product.php" class="<?php echo ($current_page == 'admin_add_product.php') ? 'active' : ''; ?>" style="color: #ff4444; font-weight: 600;">Add Product</a></li>
                    <li><a href="admin_users.php" class="<?php echo ($current_page == 'admin_users.php') ? 'active' : ''; ?>" style="color: #ff4444; font-weight: 600;">Customer Management</a></li>
                    <li><a href="admin_change_password.php" class="<?php echo ($current_page == 'admin_change_password.php') ? 'active' : ''; ?>" style="color: #ff4444; font-weight: 600;">Settings</a></li>
                    <li><a href="admin_logout.php" style="color: #ff4444; font-weight: 600;">Logout</a></li>
                <?php else: ?>
                    <li><a href="services.php" class="<?php echo ($current_page == 'services.php') ? 'active' : ''; ?>">Services</a></li>
                    <li><a href="about.php" class="<?php echo ($current_page == 'about.php') ? 'active' : ''; ?>">About</a></li>
                    <li><a href="contact.php" class="<?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>">Contact Us</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        
        <div class="header-icons">

            <a href="#" onclick="toggleSearch(); return false;">
                <i class="fa-solid fa-magnifying-glass"></i>
            </a>
            
            <?php if (isset($_SESSION['user_id'])): 
                // Extract first letter for avatar display
                $first_letter = strtoupper(substr($_SESSION['user_name'], 0, 1));
                $user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : "User Email";
            ?>

                <div class="user-menu-container">
                    <div class="user-avatar" onclick="toggleDropdown()">
                        <?php echo $first_letter; ?>
                    </div>

                    <div id="profileDropdown" class="profile-dropdown">
                        <div class="dropdown-header">
                            <div class="big-avatar"><?php echo $first_letter; ?></div>
                            <h4><?php echo $_SESSION['user_name']; ?></h4>
                            <p><?php echo $user_email; ?></p>
                        </div>

                        <div class="dropdown-links">
                            <a href="user_profile.php"><i class="fa-regular fa-id-badge"></i> My Profile</a>
                            <a href="user_orders.php"><i class="fa-solid fa-box-open"></i> My Orders</a>
                            <a href="change_password.php"><i class="fa-solid fa-lock"></i> Change Password</a>
                            <a href="user_logout.php" class="logout-link"><i class="fa-solid fa-power-off"></i> Logout</a>
                            <div style="border-top: 1px solid #333; margin: 5px 0;"></div>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <a href="login.php"><i class="fa-regular fa-user"></i></a>
            <?php endif; ?>

            <a href="cart.php" class="cart-icon">
                <i class="fa-solid fa-cart-shopping"></i>
                <?php 
                // Display cart item count if available
                $count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                if($count > 0) { 
                    echo '<span class="cart-badge">'.$count.'</span>'; 
                }
                ?>
            </a>

            <div class="mobile-menu-toggle" id="menuToggle">
                <i class="fa-solid fa-bars"></i>
            </div>
        </div>
    </div>
</header>

<div class="mobile-menu-overlay" id="menuOverlay"></div>

<script>
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // ------------------------------------
        // User Profile Dropdown Logic
        // ------------------------------------
        
        // Toggle profile dropdown visibility
        window.toggleDropdown = function() {
            document.getElementById("profileDropdown").classList.toggle("show");
        };

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('.user-avatar') && !event.target.matches('.user-avatar *')) {
                var dropdowns = document.getElementsByClassName("profile-dropdown");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }

        // ------------------------------------
        // Mobile Sidebar Menu Logic
        // ------------------------------------
        
        const menuToggle = document.getElementById('menuToggle');
        const menuClose = document.getElementById('menuClose');
        const navBar = document.getElementById('navBar');
        const menuOverlay = document.getElementById('menuOverlay');

        // Open Menu
        if(menuToggle) {
            menuToggle.addEventListener('click', () => {
                navBar.classList.add('active');
                menuOverlay.classList.add('active');
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            });
        }

        // Close Menu Function
        const closeMobileMenu = () => {
            navBar.classList.remove('active');
            menuOverlay.classList.remove('active');
            document.body.style.overflow = ''; // Restore scrolling
        };

        // Attach Close events
        if(menuClose) menuClose.addEventListener('click', closeMobileMenu);
        if(menuOverlay) menuOverlay.addEventListener('click', closeMobileMenu);

        // Close menu when clicking on navigation links
        const navLinks = document.querySelectorAll('.nav-links a');
        navLinks.forEach(link => {
            link.addEventListener('click', closeMobileMenu);
        });

        // Close menu on window resize (if switching to desktop)
        window.addEventListener('resize', () => {
            if (window.innerWidth > 992) {
                closeMobileMenu();
            }
        });
    });
</script>

<?php include('search_bar.php'); ?>