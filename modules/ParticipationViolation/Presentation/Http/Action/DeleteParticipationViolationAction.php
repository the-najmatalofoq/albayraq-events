<?php
// modules/ParticipationViolation/Presentation/Http/Action/DeleteParticipationViolationAction.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\ParticipationViolation\Application\Command\DeleteParticipationViolation\DeleteParticipationViolationCommand;
use Modules\ParticipationViolation\Application\Command\DeleteParticipationViolation\DeleteParticipationViolationHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class DeleteParticipationViolationAction
{
    public function __construct(
        private DeleteParticipationViolationHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new DeleteParticipationViolationCommand($id));

        return $this->responder->success(
            messageKey: 'messages.deleted'
        );
    }
}
