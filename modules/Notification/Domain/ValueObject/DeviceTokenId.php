<?php

declare(strict_types=1);

namespace Modules\Notification\Domain\ValueObject;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

// todo: extend the base class for value objects if you have one in our project
final class DeviceTokenId
{
    private UuidInterface $value;

    private function __construct(UuidInterface $value)
    {
        $this->value = $value;
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $value): self
    {
        return new self(Uuid::fromString($value));
    }

    public function toString(): string
    {
        return $this->value->toString();
    }

    public function equals(DeviceTokenId $other): bool
    {
        return $this->value->equals($other->value);
    }
}
