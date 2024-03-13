package main

import (
	"fmt"
	"log"
	"net/http"
	"os"
	repository "url_shortener/pkg/repository/dbrepo"

	"github.com/go-redis/redis"
	"github.com/joho/godotenv"
)

type application struct {
	DSN   string
	DB    repository.DatabaseRepo
	Redis *redis.Client
}

func main() {
	godotenv.Load()
	// set up an app config
	app := application{}

	port := os.Getenv("PORT")

	// dbConfig := mysql.Config{
	// 	User:                 os.Getenv("POSTGRES_USER"),
	// 	Passwd:               os.Getenv("POSTGRES_PASSWORD"),
	// 	Net:                  "tcp",
	// 	Addr:                 fmt.Sprintf("%s:%s", os.Getenv("POSTGRES_HOST"), os.Getenv("POSTGRES_PORT")),
	// 	DBName:               os.Getenv("POSTGRES_DATABASE"),
	// 	AllowNativePasswords: true,
	// 	ParseTime:            true,
	// }
	app.DSN = os.Getenv("POSTGRES_DSN")

	conn, err := app.connectToDB()
	if err != nil {
		log.Fatal(err)
	}
	defer conn.Close()

	app.DB = &repository.PostgresDBRepo{DB: conn}

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
	log.Printf("starting server port %s", port)

	// start the server
	err = http.ListenAndServe(fmt.Sprintf(":%s", port), mux)
	if err != nil {
		log.Fatal(err)
	}
}
