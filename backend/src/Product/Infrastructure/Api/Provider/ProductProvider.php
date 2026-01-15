<?php

namespace Dadinaks\Product\Infrastructure\Api\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Dadinaks\Product\Application\UseCase\ListProduct;
use Dadinaks\Shared\Adapter\Interface\PresenterInterface;

final class ProductProvider implements ProviderInterface
{
    public function __construct(
        private readonly ListProduct $useCaseList,
        private readonly PresenterInterface $presenter
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $output = $this->useCaseList->execute();

        return $this->presenter->presentSuccess(
            200,
            'Products retrieved successfully',
            $output
        );
    }
}
