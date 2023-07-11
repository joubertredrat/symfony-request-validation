<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\InvalidJsonRequest;
use Fig\Http\Message\StatusCodeInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof InvalidJsonRequest) {
            $response = new JsonResponse(
                data: ['errors' => $exception->getErrors()],
                status: StatusCodeInterface::STATUS_BAD_REQUEST,
            );
            $event->setResponse($response);
        }
    }
}