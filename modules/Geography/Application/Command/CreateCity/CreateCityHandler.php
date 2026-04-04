<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\CreateCity;

use Modules\Geography\Domain\City;
use Modules\Geography\Domain\Repository\CityRepositoryInterface;
use Modules\Geography\Domain\ValueObject\CountryId;
use Modules\Geography\Domain\ValueObject\StateId;
use Modules\Shared\Infrastructure\Services\CacheService;

final class CreateCityHandler
{
    public function __construct(
        private readonly CityRepositoryInterface $repository,
        private readonly CacheService $cache
    ) {
    }

    public function handle(CreateCityCommand $command): void
    {
        $city = new City(
            $this->repository->nextIdentity(),
            new CountryId($command->data['country_id']),
            isset($command->data['state_id']) ? new StateId($command->data['state_id']) : null,
            $command->data['name'],
            new \DateTimeImmutable(),
            null
        );

        $this->repository->save($city);
        $this->cache->flushGroup('geography');
    }
}
