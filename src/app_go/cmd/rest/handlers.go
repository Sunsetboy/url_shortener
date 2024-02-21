package main

import (
	"encoding/json"
	"fmt"
	"log"
	"net/http"
	"time"

	"github.com/go-chi/chi"
)

type addUrlResponse struct {
	ShortUrl string `json:"short_url"`
}

type urlModel struct {
	Url string `json:"url"`
}

type longUrlRequest struct {
	Url string `json:"url"`
}

func (app *application) RedirectToUrl(w http.ResponseWriter, r *http.Request) {
	shortUrl := chi.URLParam(r, "shortUrl")
	longUrlCached, err := app.Redis.Get(app.generateUrlCacheKey(shortUrl)).Result()
	if err != nil {
		app.writeJSON(w, http.StatusNotFound, "URL not found")
		return
	}

	var urlModel urlModel
	err = json.Unmarshal([]byte(longUrlCached), &urlModel)
	if err != nil {
		app.writeJSON(w, http.StatusNotFound, "URL not found")
		return
	}

	http.Redirect(w, r, urlModel.Url, http.StatusFound)
}

func (app *application) AddUrl(w http.ResponseWriter, r *http.Request) {
	var longUrlRequestPayload longUrlRequest

	err := app.readJSON(w, r, &longUrlRequestPayload)
	if err != nil {
		app.writeJSON(w, http.StatusBadRequest, "Incorrect request body")
		return
	}
	if longUrlRequestPayload.Url == "" {
		app.writeJSON(w, http.StatusBadRequest, "Incorrect request body")
		return
	}

	shortUrlCode, err := app.DB.FetchAvailableCode()
	if err != nil {
		log.Println(err)
		app.writeJSON(w, http.StatusInternalServerError, "Could not create a short URL")
		return
	}

	cacheValue, err := json.Marshal(urlModel{Url: longUrlRequestPayload.Url})
	_, err = app.Redis.Set(app.generateUrlCacheKey(shortUrlCode), cacheValue, time.Hour*1000000).Result()
	if err != nil {
		log.Println(err)
		app.writeJSON(w, http.StatusInternalServerError, "Could not create a short URL")
		return
	}
	response := addUrlResponse{
		ShortUrl: shortUrlCode,
	}

	_ = app.writeJSON(w, http.StatusOK, response)
}

func (app *application) generateUrlCacheKey(shortUrl string) string {
	return fmt.Sprintf("url_%s", shortUrl)
}

func (app *application) GenerateCodes(w http.ResponseWriter, r *http.Request) {
	generatedCodesCount, err := app.DB.GenerateUrlCodes(10000)
	if err != nil {
		log.Println(err)
		app.writeJSON(w, http.StatusInternalServerError, "Could not generate codes")
		return
	}
	app.writeJSON(w, http.StatusOK, fmt.Sprintf("Codes generated: %d", generatedCodesCount))
}
