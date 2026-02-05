<?php

namespace Dadinaks\User\Infrastructure\Security\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Psr\Cache\CacheItemPoolInterface;

final class JWTInvalidationListener
{
    public function __construct(
        private readonly CacheItemPoolInterface $cache
    ) {}

    public function onJWTDecoded(JWTDecodedEvent $event): void
    {
        $payload = $event->getPayload();

        if (!isset($payload['jti'])) {
            return;
        }

        $cacheItem = $this->cache->getItem(key: 'jwt_blocklist_' . $payload['jti']);

        if ($cacheItem->isHit()) {
            $event->markAsInvalid();
        }
    }
}
