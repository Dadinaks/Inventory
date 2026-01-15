<?php

namespace Dadinaks\Product\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Dadinaks\Product\Application\UseCase\AddThreshold;
use Dadinaks\Product\Application\UseCase\CreateProduct;
use Dadinaks\Shared\Adapter\Interface\PresenterInterface;

final class ProductProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly CreateProduct $useCaseCreate,
        private readonly AddThreshold $useCaseThereshold,
        private readonly PresenterInterface $presenter,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (isset($uriVariables['uid'])) {
            $product = $this->useCaseThereshold->execute(
                $uriVariables['uid'],
                $data->threshold
            );

            return $this->presenter->presentSuccess(
                201,
                'Threshold updated successfully.',
                $product
            );
        }

        $output = $this->useCaseCreate->execute(
            $data->name,
        );

        return $this->presenter->presentSuccess(
            201,
            'Product created successfully',
            $output
        );
    }
}
