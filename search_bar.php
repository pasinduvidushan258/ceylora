<style>
    /* === Compact Search Bar CSS === */
    .compact-search-bar {
        position: fixed;
        top: 85px; 
        right: 5%; 
        width: 450px; 
        background: #111; 
        padding: 6px 8px;
        z-index: 1000;
        border-radius: 12px;
        border: 1px solid #d4af37; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.8);
        
        transform: translateY(-20px);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .compact-search-bar.active {
        transform: translateY(0);
        opacity: 1;
        visibility: visible;
    }

    .compact-search-form { 
        display: flex; 
        align-items: center; 
        border-bottom: 1px solid #333; 
        padding-bottom: 10px; 
    }
    
    .compact-search-icon { 
        font-size: 1.2rem; 
        color: #d4af37; 
        margin-right: 15px; 
    }
    
    .compact-search-form input {
        flex: 1; 
        border: none; 
        background: transparent;
        font-size: 0.95rem; 
        font-family: 'Montserrat', sans-serif;
        color: #fff; 
        outline: none;
    }
    
    .compact-search-form input::placeholder { 
        color: #888; 
        font-weight: 300; 
    }
    
    .compact-close-search { 
        font-size: 1.2rem; 
        color: #888; 
        cursor: pointer; 
        margin-left: 15px; 
        transition: 0.3s; 
    }
    
    .compact-close-search:hover { 
        color: #ff4444; 
        transform: scale(1.1); 
    }

    /* --- Live Results Dropdown Box --- */
    .live-search-results {
        max-height: 400px;
        overflow-y: auto;
        margin-top: 15px;
        display: none; /* Hidden by default */
    }
    
    /* Scrollbar Style */
    .live-search-results::-webkit-scrollbar { 
        width: 5px; 
    }
    
    .live-search-results::-webkit-scrollbar-thumb { 
        background: #d4af37; 
        border-radius: 10px; 
    }

    /* Results Items */
    .result-section-title { 
        color: #666; 
        font-size: 0.75rem; 
        letter-spacing: 2px; 
        text-transform: uppercase; 
        margin: 15px 0 10px; 
        border-bottom: 1px solid #222; 
        padding-bottom: 5px; 
    }
    
    .live-result-item {
        display: flex; 
        align-items: center; 
        gap: 15px;
        padding: 10px; 
        border-radius: 8px; 
        cursor: pointer;
        transition: 0.3s; 
        text-decoration: none; 
        color: #fff;
    }
    
    .live-result-item:hover { 
        background: #1a1a1a; 
    }
    
    .live-result-item img { 
        width: 40px; 
        height: 40px; 
        object-fit: cover; 
        border-radius: 5px; 
        border: 1px solid #333; 
    }
    
    .live-result-info h4 { 
        font-size: 0.9rem; 
        margin: 0 0 5px; 
        color: #fff; 
        font-weight: 500; 
    }
    
    .live-result-info p { 
        font-size: 0.8rem; 
        margin: 0; 
        color: #d4af37; 
        font-weight: bold; 
    }

/* Mobile Responsive Styles */
@media screen and (max-width: 768px) {
    .compact-search-bar {
        top: 70px; /* Adjust for smaller header on mobile */
        right: 10px; /* Closer to edge */
        left: 10px; /* Add left positioning */
        width: auto; /* Let it fill the space between left and right */
        max-width: calc(100vw - 20px); /* Full width minus padding */
        padding: 8px 12px; /* Slightly more padding on mobile */
    }

    .compact-search-form {
        padding-bottom: 12px; /* More padding on mobile */
    }

    .compact-search-icon {
        font-size: 1.1rem; /* Slightly smaller icon */
        margin-right: 12px; /* Less margin */
    }

    .compact-search-form input {
        font-size: 0.9rem; /* Slightly smaller font */
    }

    .compact-close-search {
        font-size: 1.1rem; /* Slightly smaller close button */
        margin-left: 12px; /* Less margin */
    }

    .live-search-results {
        max-height: 300px; /* Shorter results on mobile */
        margin-top: 12px; /* Less margin */
    }

    .live-result-item {
        gap: 12px; /* Less gap */
        padding: 8px; /* Less padding */
    }

    .live-result-item img {
        width: 35px; /* Smaller images */
        height: 35px;
    }

    .live-result-info h4 {
        font-size: 0.85rem; /* Smaller title */
    }

    .live-result-info p {
        font-size: 0.75rem; /* Smaller price */
    }
}

@media screen and (max-width: 480px) {
    .compact-search-bar {
        top: 65px; /* Even closer to header on very small screens */
        right: 5px;
        left: 5px;
        max-width: calc(100vw - 10px);
        padding: 6px 10px; /* Tighter padding */
    }

    .compact-search-form {
        padding-bottom: 10px;
    }

    .compact-search-icon {
        font-size: 1rem;
        margin-right: 10px;
    }

    .compact-search-form input {
        font-size: 0.85rem;
    }

    .compact-close-search {
        font-size: 1rem;
        margin-left: 10px;
    }

    .live-search-results {
        max-height: 250px; /* Even shorter on small screens */
    }

    .result-section-title {
        font-size: 0.7rem; /* Smaller section titles */
        margin: 12px 0 8px;
    }

    .live-result-item {
        gap: 10px;
        padding: 6px;
    }

    .live-result-item img {
        width: 30px;
        height: 30px;
    }

    .live-result-info h4 {
        font-size: 0.8rem;
    }

    .live-result-info p {
        font-size: 0.7rem;
    }
}
</style>

<div id="topSearchBar" class="compact-search-bar">
    <form action="shop.php" method="GET" class="compact-search-form" onsubmit="return false;">
        <span class="compact-search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
        <input type="text" id="liveSearchInput" name="search" placeholder="Search your scent..." autocomplete="off" onkeyup="fetchLiveResults()">
        <span class="compact-close-search" onclick="toggleSearch()"><i class="fa-solid fa-xmark"></i></span>
    </form>
    
    <div id="liveSearchResults" class="live-search-results"></div>
</div>

<script>
    /**
     * Toggles the visibility of the search bar and clears its content when closed
     */
    function toggleSearch() {
        var searchBar = document.getElementById("topSearchBar");
        var searchInput = document.getElementById("liveSearchInput");
        var resultsBox = document.getElementById("liveSearchResults");

        searchBar.classList.toggle("active");
        
        if (searchBar.classList.contains("active")) {
            setTimeout(() => { searchInput.focus(); }, 100);
        } else {
            // Clear input and hide results when the search bar is closed
            searchInput.value = "";
            resultsBox.style.display = "none";
            resultsBox.innerHTML = "";
        }
    }

    // Automatically close the search bar if the user clicks outside of it
    window.addEventListener('click', function(event) {
        var searchBar = document.getElementById("topSearchBar");
        var searchIcon = document.querySelector('.fa-magnifying-glass').parentElement; 
        
        if (searchBar.classList.contains('active')) {
            if (!searchBar.contains(event.target) && !searchIcon.contains(event.target)) {
                toggleSearch(); // Call the toggle function to close it
            }
        }
    });

    /**
     * Fetches real-time search results via AJAX based on user input
     */
    function fetchLiveResults() {
        let query = document.getElementById("liveSearchInput").value;
        let resultsBox = document.getElementById("liveSearchResults");

        if (query.length > 0) {
            // Send the typed characters to live_search.php
            fetch('live_search.php?q=' + encodeURIComponent(query))
            .then(response => response.text())
            .then(data => {
                resultsBox.innerHTML = data;
                resultsBox.style.display = "block";
            });
        } else {
            // Hide the results box if the input is empty
            resultsBox.style.display = "none";
            resultsBox.innerHTML = "";
        }
    }
</script>