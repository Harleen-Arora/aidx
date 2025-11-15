// Responsive JavaScript for AID-X Website

// Mobile menu toggle functionality
function toggleMobileMenu() {
    const navLinks = document.querySelector('.nav-links');
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    
    if (navLinks) {
        navLinks.classList.toggle('active');
        if (mobileMenuBtn) {
            mobileMenuBtn.innerHTML = navLinks.classList.contains('active') ? '✕' : '☰';
        }
    }
}

// Dashboard sidebar toggle for mobile
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar-mobile');
    const overlay = document.querySelector('.sidebar-overlay');
    
    if (sidebar) {
        sidebar.classList.toggle('active');
    }
    
    if (overlay) {
        overlay.classList.toggle('active');
    }
}

// Responsive chatbot positioning
function adjustChatbotPosition() {
    const chatbot = document.querySelector('.chatbot-iframe');
    if (!chatbot) return;
    
    const screenWidth = window.innerWidth;
    
    if (screenWidth < 768) {
        // Mobile: smaller and positioned differently
        chatbot.style.width = '280px';
        chatbot.style.height = '400px';
        chatbot.style.bottom = '10px';
        chatbot.style.right = '10px';
    } else if (screenWidth < 1024) {
        // Tablet
        chatbot.style.width = '350px';
        chatbot.style.height = '500px';
        chatbot.style.bottom = '20px';
        chatbot.style.right = '20px';
    } else {
        // Desktop
        chatbot.style.width = '400px';
        chatbot.style.height = '600px';
        chatbot.style.bottom = '20px';
        chatbot.style.right = '20px';
    }
}

// Responsive grid adjustments
function adjustGridLayouts() {
    const actionCards = document.querySelector('.action-cards');
    const metricsGrid = document.querySelector('.metrics-grid');
    const servicesGrid = document.querySelector('.services-grid');
    
    const screenWidth = window.innerWidth;
    
    if (screenWidth < 768) {
        // Mobile: single column
        if (actionCards) actionCards.style.gridTemplateColumns = '1fr';
        if (metricsGrid) metricsGrid.style.gridTemplateColumns = '1fr';
        if (servicesGrid) servicesGrid.style.gridTemplateColumns = 'repeat(2, 1fr)';
    } else if (screenWidth < 1024) {
        // Tablet: two columns
        if (actionCards) actionCards.style.gridTemplateColumns = 'repeat(2, 1fr)';
        if (metricsGrid) metricsGrid.style.gridTemplateColumns = 'repeat(2, 1fr)';
        if (servicesGrid) servicesGrid.style.gridTemplateColumns = 'repeat(3, 1fr)';
    } else {
        // Desktop: three/four columns
        if (actionCards) actionCards.style.gridTemplateColumns = 'repeat(3, 1fr)';
        if (metricsGrid) metricsGrid.style.gridTemplateColumns = 'repeat(4, 1fr)';
        if (servicesGrid) servicesGrid.style.gridTemplateColumns = 'repeat(4, 1fr)';
    }
}

// Touch-friendly interactions for mobile
function addTouchSupport() {
    const cards = document.querySelectorAll('.action-card, .service-card, .metric-card');
    
    cards.forEach(card => {
        card.addEventListener('touchstart', function() {
            this.style.transform = 'scale(0.98)';
        });
        
        card.addEventListener('touchend', function() {
            this.style.transform = 'scale(1)';
        });
    });
}

// Smooth scrolling for mobile navigation
function addSmoothScrolling() {
    const navLinks = document.querySelectorAll('.nav-links a');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            // Only apply smooth scrolling to anchor links
            if (href && href.startsWith('#')) {
                e.preventDefault();
                const target = document.querySelector(href);
                
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    
                    // Close mobile menu after navigation
                    const navLinks = document.querySelector('.nav-links');
                    if (navLinks && navLinks.classList.contains('active')) {
                        toggleMobileMenu();
                    }
                }
            }
        });
    });
}

// Responsive image loading
function optimizeImages() {
    const images = document.querySelectorAll('img');
    const screenWidth = window.innerWidth;
    
    images.forEach(img => {
        if (img.dataset.mobile && screenWidth < 768) {
            img.src = img.dataset.mobile;
        } else if (img.dataset.tablet && screenWidth < 1024) {
            img.src = img.dataset.tablet;
        } else if (img.dataset.desktop) {
            img.src = img.dataset.desktop;
        }
    });
}

// Handle orientation changes on mobile
function handleOrientationChange() {
    setTimeout(() => {
        adjustChatbotPosition();
        adjustGridLayouts();
    }, 100);
}

// Initialize responsive features
function initResponsive() {
    adjustChatbotPosition();
    adjustGridLayouts();
    addTouchSupport();
    addSmoothScrolling();
    optimizeImages();
    
    // Add event listeners
    window.addEventListener('resize', () => {
        adjustChatbotPosition();
        adjustGridLayouts();
        optimizeImages();
    });
    
    window.addEventListener('orientationchange', handleOrientationChange);
    
    // Add mobile menu button if it doesn't exist
    const navbar = document.querySelector('.navbar');
    if (navbar && !document.querySelector('.mobile-menu-btn')) {
        const menuBtn = document.createElement('button');
        menuBtn.className = 'mobile-menu-btn';
        menuBtn.innerHTML = '☰';
        menuBtn.onclick = toggleMobileMenu;
        navbar.insertBefore(menuBtn, navbar.firstChild);
    }
}

// Run when DOM is loaded
document.addEventListener('DOMContentLoaded', initResponsive);

// Export functions for use in other scripts
window.ResponsiveUtils = {
    toggleMobileMenu,
    toggleSidebar,
    adjustChatbotPosition,
    adjustGridLayouts
};