<?php
// modules/IAM/Presentation/Http/Action/RefreshTokenAction.php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManagerInterface;
use Modules\Shared\Presentation\Http\JsonResponder;

final class RefreshTokenAction
{
    public function __construct(
        private readonly TokenManagerInterface $tokenManager,
        private readonly JsonResponder $responder,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $tokenData = $this->tokenManager->refresh();

        return $this->responder->success(
            data: $tokenData,
            messageKey: 'auth.token_refreshed'
        );
    }
}
