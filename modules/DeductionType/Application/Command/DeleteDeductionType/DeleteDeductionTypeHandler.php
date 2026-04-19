<?php
// modules/DeductionType/Application/Command/DeleteDeductionType/DeleteDeductionTypeHandler.php
declare(strict_types=1);

namespace Modules\DeductionType\Application\Command\DeleteDeductionType;

use Modules\DeductionType\Domain\Repository\DeductionTypeRepositoryInterface;
use Modules\DeductionType\Domain\ValueObject\DeductionTypeId;

final readonly class DeleteDeductionTypeHandler
{
    public function __construct(
        private DeductionTypeRepositoryInterface $repository
    ) {}

    public function handle(DeleteDeductionTypeCommand $command): void
    {
        $id = DeductionTypeId::fromString($command->id);
        
        $deductionType = $this->repository->findById($id);

        if (!$deductionType) {
            throw new \DomainException("Deduction type not found.");
        }

        $this->repository->delete($id);
    }
}
