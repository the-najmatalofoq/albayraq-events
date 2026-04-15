<?php
// modules/ParticipationViolation/Application/Command/DeleteParticipationViolation/DeleteParticipationViolationHandler.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Application\Command\DeleteParticipationViolation;

use Modules\ParticipationViolation\Domain\ValueObject\ViolationId;
use Modules\ParticipationViolation\Domain\Repository\ParticipationViolationRepositoryInterface;

final readonly class DeleteParticipationViolationHandler
{
    public function __construct(
        private ParticipationViolationRepositoryInterface $repository
    ) {}

    public function handle(DeleteParticipationViolationCommand $command): void
    {
        $id = ViolationId::fromString($command->id);
        $violation = $this->repository->findById($id);

        if (!$violation) {
            throw new \DomainException("Participation violation not found.");
        }

        $this->repository->delete($id);
    }
}
