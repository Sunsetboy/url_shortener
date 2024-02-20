package main

import (
	"net/http"

	"github.com/go-chi/chi"
	"github.com/go-chi/chi/middleware"
)

func (app *application) routes() http.Handler {
	// create a router mux
	mux := chi.NewRouter()

	// add middlewares
	mux.Use(middleware.Recoverer)
	mux.Use(app.enableCORS)

	mux.Post("/api/url", app.AddUrl)
	mux.Get("/{shortUrl:[a-zA-Z0-9]{8}}", app.RedirectToUrl)

	return mux
}
