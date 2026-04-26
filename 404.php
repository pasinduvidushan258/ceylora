<?php include('includes/header.php'); ?>

<style>
    /* Premium 404 Luxury UI */
    .error-wrapper {
        min-height: 80vh; /* පේජ් එකේ උස */
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #000;
        position: relative;
        overflow: hidden;
        padding: 100px 20px;
    }

    .error-content {
        text-align: center;
        z-index: 2;
        animation: fadeInUp 1.2s ease-out;
    }

    /* පසුබිමේ මැකී පෙනෙන ලොකු 404 අංකය */
    .ghost-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-family: 'Playfair Display', serif;
        font-size: 25vw; /* Screen එකේ පළල අනුව ලොකු වෙනවා */
        font-weight: 700;
        color: rgba(212, 175, 55, 0.03); /* ඉතාමත් ලාවට පෙනෙන රන්වන් පාට */
        z-index: 1;
        pointer-events: none;
        letter-spacing: -10px;
    }

    .error-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2rem, 5vw, 4rem); /* Responsive font size */
        color: #d4af37;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 8px;
        font-weight: 400;
    }

    .error-subtitle {
        font-family: 'Montserrat', sans-serif;
        color: #ffffff;
        font-size: 1.1rem;
        letter-spacing: 4px;
        text-transform: uppercase;
        margin-bottom: 40px;
        opacity: 0.8;
    }

    .error-divider {
        width: 50px;
        height: 1px;
        background: #d4af37;
        margin: 0 auto 40px;
    }

    .btn-luxury {
        display: inline-block;
        padding: 18px 45px;
        border: 1px solid #d4af37;
        color: #d4af37;
        text-decoration: none;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 3px;
        transition: all 0.5s border;
        position: relative;
        overflow: hidden;
        background: transparent;
    }

    .btn-luxury:hover {
        background: #d4af37;
        color: #000;
        box-shadow: 0 0 30px rgba(212, 175, 55, 0.2);
        transform: translateY(-2px);
    }

    /* Fade In Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* පසුබිමේ ඇති රන්වන් එළිය */
    .gold-glow {
        position: absolute;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(212, 175, 55, 0.05) 0%, rgba(0,0,0,0) 70%);
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1;
    }
</style>

<div class="error-wrapper">
    <div class="gold-glow"></div>
    <div class="ghost-text">404</div>
    
    <div class="error-content">
        <h1 class="error-title">Page Not Found</h1>
        <div class="error-divider"></div>
        <p class="error-subtitle">The scent you're seeking has vanished</p>
        <a href="index.php" class="btn-luxury">Return to Boutique</a>
    </div>
</div>

<?php include('includes/footer.php'); ?>