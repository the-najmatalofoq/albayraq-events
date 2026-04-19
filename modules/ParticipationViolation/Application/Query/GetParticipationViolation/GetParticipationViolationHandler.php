<?php
// modules/ParticipationViolation/Application/Query/GetParticipationViolation/GetParticipationViolationHandler.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Application\Query\GetParticipationViolation;

use Modules\ParticipationViolation\Domain\ParticipationViolation;
use Modules\ParticipationViolation\Domain\ValueObject\ViolationId;
use Modules\ParticipationViolation\Domain\Repository\ParticipationViolationRepositoryInterface;

final readonly class GetParticipationViolationHandler
{
    public function __construct(
        private ParticipationViolationRepositoryInterface $repository
    ) {}

    public function handle(GetParticipationViolationQuery $query): ?ParticipationViolation
    {
        return $this->repository->findById(ViolationId::fromString($query->id));
    }
}
