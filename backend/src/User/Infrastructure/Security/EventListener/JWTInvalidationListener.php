<?php

namespace Dadinaks\User\Infrastructure\Security\EventListener;

use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Psr\Cache\CacheItemPoolInterface;

final class JWTInvalidationListener
{
    public function __construct(
        private readonly CacheItemPoolInterface $cache,
        private readonly RepositoryInterface $repository
    ) {}

    public function onJWTDecoded(JWTDecodedEvent $event): void
    {
        $payload = $event->getPayload();

        if (isset($payload['jti'])) {
            $cacheItem = $this->cache->getItem(key: 'jwt_blocklist_' . $payload['jti']);
            if ($cacheItem->isHit()) {
                $event->markAsInvalid();
                return;
            }
        }

        if (!isset($payload['username'])) {
            $event->markAsInvalid();
            return;
        }

        $user = $this->repository->findOneBy(criteria: ['username' => $payload['username']]);

        if (!$user->getIsConnected()) {
            $event->markAsInvalid();
            return;
        }

        if (!$user->getIsActive()) {
            if ($user->getIsConnected()) {
                $user->markAsDisconnected();
                $this->repository->save(entity: $user);
            }
            $event->markAsInvalid();
            return;
        }
    }
}
