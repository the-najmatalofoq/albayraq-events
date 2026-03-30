<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Presentation\Http\Action;

use Modules\DigitalSignature\Application\Query\GetOneById\GetDigitalSignatureByIdQuery;
use Modules\DigitalSignature\Application\Query\GetOneById\GetDigitalSignatureByIdHandler;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Shared\Presentation\Http\Presenter\DigitalSignaturePresenter;
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

        return $this->responder->ok(DigitalSignaturePresenter::toArray($signature));
    }
}
