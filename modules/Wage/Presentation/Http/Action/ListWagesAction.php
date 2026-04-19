<?php
// modules/Wage/Presentation/Http/Action/ListWagesAction.php
declare(strict_types=1);

namespace Modules\Wage\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Wage\Domain\Repository\WageRepositoryInterface;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Wage\Presentation\Http\Presenter\WagePresenter;

final readonly class ListWagesAction
{
    public function __construct(
        private WageRepositoryInterface $repository,
        private JsonResponder $responder,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $wageableId = $request->query('wageable_id');
        $wageableType = $request->query('wageable_type');

        if ($wageableId && $wageableType) {
            $wages = $this->repository->findByWageable((string) $wageableId, (string) $wageableType);
        } else {
            // Placeholder: in a real app, we might support paginated global list
            $wages = [];
        }

        return $this->responder->success(
            data: array_map(fn($w) => WagePresenter::fromDomain($w), $wages)
        );
    }
}
