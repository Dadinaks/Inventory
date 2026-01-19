<?php

namespace Dadinaks\Role\Infrastructure\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use Dadinaks\Role\Adapter\Dto\InputDto;
use Dadinaks\Role\Adapter\Dto\OutputDto;
use Dadinaks\Role\Infrastructure\Api\Processor\RoleProcessor;
use Dadinaks\Role\Infrastructure\Api\Provider\RoleProvider;

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
        new GetCollection(
            output: OutputDto::class,
            provider: RoleProvider::class,
            openapi: new Operation(
                summary: 'Retrieve all roles',
                description: 'Fetches a collection of all roles.'
            )
        ),
        new Get(
            uriTemplate: '/roles/{uid}',
            output: OutputDto::class,
            provider: RoleProvider::class,
            openapi: new Operation(
                summary: 'Retrieve a role by UID',
                description: 'Fetches a single role by its unique identifier.'
            )
        )
    ]
)]
final class RoleApi {}
