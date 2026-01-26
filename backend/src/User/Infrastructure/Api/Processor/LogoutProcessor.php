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

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $output = $this->useCaseLogout->execute(
            $data->username,
            $data->token
        );

        return $this->presenter->presentSuccess(
            200,
            'User disconnected successfully',
            $output
        );
    }
}
