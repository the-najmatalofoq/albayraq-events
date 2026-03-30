<?php
declare(strict_types=1);

namespace Modules\Shared\Domain\ValueObject;

use Modules\Shared\Domain\ValueObject;

final class FilterCriteria extends ValueObject
{
    /**
     * @param array<string, mixed> $filters
     */
    public function __construct(
        public readonly array $filters = [],
    ) {}

    public static function fromArray(array $filters): self
    {
        return new self(
            filters: array_filter($filters, fn($value) => $value !== null && $value !== ''),
        );
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->filters[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($this->filters[$key]);
    }

    public function equals(ValueObject $other): bool
    {
        return $other instanceof self && $this->filters === $other->filters;
    }
}
