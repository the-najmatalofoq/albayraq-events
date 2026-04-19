<?php
// modules/PenaltyType/Presentation/Http/Action/Dashboard/CreatePenaltyTypeAction.php
declare(strict_types=1);

namespace Modules\PenaltyType\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\PenaltyType\Application\Command\CreatePenaltyType\CreatePenaltyTypeCommand;
use Modules\PenaltyType\Application\Command\CreatePenaltyType\CreatePenaltyTypeHandler;
use Modules\PenaltyType\Presentation\Http\Request\CreatePenaltyTypeRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CreatePenaltyTypeAction
{
    public function __construct(
        private CreatePenaltyTypeHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(CreatePenaltyTypeRequest $request): JsonResponse
    {
        $id = $this->handler->handle(new CreatePenaltyTypeCommand(
            name: TranslatableText::fromArray($request->validated('name')),
            slug: $request->validated('slug'),
            isActive: $request->validated('is_active', true)
        ));

        return $this->responder->created(
            data: ['id' => $id->value],
            messageKey: 'messages.created'
        );
    }
}
