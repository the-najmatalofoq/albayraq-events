<?php
declare(strict_types=1);

namespace Modules\Geography\Domain;

use Modules\Geography\Domain\ValueObject\CountryId;
use Modules\Shared\Domain\Entity;
use Modules\Shared\Domain\Identity;
use DateTimeImmutable;

final class Country extends Entity
{
    public function __construct(
        private readonly CountryId $uuid,
        private readonly string $code, // ISO 3166-1 alpha-2
        private readonly array $names, // ['ar' => '...', 'en' => '...']
        private readonly ?string $phoneCode,
        private readonly bool $isActive,
        private readonly DateTimeImmutable $createdAt,
        private readonly ?DateTimeImmutable $updatedAt
    ) {
    }

    public function id(): Identity
    {
        return $this->uuid;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function names(): array
    {
        return $this->names;
    }

    public function name(string $locale): ?string
    {
        return $this->names[$locale] ?? $this->names['en'] ?? null;
    }

    public function phoneCode(): ?string
    {
        return $this->phoneCode;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
