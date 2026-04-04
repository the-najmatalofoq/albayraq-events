<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Geography\Domain\Repository\CityRepositoryInterface;
use Modules\Geography\Presentation\Http\Presenter\CityPresenter;
use Modules\Geography\Domain\ValueObject\CountryId;
use Modules\Geography\Presentation\Http\Request\ListCitiesByCountryRequest;
use Modules\Shared\Infrastructure\Services\CacheService;
use Modules\Shared\Presentation\Http\JsonResponder;

final class ListCitiesByCountryAction
{
    public function __construct(
        private readonly CityRepositoryInterface $repository,
        private readonly CityPresenter $presenter,
        private readonly CacheService $cache,
        private readonly JsonResponder $responder
    ) {
    }

    public function __invoke(ListCitiesByCountryRequest $request, string $countryId): JsonResponse
    {
        $cities = $this->cache->remember('geography', "cities:country:{$countryId}", function () use ($countryId) {
            $domainCities = $this->repository->findByCountryId(new CountryId($countryId));
            return array_map([$this->presenter, 'present'], $domainCities);
        });

        return $this->responder->success(data: $cities);
    }
}
