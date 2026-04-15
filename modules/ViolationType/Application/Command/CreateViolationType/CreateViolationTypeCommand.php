<?php
// modules/ViolationType/Application/Command/CreateViolationType/CreateViolationTypeCommand.php
declare(strict_types=1);

namespace Modules\ViolationType\Application\Command\CreateViolationType;

use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class CreateViolationTypeCommand
{
    public function __construct(
        public TranslatableText $name,
        public string $slug,
        public bool $isActive = true
    ) {}
}
