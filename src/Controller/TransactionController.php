<?php

declare(strict_types=1);

namespace App\Controller;

use DateTimeImmutable;
use Fig\Http\Message\RequestMethodInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    #[Route(
        '/api/transactions',
        name: 'app_api_create_transaction',
        methods: [RequestMethodInterface::METHOD_POST],
    )]
    public function create(Request $request): JsonResponse
    {
        return $this->json([
            'data' => 'ok',
            'datetime' => (new DateTimeImmutable('now'))->format('Y-m-d H:i:s'),
        ]);
    }
}