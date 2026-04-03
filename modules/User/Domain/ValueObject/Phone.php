<?php

declare(strict_types=1);

namespace Modules\User\Domain\ValueObject;

use InvalidArgumentException;
use Modules\Shared\Domain\ValueObject;

final readonly class Phone extends ValueObject
{
    public string $value;

    public function __construct(string $value)
    {
        $normalized = $this->normalize($value);

        if (!$this->isValid($normalized)) {
            throw new InvalidArgumentException("phone is not valid");
        }

        $this->value = $normalized;
    }
    private function normalize(string $value): string
    {
        $digits = preg_replace('/[^0-9]/', '', $value);

        if (str_starts_with($digits, '9665')) {
            return '0' . substr($digits, 3);
        }

        if (str_starts_with($digits, '5') && strlen($digits) === 9) {
            return '0' . $digits;
        }

        return $digits;
    }

    private function isValid(string $value): bool
    {
        return preg_match('/^05\d{8}$/', $value) === 1;
    }

    public function equals(ValueObject $other): bool
    {
        return $other instanceof self && $this->value === $other->value;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
