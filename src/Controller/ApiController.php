<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

class ApiController extends AbstractController
{
    #[Route('/api/users/{id}', methods: ['GET'])]
    public function getUsers(UserRepository $userRepository, $id): JsonResponse
    {
        $users = $userRepository->findOneById($id);
        return $this->json($users);
    }
}
