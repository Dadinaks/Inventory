<?php

namespace Dadinaks\User\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use Dadinaks\Shared\Adapter\Interface\PresenterInterface;
use Dadinaks\User\Application\UseCase\CreateUser;
use Dadinaks\User\Application\UseCase\DeleteUser;
use Dadinaks\User\Application\UseCase\DisabledUser;
use Dadinaks\User\Application\UseCase\EnableUser;
use Dadinaks\User\Application\UseCase\RestoreUser;

final class UserProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly CreateUser $useCaseCreate,
        private readonly DisabledUser $useCaseDisable,
        private readonly EnableUser $useCaseEnable,
        private readonly DeleteUser $useCaseDelete,
        private readonly RestoreUser $useCaseRestore,
        private readonly PresenterInterface $presenter,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (isset($uriVariables['uid'])) {
            if ($operation instanceof Put) {
                $output = $this->useCaseDisable->execute(uid: $uriVariables['uid']);

                return $this->presenter->presentSuccess(
                    code: 201,
                    message: 'User disabled successfully',
                    data: $output
                );
            }

            if ($operation instanceof Post) {
                $output = $this->useCaseEnable->execute(uid: $uriVariables['uid']);

                return $this->presenter->presentSuccess(
                    code: 201,
                    message: 'User enabled successfully',
                    data: $output
                );
            }

            if ($operation instanceof Delete) {
                $output = $this->useCaseDelete->execute(uid: $uriVariables['uid']);

                return $this->presenter->presentSuccess(
                    code: 200,
                    message: 'User deleted successfully',
                    data: $output
                );
            }

            if ($operation instanceof Patch) {
                $output = $this->useCaseRestore->execute(uid: $uriVariables['uid']);

                return $this->presenter->presentSuccess(
                    code: 201,
                    message: 'User restored successfully',
                    data: $output
                );
            }
        }

        $output = $this->useCaseCreate->execute(
            firstname: $data->firstname,
            lastname: $data->lastname,
            username: $data->username,
            password: $data->password,
            roleUid: $data->role
        );

        return $this->presenter->presentSuccess(
            code: 201,
            message: 'User created successfully',
            data: $output
        );
    }
}
