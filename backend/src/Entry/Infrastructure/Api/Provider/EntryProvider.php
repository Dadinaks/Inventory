<?php

namespace Dadinaks\Entry\Infrastructure\Api\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Dadinaks\Entry\Application\UseCase\ListEntry;
use Dadinaks\Entry\Application\UseCase\ShowEntry;
use Dadinaks\Shared\Adapter\Interface\PresenterInterface;

final class EntryProvider implements ProviderInterface
{
    public function __construct(
        private readonly ListEntry $useCaseList,
        private readonly ShowEntry $useCaseShow,
        private readonly PresenterInterface $presenter,
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (isset($uriVariables['uid'])) {
            $output = $this->useCaseShow->execute($uriVariables['uid']);

            return $this->presenter->presentSuccess(
                200,
                'Entry retrieved successfully',
                $output
            );
        }

        $output = $this->useCaseList->execute();

        return $this->presenter->presentSuccess(
            200,
            'Entries retrieved successfully',
            $output
        );
    }
}
