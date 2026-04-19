<?php
// modules/Wage/Application/Command/UpdateWage/UpdateWageHandler.php
declare(strict_types=1);

namespace Modules\Wage\Application\Command\UpdateWage;

use Modules\Wage\Domain\Repository\WageRepositoryInterface;
use Modules\Shared\Domain\ValueObject\Money;

final readonly class UpdateWageHandler
{
    public function __construct(
        private WageRepositoryInterface $repository,
    ) {}

    public function handle(UpdateWageCommand $command): void
    {
        $wage = $this->repository->findById($command->id);

        if ($wage === null) {
            throw new \DomainException("messages.not_found");
        }

        $wage->update(
            amount: new Money($command->amount),
            period: $command->period,
            currencyId: $command->currencyId,
        );

        $this->repository->save($wage);
    }
}
