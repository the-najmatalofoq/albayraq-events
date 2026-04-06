<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\ValueObject;

use Modules\Shared\Domain\ValueObject;
use InvalidArgumentException;

final readonly class FilePath extends ValueObject
{
    public function __construct(public string $value)
    {
        if (empty($value)) {
            throw new InvalidArgumentException('File path cannot be empty');
        }

        // Basic validation - ensure it's a relative path without dangerous characters
        if (str_contains($value, '..') || str_starts_with($value, '/')) {
            throw new InvalidArgumentException('Invalid file path');
        }
    }

    public function equals(ValueObject $other): bool
    {
        return $other instanceof self && $this->value === $other->value;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function getDirectory(): string
    {
        return dirname($this->value);
    }

    public function getFilename(): string
    {
        return basename($this->value);
    }
}
