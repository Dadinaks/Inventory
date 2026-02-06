<?php

namespace Dadinaks\Product\Infrastructure\Api\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Dadinaks\Product\Application\UseCase\ListProduct;
use Dadinaks\Product\Application\UseCase\ProductMovementHistory;
use Dadinaks\Shared\Adapter\Interface\PresenterInterface;

final class ProductProvider implements ProviderInterface
{
    public function __construct(
        private readonly ListProduct $useCaseList,
        private readonly ProductMovementHistory $useCaseHistory,
        private readonly PresenterInterface $presenter
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (isset($uriVariables['uid'])) {
            $output = $this->useCaseHistory->execute($uriVariables['uid']);

            return $this->presenter->presentSuccess(
                code: 200,
                message: 'Products movement history retrieved successfully',
                data: $output
            );
        }

        $output = $this->useCaseList->execute();

        return $this->presenter->presentSuccess(
            code: 200,
            message: 'Products retrieved successfully',
            data: $output
        );
    }
}
