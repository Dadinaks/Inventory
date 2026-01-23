<?php

namespace Dadinaks\User\Infrastructure\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\OpenApi\Model\Operation;
use Dadinaks\User\Adapter\Dto\InputDto;
use Dadinaks\User\Adapter\Dto\OutputDto;
use Dadinaks\User\Infrastructure\Api\Processor\UserProcessor;
use Dadinaks\User\Infrastructure\Api\Provider\UserProvider;

#[ApiResource(
    shortName: 'User',
    description: 'User Resource',
    uriTemplate: '/user',
    operations: [
        new Post(
            input: InputDto::class,
            output: OutputDto::class,
            processor: UserProcessor::class,
            openapi: new Operation(
                summary: 'Create a new user',
                description: 'Creates a new user with the provided details.'
            )
        ),
        new GetCollection(
            output: OutputDto::class,
            provider: UserProvider::class,
            openapi: new Operation(
                summary: 'Retrieve a list of users',
                description: 'Fetches a collection of all users in the system.'
            )
        ),
        new Put(
            uriTemplate: '/user/{uid}/disable',
            input: false,
            output: OutputDto::class,
            processor: UserProcessor::class,
            provider: UserProvider::class,
            openapi: new Operation(
                summary: 'Disable a user',
                description: 'Disables the user identified by the provided UID.'
            )
        ),
        new Post(
            uriTemplate: '/user/{uid}/enable',
            input: false,
            output: OutputDto::class,
            processor: UserProcessor::class,
            openapi: new Operation(
                summary: 'Enable a user',
                description: 'Enables the user identified by the provided UID.'
            )
        ),
        new Delete(
            uriTemplate: '/user/{uid}/delete',
            output: OutputDto::class,
            processor: UserProcessor::class,
            provider: UserProvider::class,
            status: 200,
            openapi: new Operation(
                summary: 'Delete a user',
                description: 'Deletes a specific user identified by its UID.'
            )
        )
    ]
)]
final class UserApi {}
