<?php
// modules/User/Domain/ContactPhone.php
declare(strict_types=1);

namespace Modules\User\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\User\Domain\ValueObject\ContactPhoneId;
use Modules\User\Domain\ValueObject\Phone;
use Modules\User\Domain\ValueObject\UserId;

final class ContactPhone extends AggregateRoot
{
    private function __construct(
        public readonly ContactPhoneId $uuid,
        public readonly UserId $userId,
        public private(set) string $name,
        public private(set) Phone $phone,
        public private(set) ?string $relation = null,
    ) {}

    public static function create(
        ContactPhoneId $uuid,
        UserId $userId,
        string $name,
        Phone $phone,
        ?string $relation = null,
    ): self {
        return new self($uuid, $userId, $name, $phone, $relation);
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
