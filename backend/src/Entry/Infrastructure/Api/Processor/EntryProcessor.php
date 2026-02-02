<?php

namespace Dadinaks\Entry\Infrastructure\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Dadinaks\Entry\Application\UseCase\NewEntry;
use Dadinaks\Product\Domain\Repository\ProductRepositoryInterface;
use Dadinaks\Shared\Adapter\Interface\PresenterInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Dadinaks\User\Domain\Entity\User;

final class EntryProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly NewEntry $newUseCase,
        private readonly PresenterInterface $presenter,
        private readonly ProductRepositoryInterface $repository,
        private readonly Security $security,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new \LogicException('User must be authenticated to perform this action.');
        }

        $output = $this->newUseCase->execute(
            $data->quantity,
            $data->productUid,
            $user
        );

        $product = $this->repository->findByUid($data->productUid);
        $name = $product ? $product->getName() : 'unknown';

        return $this->presenter->presentSuccess(
            201,
            "New entry for product $name created successfully",
            $output
        );
    }
}
