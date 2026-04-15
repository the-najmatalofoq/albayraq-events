<?php
// modules/Wage/Application/Command/CreateWage/CreateWageHandler.php
declare(strict_types=1);

namespace Modules\Wage\Application\Command\CreateWage;

use Modules\Wage\Domain\Repository\WageRepositoryInterface;
use Modules\Wage\Domain\Wage;
use Modules\Wage\Domain\ValueObject\WageId;
use Modules\Shared\Domain\ValueObject\Money;
use Modules\EventStaffingPosition\Infrastructure\Persistence\Eloquent\EventStaffingPositionModel;

final readonly class CreateWageHandler
{
    // fix: grep all the const and make a constant files.
    private const WHITELIST = [
        'staffing_position' => EventStaffingPositionModel::class,
    ];

    public function __construct(
        private WageRepositoryInterface $repository,
    ) {
    }

    public function handle(CreateWageCommand $command): WageId
    {
        $mappedType = self::WHITELIST[$command->wageableType] ?? null;

        if ($mappedType === null) {
            throw new \InvalidArgumentException("Invalid wageable type: {$command->wageableType}");
        }

        // Verify existence
        if (!($mappedType::where('id', $command->wageableId)->exists())) {
            throw new \DomainException("Resource {$command->wageableId} of type {$command->wageableType} not found.");
        }

        $id = $this->repository->nextIdentity();
        $wage = Wage::create(
            uuid: $id,
            wageableId: $command->wageableId,
            wageableType: $mappedType,
            amount: new Money($command->amount),
            period: $command->period,
            currencyId: $command->currencyId,
        );

        $this->repository->save($wage);
        return $id;
    }
}
