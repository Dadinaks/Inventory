<?php

namespace Dadinaks\Role\Infrastructure\Api\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Dadinaks\Role\Adapter\Dto\OutputDto;
use Dadinaks\Role\Application\UseCase\ListRole;
use Dadinaks\Shared\Adapter\Interface\PresenterInterface;

final class RoleProvider implements ProviderInterface
{
    public function __construct(
        private readonly PresenterInterface $presenter,
        private readonly ListRole $useCaseList
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $output = $this->useCaseList->execute();

        return $this->presenter->presentSuccess(
            200,
            'Roles retrieved successfully',
            $output
        );
    }
}
