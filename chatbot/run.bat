@echo off
echo Installing dependencies...
pip install -r requirements.txt

echo Starting AID-X Chatbot API...
uvicorn main:app --host 0.0.0.0 --port 8000 --reload

pause