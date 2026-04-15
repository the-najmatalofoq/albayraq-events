<?php

declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent\Repositories;

use Modules\User\Domain\UserUpdateRequest;
use Modules\User\Domain\Enum\UpdateRequestStatus;
use Modules\User\Domain\Repository\UserUpdateRequestRepositoryInterface;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserUpdateRequestModel;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\UserUpdateRequestId;
use Illuminate\Support\Str;

final readonly class EloquentUserUpdateRequestRepository implements UserUpdateRequestRepositoryInterface
{
    public function nextIdentity(): UserUpdateRequestId
    {
        return new UserUpdateRequestId(Str::uuid()->toString());
    }

    public function save(UserUpdateRequest $request): void
    {
        $userId = $request->userId;
        
        $newData = json_encode($request->newData);
        
        $reviewedAt = $request->reviewedAt 
            ? $request->reviewedAt->format('Y-m-d H:i:s') 
            : null;
        
        UserUpdateRequestModel::updateOrCreate(
            ['id' => $request->id()],
            [
                'user_id' => $userId,
                'target_type' => $request->targetType,
                'target_id' => $request->targetId,
                'new_data' => $newData,
                'status' => $request->status->value,
                'rejection_reason' => $request->rejectionReason,
                'reviewed_by' => $request->reviewedBy,
                'reviewed_at' => $reviewedAt,
            ]
        );
    }

    public function findById(string $id): ?UserUpdateRequest
    {
        $model = UserUpdateRequestModel::find($id);

        if (!$model) {
            return null;
        }

        return $this->toDomain($model);
    }

    public function findByUserId(string $userId): array
    {
        $models = UserUpdateRequestModel::where('user_id', $userId)
            ->latest()
            ->get();

        return $models->map(fn($m) => $this->toDomain($m))->toArray();
    }

    public function findAllPending(): array
    {
        $models = UserUpdateRequestModel::where('status', UpdateRequestStatus::PENDING->value)
            ->latest()
            ->get();

        return $models->map(fn($m) => $this->toDomain($m))->toArray();
    }

    private function toDomain(UserUpdateRequestModel $model): UserUpdateRequest
    {
        $newData = is_string($model->new_data) 
            ? json_decode($model->new_data, true) 
            : ($model->new_data ?? []);
        
        return new UserUpdateRequest(
            uuid: new UserUpdateRequestId($model->id),           // ✅ اسم الحقل uuid وليس id
            userId: new UserId($model->user_id),                 // ✅ تحويل إلى ValueObject
            targetType: $model->target_type,
            targetId: $model->target_id,
            newData: $newData,
            status: UpdateRequestStatus::from($model->status),
            rejectionReason: $model->rejection_reason,
            reviewedBy: $model->reviewed_by,
            reviewedAt: $model->reviewed_at 
                ? new \DateTimeImmutable($model->reviewed_at) 
                : null,
            createdAt: $model->created_at 
                ? new \DateTimeImmutable($model->created_at) 
                : null,
            updatedAt: $model->updated_at 
                ? new \DateTimeImmutable($model->updated_at) 
                : null,
        );
    }
}