<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\CreateTransactionDto;
use App\Request\CreateTransactionRequest;
use DateTimeImmutable;
use Fig\Http\Message\RequestMethodInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    #[Route(
        '/api/v1/transactions',
        name: 'app_api_create_transaction_v1',
        methods: [RequestMethodInterface::METHOD_POST],
    )]
    public function v1Create(#[MapRequestPayload] CreateTransactionDto $createTransaction): JsonResponse
    {
        return $this->json([
            'resonse' => 'ok',
            'datetime' => (new DateTimeImmutable('now'))->format('Y-m-d H:i:s'),
            'firstName' => $createTransaction->firstName,
            'lastName' => $createTransaction->lastName,
            'amount' => $createTransaction->amount,
            'installments' => $createTransaction->installments,
            'description' => $createTransaction->description,
        ]);
    }

    #[Route(
        '/api/v2/transactions',
        name: 'app_api_create_transaction_v2',
        methods: [RequestMethodInterface::METHOD_POST],
    )]
    public function v2Create(CreateTransactionRequest $request): JsonResponse
    {
        return $this->json([
            'resonse' => 'ok',
            'datetime' => (new DateTimeImmutable('now'))->format('Y-m-d H:i:s'),
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'amount' => $request->amount,
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