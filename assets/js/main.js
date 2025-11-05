// Main JavaScript file

// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuClose = document.getElementById('mobile-menu-close');
    
    function toggleMobileMenu() {
        if (mobileMenu) {
            const isHidden = mobileMenu.classList.contains('hidden');
            if (isHidden) {
                // Calculate header height for proper positioning
                const header = document.querySelector('header');
                if (header) {
                    const headerHeight = header.offsetHeight;
                    mobileMenu.style.top = headerHeight + 'px';
                }
                // Remove hidden class to show menu
                mobileMenu.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                // Focus search input when menu opens
                setTimeout(function() {
                    const searchInputMobile = document.getElementById('search-input-mobile');
                    if (searchInputMobile) {
                        searchInputMobile.focus();
                    }
                }, 100);
            } else {
                mobileMenu.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }
    }
    
    function closeMobileMenu() {
        if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleMobileMenu();
        });
    }
    
    if (mobileMenuClose && mobileMenu) {
        mobileMenuClose.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeMobileMenu();
        });
    }
    
    // Close mobile menu when clicking on a link
    if (mobileMenu) {
        const mobileMenuLinks = mobileMenu.querySelectorAll('a');
        mobileMenuLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                closeMobileMenu();
            });
        });
    }
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
            if (!mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                closeMobileMenu();
            }
        }
    });
    
    // Close mobile menu when search form is submitted
    const searchFormMobile = document.getElementById('search-form-mobile');
    if (searchFormMobile) {
        searchFormMobile.addEventListener('submit', function() {
            closeMobileMenu();
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
    
    // Scroll buttons (top, bottom-left up, bottom-right down)
    const scrollToTopBottom = document.getElementById('scroll-to-top-bottom');
    const scrollToTopTop = document.getElementById('scroll-to-top-top');
    const scrollToBottom = document.getElementById('scroll-to-bottom');
    
    function handleScrollButtons() {
        const scrollPosition = window.pageYOffset;
        const documentHeight = document.documentElement.scrollHeight - window.innerHeight;
        const shouldShowTop = scrollPosition > 300;
        const shouldShowBottom = scrollPosition < documentHeight - 300;
        
        // Bottom left - scroll to top (show when scrolled down)
        if (scrollToTopBottom) {
            if (shouldShowTop) {
                scrollToTopBottom.classList.remove('hidden');
            } else {
                scrollToTopBottom.classList.add('hidden');
            }
        }
        
        // Top right - scroll to top (show when scrolled down)
        if (scrollToTopTop) {
            if (shouldShowTop) {
                scrollToTopTop.classList.remove('hidden');
            } else {
                scrollToTopTop.classList.add('hidden');
            }
        }
        
        // Bottom right - scroll to bottom (show when not at bottom)
        if (scrollToBottom) {
            if (shouldShowBottom) {
                scrollToBottom.classList.remove('hidden');
            } else {
                scrollToBottom.classList.add('hidden');
            }
        }
    }
    
    // Show/hide buttons based on scroll position
    window.addEventListener('scroll', handleScrollButtons);
    
    // Initial check
    handleScrollButtons();
    
    // Scroll to top when clicked
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
    
    // Scroll to bottom when clicked
    function scrollToBottomFunc() {
        window.scrollTo({
            top: document.documentElement.scrollHeight,
            behavior: 'smooth'
        });
    }
    
    if (scrollToTopBottom) {
        scrollToTopBottom.addEventListener('click', scrollToTop);
    }
    
    if (scrollToTopTop) {
        scrollToTopTop.addEventListener('click', scrollToTop);
    }
    
    if (scrollToBottom) {
        scrollToBottom.addEventListener('click', scrollToBottomFunc);
    }
    
});
