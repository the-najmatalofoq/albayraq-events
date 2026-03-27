<?php
// modules/ReportType/Presentation/Http/Action/ListReportTypesAction.php
declare(strict_types=1);

namespace Modules\ReportType\Presentation\Http\Action;

use Modules\ReportType\Domain\Repository\ReportTypeRepositoryInterface;
use Modules\ReportType\Presentation\Http\Presenter\ReportTypePresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListReportTypesAction
{
    public function __construct(
        private ReportTypeRepositoryInterface $repository,
        private JsonResponder $responder
    ) {
    }

    public function __invoke(): mixed
    {
        $types = $this->repository->listAll();

        return $this->responder->success(
            data: array_map(fn($type) => ReportTypePresenter::fromDomain($type), $types)
        );
    }
}
