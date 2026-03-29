<?php

namespace Modules\EventExperienceCertificate\Domain;

use DateTimeImmutable;

class EventExperienceCertificate
{
    public function __construct(
        public string $id,
        public string $eventParticipationId,
        public float $totalHours,
        public float $averageScore,
        public DateTimeImmutable $issuedAt,
        public string $verificationCode,
    ) {}
}
