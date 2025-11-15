// Configuration file for AID-X Website
// Manages paths and settings for responsive design

const AIDXConfig = {
    // Base paths for different file types
    paths: {
        css: './css/',
        js: './js/',
        html: './html/',
        php: './php/',
        assets: './assets/',
        images: './assets/images/',
        icons: './assets/icons/'
    },
    
    // Responsive breakpoints (in pixels)
    breakpoints: {
        mobile: 768,
        tablet: 1024,
        desktop: 1440
    },
    
    // Theme colors
    colors: {
        primary: '#0F766E',
        secondary: '#14B8A6',
        accent: '#FDE047',
        background: '#050709'
    },
    
    // Chatbot settings
    chatbot: {
        mobile: { width: '280px', height: '400px' },
        tablet: { width: '350px', height: '500px' },
        desktop: { width: '400px', height: '600px' }
    },
    
    // Animation settings
    animations: {
        duration: 300,
        easing: 'ease-in-out'
    },
    
    // Utility functions
    utils: {
        // Get current screen size category
        getScreenSize: function() {
            const width = window.innerWidth;
            if (width < this.breakpoints.mobile) return 'mobile';
            if (width < this.breakpoints.tablet) return 'tablet';
            if (width < this.breakpoints.desktop) return 'desktop';
            return 'large-desktop';
        },
        
        // Check if device is mobile
        isMobile: function() {
            return window.innerWidth < this.breakpoints.mobile;
        },
        
        // Check if device is tablet
        isTablet: function() {
            const width = window.innerWidth;
            return width >= this.breakpoints.mobile && width < this.breakpoints.tablet;
        },
        
        // Check if device is desktop
        isDesktop: function() {
            return window.innerWidth >= this.breakpoints.tablet;
        },
        
        // Get relative path based on current location
        getRelativePath: function(targetPath, currentPath = '') {
            if (currentPath.includes('/html/')) {
                return '../' + targetPath;
            } else if (currentPath.includes('/php/')) {
                return '../' + targetPath;
            }
            return targetPath;
        }
    }
};

// Make config available globally
window.AIDXConfig = AIDXConfig;

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AIDXConfig;
}