<?php
// modules/ViolationType/Domain/ViolationType.php
declare(strict_types=1);

namespace Modules\ViolationType\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Domain\ValueObject\Money;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;
use Modules\ViolationType\Domain\Enum\ViolationSeverityEnum;
use Modules\Event\Domain\ValueObject\EventId;

final class ViolationType extends AggregateRoot
{
    public function __construct(
        public readonly ViolationTypeId $uuid,
        public private(set) TranslatableText $name,
        public private(set) ?Money $defaultDeduction,
        public private(set) ViolationSeverityEnum $severity,
        public private(set) ?EventId $eventId = null,
        public private(set) bool $isActive = true
    ) {}

    public static function create(
        ViolationTypeId $uuid,
        TranslatableText $name,
        ?Money $defaultDeduction,
        ViolationSeverityEnum $severity,
        ?EventId $eventId = null,
        bool $isActive = true
    ): self {
        return new self($uuid, $name, $defaultDeduction, $severity, $eventId, $isActive);
    }

    public function update(
        TranslatableText $name,
        ?Money $defaultDeduction,
        ViolationSeverityEnum $severity,
        ?EventId $eventId = null
    ): void {

        $this->name = $name;
        $this->defaultDeduction = $defaultDeduction;
        $this->severity = $severity;
        $this->eventId = $eventId;
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
