<?php

namespace Dadinaks\Entry\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\State\ProcessorInterface;
use Dadinaks\Entry\Application\UseCase\EditEntry;
use Dadinaks\Entry\Application\UseCase\NewEntry;
use Dadinaks\Product\Domain\Repository\ProductRepositoryInterface;
use Dadinaks\Shared\Adapter\Interface\PresenterInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Dadinaks\User\Domain\Entity\User;

final class EntryProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly NewEntry $newUseCase,
        private readonly EditEntry $editUseCase,
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
            if ($operation instanceof Patch) {
                $entry = $this->editUseCase->execute(
                    uid: $uriVariables['uid'],
                    quantity: $data->quantity,
                    updateBy: $user
                );

                return $this->presenter->presentSuccess(
                    code: 201,
                    message: "Entry updated successfully.",
                    data: $entry
                );
            }
        }

        $output = $this->newUseCase->execute(
            $data->quantity,
            $data->productUid,
            $user
        );

        $product = $this->repository->findByUid($data->productUid);
        $name = $product ? $product->getName() : 'unknown';
        $code = $product ? $product->getCode() : 'unknown';

        return $this->presenter->presentSuccess(
            201,
            "New entry for product $code - $name created successfully",
            $output
        );
    }
}
