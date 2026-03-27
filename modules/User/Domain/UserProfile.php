<?php
// modules/User/Domain/UserProfile.php
declare(strict_types=1);

namespace Modules\User\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\UserProfileId;

final class UserProfile extends AggregateRoot
{
    public function __construct(
        public readonly UserProfileId $uuid,
        public readonly UserId $userId,
        public private(set) string $employeeNumber,
        public private(set) TranslatableText $jobTitle,
        public private(set) TranslatableText $department,
        public private(set) ?\DateTimeImmutable $hiringDate = null,
        public private(set) bool $isActive = true
    ) {}

    public static function create(
        UserProfileId $uuid,
        UserId $userId,
        string $employeeNumber,
        TranslatableText $jobTitle,
        TranslatableText $department,
        ?\DateTimeImmutable $hiringDate = null,
        bool $isActive = true
    ): self {
        return new self($uuid, $userId, $employeeNumber, $jobTitle, $department, $hiringDate, $isActive);
    }

    public function updateProfessionalData(
        TranslatableText $jobTitle,
        TranslatableText $department
    ): void {
        $this->jobTitle = $jobTitle;
        $this->department = $department;
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
