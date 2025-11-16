// Chatbot Widget for AID-X
class ChatbotWidget {
    constructor() {
        this.isOpen = false;
        this.apiUrl = 'http://localhost:8000/chat';
        this.init();
    }

    init() {
        this.createWidget();
        this.attachEvents();
    }

    createWidget() {
        // Create chatbot HTML
        const chatbotHTML = `
            <div id="aidx-chatbot" style="position: fixed; bottom: 20px; right: 20px; z-index: 10000; font-family: Arial, sans-serif;">
                <!-- Chat Button -->
                <div id="chat-button" style="width: 60px; height: 60px; background: #00b4d8; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,180,216,0.4); transition: all 0.3s;">
                    <svg width="24" height="24" fill="white" viewBox="0 0 24 24">
                        <path d="M20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h4l4 4 4-4h4c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
                    </svg>
                </div>

                <!-- Chat Window -->
                <div id="chat-window" style="display: none; width: 350px; height: 500px; background: white; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.2); position: absolute; bottom: 70px; right: 0; flex-direction: column;">
                    <!-- Header -->
                    <div style="background: #00b4d8; color: white; padding: 16px; border-radius: 12px 12px 0 0; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h3 style="margin: 0; font-size: 16px; font-weight: bold;">AID-X Assistant</h3>
                            <p style="margin: 0; font-size: 12px; opacity: 0.9;">How can I help you?</p>
                        </div>
                        <button id="close-chat" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer;">&times;</button>
                    </div>

                    <!-- Messages -->
                    <div id="chat-messages" style="flex: 1; padding: 16px; overflow-y: auto; max-height: 350px;">
                        <div class="bot-message" style="background: #f3f4f6; color: #374151; padding: 8px 12px; border-radius: 18px; margin-bottom: 10px; max-width: 80%;">
                            Hello! I'm AID-X assistant. I can help you with aid requests, donations, and platform navigation. What do you need?
                        </div>
                    </div>

                    <!-- Input -->
                    <div style="padding: 16px; border-top: 1px solid #e5e7eb;">
                        <div style="display: flex; gap: 8px;">
                            <input type="text" id="chat-input" placeholder="Type your message..." style="flex: 1; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 20px; outline: none; font-size: 14px;">
                            <button id="send-message" style="background: #00b4d8; color: white; border: none; padding: 8px 16px; border-radius: 20px; cursor: pointer; font-size: 14px;">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Insert into page
        document.body.insertAdjacentHTML('beforeend', chatbotHTML);
    }

    attachEvents() {
        const chatButton = document.getElementById('chat-button');
        const chatWindow = document.getElementById('chat-window');
        const closeChat = document.getElementById('close-chat');
        const sendButton = document.getElementById('send-message');
        const chatInput = document.getElementById('chat-input');

        chatButton.addEventListener('click', () => this.toggleChat());
        closeChat.addEventListener('click', () => this.closeChat());
        sendButton.addEventListener('click', () => this.sendMessage());
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.sendMessage();
        });
    }

    toggleChat() {
        const chatWindow = document.getElementById('chat-window');
        this.isOpen = !this.isOpen;
        chatWindow.style.display = this.isOpen ? 'flex' : 'none';
    }

    closeChat() {
        const chatWindow = document.getElementById('chat-window');
        this.isOpen = false;
        chatWindow.style.display = 'none';
    }

    async sendMessage() {
        const input = document.getElementById('chat-input');
        const message = input.value.trim();
        
        if (!message) return;

        // Add user message
        this.addMessage(message, true);
        input.value = '';

        // Use local responses first
        this.addMessage(this.getSimpleResponse(message), false);
    }

    addMessage(message, isUser = false) {
        const messagesContainer = document.getElementById('chat-messages');
        const messageDiv = document.createElement('div');
        
        messageDiv.style.cssText = `
            padding: 8px 12px;
            border-radius: 18px;
            margin-bottom: 10px;
            max-width: 80%;
            ${isUser ? 
                'background: #00b4d8; color: white; margin-left: auto;' : 
                'background: #f3f4f6; color: #374151;'
            }
        `;
        
        messageDiv.textContent = message;
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    getSimpleResponse(message) {
        const responses = {
            "hello": "Hello! Welcome to AID-X - Smart Humanitarian Support Platform. I can help you with aid requests, donations, volunteering, and platform navigation.",
            "hi": "Hi there! I'm your AID-X assistant. How can I help you today?",
            "help": "I can assist you with: \n• Requesting aid (food, medical, shelter, etc.)\n• Making donations\n• Volunteering opportunities\n• Using the interactive map\n• Account registration\n• Platform navigation",
            "aid": "AID-X connects donors, NGOs, volunteers, and people in need. You can request aid by filling out our form or browse the map to see nearby assistance.",
            "request": "To request aid:\n1. Click 'Add Aid Request' on the map\n2. Fill out the request form\n3. Select aid type (food, medical, shelter, etc.)\n4. Your request will appear on our real-time map",
            "donate": "Thank you for wanting to help! You can donate through our secure platform. We ensure transparency and track where your donations go.",
            "volunteer": "Great! We need volunteers for disaster relief and community service. You can sign up through our volunteer form.",
            "map": "Our interactive map shows real-time aid requests with priority levels:\n• Red markers = High priority (urgent)\n• Orange markers = Medium priority\n• Green markers = Low priority",
            "food": "For food assistance, select 'Food & Nutrition' in our aid request form. We coordinate with local NGOs and food banks.",
            "medical": "For medical aid, select 'Medical Care' in the request form. This includes medicines, medical supplies, and healthcare services.",
            "shelter": "For shelter assistance, select 'Shelter & Housing' in our form. We help coordinate temporary housing and emergency shelter.",
            "emergency": "For life-threatening emergencies, please contact local authorities (911/108). AID-X helps with non-emergency humanitarian coordination.",
            "login": "To login, click the 'Login' button in the top navigation. If you don't have an account, you can sign up first.",
            "signup": "To create an account, click 'Sign Up' in the navigation. Registration is free and helps us coordinate aid better.",
            "dashboard": "Your dashboard shows your aid requests, their status (pending/approved), and quick actions to request more aid or view the map.",
            "contact": "You can reach us through:\n• This chat for quick help\n• Contact form on our website\n• Email support\n• Phone support during business hours",
            "about": "AID-X is a smart humanitarian support platform that makes giving faster, transparent, and more impactful. We use technology to ensure help reaches the right place at the right time.",
            "mission": "Our mission is to revolutionize humanitarian aid delivery by making it smart, transparent, and timely for everyone.",
            "vision": "Our vision is to build a world where no call for help goes unheard, and every act of giving creates measurable, meaningful change.",
            "features": "AID-X features:\n• Real-time aid tracking\n• Interactive map\n• Secure donations\n• Volunteer coordination\n• NGO partnerships\n• Transparent reporting",
            "security": "We use end-to-end encryption, AI verification, fraud detection, and GPS verification to ensure secure and authentic aid coordination.",
            "ngo": "We partner with verified NGOs and organizations to ensure aid reaches those who need it most. NGOs can register to coordinate relief efforts.",
            "track": "You can track your aid requests in your dashboard. We provide real-time updates on request status and coordination progress."
        };

        const msg = message.toLowerCase();
        
        for (const key in responses) {
            if (msg.includes(key)) {
                return responses[key];
            }
        }
        
        if (msg.includes('how') && (msg.includes('work') || msg.includes('use'))) {
            return "AID-X works by connecting people in need with donors and volunteers through our real-time platform. Simply create an account, submit aid requests, or offer help through our interactive map.";
        }
        
        if (msg.includes('what') && msg.includes('aidx')) {
            return "AID-X is a smart humanitarian support platform that connects donors, NGOs, volunteers, and people in need through real-time technology for faster, transparent aid delivery.";
        }
        
        if (msg.includes('register') || msg.includes('account')) {
            return "To register, click 'Sign Up' in the navigation. You'll need to provide your name, email, and create a password. Registration is free!";
        }
        
        return "I'm here to help with AID-X platform! You can ask me about requesting aid, donations, volunteering, using the map, or account help.";
    }
}

// Initialize chatbot when page loads
document.addEventListener('DOMContentLoaded', () => {
    new ChatbotWidget();
});