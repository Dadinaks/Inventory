<?php

namespace Dadinaks\User\Infrastructure\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use Dadinaks\User\Adapter\Dto\InputDto;
use Dadinaks\User\Adapter\Dto\OutputDto;
use Dadinaks\User\Infrastructure\Api\Processor\UserProcessor;

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
    ]
)]
final class UserApi {}
