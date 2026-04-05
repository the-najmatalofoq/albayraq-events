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
use Modules\User\Infrastructure\Persistence\UserJoinRequestReflector;

final class EloquentUserJoinRequestRepository implements UserJoinRequestRepositoryInterface
{
    public function __construct(
        private readonly UserJoinRequestReflector $reflector,
    ) {
    }

    public function nextIdentity(): UserJoinRequestId
    {
        return UserJoinRequestId::generate();
    }

    public function save(UserJoinRequest $joinRequest): void
    {
        UserJoinRequestModel::updateOrCreate(
            ['id' => $joinRequest->uuid->value],
            $this->reflector->fromEntity($joinRequest)
        );
    }

    public function findById(UserJoinRequestId $id): ?UserJoinRequest
    {
        $model = UserJoinRequestModel::find($id->value);
        return $model ? $this->reflector->toEntity($model) : null;
    }

    public function findLatestByUserId(UserId $userId): ?UserJoinRequest
    {
        $model = UserJoinRequestModel::where('user_id', $userId->value)
            ->latest()
            ->first();

        return $model ? $this->reflector->toEntity($model) : null;
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
            ->map(fn(UserJoinRequestModel $m) => $this->reflector->toEntity($m))
            ->all();
    }

    public function paginate(int $page, int $perPage): LengthAwarePaginator
    {
        return UserJoinRequestModel::with('user')
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);
    }
}
