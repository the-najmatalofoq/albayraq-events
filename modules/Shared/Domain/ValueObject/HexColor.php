<?php
// modules/Shared/Domain/ValueObject/HexColor.php
declare(strict_types=1);

namespace Modules\Shared\Domain\ValueObject;

use Modules\Shared\Domain\ValueObject;

final readonly class HexColor extends ValueObject
{
    public function __construct(
        public string $value
    ) {
        if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $this->value)) {
            throw new \InvalidArgumentException("Invalid hex color format: {$this->value}");
        }
    }

    public function equals(ValueObject $other): bool
    {
        return $other instanceof self && strtolower($this->value) === strtolower($other->value);
    }
}
