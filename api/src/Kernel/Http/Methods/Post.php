<?php

namespace Api\Framework\Kernel\Http\Methods;

use Api\Framework\Kernel\Http\JsonResponse;
use Api\Framework\Kernel\Model\Model;
use Api\Framework\Kernel\Utils\ResourceEndpoint;
use Api\Framework\Kernel\Utils\Utils;

class Post extends ResourceEndpoint
{

    public function __construct(string $resource)
    {
        parent::__construct($resource);
    }

    public final function execute(): array | object
    {
        $vars = Utils::getRequestBody();
        try {
            $sanitizedData = Utils::sanitizeData($vars);
            Model::getInstance()->post($this->getTable(), $sanitizedData);
            return new JsonResponse(['message' => 'Resource created successfully!']);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Resource could not be created!'], 500);
        }
    }
}