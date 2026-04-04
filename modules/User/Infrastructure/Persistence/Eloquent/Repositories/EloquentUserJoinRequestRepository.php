<?php

declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Modules\User\Domain\Repository\UserJoinRequestRepositoryInterface;
use Modules\User\Domain\UserJoinRequest;
use Modules\User\Domain\ValueObject\UserJoinRequestId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserJoinRequestModel;

// fix: use constructor property promotion
final class EloquentUserJoinRequestRepository implements UserJoinRequestRepositoryInterface
{
    public function nextIdentity(): UserJoinRequestId
    {
        // fix: don't use str uuid.
        return new UserJoinRequestId((string) Str::uuid());
    }

    public function save(UserJoinRequest $joinRequest): void
    {
        UserJoinRequestModel::updateOrCreate(
            ['id' => $joinRequest->uuid->value],
            [
                'user_id' => $joinRequest->userId->value,
                'status' => $joinRequest->status->value,
                'reviewed_by' => $joinRequest->reviewedBy,
                'reviewed_at' => $joinRequest->reviewedAt,
                'notes' => $joinRequest->notes,
            ]
        );
    }

    public function findById(UserJoinRequestId $id): ?UserJoinRequest
    {
        $model = UserJoinRequestModel::find($id->value);
        return $model ? $this->toDomain($model) : null;
    }

    public function findLatestByUserId(UserId $userId): ?UserJoinRequest
    {
        $model = UserJoinRequestModel::where('user_id', $userId->value)
            ->latest()
            ->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function delete(UserJoinRequestId $id): void
    {
        UserJoinRequestModel::destroy($id->value);
    }

    public function findAll(): array
    {
        return UserJoinRequestModel::with('user')
            ->latest()
            ->get()
            ->map(fn(UserJoinRequestModel $m) => $this->toDomain($m))
            ->all();
    }

    public function paginate(int $page, int $perPage): LengthAwarePaginator
    {
        return UserJoinRequestModel::with('user')
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);
    }

    private function toDomain(UserJoinRequestModel $model): UserJoinRequest
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
}
