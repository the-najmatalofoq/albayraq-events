<?php
// modules/Wage/Presentation/Http/Action/DeleteWageAction.php
declare(strict_types=1);

namespace Modules\Wage\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Wage\Application\Command\DeleteWage\DeleteWageHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class DeleteWageAction
{
    public function __construct(
        private DeleteWageHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle($id);

        return $this->responder->noContent();
    }
}
