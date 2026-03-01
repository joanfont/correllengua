<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\EventListener;

use function str_contains;

use App\Domain\Exception\Auth\InvalidCredentialsException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Throwable;

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

        if ($exception instanceof InvalidCredentialsException) {
            $statusCode = Response::HTTP_UNAUTHORIZED;
            $data = [
                'error' => 'unauthorized',
                'message' => $exception->getMessage(),
                'status' => $statusCode,
            ];

            $response = new JsonResponse($data, $statusCode);
            $event->setResponse($response);

            return;
        }

        if ($exception instanceof ValidationFailedException) {
            $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
            $data = [
                'error' => 'validation_error',
                'message' => 'Validation failed',
                'status' => $statusCode,
                'violations' => $this->extractViolationsFromValidationException($exception),
            ];

            $response = new JsonResponse($data, $statusCode);
            $event->setResponse($response);

            return;
        }

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

        // Handle UnprocessableEntityHttpException with wrapped ValidationFailedException
        if ($exception instanceof UnprocessableEntityHttpException && Response::HTTP_UNPROCESSABLE_ENTITY === $statusCode) {
            $violations = $this->extractViolations($exception);
            if (!empty($violations)) {
                $data['violations'] = $violations;
            }
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

    /**
     * @return array<array{field: string, message: string}>
     */
    private function extractViolations(UnprocessableEntityHttpException $exception): array
    {
        $violations = [];
        $previous = $exception->getPrevious();

        if ($previous instanceof ValidationFailedException) {
            return $this->extractViolationsFromValidationException($previous);
        }

        return $violations;
    }

    /**
     * @return array<array{field: string, message: string}>
     */
    private function extractViolationsFromValidationException(ValidationFailedException $exception): array
    {
        $violations = [];

        foreach ($exception->getViolations() as $violation) {
            $violations[] = [
                'field' => $violation->getPropertyPath(),
                'message' => (string) $violation->getMessage(),
            ];
        }

        return $violations;
    }
}
