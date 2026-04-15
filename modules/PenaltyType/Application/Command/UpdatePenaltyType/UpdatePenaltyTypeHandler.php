<?php
// modules/PenaltyType/Application/Command/UpdatePenaltyType/UpdatePenaltyTypeHandler.php
declare(strict_types=1);

namespace Modules\PenaltyType\Application\Command\UpdatePenaltyType;

use Modules\PenaltyType\Domain\Repository\PenaltyTypeRepositoryInterface;
use Modules\PenaltyType\Domain\ValueObject\PenaltyTypeId;
use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class UpdatePenaltyTypeHandler
{
    public function __construct(
        private PenaltyTypeRepositoryInterface $repository
    ) {}

    public function handle(UpdatePenaltyTypeCommand $command): void
    {
        $penaltyType = $this->repository->findById(PenaltyTypeId::fromString($command->id));

        if (!$penaltyType) {
            throw new \DomainException("Penalty type not found.");
        }

        $penaltyType->update(
            slug: $command->slug ?? $penaltyType->slug,
            name: TranslatableText::fromArray(array_merge($penaltyType->name->values, $command->name->values)),
        );

        if ($command->isActive !== null) {
            $command->isActive ? $penaltyType->activate() : $penaltyType->deactivate();
        }

        $this->repository->save($penaltyType);
    }
}
