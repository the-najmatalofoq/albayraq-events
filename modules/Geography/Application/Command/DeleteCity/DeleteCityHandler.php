<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\DeleteCity;

use Modules\Geography\Domain\ValueObject\CityId;
use Modules\Geography\Domain\Repository\CityRepositoryInterface;
use Modules\Shared\Infrastructure\Services\CacheService;

final class DeleteCityHandler
{
    public function __construct(
        private readonly CityRepositoryInterface $repository,
        private readonly CacheService $cache
    ) {}

    public function handle(DeleteCityCommand $command): void
    {
        $this->repository->delete(new CityId($command->id));
        $this->cache->flushGroup('geography');
    }
}
