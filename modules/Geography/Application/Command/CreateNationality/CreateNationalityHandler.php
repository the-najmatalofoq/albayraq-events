<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\CreateNationality;

use Modules\Geography\Domain\Nationality;
use Modules\Geography\Domain\Repository\NationalityRepositoryInterface;
use Modules\Geography\Domain\ValueObject\CountryId;
use Modules\Shared\Infrastructure\Services\CacheService;

final class CreateNationalityHandler
{
    public function __construct(
        private readonly NationalityRepositoryInterface $repository,
        private readonly CacheService $cache
    ) {
    }

    public function handle(CreateNationalityCommand $command): void
    {
        $nationality = new Nationality(
            $this->repository->nextIdentity(),
            new CountryId($command->data['country_id']),
            $command->data['name'],
            $command->data['is_active'] ?? true,
            new \DateTimeImmutable(),
            null
        );

        $this->repository->save($nationality);
        $this->cache->flushGroup('geography');
    }
}
