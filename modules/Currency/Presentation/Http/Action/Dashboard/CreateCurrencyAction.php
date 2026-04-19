<?php
// modules/Currency/Presentation/Http/Action/CreateCurrencyAction.php
declare(strict_types=1);

namespace Modules\Currency\Presentation\Http\Action\Dashboard;

use Modules\Currency\Application\Command\CreateCurrency\CreateCurrencyCommand;
use Modules\Currency\Application\Command\CreateCurrency\CreateCurrencyHandler;
use Modules\Currency\Presentation\Http\Request\Dashboard\CreateCurrencyRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Http\JsonResponse;
use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class CreateCurrencyAction
{
    public function __construct(
        private CreateCurrencyHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(CreateCurrencyRequest $request): JsonResponse
    {
        $id = $this->handler->handle(new CreateCurrencyCommand(
            name: TranslatableText::fromMixed($request->validated('name')),
            code: $request->validated('code'),
            symbol: $request->validated('symbol'),
            isActive: $request->validated('is_active', true)
        ));

        return $this->responder->created(['id' => $id->value]);
    }
}
