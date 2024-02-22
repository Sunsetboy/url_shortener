FROM golang:1.21

RUN mkdir /app
COPY . /app
WORKDIR /app


# pre-copy/cache go.mod for pre-downloading dependencies and only redownloading them in subsequent builds if they change
RUN go mod download && go mod verify

RUN go build -v -o /app/urlShortener cmd/rest/*
RUN chmod +x /app/urlShortener
EXPOSE 8088

RUN groupadd -g 1000 -r developer && useradd -r -m -u 1000 -g developer developer
USER developer

CMD ["/app/urlShortener"]