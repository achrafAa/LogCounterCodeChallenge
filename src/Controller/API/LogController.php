<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\Dto\LogFilterDto;
use App\Repository\LogRepository;
use App\Request\LogCountRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LogController extends AbstractController
{
    #[Route('/count', name: 'log_count', methods: ['GET'])]
    public function __invoke(Request $request, LogRepository $logRepository, ValidatorInterface $validator): JsonResponse
    {
        try {
            $logFilterRequest = new LogCountRequest($request, $validator);
        } catch (\InvalidArgumentException $e) {
            return $this->json(
                [
                    'status' => 'error',
                    'errors' => json_decode($e->getMessage()),
                ],
                Response::HTTP_BAD_REQUEST,
            );
        }
        $logFilterDto = new LogFilterDto(
            $logFilterRequest->serviceNames,
            $logFilterRequest->statusCode,
            $logFilterRequest->startDate,
            $logFilterRequest->endDate,
        );

        return $this->json([
            'counter' => $logRepository->getLogRecordsCount($logFilterDto),
        ]);
    }
}
