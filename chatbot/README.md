# AID-X Chatbot Setup

## Prerequisites
- Python 3.7+ installed
- OpenAI API key

## Setup Instructions

1. **Get OpenAI API Key**
   - Go to https://platform.openai.com/api-keys
   - Create a new API key
   - Copy the key

2. **Configure API Key**
   - Open `.env` file
   - Replace `sk-your-openai-api-key-here` with your actual API key

3. **Install Dependencies**
   ```bash
   pip install -r requirements.txt
   ```

4. **Run Chatbot Server**
   ```bash
   python app.py
   ```
   Or double-click `run.bat` on Windows

5. **Test the Chatbot**
   - Server runs on http://localhost:5000
   - Chatbot is integrated into your website
   - Test via the chat popup on your homepage

## Features
- OpenAI GPT-3.5 powered responses
- Humanitarian aid context awareness
- Multilingual support (English/Hindi)
- CORS enabled for web integration
- Error handling and fallbacks

## API Endpoints
- `POST /chat` - Send message to chatbot
- `GET /health` - Check server status