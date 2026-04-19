<?php
// modules/ParticipationViolation/Application/Command/DeleteParticipationViolation/DeleteParticipationViolationCommand.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Application\Command\DeleteParticipationViolation;

final readonly class DeleteParticipationViolationCommand
{
    public function __construct(
        public string $id
    ) {}
}
