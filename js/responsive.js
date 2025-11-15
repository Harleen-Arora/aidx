// AID-X Responsive JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Responsive grid adjustments
    function adjustGrids() {
        const grids = document.querySelectorAll('.grid');
        const screenWidth = window.innerWidth;
        
        grids.forEach(grid => {
            if (screenWidth < 768) {
                grid.style.gridTemplateColumns = '1fr';
            } else if (screenWidth < 1024) {
                if (grid.classList.contains('md:grid-cols-3')) {
                    grid.style.gridTemplateColumns = 'repeat(2, 1fr)';
                } else if (grid.classList.contains('md:grid-cols-2')) {
                    grid.style.gridTemplateColumns = 'repeat(2, 1fr)';
                }
            } else {
                grid.style.gridTemplateColumns = '';
            }
        });
    }
    
    // Debounced resize handler
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(adjustGrids, 250);
    });
    
    // Initial grid adjustment
    adjustGrids();
    
    // Touch support for mobile devices
    if ('ontouchstart' in window) {
        document.body.classList.add('touch-device');
    }
    
    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
            }
        });
    }, observerOptions);
    
    // Observe sections for animations
    document.querySelectorAll('section').forEach(section => {
        observer.observe(section);
    });
});

// Configuration object
window.AIDX_CONFIG = {
    apiEndpoint: '/php/',
    chatbotEndpoint: '/assets/chatbot.py',
    mapConfig: {
        defaultZoom: 10,
        center: [0, 0]
    }
};