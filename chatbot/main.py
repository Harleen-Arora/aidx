from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
import random

app = FastAPI()

# CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

class ChatMessage(BaseModel):
    message: str

# Simple rule-based responses
responses = {
    "hello": "Hello! I'm AID-X assistant. How can I help you with humanitarian aid today?",
    "help": "I can help you with aid requests, donations, and platform navigation. What do you need?",
    "aid": "You can request aid by filling out our form or browsing the map for nearby assistance.",
    "donate": "Thank you for wanting to help! You can donate through our secure platform.",
    "map": "Check our interactive map to see aid requests in your area.",
    "default": "I'm here to help with AID-X platform. Try asking about aid requests, donations, or navigation."
}

@app.post("/chat")
async def chat(chat_message: ChatMessage):
    try:
        message = chat_message.message.lower()
        
        for key in responses:
            if key in message:
                return {"response": responses[key]}
        
        return {"response": responses["default"]}
    
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.get("/")
async def root():
    return {"message": "AID-X Chatbot API is running"}

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)