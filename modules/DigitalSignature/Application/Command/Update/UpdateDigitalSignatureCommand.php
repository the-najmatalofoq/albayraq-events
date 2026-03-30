<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Application\Command\Update;

final readonly class UpdateDigitalSignatureCommand
{
    public function __construct(
        public string $id,
        public string $signatureSvg,
        public ?string $ipAddress,
        public ?string $userAgent,
    ) {}
}
