<?php
// modules/Wage/Application/Command/DeleteWage/DeleteWageHandler.php
declare(strict_types=1);

namespace Modules\Wage\Application\Command\DeleteWage;

use Modules\Wage\Domain\Repository\WageRepositoryInterface;
use Modules\Wage\Domain\ValueObject\WageId;

final readonly class DeleteWageHandler
{
    public function __construct(
        private WageRepositoryInterface $repository,
    ) {
    }

    public function handle(string $id): void
    {
        $this->repository->delete(WageId::fromString($id));
    }
}
