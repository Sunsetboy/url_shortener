package main

import (
	"database/sql"
	"log"
	"time"
)

func openDB(dsn string) (*sql.DB, error) {
	db, err := sql.Open("mysql", dsn)
	if err != nil {
		return nil, err
	}
	err = db.Ping()
	if err != nil {
		return nil, err
	}
	return db, nil
}

func (app *application) connectToDB() (*sql.DB, error) {
	time.Sleep(time.Second * 5)
	connection, err := openDB(app.DSN)
	if err != nil {
		return nil, err
	}
	log.Println("connected to Mysql")

	return connection, nil
}
