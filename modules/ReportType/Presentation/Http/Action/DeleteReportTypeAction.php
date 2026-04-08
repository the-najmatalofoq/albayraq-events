<?php
// modules/ReportType/Presentation/Http/Action/DeleteReportTypeAction.php
declare(strict_types=1);

namespace Modules\ReportType\Presentation\Http\Action;

use Modules\ReportType\Application\Command\DeleteReportType\DeleteReportTypeCommand;
use Modules\ReportType\Application\Command\DeleteReportType\DeleteReportTypeHandler;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Http\JsonResponse;

final readonly class DeleteReportTypeAction
{
    public function __construct(
        private DeleteReportTypeHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new DeleteReportTypeCommand($id));

        return $this->responder->noContent();
    }
}
