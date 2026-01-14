<?php

namespace Dadinaks\Shared\Adapter\Presenter;

use Dadinaks\Shared\Adapter\Dto\OutputDtoInterface;
use Dadinaks\Shared\Adapter\Interface\PresenterInterface;

/**
 * Generic JSON presenter usable across all bounded contexts.
 *
 * Ensures a consistent response format while delegating
 * DTO serialization to OutputDto implementations.
 *
 * @author Dadinaks Cedrick <cedrick.henintsoa.8821@gmail.com>
 */
final class JsonPresenter implements PresenterInterface
{
    public function presentSuccess(int $code, string $message, array|OutputDtoInterface $data): array
    {
        return [
            'success' => true,
            'code'    => $code,
            'message' => $message,
            'data'    => $this->normalize($data),
        ];
    }

    public function presentError(int $code, string $message, ?array $data): array
    {
        return [
            'success' => false,
            'code'    => $code,
            'message' => $message,
            'data'    => $data ?? [],
        ];
    }

    private function normalize(array|OutputDtoInterface $data): array
    {
        if ($data instanceof OutputDtoInterface) {
            return [$data->toArray()];
        }

        return array_map(
            fn(OutputDtoInterface $dto) => $dto->toArray(),
            $data
        );
    }
}
