<?php
// modules/DeductionType/Presentation/Http/Action/Dashboard/CreateDeductionTypeAction.php
declare(strict_types=1);

namespace Modules\DeductionType\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\DeductionType\Application\Command\CreateDeductionType\CreateDeductionTypeCommand;
use Modules\DeductionType\Application\Command\CreateDeductionType\CreateDeductionTypeHandler;
use Modules\DeductionType\Presentation\Http\Request\CreateDeductionTypeRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CreateDeductionTypeAction
{
    public function __construct(
        private CreateDeductionTypeHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(CreateDeductionTypeRequest $request): JsonResponse
    {
        $id = $this->handler->handle(new CreateDeductionTypeCommand(
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
