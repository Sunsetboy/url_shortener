import db
import json

def fetchAvailableUrlCode()->str:
    short_url_code = db.fetchAvailableCode()
    return short_url_code

def saveUrls(long_url, short_url):
    db.saveUrlsInRedis(getUrlKey(short_url), json.dumps({"url": long_url}))

def getUrlKey(short_url: str)->str:
    return f"url_{short_url}"

def findLongUrlByShort(urlCode: str)->str: 
    return db.findLongUrl(getUrlKey(urlCode))