<?php

namespace Dadinaks\User\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Dadinaks\Shared\Adapter\Interface\PresenterInterface;
use Dadinaks\User\Application\UseCase\Auth\Login;

final class LoginProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly Login $useCaseLogin,
        private readonly PresenterInterface $presenter,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $output = $this->useCaseLogin->execute(
            username: $data->username,
            password: $data->password
        );

        return $this->presenter->presentSuccess(
            code: 201,
            message: 'User connected successfully',
            data: $output
        );
    }
}
