<?php
// modules/IAM/Presentation/Http/Action/LogoutAction.php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Notification\Application\Command\RevokeDeviceToken\RevokeDeviceTokenCommand;
use Modules\Notification\Application\Command\RevokeDeviceToken\RevokeDeviceTokenHandler;
use Illuminate\Http\Request;

final readonly class LogoutAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private JsonResponder $responder,
        private RevokeDeviceTokenHandler $revokeHandler,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $this->tokenManager->invalidate();

        if ($token = $request->input('fcm_token')) {
            $this->revokeHandler->handle(new RevokeDeviceTokenCommand($token));
        }

        return $this->responder->success(
            data: null,
            messageKey: __('messages.auth.logged_out')
        );
    }
}
