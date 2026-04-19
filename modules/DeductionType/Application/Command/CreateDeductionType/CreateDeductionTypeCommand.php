<?php
// modules/DeductionType/Application/Command/CreateDeductionType/CreateDeductionTypeCommand.php
declare(strict_types=1);

namespace Modules\DeductionType\Application\Command\CreateDeductionType;

use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class CreateDeductionTypeCommand
{
    public function __construct(
        public TranslatableText $name,
        public string $slug,
        public bool $isActive = true
    ) {}
}
