<?php
// modules/ReportType/Presentation/Http/Action/CreateReportTypeAction.php
declare(strict_types=1);

namespace Modules\ReportType\Presentation\Http\Action;

use Modules\ReportType\Application\Command\CreateReportType\CreateReportTypeCommand;
use Modules\ReportType\Application\Command\CreateReportType\CreateReportTypeHandler;
use Modules\ReportType\Presentation\Http\Request\CreateReportTypeRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Http\JsonResponse;
use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class CreateReportTypeAction
{
    public function __construct(
        private CreateReportTypeHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(CreateReportTypeRequest $request): JsonResponse
    {
        $id = $this->handler->handle(new CreateReportTypeCommand(
            name: TranslatableText::fromMixed($request->validated('name')),
            slug: $request->validated('slug'),
            isActive: $request->validated('is_active', true)
        ));

        return $this->responder->created([
            'id' => $id->value
        ]);
    }
}
