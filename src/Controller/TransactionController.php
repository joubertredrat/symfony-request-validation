<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\CreateTransactionRequest;
use DateTimeImmutable;
use Fig\Http\Message\RequestMethodInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    #[Route(
        '/api/transactions',
        name: 'app_api_create_transaction',
        methods: [RequestMethodInterface::METHOD_POST],
    )]
    public function create(CreateTransactionRequest $request): JsonResponse
    {
        return $this->json([
            'resonse' => 'ok',
            'datetime' => (new DateTimeImmutable('now'))->format('Y-m-d H:i:s'),
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'full_name' => $request->getFullName(),
            'amount' => [
                'cents' => $request->amount,
                'brl' => $request->getAmountBRL(),
            ],
            'installments' => $request->installments,
            'description' => $request->description,
            'headers' => [
                'Content-Type' => $request
                    ->getRequest()
                    ->headers
                    ->get('Content-Type')
                ,
            ],
        ]);
    }
}