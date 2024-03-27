<?php

namespace Api\Framework\App\Controller;

use Api\Framework\App\Entity\User;
use Api\Framework\App\Repository\UserRepository;
use Api\Framework\Kernel\Attributes\Guard;
use Api\Framework\Kernel\Http\JsonResponse;
use Api\Framework\Kernel\Attributes\Endpoint;
use Api\Framework\Kernel\Abstract\AbstractController;
use Api\Framework\Kernel\Services\JwtManager;
use Api\Framework\Kernel\Services\PasswordHasher;
use Api\Framework\Kernel\Services\Request;

class AuthController extends AbstractController
{

    #[Endpoint(path: '/login', requestMethod: 'POST')]
    public function login(UserRepository $userRepository, Request $request, JwtManager $manager): JsonResponse
    {
        $data = $request->retrievePostValues();
        $user = $userRepository->findOneBy(['email' => $data['email']]);
        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }
        if (!PasswordHasher::verify($data['pwd'], $user->getPwd())) {
            return new JsonResponse(['message' => 'Invalid password'], 401);
        }
        $token = $manager->encode([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
        ]);
        return new JsonResponse(['message' => 'Welcome back ' . $user->getName(),
            'token' => $token
        ]);
    }

    #[Guard]
    #[Endpoint(path: '/login_check', requestMethod: "GET")]
    public function checkToken()
    {
        $decodedToken = $this->getDecodedToken();
        if (!$decodedToken) {
            return new JsonResponse(['message' => 'Invalid token'], 401);
        }
        return $this->send(['message' => 'Token is valid']);
    }

}