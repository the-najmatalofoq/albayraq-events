<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Application\Command\Create;

use DateTimeImmutable;

final readonly class CreateDigitalSignatureCommand
{
    public function __construct(
        public string $contractId,
        public string $signatureSvg,
        public ?string $ipAddress,
        public ?string $userAgent,
        public DateTimeImmutable $signedAt,
    ) {}
}
