<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Geography\Domain\Repository\CityRepositoryInterface;
use Modules\Geography\Presentation\Http\Presenter\CityPresenter;
use Modules\Geography\Domain\ValueObject\CountryId;
use Modules\Geography\Domain\ValueObject\StateId;
use Illuminate\Support\Facades\Cache;

final class ListCitiesAction
{
    public function __construct(
        private readonly CityRepositoryInterface $repository
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $countryId = $request->route('countryId');
        $stateId = $request->route('stateId');
        
        $cacheKey = 'geography_cities_' . ($countryId ? 'country_'.$countryId : 'state_'.$stateId);

        $cities = Cache::remember($cacheKey, 86400, function () use ($countryId, $stateId) {
            if ($countryId) {
                $entities = $this->repository->findByCountryId(new CountryId($countryId));
            } elseif ($stateId) {
                $entities = $this->repository->findByStateId(new StateId($stateId));
            } else {
                $entities = [];
            }
            return array_map(fn($city) => CityPresenter::present($city), $entities);
        });

        return response()->json([
            'data' => $cities,
        ]);
    }
}
