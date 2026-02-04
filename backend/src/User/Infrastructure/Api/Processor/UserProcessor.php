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
                $output = $this->useCaseDisable->execute($uriVariables['uid']);

                return $this->presenter->presentSuccess(
                    201,
                    'User disabled successfully',
                    $output
                );
            }

            if ($operation instanceof Post) {
                $output = $this->useCaseEnable->execute($uriVariables['uid']);

                return $this->presenter->presentSuccess(
                    201,
                    'Enable user successfully',
                    $output
                );
            }

            if ($operation instanceof Delete) {
                $output = $this->useCaseDelete->execute($uriVariables['uid']);

                return $this->presenter->presentSuccess(
                    200,
                    'User deleted successfully',
                    $output
                );
            }

            if ($operation instanceof Patch) {
                $output = $this->useCaseRestore->execute($uriVariables['uid']);

                return $this->presenter->presentSuccess(
                    201,
                    'User restored successfully',
                    $output
                );
            }
        }

        $output = $this->useCaseCreate->execute(
            $data->firstname,
            $data->lastname,
            $data->username,
            $data->password,
            $data->role
        );

        return $this->presenter->presentSuccess(
            201,
            'User created successfully',
            $output
        );
    }
}
