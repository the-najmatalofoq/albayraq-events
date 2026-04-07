<?php
declare(strict_types=1);

namespace Modules\Shared\Domain\Repository;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FilterableRepositoryInterface
{
    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator;
    public function all(array $filters = []): Collection;
}
