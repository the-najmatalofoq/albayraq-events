<?php
// modules/DeductionType/Presentation/Http/Action/Dashboard/UpdateDeductionTypeAction.php
declare(strict_types=1);

namespace Modules\DeductionType\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\DeductionType\Application\Command\UpdateDeductionType\UpdateDeductionTypeCommand;
use Modules\DeductionType\Application\Command\UpdateDeductionType\UpdateDeductionTypeHandler;
use Modules\DeductionType\Presentation\Http\Request\UpdateDeductionTypeRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class UpdateDeductionTypeAction
{
    public function __construct(
        private UpdateDeductionTypeHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(UpdateDeductionTypeRequest $request, string $id): JsonResponse
    {
        $this->handler->handle(new UpdateDeductionTypeCommand(
            id: $id,
            name: TranslatableText::fromArray($request->validated('name', [])),
            slug: $request->validated('slug'),
            isActive: $request->validated('is_active')
        ));

        return $this->responder->success(
            messageKey: 'messages.updated'
        );
    }
}
