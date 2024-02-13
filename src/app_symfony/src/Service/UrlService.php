<?php
declare(strict_types=1);

namespace App\Service;

class UrlService
{
    public function createShortUrl(string $longUrl): string
    {
        // get a short URL from urls storage

        // save short and long URL to the key-value storage

        // return the short URL

        return "abcd1234";
    }

    public function findLongUrl(): string
    {
        return "https://100yuristov.com";
    }
}