<?php
// modules/ParticipationViolation/Application/Command/UpdateParticipationViolation/UpdateParticipationViolationCommand.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Application\Command\UpdateParticipationViolation;

use DateTimeImmutable;

final readonly class UpdateParticipationViolationCommand
{
    public function __construct(
        public string $id,
        public string $violationTypeId,
        public DateTimeImmutable $date,
        public ?string $deductionTypeId = null,
        public ?string $penaltyTypeId = null,
        public ?string $description = null,
    ) {}
}
