<?php
declare(strict_types=1);

namespace Modules\Shared\Domain\ValueObject;

use Modules\Shared\Domain\ValueObject;

final readonly class FilterCriteria extends ValueObject
{
    public function __construct(
        public ?string $search = null,
        public ?string $sortBy = null,
        public ?string $sortDirection = 'asc',
        public array $filters = [],
    ) {
    }

    public static function fromArray(array $data): self
    {
        $search = $data['search'] ?? null;
        $sortBy = $data['sort_by'] ?? null;
        $sortDirection = $data['sort_dir'] ?? 'asc';

        $filters = array_diff_key($data, array_flip(['search', 'sort_by', 'sort_dir', 'per_page', 'page']));
        $filters = array_filter($filters, fn($value) => $value !== null && $value !== '');

        return new self(
            search: $search,
            sortBy: $sortBy,
            sortDirection: $sortDirection,
            filters: $filters
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'search' => $this->search,
            'sort_by' => $this->sortBy,
            'sort_dir' => $this->sortDirection,
            ...$this->filters,
        ], fn($value) => $value !== null && $value !== '');
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
        return $other instanceof self &&
            $this->search === $other->search &&
            $this->sortBy === $other->sortBy &&
            $this->sortDirection === $other->sortDirection &&
            $this->filters === $other->filters;
    }
}
