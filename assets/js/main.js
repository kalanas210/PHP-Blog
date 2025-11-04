// Main JavaScript file

// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
    
    // Profile dropdown
    const profileMenuBtn = document.getElementById('profile-menu-btn');
    const profileDropdown = document.getElementById('profile-dropdown');
    
    if (profileMenuBtn && profileDropdown) {
        profileMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            profileDropdown.classList.toggle('hidden');
        });
        
        // Close dropdown when clicking on links inside it
        const dropdownLinks = profileDropdown.querySelectorAll('a');
        dropdownLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                profileDropdown.classList.add('hidden');
            });
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (profileMenuBtn && profileDropdown && 
                !profileMenuBtn.contains(e.target) && 
                !profileDropdown.contains(e.target)) {
                profileDropdown.classList.add('hidden');
            }
        });
    }
    
    // Newsletter form
    const newsletterForm = document.getElementById('newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Thank you for subscribing!');
            newsletterForm.reset();
        });
    }
    
    // Search toggle
    const searchToggleBtn = document.getElementById('search-toggle-btn');
    const searchForm = document.getElementById('search-form');
    const searchInput = document.getElementById('search-input');
    
    if (searchToggleBtn && searchForm) {
        searchToggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            searchForm.classList.remove('hidden');
            searchToggleBtn.classList.add('hidden'); // Hide the icon when search box appears
            // Focus on search input when it appears
            setTimeout(function() {
                if (searchInput) {
                    searchInput.focus();
                }
            }, 100);
        });
        
        // Close search when clicking outside
        document.addEventListener('click', function(e) {
            if (searchForm && !searchForm.contains(e.target) && !searchToggleBtn.contains(e.target)) {
                if (!searchForm.classList.contains('hidden')) {
                    searchForm.classList.add('hidden');
                    searchToggleBtn.classList.remove('hidden'); // Show the icon again when closing
                }
            }
        });
        
        // Prevent form from closing when clicking inside it
        if (searchForm) {
            searchForm.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
        
        // Also show icon again when form is submitted (if user wants to search again)
        if (searchForm) {
            searchForm.addEventListener('submit', function() {
                // Keep form visible after submission so user can see results
                // Icon will remain hidden until they click outside
            });
        }
    }
    
});
