<?php
// modules/EventStaffingPositionRequirement/Domain/EventStaffingPositionRequirement.php
declare(strict_types=1);

namespace Modules\EventStaffingPositionRequirement\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\EventStaffingPositionRequirement\Domain\ValueObject\RequirementId;

final class EventStaffingPositionRequirement extends AggregateRoot
{
    public function __construct(
        public readonly RequirementId $uuid,
        public readonly PositionId $positionId,
        public private(set) TranslatableText $title,
        public private(set) bool $isRequired = true,
        public private(set) ?string $description = null
    ) {
    }

    public static function create(
        RequirementId $uuid,
        PositionId $positionId,
        TranslatableText $title,
        bool $isRequired = true,
        ?string $description = null
    ): self {
        return new self($uuid, $positionId, $title, $isRequired, $description);
    }

    public function update(
        TranslatableText $title,
        bool $isRequired,
        ?string $description
    ): void {
        $this->title = $title;
        $this->isRequired = $isRequired;
        $this->description = $description;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
