<?php
// modules/ParticipationViolation/Application/Command/CreateParticipationViolation/CreateParticipationViolationHandler.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Application\Command\CreateParticipationViolation;

use Modules\ParticipationViolation\Domain\ParticipationViolation;
use Modules\ParticipationViolation\Domain\ValueObject\ViolationId;
use Modules\ParticipationViolation\Domain\Repository\ParticipationViolationRepositoryInterface;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;
use Modules\DeductionType\Domain\ValueObject\DeductionTypeId;
use Modules\PenaltyType\Domain\ValueObject\PenaltyTypeId;
use Modules\User\Domain\ValueObject\UserId;

final readonly class CreateParticipationViolationHandler
{
    public function __construct(
        private ParticipationViolationRepositoryInterface $repository
    ) {}

    public function handle(CreateParticipationViolationCommand $command): ViolationId
    {
        $id = $this->repository->nextIdentity();

        $violation = ParticipationViolation::create(
            uuid: $id,
            participationId: ParticipationId::fromString($command->participationId),
            violationTypeId: ViolationTypeId::fromString($command->violationTypeId),
            reportedBy: UserId::fromString($command->reportedBy),
            date: $command->date,
            deductionTypeId: $command->deductionTypeId ? DeductionTypeId::fromString($command->deductionTypeId) : null,
            penaltyTypeId: $command->penaltyTypeId ? PenaltyTypeId::fromString($command->penaltyTypeId) : null,
            description: $command->description
        );

        $this->repository->save($violation);

        return $id;
    }
}
