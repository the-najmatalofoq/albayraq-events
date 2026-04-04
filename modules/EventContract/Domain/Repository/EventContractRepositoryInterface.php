<?php

declare(strict_types=1);

namespace Modules\EventContract\Domain\Repository;

use Modules\Shared\Domain\PaginatedResult;
use Modules\EventContract\Domain\EventContract;
use Modules\EventContract\Domain\ValueObject\ContractId;

interface EventContractRepositoryInterface
{
    public function findById(ContractId $id): ?EventContract;

    public function paginate(int $page, int $perPage): PaginatedResult;

    public function save(EventContract $contract): void;
}
