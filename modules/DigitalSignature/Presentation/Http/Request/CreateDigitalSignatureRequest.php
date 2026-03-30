<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Presentation\Http\Request;

use Modules\Shared\Presentation\Validation\InputValidator;
use Psr\Http\Message\ServerRequestInterface;

final readonly class CreateDigitalSignatureRequest
{
    public function __construct(
        private InputValidator $validator,
    ) {}

    public function validated(ServerRequestInterface $request): array
    {
        return $this->validator->validate(
            (array) $request->getParsedBody(),
            [
                'contract_id' => ['required', 'string', 'uuid'],
                'signature_svg' => ['required', 'string'],
                'ip_address' => ['nullable', 'string', 'max:45'],
            ]
        );
    }
}
