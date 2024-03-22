<?php

namespace Api\Framework\Kernel\Http\Methods;

use Api\Framework\Kernel\Http\JsonResponse;
use Api\Framework\Kernel\Model\Model;
use Api\Framework\Kernel\Utils\ResourceEndpoint;

class Delete extends ResourceEndpoint
{


    public final function execute(int $id = null): array | object
    {
        $this->checkIfGuarded();
        try {
            Model::getInstance()->delete($this->getTable(), $id);
            return new JsonResponse(['message' => 'Resource deleted successfully!', 'id' => $id]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Resource not found!'], 404);
        }
    }

}