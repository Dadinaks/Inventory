<?php

namespace Dadinaks\User\Infrastructure\Security\EventListener;

use Dadinaks\Shared\Adapter\Interface\PresenterInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

final class JWTResponseListener
{
    public function __construct(
        private readonly PresenterInterface $presenter
    ) {}

    public function formatInvalidTokenResponse(JWTInvalidEvent $event): void
    {
        $this->sendUniformResponse($event, 401, 'Invalid or expired JWT Token');
    }

    public function formatFailureResponse(AuthenticationFailureEvent $event): void
    {
        $this->sendUniformResponse($event, 401, 'Invalid credentials');
    }

    private function sendUniformResponse($event, int $code, string $message): void
    {
        $data = $this->presenter->presentError($code, $message, []);
        $event->setResponse(new JsonResponse($data, $code));
    }
}
