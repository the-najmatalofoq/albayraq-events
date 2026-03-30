<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Presentation\Http\Action;

use Modules\DigitalSignature\Application\Query\GetOneById\GetDigitalSignatureByIdQuery;
use Modules\DigitalSignature\Application\Query\GetOneById\GetDigitalSignatureByIdHandler;
use Modules\Shared\Presentation\Http\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class GetDigitalSignatureByIdAction
{
    public function __construct(
        private GetDigitalSignatureByIdHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $signature = $this->handler->handle(new GetDigitalSignatureByIdQuery(
            id: $args['id'],
        ));

        return $this->responder->ok([
            'id' => $signature->uuid->value,
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
