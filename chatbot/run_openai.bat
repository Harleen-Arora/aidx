@echo off
echo Installing dependencies...
pip install -r requirements.txt

echo Starting AID-X Chatbot API with OpenAI...
uvicorn main_openai:app --host 0.0.0.0 --port 8000 --reload

pause