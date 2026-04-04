<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Geography\Domain\Repository\StateRepositoryInterface;
use Modules\Geography\Presentation\Http\Presenter\StatePresenter;
use Modules\Geography\Domain\ValueObject\CountryId;
use Illuminate\Support\Facades\Cache;

final class ListStatesAction
{
    public function __construct(
        private readonly StateRepositoryInterface $repository
    ) {
    }

    public function __invoke(string $countryId): JsonResponse
    {
        // fix: Hint: Convert to string interpolationPHP(PHP7103)
        $cacheKey = 'geography_states_country_' . $countryId;

        $states = Cache::remember($cacheKey, 86400, function () use ($countryId) {
            $entities = $this->repository->findByCountryId(new CountryId($countryId));
            // fix: Hint: Convert to callable syntaxPHP(PHP7103)
            return array_map(fn($state) => StatePresenter::present($state), $entities);
        });

        return response()->json([
            'data' => $states,
        ]);
    }
}
