<?php

namespace Modules\AttendanceBarcode\Domain;

use DateTimeImmutable;

class AttendanceBarcode
{
    public function __construct(
        public string $id,
        public string $eventParticipationId,
        public string $code,
        public DateTimeImmutable $generatedAt,
        public ?DateTimeImmutable $expiresAt = null,
    ) {}
}
