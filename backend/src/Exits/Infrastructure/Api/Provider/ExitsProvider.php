<?php

namespace Dadinaks\Exits\Infrastructure\Api\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Dadinaks\Exits\Application\UseCase\ListExits;
use Dadinaks\Exits\Application\UseCase\ShowExits;
use Dadinaks\Shared\Adapter\Interface\PresenterInterface;

final class ExitsProvider implements ProviderInterface
{
    public function __construct(
        private readonly ListExits $useCaseList,
        private readonly ShowExits $useCaseShow,
        private readonly PresenterInterface $presenter,
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (isset($uriVariables['uid'])) {
            $output = $this->useCaseShow->execute(uid: $uriVariables['uid']);

            return $this->presenter->presentSuccess(
                code: 200,
                message: 'Exits retrieved successfully',
                data: $output
            );
        }

        $output = $this->useCaseList->execute();

        return $this->presenter->presentSuccess(
            code: 200,
            message: 'Exits retrieved successfully',
            data: $output
        );
    }
}
