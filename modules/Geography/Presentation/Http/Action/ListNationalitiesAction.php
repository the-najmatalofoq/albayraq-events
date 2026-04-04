<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Geography\Domain\Repository\NationalityRepositoryInterface;
use Modules\Geography\Presentation\Http\Presenter\NationalityPresenter;
use Modules\Geography\Domain\ValueObject\CountryId;
use Illuminate\Support\Facades\Cache;

final class ListNationalitiesAction
{
    public function __construct(
        private readonly NationalityRepositoryInterface $repository
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $countryId = $request->query('country_id');
        $cacheKey = 'geography_nationalities_' . ($countryId ?? 'all');

        $nationalities = Cache::remember($cacheKey, 86400, function () use ($countryId) {
            // fix: Hint: Replace with ?:PHP(PHP7103)
            if ($countryId) {
                $entities = $this->repository->findActiveByCountryId(new CountryId($countryId));
            } else {
                $entities = $this->repository->findAllActive();
            }
            // fix: Hint: Convert to callable syntaxPHP(PHP7103)
            return array_map(fn($nat) => NationalityPresenter::present($nat), $entities);
        });

        return response()->json([
            'data' => $nationalities,
        ]);
    }
}
