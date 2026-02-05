<?php

namespace Dadinaks\Exits\Infrastructure\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\OpenApi\Model\Operation;
use Dadinaks\Exits\Adapter\Dto\InputDto;
use Dadinaks\Exits\Adapter\Dto\OutputDto;
use Dadinaks\Exits\Adapter\Dto\UpdateDto;
use Dadinaks\Exits\Infrastructure\Api\Processor\ExitsProcessor;
use Dadinaks\Exits\Infrastructure\Api\Provider\ExitsProvider;

#[ApiResource(
    shortName: 'Exits',
    description: 'Represents an exits record in the inventory system.',
    uriTemplate: '/exits',
    operations: [
        new Post(
            name: 'app_create_exit',
            input: InputDto::class,
            output: OutputDto::class,
            processor: ExitsProcessor::class,
            security: "is_granted('ROLE_EMPLOYEE')",
            openapi: new Operation(
                summary: 'Create a new exit',
                description: 'Creates a new exit with the provided details.'
            )
        ),
        new GetCollection(
            name: 'app_list_exits',
            output: OutputDto::class,
            provider: ExitsProvider::class,
            security: "is_granted('IS_AUTHENTICATED_FULLY')",
            openapi: new Operation(
                summary: 'Retrieve a list of exits',
                description: 'Retrieves a collection of all exits in the system.'
            )
        ),
        new Get(
            name: 'app_show_exit',
            uriTemplate: '/exits/{uid}',
            output: OutputDto::class,
            provider: ExitsProvider::class,
            security: "is_granted('IS_AUTHENTICATED_FULLY')",
            openapi: new Operation(
                summary: 'Retrieve a specific exit',
                description: 'Retrieves the details of a specific exit by its unique identifier.'
            )
        ),
        new Patch(
            name: 'app_edit_exit',
            uriTemplate: '/exits/{uid}/edit',
            input: UpdateDto::class,
            output: OutputDto::class,
            processor: ExitsProcessor::class,
            provider: ExitsProvider::class,
            security: "is_granted('ROLE_EMPLOYEE')",
            openapi: new Operation(
                summary: 'Update an existing exit',
                description: 'Updates the details of an existing exit.'
            )
        ),
        new Delete(
            name: 'app_delete_exit',
            uriTemplate: '/exits/{uid}/delete',
            output: OutputDto::class,
            processor: ExitsProcessor::class,
            provider: ExitsProvider::class,
            security: "is_granted('ROLE_EMPLOYEE')",
            status: 200,
            openapi: new Operation(
                summary: 'Delete an exit',
                description: 'Deletes a specific exit from the system.'
            )
        ),
        new Put(
            name: 'app_restore_exit',
            uriTemplate: '/exits/{uid}/restore',
            input: false,
            output: OutputDto::class,
            processor: ExitsProcessor::class,
            provider: ExitsProvider::class,
            security: "is_granted('ROLE_ADMIN')",
            openapi: new Operation(
                summary: 'Restore a deleted exit',
                description: 'Restores a previously deleted exit in the system.'
            )
        ),
    ]
)]
final class ExitsApi {}
