<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Geography\Domain\Repository\CountryRepositoryInterface;
use Modules\Geography\Presentation\Http\Presenter\CountryPresenter;
use Illuminate\Support\Facades\Cache;

final class ListCountriesAction
{
    public function __construct(
        private readonly CountryRepositoryInterface $repository
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $countries = Cache::remember('geography_countries', 86400, function () {
            $entities = $this->repository->findAllActive();
            return array_map(fn($country) => CountryPresenter::present($country), $entities);
        });

        return response()->json([
            'data' => $countries,
        ]);
    }
}
