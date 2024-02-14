<?php
declare(strict_types=1);

namespace App\Service;

use App\Exceptions\EntityNotFoundException;
use App\Repository\KeyRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class UrlService
{
    public function __construct(
        private readonly KeyRepository  $keyRepository,
        private readonly CacheInterface $cacheInterface,

    ) {

    }

    public function createShortUrl(string $longUrl): string
    {
        // get a short URL from urls storage
        $shortUrlKey = $this->keyRepository->fetchAvailableKey();

        // save short and long URL to the key-value storage
        $this->cacheInterface->get($this->getUrlKey($shortUrlKey), function (ItemInterface $item) use ($longUrl): string {
            return json_encode(["url" => $longUrl]);
        });

        // return the short URL

        return $shortUrlKey;
    }

    public function findLongUrl(string $shortUrl): string
    {
        $urlInfo = $this->cacheInterface->get($this->getUrlKey($shortUrl), function () {
            return null;
        });
        if (!$urlInfo) {
            throw new EntityNotFoundException('URL not found');
        }
        $urlInfoDecoded = json_decode($urlInfo, true);
        if (!$urlInfoDecoded || !$urlInfoDecoded['url']) {
            throw new EntityNotFoundException('URL not found');
        }
        return $urlInfoDecoded['url'];
    }

    private function getUrlKey(string $shortUrl): string
    {
        return 'url_' . $shortUrl;
    }
}