<?php

namespace Dadinaks\Shared\Infrastructure\Api\EventListener;

use ApiPlatform\Validator\Exception\ValidationException;
use Dadinaks\Shared\Adapter\Interface\PresenterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelInterface;

final class ApiExceptionListener
{
    public function __construct(
        private PresenterInterface $presenter,
        private KernelInterface $kernel
    ) {}

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ValidationException) {
            $violations = $exception->getConstraintViolationList();

            $message = $violations->count() > 0
                ? $violations[0]->getMessage()
                : 'Validation error.';

            $this->respond($event, 422, $message);
            return;
        }

        if ($exception instanceof \DomainException) {
            $this->respond($event, 409, $exception->getMessage());
            return;
        }

        if ($exception instanceof HttpExceptionInterface) {
            $this->respond(
                $event,
                $exception->getStatusCode(),
                $exception->getMessage()
            );
            return;
        }

        $message = $this->kernel->isDebug()
            ? sprintf(
                '%s: %s',
                $exception::class,
                $exception->getMessage()
            )
            : 'Internal server error.';

        $this->respond($event, 500, $message);
    }

    private function respond(ExceptionEvent $event, int $statusCode, string $message): void
    {
        $event->setResponse(
            new JsonResponse(
                $this->presenter->presentError(
                    code: $statusCode,
                    message: $message,
                    data: []
                ),
                $statusCode
            )
        );
    }
}
