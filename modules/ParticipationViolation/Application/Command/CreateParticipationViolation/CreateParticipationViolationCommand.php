<?php
// modules/ParticipationViolation/Application/Command/CreateParticipationViolation/CreateParticipationViolationCommand.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Application\Command\CreateParticipationViolation;

use DateTimeImmutable;

final readonly class CreateParticipationViolationCommand
{
    public function __construct(
        public string $participationId,
        public string $violationTypeId,
        public string $reportedBy,
        public DateTimeImmutable $date,
        public ?string $deductionTypeId = null,
        public ?string $penaltyTypeId = null,
        public ?string $description = null,
    ) {}
}
