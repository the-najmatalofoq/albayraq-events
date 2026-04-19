<?php

declare(strict_types=1);

namespace Modules\Notification\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Notification\Application\Command\RevokeDeviceToken\RevokeDeviceTokenCommand;
use Modules\Notification\Application\Command\RevokeDeviceToken\RevokeDeviceTokenHandler;
use Illuminate\Http\Request;

final readonly class RevokeDeviceTokenAction
{
    public function __construct(
        private RevokeDeviceTokenHandler $handler,
    ) {}

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $command = new RevokeDeviceTokenCommand(
            token: $id // Assuming the ID passed in route is the token string, or we should look it up.
            // Based on the handler, it takes a 'token' string.
        );

        $this->handler->handle($command);

        return response()->json([
            'message' => 'Device token revoked successfully',
        ]);
    }
}
