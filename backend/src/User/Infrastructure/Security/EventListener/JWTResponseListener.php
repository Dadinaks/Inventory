<?php

namespace Dadinaks\User\Infrastructure\Security\EventListener;

use Dadinaks\Shared\Adapter\Interface\PresenterInterface;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

final class JWTResponseListener
{
    public function __construct(
        private readonly PresenterInterface $presenter,
        private readonly RepositoryInterface $repository,
    ) {}

    public function onJWTExpired(JWTExpiredEvent $event): void
    {
        $request = $event->getRequest();

        $authHeader = $request->headers->get('Authorization');
        if ($authHeader && preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            $token = $matches[1];

            try {
                $parts = explode('.', $token);
                if (count($parts) === 3) {
                    $payload = json_decode(base64_decode($parts[1]), true);

                    if (isset($payload['username'])) {
                        $user = $this->repository->findOneBy(['username' => $payload['username']]);
                        if ($user && $user->getIsConnected()) {
                            $user->markAsDisconnected();
                            $this->repository->save($user);
                        }
                    }
                }
            } catch (\Exception $e) {
            }
        }

        $this->sendUniformResponse($event, 401, 'Session expired, please login again');
    }

    public function formatInvalidTokenResponse(JWTInvalidEvent $event): void
    {
        $this->sendUniformResponse(event: $event, code: 401, message: 'Invalid or expired JWT Token');
    }

    public function formatFailureResponse(AuthenticationFailureEvent $event): void
    {
        $this->sendUniformResponse(event: $event, code: 401, message: 'Invalid credentials');
    }

    private function sendUniformResponse($event, int $code, string $message): void
    {
        $data = $this->presenter->presentError(code: $code, message: $message, data: []);
        $event->setResponse(new JsonResponse($data, $code));
    }
}
