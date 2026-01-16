<?php

namespace Dadinaks\Role\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Dadinaks\Role\Application\UseCase\CreateRole;
use Dadinaks\Shared\Adapter\Interface\PresenterInterface;

final class RoleProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly CreateRole $useCaseCreate,
        private readonly PresenterInterface $presenter,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $output = $this->useCaseCreate->execute(
            $data->role,
            $data->label,
            $data->description
        );

        return $this->presenter->presentSuccess(
            201,
            'Role created successfully',
            $output
        );
    }
}
