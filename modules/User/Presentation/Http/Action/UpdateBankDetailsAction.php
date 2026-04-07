<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Application\Command\UpdateBankDetails\UpdateBankDetailsCommand;
use Modules\User\Application\Command\UpdateBankDetails\UpdateBankDetailsHandler;
use Modules\User\Presentation\Http\Request\UpdateBankDetailsRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class UpdateBankDetailsAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private UpdateBankDetailsHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(UpdateBankDetailsRequest $request): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        $this->handler->handle(new UpdateBankDetailsCommand(
            userId: $userId->value,
            accountOwner: (string) $request->validated('account_owner'),
            bankName: (string) $request->validated('bank_name'),
            iban: (string) $request->validated('iban')
        ));

        return $this->responder->success(
            messageKey: 'user.bank_details_updated'
        );
    }
}
