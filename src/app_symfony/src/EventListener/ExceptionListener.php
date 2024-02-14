<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exceptions\BadRequestException;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\InvalidEntityException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    /**
     * Обработчик кастомных исключений
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        switch (true) {
            case $exception instanceof EntityNotFoundException:
                $response = new JsonResponse(['message' => $exception->getMessage()], 404);
                break;
            case $exception instanceof BadRequestException:
                $response = new JsonResponse(['message' => $exception->getMessage()], 400);
                break;
            case $exception instanceof InvalidEntityException:
                $response = new JsonResponse(['errors' => json_decode($exception->getMessage(), true)], 400);
                break;
        }

        if (isset($response)) {
            $event->setResponse($response);
        }
    }
}
