<?php

namespace Dadinaks\Product\Infrastructure\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use Dadinaks\Product\Adapter\Dto\InputDto;
use Dadinaks\Product\Adapter\Dto\OutputDto;
use Dadinaks\Product\Infrastructure\Api\Processor\ProductProcessor;
use Dadinaks\Product\Infrastructure\Api\Provider\ProductProvider;

#[ApiResource(
    shortName: 'Product',
    description: 'Product resource',
    uriTemplate: '/products',
    operations: [
        new Post(
            input: InputDto::class,
            output: OutputDto::class,
            processor: ProductProcessor::class,
            openapi: new Operation(
                summary: 'Create a new product',
                description: 'Creates a new product with the provided details.'
            )
        ),
        new GetCollection(
            output: OutputDto::class,
            provider:ProductProvider::class,
            openapi: new Operation(
                summary: 'Retrieve a list of products',
                description: 'Retrieves a collection of all products.'
            )
        )
    ]
)]
final class ProductApi {}
