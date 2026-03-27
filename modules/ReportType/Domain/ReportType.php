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
        public readonly string $code,
        public private(set) bool $isActive = true
    ) {}

    public static function create(
        ReportTypeId $uuid,
        TranslatableText $name,
        string $code,
        bool $isActive = true
    ): self {
        return new self($uuid, $name, $code, $isActive);
    }

    public function update(TranslatableText $name): void
    {
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
