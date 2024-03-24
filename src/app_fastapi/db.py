import os
import redis
import json
import psycopg2
from psycopg2 import sql


def connect_to_db():
    conn = psycopg2.connect(
        host = os.getenv("POSTGRES_HOST"),
        user = os.getenv("POSTGRES_USER"),
        password = os.getenv("POSTGRES_PASSWORD"),
        database = os.getenv("POSTGRES_DB"),
    )
    return conn


r = redis.Redis(
    host=os.getenv("REDIS_HOST"), port=os.getenv("REDIS_PORT"), decode_responses=True
)


def fetchAvailableCode():
    dbConn = connect_to_db()
    query = """
        UPDATE url_code
        SET is_used = 1
        WHERE id IN (
            SELECT id FROM url_code
            WHERE is_used = 0
            LIMIT 1
        )
        RETURNING code
    """
    try:
        with dbConn.cursor() as cursor:
            cursor.execute(query)
            urlCodeResult = cursor.fetchone()
            dbConn.commit()
            if not urlCodeResult:
                raise Exception("No available short URLs") 
    except (Exception, psycopg2.DatabaseError) as error:
        raise Exception("Could not fetch available URL") 
    finally:
        # Closing the cursor & connection
        if cursor:
            cursor.close()
        if dbConn:
            dbConn.close()

    return urlCodeResult[0]


def saveUrlsInRedis(key, value):
    r.set(key, value)


def findLongUrl(short_url_key: str) -> str:
    url_record = r.get(short_url_key)
    if not url_record:
        return None

    url_record_decoded = json.loads(url_record)

    return url_record_decoded["url"]
