<?php
// modules/User/Presentation/Http/Action/UpdateAvatarAction.php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManagerInterface;
use Modules\User\Application\Command\UpdateUserAvatar\UpdateUserAvatarCommand;
use Modules\User\Application\Command\UpdateUserAvatar\UpdateUserAvatarHandler;
use Illuminate\Http\Request;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class UpdateAvatarAction
{
    public function __construct(
        private TokenManagerInterface $tokenManager,
        private UpdateUserAvatarHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if (!$userId) {
            return $this->responder->unauthorized();
        }

        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'],
        ]);

        $command = new UpdateUserAvatarCommand(
            userId: $userId->value,
            avatar: $request->file('avatar'),
        );

        $this->handler->handle($command);

        return $this->responder->success(
            messageKey: 'profile.avatar_updated'
        );
    }
}
