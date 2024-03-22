<?php

namespace Api\Framework\Kernel\Http\Methods;

use Api\Framework\Kernel\Http\JsonResponse;
use Api\Framework\Kernel\Model\Model;
use Api\Framework\Kernel\Utils\ResourceEndpoint;

class Get extends ResourceEndpoint
{

    public final function execute(int $id = null): array | object
    {
        $this->checkIfGuarded();
        if ($id) {
            try {
                return new JsonResponse(Model::getInstance()->get($this->getTable(), $id), 200);
            } catch (\Exception $e) {
                return new JsonResponse(['message' => 'Resource not found!'], 404);
            }
        } else {
            try {
                return new JsonResponse(Model::getInstance()->getAll($this->getTable()), 200);
            } catch (\Exception $e) {
                return new JsonResponse(['message' => 'Resource not found!'], 404);
            }
        }
    }
}