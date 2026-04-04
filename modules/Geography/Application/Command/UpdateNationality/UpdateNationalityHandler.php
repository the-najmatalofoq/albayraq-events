<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\UpdateNationality;

use Modules\Geography\Domain\Nationality;
use Modules\Geography\Domain\ValueObject\CountryId;
use Modules\Geography\Domain\ValueObject\NationalityId;
use Modules\Geography\Domain\Repository\NationalityRepositoryInterface;
use Modules\Shared\Infrastructure\Services\CacheService;

final class UpdateNationalityHandler
{
    public function __construct(
        private readonly NationalityRepositoryInterface $repository,
        private readonly CacheService $cache
    ) {}

    public function handle(UpdateNationalityCommand $command): void
    {
        $id = new NationalityId($command->id);
        $nationality = $this->repository->findById($id);

        if (!$nationality) {
            throw new \RuntimeException("Nationality not found");
        }

        $updatedNationality = new Nationality(
            $id,
            isset($command->data['country_id']) ? new CountryId($command->data['country_id']) : $nationality->countryId(),
            $command->data['name'] ?? $nationality->names(),
            array_key_exists('is_active', $command->data) ? $command->data['is_active'] : $nationality->isActive(),
            $nationality->createdAt(),
            new \DateTimeImmutable()
        );

        $this->repository->save($updatedNationality);
        $this->cache->flushGroup('geography');
    }
}
