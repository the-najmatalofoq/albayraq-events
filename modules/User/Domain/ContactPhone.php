<?php
declare(strict_types=1);

namespace Modules\User\Domain;

use DateTimeImmutable;
use Modules\Shared\Domain\Entity;
use Modules\Shared\Domain\Identity;
use Modules\User\Domain\ValueObject\ContactPhoneId;
use Modules\User\Domain\ValueObject\Phone;
use Modules\User\Domain\ValueObject\UserId;

final class ContactPhone extends Entity
{
    private function __construct(
        public readonly ContactPhoneId $uuid,
        public readonly UserId         $userId,
        public private(set) string     $name,
        public private(set) Phone      $phone,
        public private(set) string     $relation,
        public readonly ?DateTimeImmutable $createdAt = null,
        public private(set) ?DateTimeImmutable $updatedAt = null,
    ) {}

    public static function create(
        ContactPhoneId $uuid,
        UserId         $userId,
        string         $name,
        Phone         $phone,
        string         $relation,
    ): self {
        return new self(
            uuid: $uuid,
            userId: $userId,
            name: $name,
            phone: $phone,
            relation: $relation,
            createdAt: new DateTimeImmutable(),
        );
    }

    public static function fromPersistence(
        ContactPhoneId     $uuid,
        UserId             $userId,
        string             $name,
        Phone             $phone,
        string             $relation,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null,
    ): self {
        return new self(
            uuid: $uuid,
            userId: $userId,
            name: $name,
            phone: $phone,
            relation: $relation,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public function update(
        string $name,
        Phone $phone,
        string $relation,
    ): void {
        $this->name = $name;
        $this->phone = $phone;
        $this->relation = $relation;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
