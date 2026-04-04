<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Geography\Domain\Repository\StateRepositoryInterface;
use Modules\Geography\Presentation\Http\Presenter\StatePresenter;
use Modules\Geography\Domain\ValueObject\CountryId;
use Modules\Geography\Presentation\Http\Request\ListStatesRequest;
use Modules\Shared\Infrastructure\Services\CacheService;
use Modules\Shared\Presentation\Http\JsonResponder;
use App\Http\Controllers\Controller;

final class ListStatesAction extends Controller
{
    public function __construct(
        private readonly StateRepositoryInterface $repository,
        private readonly StatePresenter $presenter,
        private readonly CacheService $cache,
        private readonly JsonResponder $responder
    ) {
    }

    public function __invoke(ListStatesRequest $request, string $countryId): JsonResponse
    {
        $states = $this->cache->remember('geography', "states:country:{$countryId}", function () use ($countryId) {
            $domainStates = $this->repository->findByCountryId(new CountryId($countryId));
            return array_map(fn($state) => $this->presenter->present($state), $domainStates);
        });

        return $this->responder->success(data: $states);
    }
}
