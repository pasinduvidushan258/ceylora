<?php 
require_once 'includes/config.php';
require_once 'includes/db_connect.php';
include 'includes/header.php'; 


$message = "";


if (isset($_GET['status']) && $_GET['status'] == 'success') {
    $message = "<div class='success-alert' style='background: rgba(46, 204, 113, 0.1); border-color: #2ecc71; color: #2ecc71;'><i class='fa-solid fa-circle-check'></i> Thank you! Your message has been sent successfully. Our team will contact you soon.</div>";
}
?>

<style>
    /* --- Contact Page CSS --- */
    .contact-container {
        max-width: 1200px;
        margin: 60px auto;
        padding: 0 20px;
        color: #fff;
    }

    .contact-hero {
        text-align: center;
        margin-bottom: 60px;
    }
    
    .contact-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: 3.5rem;
        color: #d4af37;
        margin-bottom: 15px;
    }
    
    .contact-hero p {
        color: #aaa;
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Success Alert */
    .success-alert {
        background: rgba(212, 175, 55, 0.1);
        border: 1px solid #d4af37;
        color: #d4af37;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 40px;
        font-weight: 500;
    }
    
    .success-alert i { 
        margin-right: 10px; 
    }

    /* Layout Grid */
    .contact-wrapper {
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 50px;
        margin-bottom: 60px;
    }

    /* Left Side: Info */
    .contact-info {
        background: #111;
        padding: 40px;
        border-radius: 15px;
        border: 1px solid #222;
    }
    
    .contact-info h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
        color: #d4af37;
        margin-bottom: 30px;
    }
    
    .info-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 30px;
    }
    
    .info-icon {
        width: 50px;
        height: 50px;
        background: rgba(212, 175, 55, 0.1);
        color: #d4af37;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-right: 20px;
        flex-shrink: 0;
    }
    
    .info-text h4 {
        color: #fff;
        margin-bottom: 5px;
        font-size: 1.1rem;
    }
    
    .info-text p {
        color: #888;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    /* Right Side: Form */
    .contact-form-box {
        background: #0a0a0a;
        padding: 40px;
        border-radius: 15px;
        border: 1px solid #222;
    }
    
    .contact-form-box h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
        color: #fff;
        margin-bottom: 30px;
    }
    
    .input-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .input-group {
        flex: 1;
    }
    
    .input-group label {
        display: block;
        color: #888;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }
    
    .input-group input, 
    .input-group textarea {
        width: 100%;
        background: #111;
        border: 1px solid #333;
        color: #fff;
        padding: 15px;
        border-radius: 8px;
        outline: none;
        transition: 0.3s;
        box-sizing: border-box;
    }
    
    .input-group input:focus, 
    .input-group textarea:focus {
        border-color: #d4af37;
    }
    
    .submit-btn {
        background: #d4af37;
        color: #000;
        border: none;
        padding: 15px 30px;
        font-size: 1rem;
        font-weight: bold;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.3s;
        width: 100%;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .submit-btn:hover {
        background: #fff;
    }

    /* Map Section */
    .map-section {
        width: 100%;
        aspect-ratio: 16 / 9;
        min-height: 280px;
        border-radius: 15px;
        overflow: hidden;
        border: 1px solid #222;
        margin-bottom: 40px;
    }

    /* Mobile Responsive */
    @media (max-width: 992px) {
        .contact-wrapper { grid-template-columns: 1fr; gap: 20px; }
        .input-row { flex-direction: column; gap: 15px; }
    }

    @media (max-width: 768px) {
        .contact-hero h1 { font-size: 2.8rem; }
        .contact-hero p { font-size: 1rem; }
    }

    @media (max-width: 480px) {
        .contact-container { padding: 0 15px; margin: 40px auto; }
        .contact-info, .contact-form-box { padding: 30px 18px; }
        .info-text p, .input-group input, .input-group textarea { font-size: 0.95rem; }
        .submit-btn { padding: 14px 18px; }
    }

    .map-section iframe {
    width: 100% !important;
    height: 100% !important;
    min-height: 400px;
    display: block;
}
</style>

<main class="contact-container">
    
    <section class="contact-hero">
        <h1>Get in Touch</h1>
        <p>We'd love to hear from you. Whether you have a question about our fragrances, need assistance, or want to explore corporate gifting, our team is ready to help.</p>
    </section>

    <?php echo $message; ?>

    <section class="contact-wrapper">
        
        <div class="contact-info">
            <h3>Contact Information</h3>
            
            <div class="info-item">
                <div class="info-icon"><i class="fa-solid fa-location-dot"></i></div>
                <div class="info-text">
                    <h4>Our Fragrance Studio</h4>
                    <p>Dambadeniya,<br>Negombo Road, Sri Lanka</p>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
                <div class="info-text">
                    <h4>Phone Number</h4>
                    <p>
                        <a href="https://wa.me/94710674221" target="_blank" style="color: #d4af37; text-decoration: none; margin-right: 5px;"><i class="fa-brands fa-whatsapp"></i></a>
                        +94 71 06 74 22 1
                    </p>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon"><i class="fa-solid fa-envelope"></i></div>
                <div class="info-text">
                    <h4>Email Address</h4>
                    <p>officialceylora@gmail.com</p>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon"><i class="fa-solid fa-clock"></i></div>
                <div class="info-text">
                    <h4>Working Hours</h4>
                    <p>Monday - Saturday<br>09:00 AM - 07:00 PM</p>
                </div>
            </div>
        </div>

        <div class="contact-form-box">
            <h3>Send us a Message</h3>
            
            <form action="https://formsubmit.co/officialceylora@gmail.com" method="POST">
                
                <input type="hidden" name="_subject" value="New Inquiry from Ceylora Website">
                <input type="hidden" name="_template" value="table">
                <input type="hidden" name="_next" value="http://ceylorasignature.com/contact.php?status=success">
                <input type="hidden" name="_captcha" value="false">

                <div class="input-row">
                    <div class="input-group">
                        <label>Your Name</label>
                        <input type="text" name="name" required placeholder="John Doe">
                    </div>
                    
                    <div class="input-group">
                        <label>Email Address</label>
                        <input type="email" name="email" required placeholder="john@example.com">
                    </div>
                </div>

                <div class="input-group">
                    <label>Subject</label>
                    <input type="text" name="subject" required placeholder="How can we help you?">
                </div>

                <div class="input-group" style="margin-bottom: 30px;">
                    <label>Message</label>
                    <textarea name="message" rows="5" required placeholder="Write your message here..."></textarea>
                </div>

                <button type="submit" class="submit-btn">Send Message</button>
            </form>
        </div>

    </section>

    <section class="map-section">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1078.753841775928!2d80.1480499194877!3d7.366613103241941!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae321aeaa9bdf7f%3A0x5fa36e8d13719300!2sVidusha%20Book%20Shop!5e0!3m2!1sen!2slk!4v1774633923784!5m2!1sen!2slk" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>

</main>

<?php include 'includes/footer.php'; ?>