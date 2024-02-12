<?php
declare(strict_types=1);

namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UrlController
{
    #[Route('/api/url', methods: ['POST'])]
    public function createUrl(): JsonResponse
    {
        // create a short URL, store it and return
    }

    #[Route('/{shortUrl}', requirements: ['shortUrl' => '[0-9a-zA-Z]{8}'], methods: ['GET'])]
    public function redirectToUrl(string $shortUrl): JsonResponse
    {
        // find a full URL corresponding to the specified short one and redirect

    }
}
