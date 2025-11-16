from flask import Flask, request, jsonify
from flask_cors import CORS
import openai
import os
from datetime import datetime

app = Flask(__name__)
CORS(app)

# Set your OpenAI API key here
openai.api_key = "sk-your-openai-api-key-here"  # Replace with your actual API key

# AID-X specific system prompt
SYSTEM_PROMPT = """You are AID-X Assistant, a helpful AI for a humanitarian aid platform. You help users with:
- Aid requests and donations
- Emergency assistance information
- NGO partnerships
- Volunteer coordination
- Platform navigation
- Humanitarian support

Always be compassionate, helpful, and provide accurate information about humanitarian aid. 
Respond in the same language the user writes in (English or Hindi).
Keep responses concise and actionable."""

@app.route('/chat', methods=['POST'])
def chat():
    try:
        data = request.get_json()
        user_message = data.get('message', '')
        language = data.get('language', 'en')
        
        if not user_message:
            return jsonify({'error': 'No message provided'}), 400
        
        # Create chat completion
        response = openai.ChatCompletion.create(
            model="gpt-3.5-turbo",
            messages=[
                {"role": "system", "content": SYSTEM_PROMPT},
                {"role": "user", "content": user_message}
            ],
            max_tokens=150,
            temperature=0.7
        )
        
        bot_response = response.choices[0].message.content.strip()
        
        return jsonify({
            'response': bot_response,
            'timestamp': datetime.now().isoformat()
        })
        
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/health', methods=['GET'])
def health():
    return jsonify({'status': 'healthy', 'service': 'AID-X Chatbot'})

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)