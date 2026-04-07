<?php
declare(strict_types=1);

namespace Modules\IAM\Domain;

use DateTimeImmutable;
use Modules\IAM\Domain\Enum\OtpPurposeEnum;
use Modules\IAM\Domain\ValueObject\OtpCodeId;
use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\User\Domain\ValueObject\UserId;
use Modules\IAM\Domain\Event\OtpRequested;

final class OtpCode extends AggregateRoot
{
    private function __construct(
        public readonly OtpCodeId $uuid,
        public readonly UserId $userId,
        public readonly string $code,
        public readonly OtpPurposeEnum $purpose,
        public readonly DateTimeImmutable $expiresAt,
        public private(set) ?DateTimeImmutable $verifiedAt = null,
        public readonly ?DateTimeImmutable $createdAt = null,
    ) {
    }

    public static function create(
        OtpCodeId $uuid,
        UserId $userId,
        string $code,
        OtpPurposeEnum $purpose,
        int $expiresInMinutes = 10,
    ): self {
        $otp = new self(
            uuid: $uuid,
            userId: $userId,
            code: $code,
            purpose: $purpose,
            expiresAt: (new DateTimeImmutable())->modify("+{$expiresInMinutes} minutes"),
            createdAt: new DateTimeImmutable(),
        );

        $otp->recordEvent(new OtpRequested($userId, $code, $purpose));

        return $otp;
    }

    public static function fromPersistence(
        OtpCodeId $uuid,
        UserId $userId,
        string $code,
        OtpPurposeEnum $purpose,
        DateTimeImmutable $expiresAt,
        ?DateTimeImmutable $verifiedAt,
        ?DateTimeImmutable $createdAt,
    ): self {
        return new self(
            uuid: $uuid,
            userId: $userId,
            code: $code,
            purpose: $purpose,
            expiresAt: $expiresAt,
            verifiedAt: $verifiedAt,
            createdAt: $createdAt,
        );
    }

    public function isExpired(): bool
    {
        return new DateTimeImmutable() > $this->expiresAt;
    }

    public function isVerified(): bool
    {
        return $this->verifiedAt !== null;
    }

    public function verify(): void
    {
        $this->verifiedAt = new DateTimeImmutable();
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
