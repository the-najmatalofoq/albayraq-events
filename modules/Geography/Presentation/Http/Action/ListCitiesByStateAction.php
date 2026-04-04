<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Geography\Domain\Repository\CityRepositoryInterface;
use Modules\Geography\Presentation\Http\Presenter\CityPresenter;
use Modules\Geography\Domain\ValueObject\StateId;
use Modules\Geography\Presentation\Http\Request\ListCitiesByStateRequest;
use Modules\Shared\Infrastructure\Services\CacheService;
use Modules\Shared\Presentation\Http\JsonResponder;

final class ListCitiesByStateAction
{
    public function __construct(
        private readonly CityRepositoryInterface $repository,
        private readonly CityPresenter $presenter,
        private readonly CacheService $cache,
        private readonly JsonResponder $responder
    ) {
    }

    public function __invoke(ListCitiesByStateRequest $request, string $stateId): JsonResponse
    {
        $cities = $this->cache->remember('geography', "cities:state:{$stateId}", function () use ($stateId) {
            $domainCities = $this->repository->findByStateId(new StateId($stateId));
            return array_map(fn($city) => $this->presenter->present($city), $domainCities);
        });

        return $this->responder->success(data: $cities);
    }
}
