<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Presentation\Http\Action;

use Modules\DigitalSignature\Application\Query\GetAll\GetAllDigitalSignaturesQuery;
use Modules\DigitalSignature\Application\Query\GetAll\GetAllDigitalSignaturesHandler;
use Modules\Shared\Presentation\Http\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class GetAllDigitalSignaturesAction
{
    public function __construct(
        private GetAllDigitalSignaturesHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $signatures = $this->handler->handle(new GetAllDigitalSignaturesQuery());

        $data = array_map(function($signature) {
            return [
                'id' => $signature->uuid->value,
                'contract_id' => $signature->contractId,
                'signed_at' => $signature->signedAt->format('Y-m-d H:i:s'),
                'created_at' => $signature->createdAt->format('Y-m-d H:i:s'),
            ];
        }, $signatures);

        return $this->responder->ok($data);
    }
}
