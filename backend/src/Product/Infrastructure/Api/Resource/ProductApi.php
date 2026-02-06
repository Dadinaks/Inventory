<?php

namespace Dadinaks\Product\Infrastructure\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\OpenApi\Model\Operation;
use Dadinaks\Product\Adapter\Dto\InputDto;
use Dadinaks\Product\Adapter\Dto\OutputDto;
use Dadinaks\Product\Adapter\Dto\ProductHistoryDto;
use Dadinaks\Product\Adapter\Dto\ThresholdDto;
use Dadinaks\Product\Infrastructure\Api\Processor\ProductProcessor;
use Dadinaks\Product\Infrastructure\Api\Provider\ProductProvider;

#[ApiResource(
    shortName: 'Product',
    description: 'Product resource',
    uriTemplate: '/products',
    operations: [
        new Post(
            name: 'app_create_product',
            input: InputDto::class,
            output: OutputDto::class,
            processor: ProductProcessor::class,
            security: "is_granted('ROLE_EMPLOYEE')",
            openapi: new Operation(
                summary: 'Create a new product',
                description: 'Creates a new product with the provided details.'
            )
        ),
        new GetCollection(
            name: 'app_list_products',
            output: OutputDto::class,
            provider: ProductProvider::class,
            security: "is_granted('IS_AUTHENTICATED_FULLY')",
            openapi: new Operation(
                summary: 'Retrieve a list of products',
                description: 'Retrieves a collection of all products.'
            )
        ),
        new Patch(
            name: 'app_add_threshold',
            uriTemplate: '/product/{uid}/threshold',
            input: ThresholdDto::class,
            output: OutputDto::class,
            processor: ProductProcessor::class,
            provider: ProductProvider::class,
            security: "is_granted('ROLE_EMPLOYEE')",
            openapi: new Operation(
                summary: 'Update product threshold',
                description: 'Updates the threshold value for a specific product identified by its UID.'
            )
        ),
        new Delete(
            name: 'app_delete_product',
            uriTemplate: '/product/{uid}/delete',
            output: OutputDto::class,
            processor: ProductProcessor::class,
            provider: ProductProvider::class,
            security: "is_granted('ROLE_EMPLOYEE')",
            status: 200,
            openapi: new Operation(
                summary: 'Delete a product',
                description: 'Deletes a specific product identified by its UID.'
            )
        ),
        new Put(
            name: 'app_restore_product',
            uriTemplate: '/product/{uid}/restore',
            input: false,
            output: OutputDto::class,
            processor: ProductProcessor::class,
            provider: ProductProvider::class,
            security: "is_granted('ROLE_ADMIN')",
            openapi: new Operation(
                summary: 'Restore product',
                description: 'Restores a specific product identified by its UID.'
            )
        ),
        new Get(
            name: 'app_product_movement_history',
            uriTemplate: '/product/{uid}/history',
            output: ProductHistoryDto::class,
            provider: ProductProvider::class,
            security: "is_granted('IS_AUTHENTICATED_FULLY')",
            openapi: new Operation(
                summary: 'Get product movement history',
                description: 'Retrieves the movement history for a specific product identified by its UID.'
            )
        )
    ]
)]
final class ProductApi {}
