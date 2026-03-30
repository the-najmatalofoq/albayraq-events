<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Presentation\Http\Action;

use Modules\DigitalSignature\Application\Query\GetAllPaginated\GetAllDigitalSignaturesPaginatedQuery;
use Modules\DigitalSignature\Application\Query\GetAllPaginated\GetAllDigitalSignaturesPaginatedHandler;
use Modules\Shared\Presentation\Http\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class GetAllDigitalSignaturesPaginatedAction
{
    public function __construct(
        private GetAllDigitalSignaturesPaginatedHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        $result = $this->handler->handle(new GetAllDigitalSignaturesPaginatedQuery(
            page: (int) ($queryParams['page'] ?? 1),
            perPage: (int) ($queryParams['per_page'] ?? 15),
            filters: array_filter([
                'contract_id' => $queryParams['contract_id'] ?? null,
                'from_date' => $queryParams['from_date'] ?? null,
                'to_date' => $queryParams['to_date'] ?? null,
            ]),
            orderBy: $queryParams['order_by'] ?? 'signed_at',
            orderDirection: $queryParams['order_direction'] ?? 'desc',
        ));

        $data = array_map(function($signature) {
            return [
                'id' => $signature->uuid->value,
                'contract_id' => $signature->contractId,
                'signed_at' => $signature->signedAt->format('Y-m-d H:i:s'),
                'created_at' => $signature->createdAt->format('Y-m-d H:i:s'),
            ];
        }, $result['data']);

        return $this->responder->ok([
            'data' => $data,
            'meta' => [
                'total' => $result['total'],
                'page' => (int) ($queryParams['page'] ?? 1),
                'per_page' => (int) ($queryParams['per_page'] ?? 15),
                'last_page' => ceil($result['total'] / ($queryParams['per_page'] ?? 15)),
            ],
        ]);
    }
}
