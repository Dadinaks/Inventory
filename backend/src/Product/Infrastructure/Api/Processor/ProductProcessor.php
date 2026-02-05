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
                $product = $this->useCaseDelete->execute(uid: $uriVariables['uid'], deletedBy: $user);
                return $this->presenter->presentSuccess(
                    code: 200,
                    message: 'Product deleted successfully.',
                    data: $product
                );
            }

            if ($operation instanceof Put) {
                $product = $this->useCaseRestore->execute(uid: $uriVariables['uid'], updatedBy: $user);

                return $this->presenter->presentSuccess(
                    code: 201,
                    message: 'Product restored successfully.',
                    data: $product
                );
            }

            $product = $this->useCaseThereshold->execute(
                uid: $uriVariables['uid'],
                threshold: $data->threshold,
                updatedBy: $user
            );

            return $this->presenter->presentSuccess(
                code: 201,
                message: 'Threshold updated successfully.',
                data: $product
            );
        }

        $output = $this->useCaseCreate->execute(
            name: $data->name,
            createdBy: $user
        );

        return $this->presenter->presentSuccess(
            code: 201,
            message: 'Product created successfully',
            data: $output
        );
    }
}
