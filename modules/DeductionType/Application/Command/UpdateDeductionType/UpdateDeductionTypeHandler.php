<?php
// modules/DeductionType/Application/Command/UpdateDeductionType/UpdateDeductionTypeHandler.php
declare(strict_types=1);

namespace Modules\DeductionType\Application\Command\UpdateDeductionType;

use Modules\DeductionType\Domain\Repository\DeductionTypeRepositoryInterface;
use Modules\DeductionType\Domain\ValueObject\DeductionTypeId;
use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class UpdateDeductionTypeHandler
{
    public function __construct(
        private DeductionTypeRepositoryInterface $repository
    ) {}

    public function handle(UpdateDeductionTypeCommand $command): void
    {
        $deductionType = $this->repository->findById(DeductionTypeId::fromString($command->id));

        if (!$deductionType) {
            throw new \DomainException("Deduction type not found.");
        }

        $deductionType->update(
            slug: $command->slug ?? $deductionType->slug,
            name: TranslatableText::fromArray(array_merge($deductionType->name->values, $command->name->values)),
        );

        if ($command->isActive !== null) {
            $command->isActive ? $deductionType->activate() : $deductionType->deactivate();
        }

        $this->repository->save($deductionType);
    }
}
