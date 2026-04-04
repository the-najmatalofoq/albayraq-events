<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\CreateCountry;

use Modules\Geography\Domain\Country;
use Modules\Geography\Domain\Repository\CountryRepositoryInterface;
use Modules\Shared\Infrastructure\Services\CacheService;

final class CreateCountryHandler
{
    public function __construct(
        private readonly CountryRepositoryInterface $repository,
        private readonly CacheService $cache
    ) {
    }

    public function handle(CreateCountryCommand $command): void
    {
        $country = new Country(
            $this->repository->nextIdentity(),
            $command->data['code'],
            $command->data['name'],
            $command->data['phone_code'] ?? null,
            $command->data['is_active'] ?? true,
            new \DateTimeImmutable(),
            null
        );

        $this->repository->save($country);
        $this->cache->flushGroup('geography');
    }
}
