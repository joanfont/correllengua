<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\EventListener;

use function str_contains;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

#[AsEventListener(event: KernelEvents::EXCEPTION, priority: 0)]
final readonly class JsonExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $request = $event->getRequest();

        $accept = $request->headers->get('Accept') ?? '';
        if (!$this->acceptsJson($accept)) {
            return;
        }

        $exception = $event->getThrowable();

        $statusCode = $exception instanceof HttpExceptionInterface
            ? $exception->getStatusCode()
            : Response::HTTP_INTERNAL_SERVER_ERROR;

        $data = [
            'error' => $this->getErrorType($exception),
            'message' => $exception->getMessage(),
        ];

        if ($exception instanceof HttpExceptionInterface) {
            $data['status'] = $statusCode;
        }

        $response = new JsonResponse($data, $statusCode);
        $event->setResponse($response);
    }

    private function acceptsJson(string $accept): bool
    {
        return str_contains($accept, 'application/json') || str_contains($accept, '*/*');
    }

    private function getErrorType(Throwable $exception): string
    {
        if ($exception instanceof HttpExceptionInterface) {
            return match ($exception->getStatusCode()) {
                400 => 'bad_request',
                401 => 'unauthorized',
                403 => 'forbidden',
                404 => 'not_found',
                405 => 'method_not_allowed',
                409 => 'conflict',
                415 => 'unsupported_media_type',
                422 => 'unprocessable_entity',
                500 => 'internal_server_error',
                503 => 'service_unavailable',
                default => 'http_exception',
            };
        }

        return 'server_error';
    }
}
