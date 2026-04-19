<?php

declare(strict_types=1);

namespace Modules\Notification\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Notification\Application\Command\RegisterDeviceToken\RegisterDeviceTokenCommand;
use Modules\Notification\Application\Command\RegisterDeviceToken\RegisterDeviceTokenHandler;
use Modules\Notification\Presentation\Http\Request\RegisterDeviceTokenRequest;
use Modules\User\Domain\ValueObject\UserId;

final class RegisterDeviceTokenAction
{
    public function __construct(
        private readonly RegisterDeviceTokenHandler $handler,
    ) {}

    public function __invoke(RegisterDeviceTokenRequest $request): JsonResponse
    {
        $command = new RegisterDeviceTokenCommand(
            userId: new UserId($request->user()->id),
            deviceId: $request->input('device_id'),
            token: $request->input('token'),
            platform: $request->input('platform'),
            deviceName: $request->input('device_name'),
        );

        $tokenId = $this->handler->handle($command);

        return response()->json([
            'id' => $tokenId->value,
            'message' => 'Device registered successfully',
        ], 201);
    }
}
