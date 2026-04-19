<?php
// modules/PenaltyType/Application/Command/CreatePenaltyType/CreatePenaltyTypeCommand.php
declare(strict_types=1);

namespace Modules\PenaltyType\Application\Command\CreatePenaltyType;

use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class CreatePenaltyTypeCommand
{
    public function __construct(
        public TranslatableText $name,
        public string $slug,
        public bool $isActive = true
    ) {}
}
