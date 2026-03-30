<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Presentation\Http\Action;

use Modules\DigitalSignature\Application\Query\GetByContractId\GetDigitalSignatureByContractIdQuery;
use Modules\DigitalSignature\Application\Query\GetByContractId\GetDigitalSignatureByContractIdHandler;
use Modules\Shared\Presentation\Http\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class GetDigitalSignatureByContractIdAction
{
    public function __construct(
        private GetDigitalSignatureByContractIdHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $signature = $this->handler->handle(new GetDigitalSignatureByContractIdQuery(
            contractId: $args['contractId'],
        ));

        return $this->responder->ok([
            'id' => $signature->id->value,
            'contract_id' => $signature->contractId,
            'signature_svg' => $signature->signatureSvg,
            'ip_address' => $signature->ipAddress,
            'user_agent' => $signature->userAgent,
            'signed_at' => $signature->signedAt->format('Y-m-d H:i:s'),
            'created_at' => $signature->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $signature->updatedAt?->format('Y-m-d H:i:s'),
        ]);
    }
}
