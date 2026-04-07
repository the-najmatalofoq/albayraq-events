<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Application\Command\UpdateContactPhone\UpdateContactPhoneCommand;
use Modules\User\Application\Command\UpdateContactPhone\UpdateContactPhoneHandler;
use Modules\User\Presentation\Http\Request\UpdateContactPhoneRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class UpdateContactPhoneAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private UpdateContactPhoneHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(UpdateContactPhoneRequest $request, string $id): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        $this->handler->handle(new UpdateContactPhoneCommand(
            userId: $userId->value,
            contactPhoneId: $id,
            name: (string) $request->validated('name'),
            phone: (string) $request->validated('phone'),
            relation: (string) $request->validated('relation')
        ));

        return $this->responder->success(
            messageKey: 'user.contact_phone_updated'
        );
    }
}
