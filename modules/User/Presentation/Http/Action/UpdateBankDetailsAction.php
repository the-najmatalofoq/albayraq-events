<?php
// modules/User/Presentation/Http/Action/UpdateBankDetailsAction.php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManagerInterface;
use Modules\User\Application\Command\UpdateBankDetails\UpdateBankDetailsCommand;
use Modules\User\Application\Command\UpdateBankDetails\UpdateBankDetailsHandler;
use Illuminate\Http\Request;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Shared\Infrastructure\Validation\Rules\IbanRule;

final readonly class UpdateBankDetailsAction
{
    public function __construct(
        private TokenManagerInterface $tokenManager,
        private UpdateBankDetailsHandler $handler,
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
            'account_owner' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'iban' => ['required', 'string', new IbanRule()],
        ]);

        $command = new UpdateBankDetailsCommand(
            userId: $userId->value,
            accountOwner: (string) $request->input('account_owner'),
            bankName: (string) $request->input('bank_name'),
            iban: (string) $request->input('iban'),
        );

        $this->handler->handle($command);

        return $this->responder->success(
            messageKey: 'profile.bank_updated'
        );
    }
}
