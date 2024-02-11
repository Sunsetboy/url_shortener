FROM golang:1.21

WORKDIR /usr/src/app

# pre-copy/cache go.mod for pre-downloading dependencies and only redownloading them in subsequent builds if they change
COPY go.mod go.sum ./
RUN go mod download && go mod verify

COPY . .
RUN cd cmd/rest && go build -v -o /usr/local/bin/app main.go
EXPOSE 8082

RUN groupadd -g 1000 -r developer && useradd -r -m -u 1000 -g developer developer
USER developer

CMD ["app"]