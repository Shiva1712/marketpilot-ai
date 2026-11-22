from fastapi import FastAPI

app = FastAPI(
    title="MarketPilot AI",
    description="Agentic Marketing Automation Service",
    version="1.0.0"
)

@app.get("/")
async def home():
    return {
        "success": True,
        "message": "MarketPilot AI service running"
    }

@app.get("/health")
async def health():
    return {
        "status": "healthy"
    }
