<?php

namespace Api\Framework\Kernel\Http\Methods;

use Api\Framework\Kernel\Http\JsonResponse;
use Api\Framework\Kernel\Model\Model;
use Api\Framework\Kernel\Utils\ResourceEndpoint;
use Api\Framework\Kernel\Utils\Utils;

class Put extends ResourceEndpoint
{


    public final function execute(int $id = null): array | object
    {
        $this->checkIfGuarded();
        $vars = Utils::getRequestBody();
        try {
            $sanitizedData = Utils::sanitizeData($vars);
            Model::getInstance()->put($this->getTable(), $id, $sanitizedData);
            return new JsonResponse(['message' => 'Resource put successfully!', 'id' => $id]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Resource not found!'], 404);
        }
    }
}