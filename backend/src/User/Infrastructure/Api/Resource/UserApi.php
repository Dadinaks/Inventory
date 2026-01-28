<?php

namespace Dadinaks\User\Infrastructure\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\OpenApi\Model\Operation;
use Dadinaks\User\Adapter\Dto\InputDto;
use Dadinaks\User\Adapter\Dto\InputLoginDto;
use Dadinaks\User\Adapter\Dto\InputLogoutDto;
use Dadinaks\User\Adapter\Dto\OutputDto;
use Dadinaks\User\Adapter\Dto\OutputLoginDto;
use Dadinaks\User\Adapter\Dto\OutputLogoutDto;
use Dadinaks\User\Infrastructure\Api\Processor\LoginProcessor;
use Dadinaks\User\Infrastructure\Api\Processor\LogoutProcessor;
use Dadinaks\User\Infrastructure\Api\Processor\UserProcessor;
use Dadinaks\User\Infrastructure\Api\Provider\UserProvider;

#[ApiResource(
    shortName: 'User',
    description: 'User Resource',
    uriTemplate: '/user',
    operations: [
        new Post(
            name: 'app_login',
            uriTemplate: '/login',
            input: InputLoginDto::class,
            output: OutputLoginDto::class,
            processor: LoginProcessor::class,
            openapi: new Operation(
                summary: 'Log user',
                description: 'Logs in a user with the provided credentials.'
            )
        ),
        new Post(
            name: 'app_logout',
            uriTemplate: '/logout',
            security: "is_granted('IS_AUTHENTICATED_FULLY')",
            input: InputLogoutDto::class,
            output: OutputLogoutDto::class,
            processor: LogoutProcessor::class,
            openapi: new Operation(
                summary: 'Disconnect a user',
                description: 'Disconnect user with the provided credentials.'
            )
        ),
        new Post(
            name: 'app_user_create',
            input: InputDto::class,
            output: OutputDto::class,
            processor: UserProcessor::class,
            security: "is_granted('ROLE_ADMIN')",
            openapi: new Operation(
                summary: 'Create a new user',
                description: 'Creates a new user with the provided details.'
            )
        ),
        new GetCollection(
            name: 'app_user_list',
            output: OutputDto::class,
            provider: UserProvider::class,
            security: "is_granted('IS_AUTHENTICATED_FULLY')",
            openapi: new Operation(
                summary: 'Retrieve a list of users',
                description: 'Fetches a collection of all users in the system.'
            )
        ),
        new Put(
            name: 'app_disable_user',
            uriTemplate: '/user/{uid}/disable',
            input: false,
            output: OutputDto::class,
            processor: UserProcessor::class,
            provider: UserProvider::class,
            security: "is_granted('ROLE_ADMIN')",
            openapi: new Operation(
                summary: 'Disable a user',
                description: 'Disables the user identified by the provided UID.'
            )
        ),
        new Post(
            name: 'app_enable_user',
            uriTemplate: '/user/{uid}/enable',
            input: false,
            output: OutputDto::class,
            processor: UserProcessor::class,
            security: "is_granted('ROLE_ADMIN')",
            openapi: new Operation(
                summary: 'Enable a user',
                description: 'Enables the user identified by the provided UID.'
            )
        ),
        new Delete(
            name: 'app_delete_user',
            uriTemplate: '/user/{uid}/delete',
            output: OutputDto::class,
            processor: UserProcessor::class,
            provider: UserProvider::class,
            security: "is_granted('ROLE_ADMIN')",
            status: 200,
            openapi: new Operation(
                summary: 'Delete a user',
                description: 'Deletes a specific user identified by its UID.'
            )
        ),
        new Patch(
            name: 'app_restore_user',
            uriTemplate: '/user/{uid}/restore',
            input: false,
            output: OutputDto::class,
            processor: UserProcessor::class,
            provider: UserProvider::class,
            security: "is_granted('ROLE_ADMIN')",
            openapi: new Operation(
                summary: 'Restore a user',
                description: 'Restores the user identified by the provided UID.'
            )
        )
    ]
)]
final class UserApi {}
