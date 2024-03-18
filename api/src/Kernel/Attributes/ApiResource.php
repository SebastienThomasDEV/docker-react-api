<?php

namespace Api\Framework\Kernel\Attributes;


use Api\Framework\Kernel\Http\Methods\Patch;
use Api\Framework\Kernel\Http\Methods\Delete;
use Api\Framework\Kernel\Http\Methods\Get;
use Api\Framework\Kernel\Http\Methods\Post;
use Api\Framework\Kernel\Http\Methods\Put;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS)]
class ApiResource
{



    public function __construct(
        private readonly string $resource,
        private readonly array $operations = []
    ){}

    public final function getOperations(): array
    {
        return $this->operations;
    }

    public final function getResource(): string
    {
        return $this->resource;
    }

}