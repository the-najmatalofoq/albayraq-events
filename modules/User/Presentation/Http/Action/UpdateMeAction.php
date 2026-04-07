<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Application\Command\UpdateMe\UpdateMeCommand;
use Modules\User\Application\Command\UpdateMe\UpdateMeHandler;
use Modules\User\Presentation\Http\Request\UpdateMeRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class UpdateMeAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private UpdateMeHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(UpdateMeRequest $request): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        $this->handler->handle(new UpdateMeCommand(
            userId: $userId->value,
            name: (string) $request->validated('name'),
            phone: (string) $request->validated('phone')
        ));

        return $this->responder->success(
            messageKey: 'user.profile_updated'
        );
    }
}
