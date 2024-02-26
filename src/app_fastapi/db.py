import os
import redis
import json
from mysql.connector import connect, Error

dbConn = connect(
    host=os.getenv("MYSQL_HOST"),
    user=os.getenv("MYSQL_USER"),
    password=os.getenv("MYSQL_PASSWORD"),
    database=os.getenv("MYSQL_DATABASE")
)


r = redis.Redis(host=os.getenv("REDIS_HOST"), port=os.getenv("REDIS_PORT"), decode_responses=True)

def fetchAvailableCode():
    try:
        find_one_code_query = "SELECT id, code FROM url_code WHERE is_used = 0 limit 1"
        with dbConn.cursor() as cursor:
            cursor.execute(find_one_code_query)
            urlCodeResult = cursor.fetchone()

            if urlCodeResult:
                id, code = urlCodeResult

                flag_code_used_query = "UPDATE url_code SET is_used = 1 WHERE id = %s AND is_used = 0"
                cursor.execute(flag_code_used_query, (id,))
                dbConn.commit()
            else:
                raise Exception("No available short URLs")
    except Error as e:
        raise Exception("Could not get short URL")
    
    return code

def saveUrlsInRedis(key, value):
    r.set(key, value)

def findLongUrl(short_url_key: str)->str:
    url_record = r.get(short_url_key)
    if not url_record:
        return None
    
    url_record_decoded = json.loads(url_record)
    
    return url_record_decoded["url"]