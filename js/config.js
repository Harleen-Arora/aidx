// AID-X Configuration Management

const AIDX_CONFIG = {
    // API Configuration
    api: {
        baseUrl: '/php/',
        endpoints: {
            signin: 'signin.php',
            signup: 'singup.php',
            request: 'request.php',
            form: 'aidxForm.php'
        }
    },
    
    // Chatbot Configuration
    chatbot: {
        endpoint: '/assets/chatbot.py',
        dimensions: {
            mobile: { width: 280, height: 400 },
            tablet: { width: 350, height: 500 },
            desktop: { width: 400, height: 600 }
        }
    },
    
    // Map Configuration
    map: {
        defaultZoom: 10,
        center: [0, 0],
        markers: {
            donor: '#00b4d8',
            ngo: '#48cae4',
            beneficiary: '#90e0ef'
        }
    },
    
    // Responsive Breakpoints
    breakpoints: {
        mobile: 768,
        tablet: 1024,
        desktop: 1440
    },
    
    // Theme Colors
    colors: {
        primary: '#00b4d8',
        secondary: '#48cae4',
        accent: '#90e0ef',
        text: '#333333',
        background: '#ffffff'
    }
};

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AIDX_CONFIG;
} else {
    window.AIDX_CONFIG = AIDX_CONFIG;
}