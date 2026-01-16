<?php

namespace Dadinaks\Role\Infrastructure\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use Dadinaks\Role\Adapter\Dto\InputDto;
use Dadinaks\Role\Adapter\Dto\OutputDto;
use Dadinaks\Role\Infrastructure\Api\Processor\RoleProcessor;

#[ApiResource(
    shortName: 'Role',
    description: 'Roles resource',
    uriTemplate: '/roles',
    operations: [
        new Post(
            input: InputDto::class,
            output: OutputDto::class,
            processor: RoleProcessor::class,
            openapi: new Operation(
                summary: 'Create a new role',
                description: 'Creates a new role with the provided details.'
            )
        ),
    ]
)]
final class RoleApi {}
