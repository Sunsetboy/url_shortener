from typing import Union
from models import UrlRequest
from fastapi import FastAPI, HTTPException
from fastapi.responses import RedirectResponse
import url_service
from dotenv import load_dotenv

load_dotenv()
app = FastAPI()


@app.get("/")
def read_root():
    return {"Hello": "World 3"}


@app.get("/{short_url}", status_code=302)
async def redirect_to_long_url(short_url: str):
    long_url = url_service.findLongUrlByShort(short_url)
    if not long_url:
        raise HTTPException(
            status_code=404, detail=f"URL with code {short_url} does not exist"
        )
    return RedirectResponse(url=long_url, status_code=302)


@app.post("/api/url")
async def add_url(url_request: UrlRequest):
    try:
        short_url = url_service.fetchAvailableUrlCode()
    except Exception as e:
        raise HTTPException(
            status_code=500, detail=str(e)
        )
    if short_url:
        url_service.saveUrls(url_request.url, short_url)

    return {"short_url": short_url}
