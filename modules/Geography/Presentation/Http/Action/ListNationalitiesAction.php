<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Geography\Domain\Repository\NationalityRepositoryInterface;
use Modules\Geography\Presentation\Http\Presenter\NationalityPresenter;
use Modules\Geography\Domain\ValueObject\CountryId;
use Modules\Geography\Presentation\Http\Request\ListNationalitiesRequest;
use Modules\Shared\Infrastructure\Services\CacheService;
use Modules\Shared\Presentation\Http\JsonResponder;

final class ListNationalitiesAction
{
    public function __construct(
        private readonly NationalityRepositoryInterface $repository,
        private readonly NationalityPresenter $presenter,
        private readonly CacheService $cache,
        private readonly JsonResponder $responder
    ) {
    }

    public function __invoke(ListNationalitiesRequest $request): JsonResponse
    {
        $countryId = $request->validated('country_id');

        $cacheKey = $countryId ? "nationalities:country:{$countryId}" : 'nationalities:all';

        $nationalities = $this->cache->remember('geography', $cacheKey, function () use ($countryId) {
            $domainNationalities = $countryId
                ? $this->repository->findActiveByCountryId(new CountryId($countryId))
                : $this->repository->findAllActive();

            return array_map(fn($nat) => $this->presenter->present($nat), $domainNationalities);
        });

        return $this->responder->success(data: $nationalities);
    }
}
