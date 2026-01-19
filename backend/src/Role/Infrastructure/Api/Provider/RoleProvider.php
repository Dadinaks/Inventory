<?php

namespace Dadinaks\Role\Infrastructure\Api\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Dadinaks\Role\Adapter\Dto\OutputDto;
use Dadinaks\Role\Application\UseCase\GetOneRole;
use Dadinaks\Role\Application\UseCase\ListRole;
use Dadinaks\Shared\Adapter\Interface\PresenterInterface;

final class RoleProvider implements ProviderInterface
{
    public function __construct(
        private readonly PresenterInterface $presenter,
        private readonly ListRole $useCaseList,
        private readonly GetOneRole $useCaseGetOne,
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (isset($uriVariables['uid'])) {
            $output = $this->useCaseGetOne->execute($uriVariables['uid']);

            return $this->presenter->presentSuccess(
                200,
                'Role retrieved successfully',
                $output
            );
        }

        $output = $this->useCaseList->execute();

        return $this->presenter->presentSuccess(
            200,
            'Roles retrieved successfully',
            $output
        );
    }
}
