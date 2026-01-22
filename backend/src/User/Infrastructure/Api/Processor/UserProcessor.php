<?php

namespace Dadinaks\User\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Dadinaks\Shared\Adapter\Interface\PresenterInterface;
use Dadinaks\User\Application\UseCase\CreateUser;

final class UserProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly CreateUser $useCaseCreate,
        private readonly PresenterInterface $presenter,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
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
