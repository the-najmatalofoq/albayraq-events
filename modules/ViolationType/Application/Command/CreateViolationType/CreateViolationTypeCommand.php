<?php
// modules/ViolationType/Application/Command/CreateViolationType/CreateViolationTypeCommand.php
declare(strict_types=1);

namespace Modules\ViolationType\Application\Command\CreateViolationType;

final readonly class CreateViolationTypeCommand
{
    public function __construct(
        public array $name,
        public ?float $deductionAmount,
        public ?string $deductionCurrency,
        public string $severity,
        public ?string $eventId = null,
        public bool $isActive = true
    ) {}
}
