<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\UpdateCity;

use Modules\Geography\Domain\ValueObject\CityId;
use Modules\Geography\Domain\Repository\CityRepositoryInterface;
use Modules\Geography\Domain\ValueObject\CountryId;
use Modules\Geography\Domain\ValueObject\StateId;
use Modules\Shared\Infrastructure\Services\CacheService;

final class UpdateCityHandler
{
    public function __construct(
        private readonly CityRepositoryInterface $repository,
        private readonly CacheService $cache
    ) {}

    public function handle(UpdateCityCommand $command): void
    {
        $id = new CityId($command->id);
        $city = $this->repository->findById($id);

        if (!$city) {
            throw new \RuntimeException("City not found");
        }

        $updatedCity = new \Modules\Geography\Domain\City(
            $id,
            isset($command->data['country_id']) ? new CountryId($command->data['country_id']) : $city->countryId(),
            array_key_exists('state_id', $command->data) 
                ? ($command->data['state_id'] ? new StateId($command->data['state_id']) : null)
                : $city->stateId(),
            $command->data['name'] ?? $city->names(),
            $city->createdAt(),
            new \DateTimeImmutable()
        );

        $this->repository->save($updatedCity);
        $this->cache->flushGroup('geography');
    }
}
