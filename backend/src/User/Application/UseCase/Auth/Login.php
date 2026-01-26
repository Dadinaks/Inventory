<?php

namespace Dadinaks\User\Application\UseCase\Auth;

use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Dadinaks\User\Adapter\Dto\OutputLoginDto;
use Dadinaks\Role\Adapter\Dto\OutputDto as RoleOutputDto;
use Dadinaks\User\Domain\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

final class Login
{
    public function __construct(
        private readonly RepositoryInterface $repository,
        private readonly JWTTokenManagerInterface $tokenManager
    ) {}

    private function payload(User $user): array
    {
        return [
            'username'      => $user->getUserIdentifier(),
            'isConnected'   => $user->getIsConnected(),
            'isActive'      => $user->getIsActive(),
            'roles'         => $user->getRole()->getLabel(),
        ];
    }

    public function execute(string $username, string $password): OutputLoginDto
    {
        $user = $this->repository->findOneBy(['username' => $username]);

        if (!$user || !password_verify($password, $user->getPassword())) {
            throw new \DomainException('Invalid credentials.');
        }

        $token = $this->tokenManager->createFromPayload($user, $this->payload($user));
        $user->markAsConnected();
        $this->repository->save($user);

        return new OutputLoginDto(
            uid: $user->getUid(),
            firstname: $user->getFirstname(),
            lastname: $user->getLastname(),
            username: $user->getUserIdentifier(),
            isActive: $user->getIsActive(),
            isConnected: $user->getIsConnected(),
            role: new RoleOutputDto(
                uid: $user->getRole()->getUid(),
                role: $user->getRole()->getRole(),
                label: $user->getRole()->getLabel(),
                description: $user->getRole()->getDescription(),
            ),
            token: $token
        );
    }
}
