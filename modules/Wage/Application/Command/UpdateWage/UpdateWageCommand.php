<?php
// modules/Wage/Application/Command/UpdateWage/UpdateWageCommand.php
declare(strict_types=1);

namespace Modules\Wage\Application\Command\UpdateWage;

use Modules\Wage\Domain\ValueObject\WageId;

final readonly class UpdateWageCommand
{
    public function __construct(
        public WageId $id,
        public float $amount,
        public string $period,
        public ?string $currencyId = null,
    ) {}
}
