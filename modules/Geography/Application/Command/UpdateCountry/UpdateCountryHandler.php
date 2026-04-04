<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\UpdateCountry;

use Modules\Geography\Domain\Country;
use Modules\Geography\Domain\ValueObject\CountryId;
use Modules\Geography\Domain\Repository\CountryRepositoryInterface;
use Modules\Shared\Infrastructure\Services\CacheService;

final class UpdateCountryHandler
{
    public function __construct(
        private readonly CountryRepositoryInterface $repository,
        private readonly CacheService $cache
    ) {
    }

    public function handle(UpdateCountryCommand $command): void
    {
        $id = new CountryId($command->id);
        $country = $this->repository->findById($id);

        if (!$country) {
            throw new \RuntimeException("Country not found");
        }

        $updatedCountry = new Country(
            $id,
            $command->data['code'] ?? $country->code(),
            $command->data['name'] ?? $country->names(),
            array_key_exists('phone_code', $command->data) ? $command->data['phone_code'] : $country->phoneCode(),
            array_key_exists('is_active', $command->data) ? $command->data['is_active'] : $country->isActive(),
            $country->createdAt(),
            new \DateTimeImmutable()
        );

        $this->repository->save($updatedCountry);
        $this->cache->flushGroup('geography');
    }
}
