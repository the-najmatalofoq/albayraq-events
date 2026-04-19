<?php
// modules/PenaltyType/Application/Command/UpdatePenaltyType/UpdatePenaltyTypeCommand.php
declare(strict_types=1);

namespace Modules\PenaltyType\Application\Command\UpdatePenaltyType;

use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class UpdatePenaltyTypeCommand
{
    public function __construct(
        public string $id,
        public TranslatableText $name,
        public ?string $slug = null,
        public ?bool $isActive = null
    ) {}
}
