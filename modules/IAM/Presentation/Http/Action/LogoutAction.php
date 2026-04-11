<?php
// modules/IAM/Presentation/Http/Action/LogoutAction.php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class LogoutAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $this->tokenManager->invalidate();

        return $this->responder->success(
            data: null,
            messageKey: __('messages.auth.logged_out')
        );
    }
}