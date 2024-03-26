<?php

namespace Api\Framework\App\Controller;


use Api\Framework\App\Entity\User;
use Api\Framework\App\Repository\UserRepository;
use Api\Framework\Kernel\Abstract\AbstractController;
use Api\Framework\Kernel\Attributes\Endpoint;
use Api\Framework\Kernel\Attributes\Guard;
use Api\Framework\Kernel\Http\JsonResponse;
use Api\Framework\Kernel\Services\JwtManager;

class IndexController extends AbstractController
{

    #[Endpoint(path: '/', requestMethod: 'GET')]
    public function home(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        return new JsonResponse(['message' => $users]);
    }


}