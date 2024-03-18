<?php

namespace Api\Framework\Kernel\Utils;

abstract class ResourceEndpoint
{

    protected string $table;
    public function __construct(
        private string $resource,
    ){
        $this->table = substr($this->resource, 0, -1);
    }
    public final function getResource(): string
    {
        return $this->resource;
    }


    public final function getOperationShortName(): string
    {
        $completeName = explode('\\', get_class($this));
        return end($completeName);
    }


    /**
     * @return string
     */
    public final function getTable(): string
    {
        return $this->table;
    }
    abstract public function execute(): array | object;







}