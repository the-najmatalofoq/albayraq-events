<?php
// modules/Wage/Application/Command/UpdateWage/UpdateWageHandler.php
declare(strict_types=1);

namespace Modules\Wage\Application\Command\UpdateWage;

use Modules\Wage\Domain\Repository\WageRepositoryInterface;
use Modules\Wage\Domain\ValueObject\WageId;
use Modules\Shared\Domain\ValueObject\Money;

final readonly class UpdateWageHandler
{
    public function __construct(
        private WageRepositoryInterface $repository,
    ) {
    }

    public function handle(UpdateWageCommand $command): void
    {
        $wage = $this->repository->findById(WageId::fromString($command->id));

        if ($wage === null) {
            throw new \DomainException("Wage {$command->id} not found.");
        }

        $wage->update(
            amount: new Money($command->amount, $command->currency),
            period: $command->period,
        );

        $this->repository->save($wage);
    }
}
