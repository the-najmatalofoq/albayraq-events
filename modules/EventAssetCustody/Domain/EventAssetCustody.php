<?php
// modules/EventAssetCustody/Domain/EventAssetCustody.php
declare(strict_types=1);

namespace Modules\EventAssetCustody\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\EventAssetCustody\Domain\ValueObject\CustodyId;
use Modules\EventAssetCustody\Domain\ValueObject\CustodyStatus;
use Modules\IAM\Domain\ValueObject\UserId;

final class EventAssetCustody extends AggregateRoot
{
    public function __construct(
        public readonly CustodyId $uuid,
        public readonly ParticipationId $participationId,
        public private(set) TranslatableText $itemName,
        public private(set) CustodyStatus $status = CustodyStatus::HANDED_OVER,
        public private(set) ?TranslatableText $description = null,
        public readonly \DateTimeImmutable $handedAt = new \DateTimeImmutable(),
        public private(set) ?\DateTimeImmutable $returnedAt = null,
        public readonly UserId $handedBy
    ) {
    }

    public static function create(
        CustodyId $uuid,
        ParticipationId $participationId,
        TranslatableText $itemName,
        UserId $handedBy,
        CustodyStatus $status = CustodyStatus::HANDED_OVER,
        ?TranslatableText $description = null
    ): self {
        return new self($uuid, $participationId, $itemName, $status, $description, new \DateTimeImmutable(), null, handedBy: $handedBy);
    }

    public function returnAsset(): void
    {
        $this->status = CustodyStatus::RETURNED;
        $this->returnedAt = new \DateTimeImmutable();
    }

    public function markAsLost(): void
    {
        $this->status = CustodyStatus::LOST;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
