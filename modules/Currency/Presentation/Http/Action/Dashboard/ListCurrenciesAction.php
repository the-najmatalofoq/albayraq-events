<?php
// modules/Currency/Presentation/Http/Action/Crm/ListCurrenciesAction.php
declare(strict_types=1);

namespace Modules\Currency\Presentation\Http\Action\Dashboard;

use Modules\Currency\Domain\Repository\CurrencyRepositoryInterface;
use Modules\Currency\Presentation\Http\Presenter\CurrencyPresenter;
use Modules\Currency\Presentation\Http\Request\Dashboard\CurrencyFilterRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Http\JsonResponse;

final readonly class ListCurrenciesAction
{
    public function __construct(
        private CurrencyRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(CurrencyFilterRequest $request): JsonResponse
    {
        $criteria = $request->toFilterCriteria();
        $currencies = $this->repository->all($criteria);

        return $this->responder->success(
            $currencies->map(fn($currency) => CurrencyPresenter::fromDomain($currency))->toArray()
        );
    }
}
