<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\CreateState;

use Modules\Geography\Domain\Repository\StateRepositoryInterface;
use Modules\Geography\Domain\State;
use Modules\Geography\Domain\ValueObject\CountryId;
use Modules\Shared\Infrastructure\Services\CacheService;

final class CreateStateHandler
{
    public function __construct(
        private readonly StateRepositoryInterface $repository,
        private readonly CacheService $cache
    ) {}

    public function handle(CreateStateCommand $command): void
    {
        $state = new State(
            $this->repository->nextIdentity(),
            new CountryId($command->data['country_id']),
            $command->data['name'],
            new \DateTimeImmutable(),
            null
        );

        $this->repository->save($state);
        $this->cache->flushGroup('geography');
    }
}
