from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
import openai
import os

app = FastAPI()

# CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Set your OpenAI API key
openai.api_key = "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"

class ChatMessage(BaseModel):
    message: str

@app.post("/chat")
async def chat(chat_message: ChatMessage):
    try:
        response = openai.ChatCompletion.create(
            model="gpt-3.5-turbo",
            messages=[
                {"role": "system", "content": "You are AID-X assistant, a helpful chatbot for a humanitarian aid platform. Help users with aid requests, donations, and platform navigation. Keep responses short and helpful."},
                {"role": "user", "content": chat_message.message}
            ],
            max_tokens=150
        )
        
        return {"response": response.choices[0].message.content}
    
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.get("/")
async def root():
    return {"message": "AID-X Chatbot API with OpenAI is running"}

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)