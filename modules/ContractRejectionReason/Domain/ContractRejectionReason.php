<?php
// modules/ContractRejectionReason/Domain/ContractRejectionReason.php
declare(strict_types=1);

namespace Modules\ContractRejectionReason\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\ContractRejectionReason\Domain\ValueObject\ContractRejectionReasonId;

final class ContractRejectionReason extends AggregateRoot
{
    public function __construct(
        public readonly ContractRejectionReasonId $uuid,
        public private(set) TranslatableText $reason,
        public private(set) bool $isActive = true
    ) {}

    public static function create(
        ContractRejectionReasonId $uuid,
        TranslatableText $reason,
        bool $isActive = true
    ): self {
        return new self($uuid, $reason, $isActive);
    }

    public function update(TranslatableText $reason): void
    {
        $this->reason = $reason;
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
