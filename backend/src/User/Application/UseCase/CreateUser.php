<?php

namespace Dadinaks\User\Application\UseCase;

use Dadinaks\Role\Adapter\Dto\OutputDto as RoleOutputDto;
use Dadinaks\Role\Domain\Repository\RoleRepositoryInterface;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Dadinaks\User\Adapter\Dto\OutputDto;
use Dadinaks\User\Application\Policy\PasswordPolicy;
use Dadinaks\User\Application\Policy\UsernamePolicy;
use Dadinaks\User\Domain\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class CreateUser
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository,
        private readonly RepositoryInterface $repository,
        private readonly UsernamePolicy $usernamePolicy,
        private readonly PasswordPolicy $passwordPolicy,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function execute(string $firstname, string $lastname, string $username, string $password, string $roleUid): OutputDto
    {
        if (empty($firstname)) {
            throw new \DomainException('Firstname cannot be empty.');
        }

        if (empty($lastname)) {
            throw new \DomainException('Lastname cannot be empty.');
        }

        if ($this->usernamePolicy->isAlreadyUsed($username)) {
            throw new \DomainException(
                sprintf('User with username: "%s" already exists.', $username)
            );
        }

        if (empty($password)) {
            throw new \DomainException('Password cannot be empty.');
        } elseif (!$this->passwordPolicy->isValid($password)) {
            throw new \DomainException(
                'The password must contain at least one lowercase letter, one uppercase letter, one number and one special character.'
            );
        }

        if (empty($roleUid)) {
            throw new \DomainException('Role UID cannot be empty.');
        }

        $role = $this->roleRepository->findByUid($roleUid);

        if (!$role) {
            throw new \DomainException(
                sprintf('Role with UID: "%s" does not exist.', $roleUid)
            );
        }

        $user = new User(
            firstname: $firstname,
            lastname: $lastname,
            username: $username,
            password: $this->passwordHasher->hashPassword(
                new User($firstname, $lastname, $username, '', $role),
                $password
            ),
            role: $role,
        );

        $this->repository->save($user);

        return new OutputDto(
            uid: $user->getUid(),
            firstname: $user->getFirstname(),
            lastname: $user->getLastname(),
            username: $user->getUserIdentifier(),
            isActive: $user->getIsActive(),
            isConnected: $user->getIsConnected(),
            role: new RoleOutputDto(
                uid: $role->getUid(),
                role: $role->getRole(),
                label: $role->getLabel(),
                description: $role->getDescription(),
            ),
            createdAt: $user->getCreatedAt(),
            updatedAt: $user->getUpdatedAt(),
            deletedAt: $user->getDeletedAt(),
        );
    }
}
