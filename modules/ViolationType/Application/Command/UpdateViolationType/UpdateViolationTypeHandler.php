<?php
// modules/ViolationType/Application/Command/UpdateViolationType/UpdateViolationTypeHandler.php
declare(strict_types=1);

namespace Modules\ViolationType\Application\Command\UpdateViolationType;

use Modules\ViolationType\Domain\Repository\ViolationTypeRepositoryInterface;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Domain\ValueObject\Money;
use Modules\ViolationType\Domain\Enum\ViolationSeverityEnum;
use Modules\Event\Domain\ValueObject\EventId;

final readonly class UpdateViolationTypeHandler
{
    public function __construct(
        private ViolationTypeRepositoryInterface $repository
    ) {}

    public function handle(UpdateViolationTypeCommand $command): void
    {
        $id = ViolationTypeId::fromString($command->id);
        $violationType = $this->repository->findById($id);

        if (!$violationType) {
            // throw Not Found Exception
            return;
        }

        $violationType->update(
            name: TranslatableText::fromArray($command->name),
            defaultDeduction: $command->deductionAmount !== null 
                ? new Money($command->deductionAmount, $command->deductionCurrency ?? 'SAR') 
                : null,
            severity: ViolationSeverityEnum::from($command->severity),
            eventId: $command->eventId ? EventId::fromString($command->eventId) : null
        );

        if ($command->isActive !== null) {
            $command->isActive ? $violationType->activate() : $violationType->deactivate();
        }

        $this->repository->save($violationType);
    }
}
