<?php

namespace Api\Framework\Kernel\Utils;

use Api\Framework\Kernel\Auth\JwtTokenManager;
use Api\Framework\Kernel\Exception\ExceptionManager;

abstract class ResourceEndpoint
{

    protected string $table;
    protected string $resource;
    public function __construct(
        private bool $guarded = false
    ){
    }

    public final function build(string $resource): void
    {
        $this->setResource($resource);
        $this->table = substr($this->resource, 0, -1);
    }

    /**
     * @param string $resource
     */
    public final function setResource(string $resource): void
    {
        $this->resource = $resource;
    }

    /**
     * @param string $table
     */
    public final function setTable(string $table): void
    {
        $this->table = $table;
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

    public final function checkIfGuarded(): void
    {
        if ($this->guarded) {
            if (!JwtTokenManager::check()) {
                ExceptionManager::send(new \Exception('Unauthorized access', 401));
            }
        }
    }

    /**
     * @return string
     */
    public final function getTable(): string
    {
        return $this->table;
    }
    public function execute(): array | object {

        return [];
    }







}