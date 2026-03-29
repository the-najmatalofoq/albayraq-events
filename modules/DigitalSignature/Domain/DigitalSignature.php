<?php

namespace Modules\DigitalSignature\Domain;

use DateTimeImmutable;

class DigitalSignature
{
    public function __construct(
        public string $id,
        public string $contractId,
        public string $signatureSvg,
        public ?string $ipAddress,
        public ?string $userAgent,
        public DateTimeImmutable $signedAt,
    ) {}
}
