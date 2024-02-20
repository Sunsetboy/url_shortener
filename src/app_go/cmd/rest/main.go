package main

import (
	"fmt"
	"log"
	"net/http"
	"os"
	repository "url_shortener/pkg/repository/dbrepo"

	"github.com/go-redis/redis"
	"github.com/go-sql-driver/mysql"
	"github.com/joho/godotenv"
)

type application struct {
	DSN   string
	DB    repository.DatabaseRepo
	Redis *redis.Client
}

const port = 8088

func main() {
	godotenv.Load()
	// set up an app config
	app := application{}

	dbConfig := mysql.Config{
		User:                 os.Getenv("DB_USER"),
		Passwd:               os.Getenv("DB_PASS"),
		Net:                  "tcp",
		Addr:                 fmt.Sprintf("%s:%s", os.Getenv("DB_HOST"), os.Getenv("DB_PORT")),
		DBName:               os.Getenv("DB_NAME"),
		AllowNativePasswords: true,
		ParseTime:            true,
	}
	app.DSN = dbConfig.FormatDSN()

	conn, err := app.connectToDB()
	if err != nil {
		log.Fatal(err)
	}
	defer conn.Close()

	app.DB = &repository.MysqlDBRepo{DB: conn}

	// connect to Redis
	redisClient := redis.NewClient(&redis.Options{
		Addr:     os.Getenv("REDIS_ADDR"),
		Password: "",
		DB:       0,
	})
	pong, err := redisClient.Ping().Result()
	if err != nil {
		log.Fatal(err)
	}
	log.Println("Redis ping: ", pong)
	app.Redis = redisClient

	// get application routes
	mux := app.routes()

	// print out a message
	log.Printf("starting server port %d", port)

	// start the server
	err = http.ListenAndServe(fmt.Sprintf(":%d", port), mux)
	if err != nil {
		log.Fatal(err)
	}
}
