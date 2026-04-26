<?php 
// Include the global header component
include 'includes/header.php'; 
?>

<style>
    /* Main container for the About page */
    .about-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 60px 20px;
        color: #fff;
    }

    /* Hero section (top introduction area) */
    .about-hero {
        text-align: center;
        margin-bottom: 80px;
    }
    .about-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: 3.5rem;
        color: #d4af37;
        margin-bottom: 20px;
    }
    .about-hero p {
        color: #aaa;
        font-size: 1.1rem;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.8;
    }

    /* Story section with split layout (image + content) */
    .story-section {
        display: flex;
        align-items: center;
        gap: 50px;
        margin-bottom: 100px;
        background: #111;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #222;
    }

    /* Background image container for story section */
    .story-image {
        flex: 1;
        min-height: 500px;
        background: url('assets/images/about_story.jpg') center/cover;
    }

    /* Text content area for story section */
    .story-content {
        flex: 1;
        padding: 50px;
    }
    .story-content h2 {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        color: #d4af37;
        margin-bottom: 20px;
    }
    .story-content p {
        color: #ccc;
        line-height: 1.8;
        margin-bottom: 20px;
        font-size: 1rem;
    }

    /* Section title for core values */
    .values-title {
        text-align: center;
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        color: #d4af37;
        margin-bottom: 50px;
    }

    /* Grid layout for value cards */
    .values-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin-bottom: 80px;
    }

    /* Individual value card styling */
    .value-card {
        background: #0a0a0a;
        padding: 40px 30px;
        text-align: center;
        border-radius: 15px;
        border: 1px solid #222;
        transition: 0.4s;
    }

    /* Hover effect for value cards */
    .value-card:hover {
        transform: translateY(-10px);
        border-color: #d4af37;
        box-shadow: 0 10px 30px rgba(212, 175, 55, 0.1);
    }

    /* Icon styling inside value cards */
    .value-icon {
        font-size: 3rem;
        color: #d4af37;
        margin-bottom: 20px;
    }

    /* Value card heading */
    .value-card h3 {
        color: #fff;
        margin-bottom: 15px;
        font-size: 1.3rem;
    }

    /* Value card description */
    .value-card p {
        color: #888;
        line-height: 1.6;
        font-size: 0.95rem;
    }

    /* Responsive adjustments for mobile devices */
    @media (max-width: 768px) {
        .story-section { flex-direction: column; }
        .story-image { min-height: 250px; width: 100%; }
        .story-content { padding: 30px; }
        .about-hero h1 { font-size: 2.5rem; }
    }
</style>

<!-- Main About Page Content -->
<main class="about-container">
    
    <!-- Hero Section -->
    <section class="about-hero">
        <h1>The Story of Ceylora</h1>
        <p>
            Discover the essence of pure luxury. We blend the finest ingredients 
            to create unforgettable, alcohol-free fragrances that leave a lasting impression.
        </p>
    </section>

    <!-- Story Section -->
    <section class="story-section">
        <div class="story-image"></div>

        <div class="story-content">
            <h2>Born from Elegance</h2>
            <p>
                CEYLORA was founded with a single vision: to redefine luxury perfumery 
                by offering premium, alcohol-free scents that are safe for the skin and long-lasting.
            </p>
            <p>
                Inspired by the rich heritage of fine fragrances and the natural beauty of the world around us, 
                every bottle of CEYLORA is meticulously crafted to perfection. 
                We believe that a fragrance is more than just a scent; 
                it is an invisible signature that speaks volumes about who you are.
            </p>
        </div>
    </section>

    <!-- Core Values Section -->
    <h2 class="values-title">Our Core Values</h2>

    <section class="values-grid">

        <!-- Value Card: Alcohol-Free -->
        <div class="value-card">
            <i class="fa-solid fa-droplet value-icon"></i>
            <h3>100% Alcohol-Free</h3>
            <p>
                Our fragrances are crafted with pure oils, making them gentle on the skin 
                and free from harsh alcoholic evaporation.
            </p>
        </div>
        
        <!-- Value Card: Long Lasting -->
        <div class="value-card">
            <i class="fa-solid fa-clock value-icon"></i>
            <h3>Long Lasting</h3>
            <p>
                Because we use highly concentrated fragrance oils, 
                a few drops of CEYLORA will stay with you from morning until night.
            </p>
        </div>

        <!-- Value Card: Cruelty-Free -->
        <div class="value-card">
            <i class="fa-solid fa-leaf value-icon"></i>
            <h3>Cruelty-Free</h3>
            <p>
                We care about nature. None of our products or ingredients are tested on animals, 
                ensuring ethical luxury.
            </p>
        </div>

        <!-- Value Card: Premium Quality -->
        <div class="value-card">
            <i class="fa-solid fa-gem value-icon"></i>
            <h3>Premium Quality</h3>
            <p>
                Sourced from the finest fragrance houses globally, 
                we guarantee an authentic and premium scent experience.
            </p>
        </div>

    </section>

</main>

<?php 
// Include the global footer component
include 'includes/footer.php'; 
?>