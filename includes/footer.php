<!-- External stylesheet for footer styling -->
<link rel="stylesheet" href="footer.css">

<!-- Main Footer Section -->
<footer class="main-footer">
    <div class="footer-container">
        
        <!-- Brand Information Column -->
        <div class="footer-col brand-col">
            <!-- Company Logo -->
            <img src="assets/images/logo.png" alt="Ceylora Signature" width="180px" class="footer-logo-img">
            
            <!-- Brand Tagline -->
            <p class="bio">
                The Crown of Luxury Scents<br>
                <span style="font-size: 0.9rem; color: #a18128;">සුවඳ ලොව රජ කිරුළ</span>
            </p>

            <!-- Social Media Links -->
            <div class="social-icons">
                <a href="https://www.facebook.com/profile.php?id=61582109575753&mibextid=ZbWKwL" target="_blank" class="social-fb">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
                <a href="https://www.instagram.com/breatheluxury.ceylora?igsh=MWd4MGdhcXYzaWJoOA==" target="_blank" class="social-ig">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                <a href="https://www.tiktok.com/@ceylora0?_r=1&_t=ZS-951zKha29ZQ" target="_blank" class="social-tt">
                    <i class="fa-brands fa-tiktok"></i>
                </a>
                <a href="https://wa.me/94710674221" target="_blank" class="social-wa">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
            </div>
        </div>

        <!-- Newsletter Subscription Column -->
        <div class="footer-col newsletter-col">
            <h3>STAY CONNECTED</h3>
            <p>Join our elite circle for exclusive fragrance updates.</p>

            <!-- Newsletter Form -->
            <form action="#" method="POST" class="mini-newsletter">
                <input type="email" placeholder="Your Email Address" required>
                <button type="submit">JOIN</button>
            </form>
        </div>

        <!-- Quick Navigation Links -->
        <div class="footer-col links-col">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop Collection</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <!-- <li><a href="panel_adma9xKpL2_admin_login.php">Admin Portal</a></li> -->
            </ul>
        </div>

        <!-- Customer Support Links -->
        <div class="footer-col links-col">
            <h3>Customer Care</h3>
            <ul>
                <!-- Modal trigger links -->
                <li><a href="#" onclick="openModal('shippingModal'); return false;">Shipping Policy</a></li>
                <li><a href="#" onclick="openModal('returnModal'); return false;">Return & Refund Policy</a></li>
                <li><a href="#">Privacy Policy</a></li>
            </ul>
        </div>

        <!-- Contact Information -->
        <div class="footer-col contact-col">
            <h3>Contact Us</h3>
            <p><i class="fa-solid fa-location-dot"></i> Negombo Road, Dambadeniya, Sri Lanka</p>
            <p><i class="fa-solid fa-phone"></i> +94 71 06 74 22 1</p>
            
            <!-- Payment Methods -->
            <div class="payment-icons">
                <i class="fa-brands fa-cc-visa"></i>
                <i class="fa-brands fa-cc-mastercard"></i>
                <span class="cod-badge">COD Available</span>
            </div>
        </div>
    </div>

    <!-- Footer Bottom Section -->
    <div class="footer-bottom">
        &copy; <?php echo date("Y"); ?> CEYLORA Signature | Designed for Vidushan
    </div>
</footer>

<!-- Shipping Policy Modal -->
<div id="shippingModal" class="modal">
    <div class="modal-content">
        <!-- Close Button -->
        <span class="close-btn" onclick="closeModal('shippingModal')">&times;</span>

        <h2 class="gold-text">Shipping Policy</h2>

        <!-- Shipping Details -->
        <p>At <strong>Ceylora Signature</strong>, we ensure your luxury scents reach you safely and quickly.</p>
        <ul style="text-align: left; margin-top: 20px; color: #ccc;">
            <li style="margin-bottom: 10px;"><strong>Processing Time:</strong> Orders are processed within 24 hours.</li>
            <li style="margin-bottom: 10px;"><strong>Colombo & Suburbs:</strong> Delivery within 1–2 Working Days.</li>
            <li style="margin-bottom: 10px;"><strong>Island-wide Delivery:</strong> Delivery within 3–5 Working Days.</li>
        </ul>

        <!-- Local Language Message -->
        <p style="color: #d4af37; margin-top: 20px; font-weight: bold;">
            "ඔබේ ඇණවුම තහවුරු කළ පසු හැකි ඉක්මනින් එය ඔබ වෙත ආරක්ෂිතව එවීමට අප බැඳී සිටිමු."
        </p>
    </div>
</div>

<!-- Return & Refund Policy Modal -->
<div id="returnModal" class="modal">
    <div class="modal-content">
        <!-- Close Button -->
        <span class="close-btn" onclick="closeModal('returnModal')">&times;</span>

        <h2 class="gold-text">Return & Refund Policy</h2>

        <!-- Return Policy Details -->
        <p>Your satisfaction is our priority. If you receive a damaged product, we’ve got you covered!</p>
        <ul style="text-align: left; margin-top: 20px; color: #ccc;">
            <li style="margin-bottom: 10px;"><strong>Damaged on Arrival:</strong> If the bottle is broken or leaking, please contact us within 7 days.</li>
            <li style="margin-bottom: 10px;"><strong>Replacement:</strong> We will send you a brand new replacement free of charge.</li>
        </ul>

        <!-- Local Language Message -->
        <p style="color: #d4af37; margin-top: 20px; font-weight: bold;">
            "භාණ්ඩය ලැබී දින 7ක් ඇතුළත අපව දැනුවත් කළහොත්, කිසිදු අමතර ගාස්තුවකින් තොරව අලුත් භාණ්ඩයක් ඔබට ලැබෙනු ඇත."
        </p>
    </div>
</div>

<!-- Modal Handling Script -->
<script>
    // Open modal by setting display to flex
    function openModal(modalId) {
        document.getElementById(modalId).style.display = "flex";
    }

    // Close modal by hiding it
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }

    // Close modal when clicking outside the content area
    window.onclick = function(event) {
        if (event.target.className === "modal") {
            event.target.style.display = "none";
        }
    }
</script>