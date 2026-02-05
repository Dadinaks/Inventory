<?php

namespace Dadinaks\Exits\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use Dadinaks\Exits\Application\UseCase\DeleteExits;
use Dadinaks\Exits\Application\UseCase\EditExits;
use Dadinaks\Exits\Application\UseCase\NewExits;
use Dadinaks\Exits\Application\UseCase\RestoreExits;
use Dadinaks\Product\Domain\Repository\ProductRepositoryInterface;
use Dadinaks\Shared\Adapter\Interface\PresenterInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Dadinaks\User\Domain\Entity\User;

final class ExitsProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly NewExits $newUseCase,
        private readonly EditExits $editUseCase,
        private readonly DeleteExits $deleteUseCase,
        private readonly RestoreExits $restoreUseCase,
        private readonly PresenterInterface $presenter,
        private readonly ProductRepositoryInterface $repository,
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
                $exits = $this->deleteUseCase->execute(
                    uid: $uriVariables['uid'],
                    deletedBy: $user
                );

                return $this->presenter->presentSuccess(
                    code: 200,
                    message: "Exit deleted successfully.",
                    data: $exits
                );
            }

            if ($operation instanceof Patch) {
                $exits = $this->editUseCase->execute(
                    uid: $uriVariables['uid'],
                    quantity: $data->quantity,
                    updatedBy: $user
                );

                return $this->presenter->presentSuccess(
                    code: 201,
                    message: "Exit updated successfully.",
                    data: $exits
                );
            }

            if ($operation instanceof Put) {
                $exits = $this->restoreUseCase->execute(
                    uid: $uriVariables['uid'],
                    updatedBy: $user
                );

                return $this->presenter->presentSuccess(
                    code: 201,
                    message: "Exit restored successfully.",
                    data: $exits
                );
            }
        }

        $output = $this->newUseCase->execute(
            quantity: $data->quantity,
            productUid: $data->productUid,
            createdBy: $user
        );

        $product = $this->repository->findByUid(uid: $data->productUid);
        $name = $product ? $product->getName() : 'unknown';
        $code = $product ? $product->getCode() : 'unknown';

        return $this->presenter->presentSuccess(
            code: 201,
            message: "New exit for product $code - $name created successfully",
            data: $output
        );
    }
}
