<?php

namespace Dadinaks\User\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Dadinaks\User\Application\UseCase\Auth\Logout;
use Dadinaks\Shared\Adapter\Interface\PresenterInterface;

final class LogoutProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly Logout $useCaseLogout,
        private readonly PresenterInterface $presenter,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $output = $this->useCaseLogout->execute(
            username: $data->username,
            token: $data->token
        );

        return $this->presenter->presentSuccess(
            code: 200,
            message: 'User disconnected successfully',
            data: $output
        );
    }
}
