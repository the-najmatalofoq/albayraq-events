<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Presentation\Http\Action;

use Modules\DigitalSignature\Application\Command\Delete\DeleteDigitalSignatureCommand;
use Modules\DigitalSignature\Application\Command\Delete\DeleteDigitalSignatureHandler;
use Modules\Shared\Presentation\Http\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class DeleteDigitalSignatureAction
{
    public function __construct(
        private DeleteDigitalSignatureHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $this->handler->handle(new DeleteDigitalSignatureCommand(
            id: $args['id'],
        ));

        return $this->responder->noContent();
    }
}
