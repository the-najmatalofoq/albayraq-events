<?php
// modules/DeductionType/Application/Command/UpdateDeductionType/UpdateDeductionTypeCommand.php
declare(strict_types=1);

namespace Modules\DeductionType\Application\Command\UpdateDeductionType;

use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class UpdateDeductionTypeCommand
{
    public function __construct(
        public string $id,
        public TranslatableText $name,
        public ?string $slug = null,
        public ?bool $isActive = null
    ) {}
}
