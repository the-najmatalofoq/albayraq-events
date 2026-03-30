<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Presentation\Http\Action;

use Modules\DigitalSignature\Application\Command\Update\UpdateDigitalSignatureCommand;
use Modules\DigitalSignature\Application\Command\Update\UpdateDigitalSignatureHandler;
use Modules\DigitalSignature\Presentation\Http\Request\UpdateDigitalSignatureRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class UpdateDigitalSignatureAction
{
    public function __construct(
        private UpdateDigitalSignatureHandler $handler,
        private UpdateDigitalSignatureRequest $request,
        private JsonResponder $responder,
    ) {}

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $id = $args['id'];
        $data = $this->request->validated($request);

        $this->handler->handle(new UpdateDigitalSignatureCommand(
            id: $id,
            signatureSvg: $data['signature_svg'],
            ipAddress: $data['ip_address'] ?? null,
            userAgent: $request->getHeaderLine('User-Agent'),
        ));

        return $this->responder->success(
            data: null,
            status: 200,
            messageKey: 'messages.digital_signature.updated'
        );
    }
}
