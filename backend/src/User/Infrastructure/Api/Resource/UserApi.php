<?php

namespace Dadinaks\User\Infrastructure\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
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
        )
    ]
)]
final class UserApi {}
