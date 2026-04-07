<?php
// modules/User/Infrastructure/Persistence/Eloquent/EloquentContactPhoneRepository.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\User\Domain\ContactPhone;
use Modules\User\Domain\Repository\ContactPhoneRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\ContactPhoneId;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\ContactPhoneModel;
use Modules\Shared\Domain\ValueObject\FilterCriteria;

final class EloquentContactPhoneRepository implements ContactPhoneRepositoryInterface
{
    public function __construct(
        private readonly ContactPhoneModel $model
    ) {}

    public function save(ContactPhone $contactPhone): void
    {
        $this->model->updateOrCreate(
            ['id' => $contactPhone->uuid->value],
            [
                'user_id' => $contactPhone->userId->value,
                'name' => $contactPhone->name,
                'phone' => $contactPhone->phone,
                'relation' => $contactPhone->relation,
            ]
        );
    }

    public function findByUserId(UserId $userId): array
    {
        $models = $this->model->where('user_id', $userId->value)->get();

        return $models->map(fn(ContactPhoneModel $model) => $this->toDomain($model))->toArray();
    }

    public function findById(ContactPhoneId $uuid): ?ContactPhone
    {
        $model = $this->model->find($uuid->value);

        return $model ? $this->toDomain($model) : null;
    }

    public function nextIdentity(): ContactPhoneId
    {
        return ContactPhoneId::generate();
    }

    public function delete(ContactPhoneId $uuid): void
    {
        $this->model->where('id', $uuid->value)->delete();
    }

    public function deleteBulk(UserId $userId, array $ids): void
    {
        $this->model->where('user_id', $userId->value)
            ->whereIn('id', array_map(fn($id) => $id->value, $ids))
            ->delete();
    }

    public function paginate(FilterCriteria $criteria, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->query();

        $this->applyCriteria($query, $criteria);

        $paginator = $query->paginate($perPage);

        $paginator->getCollection()->transform(fn(ContactPhoneModel $model) => $this->toDomain($model));

        return $paginator;
    }

    public function all(FilterCriteria $criteria): Collection
    {
        $query = $this->model->query();

        $this->applyCriteria($query, $criteria);

        return $query->get()->map(fn(ContactPhoneModel $model) => $this->toDomain($model));
    }

    private function applyCriteria($query, FilterCriteria $criteria): void
    {
        if ($criteria->search) {
            $query->where(function ($q) use ($criteria) {
                $q->where('name', 'like', "%{$criteria->search}%")
                  ->orWhere('phone', 'like', "%{$criteria->search}%")
                  ->orWhere('relation', 'like', "%{$criteria->search}%");
            });
        }

        if ($criteria->has('user_id')) {
            $query->where('user_id', $criteria->get('user_id'));
        }

        if ($criteria->sortBy) {
            $query->orderBy($criteria->sortBy, $criteria->sortDirection ?? 'asc');
        }
    }

    private function toDomain(ContactPhoneModel $model): ContactPhone
    {
        return ContactPhone::fromPersistence(
            uuid: ContactPhoneId::fromString($model->id),
            userId: UserId::fromString($model->user_id),
            name: $model->name,
            phone: $model->phone,
            relation: $model->relation,
            createdAt: $model->created_at ? new \DateTimeImmutable($model->created_at->toDateTimeString()) : null,
            updatedAt: $model->updated_at ? new \DateTimeImmutable($model->updated_at->toDateTimeString()) : null,
        );
    }
}
