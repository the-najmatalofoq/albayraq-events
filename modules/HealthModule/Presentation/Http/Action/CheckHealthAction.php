<?php

declare(strict_types=1);

namespace Modules\HealthModule\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\HealthModule\Application\Health\CheckApplicationHealthHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CheckHealthAction
{
    public function __construct(
        private CheckApplicationHealthHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $result = $this->handler->handle();

        if (!$result['healthy']) {
            return $this->responder->error(
                errorCode: 'SERVICE_UNHEALTHY',
                status: 503,
                messageKey: 'health.unhealthy'
            );
        }

        return $this->responder->success(
            data: $result,
            status: 200,
            messageKey: 'health.healthy'
        );
    }
}
