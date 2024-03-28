<?php

namespace Api\Framework\App\Controller;


use Api\Framework\App\Entity\User;
use Api\Framework\App\Repository\UserRepository;
use Api\Framework\Kernel\Abstract\AbstractController;
use Api\Framework\Kernel\Attributes\Endpoint;
use Api\Framework\Kernel\Http\JsonResponse;
use Api\Framework\Kernel\Services\Request;

class IndexController extends AbstractController
{

    #[Endpoint(path: '/test', requestMethod: 'GET')]
    public function home(UserRepository $userRepository, Request $request): JsonResponse
    {
        $id = (int) $request->retrieveGetValue("id");
        $user = $userRepository->findById($id);
        return $this->send([
            "message" => 'je suis sur la route racine /',
            'user' => $user
        ]);
    }



}