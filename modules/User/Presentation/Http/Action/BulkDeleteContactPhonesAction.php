<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Application\Command\BulkDeleteContactPhones\BulkDeleteContactPhonesCommand;
use Modules\User\Application\Command\BulkDeleteContactPhones\BulkDeleteContactPhonesHandler;
use Modules\User\Presentation\Http\Request\BulkDeleteContactPhonesRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class BulkDeleteContactPhonesAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private BulkDeleteContactPhonesHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(BulkDeleteContactPhonesRequest $request): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        $this->handler->handle(new BulkDeleteContactPhonesCommand(
            userId: $userId->value,
            contactPhoneIds: (array) $request->validated('ids')
        ));

        return $this->responder->success(
            messageKey: 'user.bulk_contact_phones_deleted'
        );
    }
}
