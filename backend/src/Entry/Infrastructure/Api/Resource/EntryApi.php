<?php

namespace Dadinaks\Entry\Infrastructure\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use Dadinaks\Entry\Adapter\Dto\InputDto;
use Dadinaks\Entry\Adapter\Dto\OutputDto;
use Dadinaks\Entry\Infrastructure\Api\Processor\EntryProcessor;

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
        )
    ]
)]
final class EntryApi {}
