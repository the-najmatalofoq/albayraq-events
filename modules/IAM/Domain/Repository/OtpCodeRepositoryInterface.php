<?php
declare(strict_types=1);

namespace Modules\IAM\Domain\Repository;

use Modules\IAM\Domain\Enum\OtpPurposeEnum;
use Modules\IAM\Domain\OtpCode;
use Modules\IAM\Domain\ValueObject\OtpCodeId;
use Modules\User\Domain\ValueObject\UserId;

interface OtpCodeRepositoryInterface
{
    public function nextIdentity(): OtpCodeId;
    public function save(OtpCode $otpCode): void;
    public function findLatestActiveByUserAndPurpose(
        UserId $userId,
        OtpPurposeEnum $purpose,
    ): ?OtpCode;
    public function invalidateAllForUserAndPurpose(
        UserId $userId,
        OtpPurposeEnum $purpose,
    ): void;
}
