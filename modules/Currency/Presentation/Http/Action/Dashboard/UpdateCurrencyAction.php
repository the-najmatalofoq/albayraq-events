<?php
// modules/Currency/Presentation/Http/Action/UpdateCurrencyAction.php
declare(strict_types=1);

namespace Modules\Currency\Presentation\Http\Action\Dashboard;

use Modules\Currency\Application\Command\UpdateCurrency\UpdateCurrencyCommand;
use Modules\Currency\Application\Command\UpdateCurrency\UpdateCurrencyHandler;
use Modules\Currency\Presentation\Http\Request\Dashboard\UpdateCurrencyRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Http\JsonResponse;
use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class UpdateCurrencyAction
{
    public function __construct(
        private UpdateCurrencyHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(UpdateCurrencyRequest $request, string $id): JsonResponse
    {
        $this->handler->handle(new UpdateCurrencyCommand(
            id: $id,
            name: TranslatableText::fromMixed($request->validated('name')),
            code: $request->validated('code'),
            symbol: $request->validated('symbol'),
            isActive: $request->validated('is_active', true)
        ));

        
        return $this->responder->success(
            data: ['id' => $id],
            status: 200,
            messageKey: 'messages.updated'
        );
    }
}
