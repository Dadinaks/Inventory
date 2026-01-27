<?php

namespace Dadinaks\Product\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use Dadinaks\Product\Application\UseCase\AddThreshold;
use Dadinaks\Product\Application\UseCase\CreateProduct;
use Dadinaks\Product\Application\UseCase\DeleteProduct;
use Dadinaks\Product\Application\UseCase\RestoreProduct;
use Symfony\Bundle\SecurityBundle\Security;
use Dadinaks\Shared\Adapter\Interface\PresenterInterface;
use Dadinaks\User\Domain\Entity\User;

final class ProductProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly CreateProduct $useCaseCreate,
        private readonly AddThreshold $useCaseThereshold,
        private readonly DeleteProduct $useCaseDelete,
        private readonly RestoreProduct $useCaseRestore,
        private readonly PresenterInterface $presenter,
        private readonly Security $security,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new \LogicException('User must be authenticated to perform this action.');
        }

        if (isset($uriVariables['uid'])) {
            if ($operation instanceof Delete) {
                $product = $this->useCaseDelete->execute($uriVariables['uid'], $user);
                return $this->presenter->presentSuccess(
                    200,
                    'Product deleted successfully.',
                    $product
                );
            }

            if ($operation instanceof Put) {
                $product = $this->useCaseRestore->execute($uriVariables['uid'], $user);

                return $this->presenter->presentSuccess(
                    201,
                    'Product restored successfully.',
                    $product
                );
            }

            $product = $this->useCaseThereshold->execute(
                $uriVariables['uid'],
                $data->threshold,
                $user
            );

            return $this->presenter->presentSuccess(
                201,
                'Threshold updated successfully.',
                $product
            );
        }

        $output = $this->useCaseCreate->execute(
            $data->name,
            $user
        );

        return $this->presenter->presentSuccess(
            201,
            'Product created successfully',
            $output
        );
    }
}
