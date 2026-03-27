<?php
// modules/ParticipationViolation/Domain/Repository/ParticipationViolationRepositoryInterface.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Domain\Repository;

use Modules\ParticipationViolation\Domain\ParticipationViolation;
use Modules\ParticipationViolation\Domain\ValueObject\ViolationId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;

interface ParticipationViolationRepositoryInterface
{
    public function nextIdentity(): ViolationId;

    public function save(ParticipationViolation $violation): void;

    public function findById(ViolationId $id): ?ParticipationViolation;

    /** @return ParticipationViolation[] */
    public function findByParticipationId(ParticipationId $participationId): array;
}
