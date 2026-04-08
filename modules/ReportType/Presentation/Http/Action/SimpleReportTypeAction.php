<?php
// modules/ReportType/Presentation/Http/Action/SimpleReportTypeAction.php
declare(strict_types=1);

namespace Modules\ReportType\Presentation\Http\Action;

use Modules\ReportType\Domain\Repository\ReportTypeRepositoryInterface;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Http\JsonResponse;
use Modules\ReportType\Presentation\Http\Presenter\SimpleReportTypePresenter;

final readonly class ListReportTypesAction
{
    public function __construct(
        private ReportTypeRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(): JsonResponse
    {
        $types = $this->repository->listAll();

        return $this->responder->success(
            data: array_map(
                fn($type) => SimpleReportTypePresenter::fromDomain($type),
                $types
            )
        );
    }
}
