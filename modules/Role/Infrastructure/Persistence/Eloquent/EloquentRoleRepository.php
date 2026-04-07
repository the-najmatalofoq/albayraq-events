<?php

declare(strict_types=1);

namespace Modules\Role\Infrastructure\Persistence\Eloquent;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Role\Domain\Repository\RoleRepository;
use Modules\Role\Domain\Role;
use Modules\Role\Domain\Enum\RoleSlugEnum;
use Modules\Role\Domain\ValueObject\RoleId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Domain\ValueObject\FilterCriteria;
use Modules\Role\Domain\Enum\RoleLevelEnum;

final class EloquentRoleRepository implements RoleRepository
{
    public function save(Role $role): void
    {
        RoleModel::query()->updateOrCreate(
            ['id' => $role->uuid->value],
            [
                'slug' => $role->slug->value,
                'name' => $role->name->toArray(),
                'is_global' => $role->isGlobal,
                'level' => $role->level->value,
            ]
        );
    }

    public function findById(RoleId $id): ?Role
    {
        $record = RoleModel::find($id->value);

        return $record ? $this->toDomain($record) : null;
    }

    public function findBySlug(RoleSlugEnum $slug): ?Role
    {
        $record = RoleModel::where('slug', $slug->value)->first();

        return $record ? $this->toDomain($record) : null;
    }

    public function nextIdentity(): RoleId
    {
        return RoleId::generate();
    }

    public function paginate(FilterCriteria $criteria, int $perPage = 15): LengthAwarePaginator
    {
        $query = RoleModel::query();
        $this->applyCriteria($query, $criteria);

        $paginator = $query->paginate($perPage);

        $paginator->setCollection(
            $paginator->getCollection()->map(fn(RoleModel $model) => $this->toDomain($model))
        );

        return $paginator;
    }

    public function all(FilterCriteria $criteria): Collection
    {
        $query = RoleModel::query();
        $this->applyCriteria($query, $criteria);

        return $query->get()->map(fn(RoleModel $model) => $this->toDomain($model));
    }

    public function delete(RoleId $id): void
    {
        RoleModel::query()->where('id', $id->value)->delete();
    }

    private function applyCriteria($query, FilterCriteria $criteria): void
    {
        if ($criteria->search) {
            $query->where('slug', 'like', "%{$criteria->search}%")
                  ->orWhere('name', 'like', "%{$criteria->search}%");
        }

        if ($criteria->has('level')) {
            $query->where('level', $criteria->get('level'));
        }

        if ($criteria->has('is_global')) {
            $query->where('is_global', (bool)$criteria->get('is_global'));
        }

        $sortBy = $criteria->sortBy ?: 'created_at';
        $sortDir = $criteria->sortDirection ?: 'desc';
        $query->orderBy($sortBy, $sortDir);
    }

    private function toDomain(RoleModel $record): Role
    {
        return Role::create(
            uuid: RoleId::fromString($record->id),
            slug: RoleSlugEnum::from($record->slug),
            name: TranslatableText::fromArray($record->name),
            isGlobal: $record->is_global,
            level: $record->level,
        );
    }
}
