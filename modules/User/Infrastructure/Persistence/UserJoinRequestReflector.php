<?php

declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence;

use Modules\User\Domain\UserJoinRequest;
use Modules\User\Domain\ValueObject\UserJoinRequestId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserJoinRequestModel;
use DateTimeImmutable;

final class UserJoinRequestReflector
{
    public function toEntity(UserJoinRequestModel $model): UserJoinRequest
    {
        return UserJoinRequest::reconstitute(
            uuid: new UserJoinRequestId($model->id),
            userId: new UserId($model->user_id),
            status: $model->status,
            reviewedBy: $model->reviewed_by,
            reviewedAt: $model->reviewed_at?->toDateTimeImmutable(),
            notes: $model->notes,
            createdAt: $model->created_at->toDateTimeImmutable(),
            updatedAt: $model->updated_at?->toDateTimeImmutable(),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function fromEntity(UserJoinRequest $entity): array
    {
        return [
            'id' => $entity->uuid->value,
            'user_id' => $entity->userId->value,
            'status' => $entity->status->value,
            'reviewed_by' => $entity->reviewedBy,
            'reviewed_at' => $entity->reviewedAt?->format('Y-m-d H:i:s'),
            'notes' => $entity->notes,
            'created_at' => $entity->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $entity->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
