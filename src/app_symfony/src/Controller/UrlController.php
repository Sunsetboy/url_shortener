<?php
declare(strict_types=1);

namespace App\Controller;


use App\Exceptions\BadRequestException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\UrlService;
use Symfony\Component\HttpFoundation\Response;

class UrlController extends AbstractController
{
    public function __construct(
        private readonly UrlService $urlService,
    )
    {
    }

    #[Route('/api/url', methods: ['POST'])]
    public function createUrl(Request $request): JsonResponse
    {
        $longUrl = (string)$request->toArray()['url'];

        if (!$longUrl) {
            throw new BadRequestException('URL is required');
        }

        // create a short URL, store it and return
        $shortUrl = $this->urlService->createShortUrl($longUrl);

        return $this->json(['short_url' => $shortUrl]);
    }

    #[Route('/{shortUrl}', requirements: ['shortUrl' => '[0-9a-zA-Z]{8}'], methods: ['GET'])]
    public function redirectToUrl(string $shortUrl): Response
    {
        $longUrl = $this->urlService->findLongUrl($shortUrl);

        // find a full URL corresponding to the specified short one and redirect
        return $this->redirect($longUrl);
    }
}
