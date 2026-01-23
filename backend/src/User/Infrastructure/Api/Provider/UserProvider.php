<?php

namespace Dadinaks\User\Infrastructure\Api\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Dadinaks\Shared\Adapter\Interface\PresenterInterface;
use Dadinaks\User\Application\UseCase\ListUser;

final class UserProvider implements ProviderInterface
{
    public function __construct(
        private readonly ListUser $useCaseList,
        private readonly PresenterInterface $presenter
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $output = $this->useCaseList->execute();

        return $this->presenter->presentSuccess(
            200,
            'Users retrieved successfully',
            $output
        );
    }
}
