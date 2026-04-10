<?php
// modules/Wage/Application/Command/UpdateWage/UpdateWageCommand.php
declare(strict_types=1);

namespace Modules\Wage\Application\Command\UpdateWage;

final readonly class UpdateWageCommand
{
    public function __construct(
        public string $id,
        public float $amount,
        public string $currency,
        public string $period,
    ) {
    }
}
