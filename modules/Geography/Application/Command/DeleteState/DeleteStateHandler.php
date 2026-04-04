<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\DeleteState;

use Modules\Geography\Domain\ValueObject\StateId;
use Modules\Geography\Domain\Repository\StateRepositoryInterface;
use Modules\Shared\Infrastructure\Services\CacheService;

final class DeleteStateHandler
{
    public function __construct(
        private readonly StateRepositoryInterface $repository,
        private readonly CacheService $cache
    ) {}

    public function handle(DeleteStateCommand $command): void
    {
        $this->repository->delete(new StateId($command->id));
        $this->cache->flushGroup('geography');
    }
}
