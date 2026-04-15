<?php
// modules/ParticipationViolation/Application/Command/UpdateParticipationViolation/UpdateParticipationViolationHandler.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Application\Command\UpdateParticipationViolation;

use Modules\ParticipationViolation\Domain\ValueObject\ViolationId;
use Modules\ParticipationViolation\Domain\Repository\ParticipationViolationRepositoryInterface;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;
use Modules\DeductionType\Domain\ValueObject\DeductionTypeId;
use Modules\PenaltyType\Domain\ValueObject\PenaltyTypeId;

final readonly class UpdateParticipationViolationHandler
{
    public function __construct(
        private ParticipationViolationRepositoryInterface $repository
    ) {}

    public function handle(UpdateParticipationViolationCommand $command): void
    {
        $id = ViolationId::fromString($command->id);
        $violation = $this->repository->findById($id);

        if (!$violation) {
            throw new \DomainException("Participation violation not found.");
        }

        $violation->update(
            violationTypeId: ViolationTypeId::fromString($command->violationTypeId),
            date: $command->date,
            deductionTypeId: $command->deductionTypeId ? DeductionTypeId::fromString($command->deductionTypeId) : null,
            penaltyTypeId: $command->penaltyTypeId ? PenaltyTypeId::fromString($command->penaltyTypeId) : null,
            description: $command->description
        );

        $this->repository->save($violation);
    }
}
