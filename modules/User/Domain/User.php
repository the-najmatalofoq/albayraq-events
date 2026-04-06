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
        public private(set) ?DateTimeImmutable $emailVerifiedAt = null,
        public private(set) ?DateTimeImmutable $updatedAt = null,
        public private(set) ?DateTimeImmutable $deletedAt = null,
    ) {}

    public static function register(
        UserId $uuid,
        TranslatableText $name,
        ?string $email,
        Phone $phone,
        HashedPassword $password,
        array $roleIds,
        DateTimeImmutable $createdAt,
        ?string $nationalId = null,
        ?FilePath $avatar = null,
        bool $isActive = false,
    ): self {
        $user = new self(
            uuid: $uuid,
            name: $name,
            email: $email,
            phone: $phone,
            password: $password,
            roleIds: $roleIds,
            isActive: $isActive,
            createdAt: $createdAt,
            avatar: $avatar,
            emailVerifiedAt: null,
        );

        $user->recordEvent(new UserRegistered($uuid));

        return $user;
    }

    public static function reconstitute(
        UserId $uuid,
        TranslatableText $name,
        ?string $email,
        Phone $phone,
        HashedPassword $password,
        array $roleIds,
        bool $isActive,
        DateTimeImmutable $createdAt,
        ?FilePath $avatar = null,
        ?DateTimeImmutable $emailVerifiedAt = null,
        ?DateTimeImmutable $updatedAt = null,
        ?DateTimeImmutable $deletedAt = null,
    ): self {
        return new self(
            uuid: $uuid,
            name: $name,
            email: $email,
            phone: $phone,
            password: $password,
            roleIds: $roleIds,
            isActive: $isActive,
            createdAt: $createdAt,
            avatar: $avatar,
            emailVerifiedAt: $emailVerifiedAt,
            updatedAt: $updatedAt,
            deletedAt: $deletedAt,
        );
    }

    public function markEmailAsVerified(): void
    {
        $this->emailVerifiedAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function hasRole(RoleId $roleId): bool
    {
        foreach ($this->roleIds as $id) {
            if ($id->equals($roleId)) {
                return true;
            }
        }
        return false;
    }

    public function assignRole(RoleId $roleId): void
    {
        if (!$this->hasRole($roleId)) {
            $this->roleIds[] = $roleId;
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function activate(): void
    {
        $this->isActive = true;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function deactivate(): void
    {
        $this->isActive = false;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function changePassword(HashedPassword $password): void
    {
        $this->password = $password;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function updateAvatar(FilePath $avatar): void
    {
        $this->avatar = $avatar;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
