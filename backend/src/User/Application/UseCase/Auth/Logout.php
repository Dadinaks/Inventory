<?php

namespace Dadinaks\User\Application\UseCase\Auth;

use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Dadinaks\User\Adapter\Dto\OutputLogoutDto;
use Dadinaks\Role\Adapter\Dto\OutputDto as RoleOutputDto;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Psr\Cache\CacheItemPoolInterface;

final class Logout
{
    public function __construct(
        private readonly RepositoryInterface $repository,
        private readonly JWTEncoderInterface $tokenManager,
        private readonly CacheItemPoolInterface $cache
    ) {}

    public function execute(string $username, string $token): OutputLogoutDto
    {
        $user = $this->repository->findOneBy(criteria: ['username' => $username]);

        if (!$user) {
            throw new \DomainException('User not found.');
        }

        try {
            $tokenData = $this->tokenManager->decode(token: $token);

            if (isset($tokenData['jti'])) {
                $cacheItem = $this->cache->getItem(key: 'jwt_blocklist_' . $tokenData['jti']);
                $ttl = $tokenData['exp'] - time();

                if ($ttl > 0) {
                    $cacheItem->set(value: true);
                    $cacheItem->expiresAfter(time: $ttl);
                    $this->cache->save(item: $cacheItem);
                }
            }
        } catch (\Exception $e) {
            throw new \DomainException('Invalid or expired token : ' . $e->getMessage());
        }

        $user->markAsDisconnected();
        $this->repository->save(entity: $user);

        return new OutputLogoutDto(
            uid: $user->getUid(),
            username: $user->getUserIdentifier(),
            isActive: $user->getIsActive(),
            isConnected: $user->getIsConnected(),
            role: new RoleOutputDto(
                uid: $user->getRole()->getUid(),
                role: $user->getRole()->getRole(),
                label: $user->getRole()->getLabel(),
                description: $user->getRole()->getDescription(),
            ),
        );
    }
}
