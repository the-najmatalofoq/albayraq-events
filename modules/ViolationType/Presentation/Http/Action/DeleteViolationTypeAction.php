<?php
// modules/ViolationType/Presentation/Http/Action/DeleteViolationTypeAction.php
declare(strict_types=1);

namespace Modules\ViolationType\Presentation\Http\Action;

use Modules\ViolationType\Application\Command\DeleteViolationType\DeleteViolationTypeCommand;
use Modules\ViolationType\Application\Command\DeleteViolationType\DeleteViolationTypeHandler;
use Modules\Shared\Presentation\Http\JsonResponder;
use Illuminate\Http\JsonResponse;

final readonly class DeleteViolationTypeAction
{
    public function __construct(
        private DeleteViolationTypeHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new DeleteViolationTypeCommand($id));

        return $this->responder->noContent();
    }
}
