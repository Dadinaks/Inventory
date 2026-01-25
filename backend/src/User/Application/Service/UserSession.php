<?php

namespace Dadinaks\User\Application\Service;

use Dadinaks\User\Domain\Entity\User;
use Symfony\Component\HttpFoundation\Request;

final class UserSession
{
    private const SESSION_KEY = '_security_main';
    private const USER_DATA_KEY = 'user_data';
    private const TOKEN_EXPIRY_KEY = 'token_expires_at';

    public function __construct(
        private readonly Request $request,
    ) {}

    public function startSession(User $user, int $token = 3600): void
    {
        $session = $this->request->getSession();

        if (!$session->isStarted()) {
            $session->start();
        }

        $session->migrate(true);

        $session->set(self::SESSION_KEY, serialize([
            'id' => $user->getUid(),
            'username' => $user->getUserIdentifier(),
            'roles' => [$user->getRole()->getRole()],
        ]));

        $session->set(self::USER_DATA_KEY, [
            'id' => $user->getUid(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'username' => $user->getUserIdentifier(),
            'login_at' => new \DateTimeImmutable(),
        ]);

        $session->set(self::TOKEN_EXPIRY_KEY, time() + $token);

        $session->set('lifetime', $token);
    }
}
