<?php

namespace Dadinaks\Entry\Infrastructure\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use Dadinaks\Entry\Adapter\Dto\InputDto;
use Dadinaks\Entry\Adapter\Dto\OutputDto;
use Dadinaks\Entry\Adapter\Dto\UpdateDto;
use Dadinaks\Entry\Infrastructure\Api\Processor\EntryProcessor;
use Dadinaks\Entry\Infrastructure\Api\Provider\EntryProvider;

#[ApiResource(
    shortName: 'Entry',
    description: 'Represents an entry record in the inventory system.',
    uriTemplate: '/entries',
    operations: [
        new Post(
            name: 'app_create_entry',
            input: InputDto::class,
            output: OutputDto::class,
            processor: EntryProcessor::class,
            security: "is_granted('ROLE_EMPLOYEE')",
            openapi: new Operation(
                summary: 'Create a new entry',
                description: 'Creates a new entry with the provided details.'
            )
        ),
        new GetCollection(
            name: 'app_list_entries',
            output: OutputDto::class,
            provider: EntryProvider::class,
            security: "is_granted('IS_AUTHENTICATED_FULLY')",
            openapi: new Operation(
                summary: 'Retrieve a list of entries',
                description: 'Retrieves a collection of all entries in the system.'
            )
        ),
        new Get(
            name: 'app_show_entry',
            uriTemplate: '/entries/{uid}',
            output: OutputDto::class,
            provider: EntryProvider::class,
            security: "is_granted('IS_AUTHENTICATED_FULLY')",
            openapi: new Operation(
                summary: 'Retrieve a specific entry',
                description: 'Retrieves the details of a specific entry by its unique identifier.'
            )
        ),
        new Patch(
            name: 'app_edit_entry',
            uriTemplate: '/entries/{uid}/edit',
            input: UpdateDto::class,
            output: OutputDto::class,
            processor: EntryProcessor::class,
            provider: EntryProvider::class,
            security: "is_granted('ROLE_EMPLOYEE')",
            openapi: new Operation(
                summary: 'Update an existing entry',
                description: 'Updates the details of an existing entry.'
            )
        ),
        new Delete(
            name: 'app_delete_entry',
            uriTemplate: '/entries/{uid}/delete',
            output: OutputDto::class,
            processor: EntryProcessor::class,
            provider: EntryProvider::class,
            security: "is_granted('ROLE_EMPLOYEE')",
            status: 200,
            openapi: new Operation(
                summary: 'Delete an entry',
                description: 'Deletes a specific entry from the system.'
            )
        )
    ]
)]
final class EntryApi {}
