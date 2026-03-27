<?php
// modules/Shared/Domain/ValueObject/Money.php
declare(strict_types=1);

namespace Modules\Shared\Domain\ValueObject;

use Modules\Shared\Domain\ValueObject;

final readonly class Money extends ValueObject
{
    public function __construct(
        public float $amount,
        public string $currency = 'SAR'
    ) {}

    public function equals(ValueObject $other): bool
    {
        return $other instanceof self 
            && $this->amount === $other->amount 
            && $this->currency === $other->currency;
    }
}
