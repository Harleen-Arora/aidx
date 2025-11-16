<!DOCTYPE html>
<html>
<head>
    <title>Test Chatbot</title>
</head>
<body>
    <h2>Test AID-X Chatbot</h2>
    <div id="messages"></div>
    <input type="text" id="messageInput" placeholder="Type your message...">
    <button onclick="sendTest()">Send</button>
    
    <script>
        async function sendTest() {
            const input = document.getElementById('messageInput');
            const messages = document.getElementById('messages');
            const message = input.value.trim();
            
            if (!message) return;
            
            messages.innerHTML += `<p><strong>You:</strong> ${message}</p>`;
            
            try {
                const response = await fetch('chatbot_direct.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: message, language: 'en' })
                });
                
                const data = await response.json();
                messages.innerHTML += `<p><strong>Bot:</strong> ${data.response || data.error}</p>`;
            } catch (error) {
                messages.innerHTML += `<p><strong>Error:</strong> ${error.message}</p>`;
            }
            
            input.value = '';
        }
    </script>
</body>
</html>