<?php

namespace Dadinaks\Entry\Infrastructure\Api\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Dadinaks\Entry\Application\UseCase\ListEntry;
use Dadinaks\Shared\Adapter\Interface\PresenterInterface;

final class EntryProvider implements ProviderInterface
{
    public function __construct(
        private readonly ListEntry $useCaseList,
        private readonly PresenterInterface $presenter,
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $output = $this->useCaseList->execute();

        return $this->presenter->presentSuccess(
            200,
            'Entries retrieved successfully',
            $output
        );
    }
}
