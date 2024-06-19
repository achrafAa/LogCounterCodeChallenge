<?php

namespace App\Controller\API;

use App\Repository\LogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
class LogController extends AbstractController
{
    #[Route('/count', name: 'log_count', methods: ['GET'])]
    public function __invoke(Request $request, LogRepository $logRepository): JsonResponse
    {

        return $this->json([
            'counter' => 20,
        ]);
    }
}
