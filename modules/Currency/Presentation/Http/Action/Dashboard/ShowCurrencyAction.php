<?php
// modules/Currency/Presentation/Http/Action/ShowCurrencyAction.php
declare(strict_types=1);

namespace Modules\Currency\Presentation\Http\Action\Dashboard;

use Modules\Currency\Domain\ValueObject\CurrencyId;
use Modules\Currency\Domain\Repository\CurrencyRepositoryInterface;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Http\JsonResponse;

final readonly class ShowCurrencyAction
{
    public function __construct(
        private CurrencyRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $currency = $this->repository->findById(CurrencyId::fromString($id));

        if (!$currency) {
            return $this->responder->notFound('Currency not found');
        }

        return $this->responder->success(
            \Modules\Currency\Presentation\Http\Presenter\CurrencyPresenter::fromDomain($currency)
        );
    }
}
