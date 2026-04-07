<?php
declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Persistence\Eloquent\Repositories;

use DateTimeImmutable;
use Modules\IAM\Domain\Enum\OtpPurposeEnum;
use Modules\IAM\Domain\OtpCode;
use Modules\IAM\Domain\Repository\OtpCodeRepositoryInterface;
use Modules\IAM\Domain\ValueObject\OtpCodeId;
use Modules\IAM\Infrastructure\Persistence\Eloquent\Models\OtpCodeModel;
use Modules\User\Domain\ValueObject\UserId;

final class EloquentOtpCodeRepository implements OtpCodeRepositoryInterface
{
    public function __construct(
        private readonly OtpCodeModel $model,
    ) {
    }

    public function nextIdentity(): OtpCodeId
    {
        return OtpCodeId::generate();
    }

    public function save(OtpCode $otpCode): void
    {
        $this->model->query()->updateOrCreate(
            ['id' => $otpCode->uuid->value],
            [
                'user_id' => $otpCode->userId->value,
                'code' => $otpCode->code,
                'purpose' => $otpCode->purpose->value,
                'expires_at' => $otpCode->expiresAt->format('Y-m-d H:i:s'),
                'verified_at' => $otpCode->verifiedAt?->format('Y-m-d H:i:s'),
            ],
        );
    }

    public function findLatestActiveByUserAndPurpose(
        UserId $userId,
        OtpPurposeEnum $purpose,
    ): ?OtpCode {
        $model = $this->model
            ->where('user_id', $userId->value)
            ->where('purpose', $purpose->value)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function invalidateAllForUserAndPurpose(
        UserId $userId,
        OtpPurposeEnum $purpose,
    ): void {
        $this->model
            ->where('user_id', $userId->value)
            ->where('purpose', $purpose->value)
            ->whereNull('verified_at')
            ->update(['verified_at' => now()]);
    }

    private function toDomain(OtpCodeModel $model): OtpCode
    {
        return OtpCode::fromPersistence(
            uuid: OtpCodeId::fromString($model->id),
            userId: UserId::fromString($model->user_id),
            code: $model->code,
            purpose: $model->purpose,
            expiresAt: new DateTimeImmutable($model->expires_at->toDateTimeString()),
            verifiedAt: $model->verified_at
            ? new DateTimeImmutable($model->verified_at->toDateTimeString())
            : null,
            createdAt: $model->created_at
            ? new DateTimeImmutable($model->created_at->toDateTimeString())
            : null,
        );
    }
}
