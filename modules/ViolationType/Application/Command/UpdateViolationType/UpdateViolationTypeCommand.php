<?php
// modules/ViolationType/Application/Command/UpdateViolationType/UpdateViolationTypeCommand.php
declare(strict_types=1);

namespace Modules\ViolationType\Application\Command\UpdateViolationType;

use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class UpdateViolationTypeCommand
{
    public function __construct(
        public string $id,
        public TranslatableText $name,
        public ?string $slug = null,
        public ?bool $isActive = null
    ) {}
}
