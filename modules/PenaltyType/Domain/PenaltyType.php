<?php
// modules/PenaltyType/Domain/PenaltyType.php
declare(strict_types=1);

namespace Modules\PenaltyType\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\PenaltyType\Domain\ValueObject\PenaltyTypeId;

final class PenaltyType extends AggregateRoot
{
    public function __construct(
        public readonly PenaltyTypeId $uuid,
        public private(set) string $slug,
        public private(set) TranslatableText $name,
        public private(set) bool $isActive = true
    ) {}

    public static function create(
        PenaltyTypeId $uuid,
        string $slug,
        TranslatableText $name,
        bool $isActive = true
    ): self {
        return new self($uuid, $slug, $name, $isActive);
    }

    public function update(
        string $slug,
        TranslatableText $name,
    ): void {
        $this->slug = $slug;
        $this->name = $name;
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
