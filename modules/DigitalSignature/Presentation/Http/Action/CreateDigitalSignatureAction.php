<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Presentation\Http\Action;

use Modules\DigitalSignature\Application\Command\Create\CreateDigitalSignatureCommand;
use Modules\DigitalSignature\Application\Command\Create\CreateDigitalSignatureHandler;
use Modules\DigitalSignature\Presentation\Http\Request\CreateDigitalSignatureRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class CreateDigitalSignatureAction
{
    public function __construct(
        private CreateDigitalSignatureHandler $handler,
        private CreateDigitalSignatureRequest $request,
        private JsonResponder $responder,
    ) {}

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $data = $this->request->validated($request);

        $signature = $this->handler->handle(new CreateDigitalSignatureCommand(
            contractId: $data['contract_id'],
            signatureSvg: $data['signature_svg'],
            ipAddress: $data['ip_address'] ?? null,
            userAgent: $request->getHeaderLine('User-Agent'),
            signedAt: new \DateTimeImmutable(),
        ));

        return $this->responder->created([
            'id' => $signature->uuid->value,
            'contract_id' => $signature->contractId,
            'signed_at' => $signature->signedAt->format('Y-m-d H:i:s'),
        ]);
    }
}
