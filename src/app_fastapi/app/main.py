from typing import Union

from fastapi import FastAPI

app = FastAPI()


@app.get("/")
def read_root():
    return {"Hello": "World 3"}


@app.get("/{short_url}")
def redirect_to_long_url(short_url: str):
    long_url = "http://example.com"
    return {"url": long_url}


@app.post("/api/url")
def add_url():
    short_url = "qwerty12"
    return {"url": short_url} 