<?php
// modules/Currency/Presentation/Http/Action/DeleteCurrencyAction.php
declare(strict_types=1);

namespace Modules\Currency\Presentation\Http\Action\Dashboard;

use Modules\Currency\Application\Command\DeleteCurrency\DeleteCurrencyCommand;
use Modules\Currency\Application\Command\DeleteCurrency\DeleteCurrencyHandler;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Http\JsonResponse;

final readonly class DeleteCurrencyAction
{
    public function __construct(
        private DeleteCurrencyHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new DeleteCurrencyCommand($id));

        return $this->responder->noContent();
    }
}
