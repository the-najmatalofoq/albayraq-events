<?php
// modules/Wage/Presentation/Http/Action/ShowWageAction.php
declare(strict_types=1);

namespace Modules\Wage\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Wage\Domain\ValueObject\WageId;
use Modules\Wage\Domain\Repository\WageRepositoryInterface;
use Modules\Wage\Presentation\Http\Presenter\WagePresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ShowWageAction
{
    public function __construct(
        private WageRepositoryInterface $repository,
        private JsonResponder $responder,
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $wage = $this->repository->findById(WageId::fromString($id));

        if ($wage === null) {
            return $this->responder->notFound(messageKey: 'messages.not_found');
        }

        return $this->responder->success(
            data: WagePresenter::fromDomain($wage)
        );
    }
}
