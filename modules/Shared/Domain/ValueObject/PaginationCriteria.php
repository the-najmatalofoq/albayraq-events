<?php
declare(strict_types=1);

namespace Modules\Shared\Domain\ValueObject;

use Modules\Shared\Domain\ValueObject;

final class PaginationCriteria extends ValueObject
{
    public function __construct(
        public readonly int $page,
        public readonly int $perPage,
    ) {
        if ($page < 1) {
            throw new \InvalidArgumentException('Page must be at least 1');
        }
        if ($perPage < 1) {
            throw new \InvalidArgumentException('Per page must be at least 1');
        }
        if ($perPage > 100) {
            throw new \InvalidArgumentException('Per page cannot exceed 100');
        }
    }

    public static function fromArray(array $params): self
    {
        return new self(
            page: (int) ($params['page'] ?? 1),
            perPage: (int) ($params['per_page'] ?? 15),
        );
    }

    public function offset(): int
    {
        return ($this->page - 1) * $this->perPage;
    }

    public function equals(ValueObject $other): bool
    {
        return $other instanceof self
            && $this->page === $other->page
            && $this->perPage === $other->perPage;
    }
}
