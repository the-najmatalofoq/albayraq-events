<?php
// modules/ViolationType/Application/Command/UpdateViolationType/UpdateViolationTypeHandler.php
declare(strict_types=1);

namespace Modules\ViolationType\Application\Command\UpdateViolationType;

use Modules\ViolationType\Domain\Repository\ViolationTypeRepositoryInterface;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;
use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class UpdateViolationTypeHandler
{
    public function __construct(
        private ViolationTypeRepositoryInterface $repository
    ) {}

    public function handle(UpdateViolationTypeCommand $command): void
    {
        $violationType = $this->repository->findById(ViolationTypeId::fromString($command->id));

        if (!$violationType) {
            throw new \DomainException("Violation type not found.");
        }

        $violationType->update(
            slug: $command->slug ?? $violationType->slug,
            name: TranslatableText::fromArray(array_merge($violationType->name->values, $command->name->values)),
        );

        if ($command->isActive !== null) {
            $command->isActive ? $violationType->activate() : $violationType->deactivate();
        }

        $this->repository->save($violationType);
    }
}
