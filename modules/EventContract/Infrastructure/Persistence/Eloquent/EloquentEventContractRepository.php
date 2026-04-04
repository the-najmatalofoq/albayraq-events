<?php

declare(strict_types=1);

namespace Modules\EventContract\Infrastructure\Persistence\Eloquent;

use Modules\Shared\Domain\PaginatedResult;
use Modules\EventContract\Domain\EventContract;
use Modules\EventContract\Domain\ValueObject\ContractId;
use Modules\EventContract\Domain\Repository\EventContractRepositoryInterface;
use Modules\EventContract\Infrastructure\Persistence\EventContractReflector;
use Modules\EventContract\Infrastructure\Persistence\Eloquent\EventContractModel;

final class EloquentEventContractRepository implements EventContractRepositoryInterface
{
    public function __construct(
        private readonly EventContractModel $model,
        private readonly EventContractReflector $reflector,
    ) {}

    public function findById(ContractId $id): ?EventContract
    {
        $model = $this->model->find($id->value);

        return $model ? $this->reflector->toEntity($model) : null;
    }

    public function paginate(int $page, int $perPage): PaginatedResult
    {
        $paginator = $this->model->paginate(
            perPage: $perPage,
            page: $page
        );

        $items = array_map(
            fn(EventContractModel $model) => $this->reflector->toEntity($model),
            $paginator->items()
        );

        return new PaginatedResult(
            items: $items,
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
            lastPage: $paginator->lastPage()
        );
    }

    public function save(EventContract $contract): void
    {
        $data = $this->reflector->fromEntity($contract);

        $this->model->updateOrCreate(
            ['id' => $contract->uuid->value],
            $data
        );
    }
}
