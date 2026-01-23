<?php

namespace Dadinaks\User\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use Dadinaks\Shared\Adapter\Interface\PresenterInterface;
use Dadinaks\User\Application\UseCase\CreateUser;
use Dadinaks\User\Application\UseCase\DisabledUser;

final class UserProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly CreateUser $useCaseCreate,
        private readonly DisabledUser $useCaseDisable,
        private readonly PresenterInterface $presenter,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
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
