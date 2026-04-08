<?php
// modules/ViolationType/Application/Command/CreateViolationType/CreateViolationTypeHandler.php
declare(strict_types=1);

namespace Modules\ViolationType\Application\Command\CreateViolationType;

use Modules\ViolationType\Domain\ViolationType;
use Modules\ViolationType\Domain\Repository\ViolationTypeRepositoryInterface;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Domain\ValueObject\Money;
use Modules\ViolationType\Domain\Enum\ViolationSeverityEnum;
use Modules\Event\Domain\ValueObject\EventId;

final readonly class CreateViolationTypeHandler
{
    public function __construct(
        private ViolationTypeRepositoryInterface $repository
    ) {}

    public function handle(CreateViolationTypeCommand $command): ViolationTypeId
    {
        $id = $this->repository->nextIdentity();

        $violationType = ViolationType::create(
            uuid: $id,
            name: TranslatableText::fromArray($command->name),
            defaultDeduction: $command->deductionAmount !== null 
                ? new Money($command->deductionAmount, $command->deductionCurrency ?? 'SAR') 
                : null,
            severity: ViolationSeverityEnum::from($command->severity),
            eventId: $command->eventId ? EventId::fromString($command->eventId) : null,
            isActive: $command->isActive
        );

        $this->repository->save($violationType);

        return $id;
    }
}
