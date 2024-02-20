package main

import (
	"log"
	"net/http"

	"github.com/go-chi/chi"
)

func (app *application) RedirectToUrl(w http.ResponseWriter, r *http.Request) {
	shortUrl := chi.URLParam(r, "shortUrl")
	longUrl := "https://100yuristov.com"

	log.Println(shortUrl)

	// todo: redirect, not return JSON
	_ = app.writeJSON(w, http.StatusOK, longUrl)
}

func (app *application) AddUrl(w http.ResponseWriter, r *http.Request) {
	shortUrlCode := "Abcd1234"
	_ = app.writeJSON(w, http.StatusOK, shortUrlCode)
}
