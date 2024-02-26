import MySQLdb

db_config = {
    'host': 'localhost',
    'user': 'your_db_username',
    'passwd': 'your_db_password',
    'db': 'your_db_name',
}

# Create a connection to the database
conn = MySQLdb.connect(**db_config)

def fetchAvailableCode():
    pass

