<?php
// modules/PenaltyType/Presentation/Http/Action/Dashboard/UpdatePenaltyTypeAction.php
declare(strict_types=1);

namespace Modules\PenaltyType\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\PenaltyType\Application\Command\UpdatePenaltyType\UpdatePenaltyTypeCommand;
use Modules\PenaltyType\Application\Command\UpdatePenaltyType\UpdatePenaltyTypeHandler;
use Modules\PenaltyType\Presentation\Http\Request\UpdatePenaltyTypeRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class UpdatePenaltyTypeAction
{
    public function __construct(
        private UpdatePenaltyTypeHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(UpdatePenaltyTypeRequest $request, string $id): JsonResponse
    {
        $this->handler->handle(new UpdatePenaltyTypeCommand(
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
