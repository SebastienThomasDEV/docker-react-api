<?php

namespace Api\Framework\Kernel\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS)]
class ApiResource
{

    public function __construct(
        private readonly string $resource,
        private readonly array $operations = [],
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