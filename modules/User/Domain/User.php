<?php
// modules/User/Domain/User.php
declare(strict_types=1);

namespace Modules\User\Domain;

use DateTimeImmutable;
use Modules\User\Domain\ValueObject\HashedPassword;
use Modules\User\Domain\ValueObject\UserId;
use Modules\Role\Domain\ValueObject\RoleId;
use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\IAM\Domain\Event\UserRegistered;
use Modules\Shared\Domain\ValueObject\FilePath;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\User\Domain\ValueObject\Phone;

final class User extends AggregateRoot
{
    private function __construct(
        public readonly UserId $uuid,
        public readonly TranslatableText $name,
        public readonly ?string $email,
        public readonly Phone $phone,
        public private(set) HashedPassword $password,
        /** @var list<RoleId> */
        public private(set) array $roleIds,
        public private(set) bool $isActive,
        public readonly DateTimeImmutable $createdAt,
        public private(set) ?FilePath $avatar = null,
        public private(set) ?DateTimeImmutable $updatedAt = null,
        public private(set) ?DateTimeImmutable $phoneVerifiedAt = null,
        public private(set) ?DateTimeImmutable $deletedAt = null,
    ) {}

    public static function register(
        UserId $uuid,
        TranslatableText $name,
        ?string $email,
        Phone $phone,
        FilePath $avatar,
        HashedPassword $password,
        array $roleIds,
        DateTimeImmutable $createdAt,
        bool $isActive = false,
    ): self {
        $user = new self(
            uuid: $uuid,
            name: $name,
            email: $email,
            phone: $phone,
            password: $password,
            avatar: $avatar,
            roleIds: $roleIds,
            isActive: $isActive,
            createdAt: $createdAt,
        );
        $user->recordEvent(new UserRegistered($uuid, $phone));
        return $user;
    }

    public function hasRole(RoleId $roleId): bool
    {
        foreach ($this->roleIds as $id) {
            if ($id->equals($roleId)) return true;
        }
        return false;
    }

    public function assignRole(RoleId $roleId): void
    {
        if (!$this->hasRole($roleId)) {
            $this->roleIds[] = $roleId;
            $this->updatedAt = new DateTimeImmutable;
        }
    }

    public function changePassword(HashedPassword $password): void
    {
        $this->password = $password;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
