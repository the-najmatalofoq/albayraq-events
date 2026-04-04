<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Geography\Domain\Repository\CountryRepositoryInterface;
use Modules\Geography\Presentation\Http\Presenter\CountryPresenter;
use Modules\Shared\Infrastructure\Services\CacheService;
use Modules\Shared\Presentation\Http\JsonResponder;
use App\Http\Controllers\Controller;

final class ListCountriesAction extends Controller
{
    public function __construct(
        private readonly CountryRepositoryInterface $repository,
        private readonly CountryPresenter $presenter,
        private readonly CacheService $cache,
        private readonly JsonResponder $responder
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $countries = $this->cache->remember('geography', 'countries:all', function () {
            $domainCountries = $this->repository->findAllActive();
            return array_map(fn($country) => $this->presenter->present($country), $domainCountries);
        });

        return $this->responder->success(data: $countries);
    }
}
