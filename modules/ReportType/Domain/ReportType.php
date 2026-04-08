<?php
// modules/ReportType/Domain/ReportType.php
declare(strict_types=1);

namespace Modules\ReportType\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\ReportType\Domain\ValueObject\ReportTypeId;

final class ReportType extends AggregateRoot
{
    public function __construct(
        public readonly ReportTypeId $uuid,
        public private(set) TranslatableText $name,
        public private(set) string $slug,
        public private(set) bool $isActive = true
    ) {}

    public static function create(
        ReportTypeId $uuid,
        TranslatableText $name,
        string $slug,
        bool $isActive = true
    ): self {
        return new self($uuid, $name, $slug, $isActive);
    }

    public function update(
        ?string $slug = null,
        ?TranslatableText $name = null,
        ?bool $isActive = null,
    ): void {
        $this->slug = $slug;
        $this->name = $name;
        $this->isActive = $isActive;
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
