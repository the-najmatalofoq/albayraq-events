<?php
// modules/ReportType/Presentation/Http/Action/UpdateReportTypeAction.php
declare(strict_types=1);

namespace Modules\ReportType\Presentation\Http\Action;

use Modules\ReportType\Application\Command\UpdateReportType\UpdateReportTypeCommand;
use Modules\ReportType\Application\Command\UpdateReportType\UpdateReportTypeHandler;
use Modules\ReportType\Presentation\Http\Request\UpdateReportTypeRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Http\JsonResponse;
use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class UpdateReportTypeAction
{
    public function __construct(
        private UpdateReportTypeHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $id, UpdateReportTypeRequest $request): JsonResponse
    {
        $this->handler->handle(new UpdateReportTypeCommand(
            id: $id,
            name: $request->validated('name') ? TranslatableText::fromMixed($request->validated('name')) : null,
            slug: $request->validated('slug'),
            isActive: $request->has('is_active') ? (bool) $request->validated('is_active') : null
        ));

        return $this->responder->success(
            messageKey: 'messages.updated',
            data: [
                'id' => $id,
            ]
        );
    }
}
