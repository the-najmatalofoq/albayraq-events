<?php
// modules/Currency/Domain/Currency.php
declare(strict_types=1);

namespace Modules\Currency\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Currency\Domain\ValueObject\CurrencyId;
use Modules\Shared\Domain\ValueObject\TranslatableText;

final class Currency extends AggregateRoot
{
    private function __construct(
        public readonly CurrencyId $uuid,
        public private(set) TranslatableText $name,
        public private(set) string $code,
        public private(set) string $symbol,
        public private(set) bool $isActive = true,
    ) {}

    public static function create(
        CurrencyId $uuid,
        TranslatableText $name,
        string $code,
        string $symbol,
        bool $isActive = true
    ): self {
        return new self($uuid, $name, $code, $symbol, $isActive);
    }

    public static function reconstitute(
        string $uuid,
        TranslatableText $name,
        string $code,
        string $symbol,
        bool $isActive
    ): self {
        return new self(CurrencyId::fromString($uuid), $name, $code, $symbol, $isActive);
    }

    public function update(
        TranslatableText $name,
        string $code,
        string $symbol,
        bool $isActive
    ): void {
        $this->name = $name;
        $this->code = $code;
        $this->symbol = $symbol;
        $this->isActive = $isActive;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
