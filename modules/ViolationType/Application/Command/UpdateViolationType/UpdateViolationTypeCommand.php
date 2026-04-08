<?php
// modules/ViolationType/Application/Command/UpdateViolationType/UpdateViolationTypeCommand.php
declare(strict_types=1);

namespace Modules\ViolationType\Application\Command\UpdateViolationType;

final readonly class UpdateViolationTypeCommand
{
    public function __construct(
        public string $id,
        public array $name,
        public ?float $deductionAmount,
        public ?string $deductionCurrency,
        public string $severity,
        public ?string $eventId = null,
        public ?bool $isActive = null
    ) {}
}
