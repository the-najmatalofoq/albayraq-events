<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\DeleteCountry;

use Modules\Geography\Domain\ValueObject\CountryId;
use Modules\Geography\Domain\Repository\CountryRepositoryInterface;
use Modules\Shared\Infrastructure\Services\CacheService;

final class DeleteCountryHandler
{
    public function __construct(
        private readonly CountryRepositoryInterface $repository,
        private readonly CacheService $cache
    ) {}

    public function handle(DeleteCountryCommand $command): void
    {
        $this->repository->delete(new CountryId($command->id));
        $this->cache->flushGroup('geography');
    }
}
