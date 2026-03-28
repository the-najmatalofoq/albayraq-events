<?php
// modules/Shared/Domain/Wage.php
declare(strict_types=1);

namespace Modules\Shared\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\WageId;
use Modules\Shared\Domain\ValueObject\Money;

final class Wage extends AggregateRoot
{
    private function __construct(
        public readonly WageId $uuid,
        public readonly string $wageableId,
        public readonly string $wageableType,
        public private(set) Money $amount,
        public private(set) string $period,
    ) {}

    public static function create(
        WageId $uuid,
        string $wageableId,
        string $wageableType,
        Money $amount,
        string $period = 'hourly'
    ): self {
        return new self($uuid, $wageableId, $wageableType, $amount, $period);
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
