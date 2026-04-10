<?php
// modules/Wage/Application/Command/CreateWage/CreateWageCommand.php
declare(strict_types=1);

namespace Modules\Wage\Application\Command\CreateWage;

final readonly class CreateWageCommand
{
    public function __construct(
        public string $wageableId,
        public string $wageableType,
        public float $amount,
        public string $currency,
        public string $period,
    ) {
    }
}
