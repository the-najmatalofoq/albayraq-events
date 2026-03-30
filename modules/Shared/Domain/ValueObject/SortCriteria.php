<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\ValueObject;

use Modules\Shared\Domain\ValueObject;

final class SortCriteria extends ValueObject
{
    public function __construct(
        public readonly string $field,
        public readonly string $direction = 'desc',
    ) {
        $direction = strtolower($direction);
        if (!in_array($direction, ['asc', 'desc'])) {
            throw new \InvalidArgumentException('Direction must be asc or desc');
        }
        $this->direction = $direction;
    }

    public static function fromArray(
        array $params,
        string $defaultField = 'created_at',
        string $defaultDirection = 'desc'
    ): self {
        return new self(
            field: $params['order_by'] ?? $defaultField,
            direction: $params['order_direction'] ?? $defaultDirection,
        );
    }

    public function equals(ValueObject $other): bool
    {
        return $other instanceof self
            && $this->field === $other->field
            && $this->direction === $other->direction;
    }
}
