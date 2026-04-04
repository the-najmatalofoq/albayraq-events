<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\DeleteNationality;

use Modules\Geography\Domain\ValueObject\NationalityId;
use Modules\Geography\Domain\Repository\NationalityRepositoryInterface;
use Modules\Shared\Infrastructure\Services\CacheService;

final class DeleteNationalityHandler
{
    public function __construct(
        private readonly NationalityRepositoryInterface $repository,
        private readonly CacheService $cache
    ) {}

    public function handle(DeleteNationalityCommand $command): void
    {
        $this->repository->delete(new NationalityId($command->id));
        $this->cache->flushGroup('geography');
    }
}
