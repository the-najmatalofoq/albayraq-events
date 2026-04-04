<?php
declare(strict_types=1);

namespace Modules\Geography\Domain;

use Modules\Geography\Domain\ValueObject\StateId;
use Modules\Geography\Domain\ValueObject\CountryId;
use Modules\Shared\Domain\Entity;
use Modules\Shared\Domain\Identity;
use DateTimeImmutable;

final class State extends Entity
{
    public function __construct(
        private readonly StateId $uuid,
        private readonly CountryId $countryId,
        private readonly array $names,
        private readonly DateTimeImmutable $createdAt,
        private readonly ?DateTimeImmutable $updatedAt
    ) {
    }

    public function id(): Identity
    {
        return $this->uuid;
    }

    public function countryId(): CountryId
    {
        return $this->countryId;
    }

    public function names(): array
    {
        return $this->names;
    }

    public function name(string $locale): ?string
    {
        return $this->names[$locale] ?? $this->names['en'] ?? null;
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
