<?php
// modules/PenaltyType/Application/Command/DeletePenaltyType/DeletePenaltyTypeHandler.php
declare(strict_types=1);

namespace Modules\PenaltyType\Application\Command\DeletePenaltyType;

use Modules\PenaltyType\Domain\Repository\PenaltyTypeRepositoryInterface;
use Modules\PenaltyType\Domain\ValueObject\PenaltyTypeId;

final readonly class DeletePenaltyTypeHandler
{
    public function __construct(
        private PenaltyTypeRepositoryInterface $repository
    ) {}

    public function handle(DeletePenaltyTypeCommand $command): void
    {
        $id = PenaltyTypeId::fromString($command->id);
        
        $penaltyType = $this->repository->findById($id);

        if (!$penaltyType) {
            throw new \DomainException("Penalty type not found.");
        }

        $this->repository->delete($id);
    }
}
