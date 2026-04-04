<?php
declare(strict_types=1);

namespace Modules\Geography\Application\Command\UpdateState;

use Modules\Geography\Domain\State;
use Modules\Geography\Domain\ValueObject\CountryId;
use Modules\Geography\Domain\ValueObject\StateId;
use Modules\Geography\Domain\Repository\StateRepositoryInterface;
use Modules\Shared\Infrastructure\Services\CacheService;

final class UpdateStateHandler
{
    public function __construct(
        private readonly StateRepositoryInterface $repository,
        private readonly CacheService $cache
    ) {
    }

    public function handle(UpdateStateCommand $command): void
    {
        $id = new StateId($command->id);
        $state = $this->repository->findById($id);

        if (!$state) {
            throw new \RuntimeException("State not found");
        }

        $updatedState = new State(
            $id,
            isset($command->data['country_id']) ? new CountryId($command->data['country_id']) : $state->countryId(),
            $command->data['name'] ?? $state->names(),
            $state->createdAt(),
            new \DateTimeImmutable()
        );

        $this->repository->save($updatedState);
        $this->cache->flushGroup('geography');
    }
}
