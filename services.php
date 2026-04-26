<?php 
include 'includes/header.php'; 
?>

<style>
    /* --- Services Page CSS --- */
    .services-hero {
        text-align: center;
        padding: 80px 20px 50px 20px;
    }
    
    .services-hero h1 {
        color: #d4af37;
        font-family: 'Playfair Display', serif;
        font-size: 3.5rem;
        margin-bottom: 15px;
    }
    
    .services-hero p {
        color: #aaa;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.8;
        font-size: 1.1rem;
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto 80px auto;
        padding: 0 20px;
    }

    .service-card {
        background: #0a0a0a;
        border: 1px solid #222;
        padding: 50px 30px;
        border-radius: 15px;
        text-align: center;
        transition: 0.4s ease;
        position: relative;
        overflow: hidden;
    }

    /* Hover Effect - Lifts the card upwards with a golden glow */
    .service-card:hover {
        transform: translateY(-15px);
        border-color: #d4af37;
        box-shadow: 0 15px 40px rgba(212, 175, 55, 0.15);
    }

    .service-icon {
        font-size: 3.5rem;
        color: #d4af37;
        margin-bottom: 25px;
        transition: 0.4s;
    }
    
    .service-card:hover .service-icon {
        transform: scale(1.1);
    }

    .service-card h3 {
        color: #fff;
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .service-card p {
        color: #888;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 25px;
    }

    .service-btn {
        display: inline-block;
        padding: 10px 25px;
        border: 1px solid #d4af37;
        color: #d4af37;
        text-decoration: none;
        border-radius: 30px;
        font-weight: 500;
        transition: 0.3s;
        font-size: 0.9rem;
    }
    
    .service-btn:hover {
        background: #d4af37;
        color: #000;
    }

    /* --- Call To Action (CTA) Section --- */
    .cta-section {
        background: linear-gradient(rgba(10,10,10,0.9), rgba(10,10,10,0.9)), url('assets/images/uploads/hero_bg.jpg') center/cover;
        text-align: center;
        padding: 80px 20px;
        border-top: 1px solid #333;
        border-bottom: 1px solid #333;
        margin-bottom: 60px;
    }
    
    .cta-section h2 {
        color: #d4af37;
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        margin-bottom: 20px;
    }
    
    .cta-section p {
        color: #ccc;
        margin-bottom: 30px;
        font-size: 1.1rem;
    }
    
    .cta-btn {
        background: #d4af37;
        color: #000;
        padding: 15px 40px;
        text-decoration: none;
        font-weight: bold;
        border-radius: 30px;
        display: inline-block;
        font-size: 1.1rem;
        transition: 0.3s;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .cta-btn:hover {
        background: #fff;
        transform: scale(1.05);
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
    }

    @media screen and (max-width: 992px) {
        .services-hero { padding: 60px 20px 40px; }
        .services-hero h1 { font-size: 2.8rem; }
        .services-hero p { font-size: 1rem; }
        .services-grid { gap: 20px; padding: 0 10px; }
        .service-card { padding: 35px 22px; }
        .service-icon { font-size: 3rem; }
        .cta-section { padding: 60px 20px; }
        .cta-section h2 { font-size: 2rem; }
        .cta-section p { font-size: 1rem; }
        .cta-btn { padding: 14px 30px; }
    }

    @media screen and (max-width: 600px) {
        .service-card { padding: 30px 18px; }
        .service-card h3 { font-size: 1.3rem; }
        .service-card p { font-size: 0.95rem; }
        .cta-btn { width: 100%; max-width: 320px; }
    }
</style>

<main>
    <section class="services-hero">
        <h1>Our Premium Services</h1>
        <p>Beyond our exclusive collections, CEYLORA offers personalized fragrance experiences tailored to your unique lifestyle and business needs.</p>
    </section>

    <section class="services-grid">
        <div class="service-card">
            <i class="fa-solid fa-flask service-icon"></i>
            <h3>Bespoke Fragrances</h3>
            <p>Work with our master perfumers to create a signature scent that is entirely unique to you. A true expression of your personality.</p>
            <a href="contact.php" class="service-btn">Inquire Now</a>
        </div>

        <div class="service-card">
            <i class="fa-solid fa-gift service-icon"></i>
            <h3>Corporate Gifting</h3>
            <p>Impress your clients and reward your team with our elegantly packaged luxury fragrance sets. Custom branding available.</p>
            <a href="contact.php" class="service-btn">View Options</a>
        </div>

        <div class="service-card">
            <i class="fa-solid fa-wand-magic-sparkles service-icon"></i>
            <h3>Event Scenting</h3>
            <p>Create unforgettable memories by scenting your wedding, corporate event, or boutique hotel with our exclusive aroma diffusers.</p>
            <a href="contact.php" class="service-btn">Book Consultation</a>
        </div>

        <div class="service-card">
            <i class="fa-solid fa-user-tie service-icon"></i>
            <h3>Personal Consultation</h3>
            <p>Not sure which scent suits you best? Book a one-on-one session with our fragrance experts to find your perfect match.</p>
            <a href="contact.php" class="service-btn">Schedule Session</a>
        </div>
    </section>

    <section class="cta-section">
        <h2>Experience the Essence of Luxury</h2>
        <p>Have a special request or want to collaborate with CEYLORA? We are just a message away.</p>
        <a href="contact.php" class="cta-btn">Contact Us Today</a>
    </section>
</main>

<?php include 'includes/footer.php'; ?>