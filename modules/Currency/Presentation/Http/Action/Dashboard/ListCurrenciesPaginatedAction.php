<?php
// modules/Currency/Presentation/Http/Action/Crm/ListCurrenciesPaginatedAction.php
declare(strict_types=1);

namespace Modules\Currency\Presentation\Http\Action\Dashboard;

use Modules\Currency\Domain\Repository\CurrencyRepositoryInterface;
use Modules\Currency\Presentation\Http\Presenter\CurrencyPresenter;
use Modules\Currency\Presentation\Http\Request\Dashboard\CurrencyFilterRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Http\JsonResponse;

final readonly class ListCurrenciesPaginatedAction
{
    public function __construct(
        private CurrencyRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(CurrencyFilterRequest $request): JsonResponse
    {
        $criteria = $request->toFilterCriteria();
        $perPage = $request->getPerPage();

        $paginator = $this->repository->paginate($criteria, $perPage);

        return $this->responder->paginated(
            items: $paginator->items(),
            total: $paginator->total(),
            pagination: $request->toPaginationCriteria(),
            presenter: fn($currency) => CurrencyPresenter::fromDomain($currency)
        );
    }
}
