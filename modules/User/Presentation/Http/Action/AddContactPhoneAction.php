<?php
// modules/User/Presentation/Http/Action/AddContactPhoneAction.php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManagerInterface;
use Modules\User\Application\Command\AddContactPhone\AddContactPhoneCommand;
use Modules\User\Application\Command\AddContactPhone\AddContactPhoneHandler;
use Illuminate\Http\Request;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Shared\Infrastructure\Validation\Rules\SaudiPhoneRule;

final readonly class AddContactPhoneAction
{
    public function __construct(
        private TokenManagerInterface $tokenManager,
        private AddContactPhoneHandler $handler,
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
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', new SaudiPhoneRule()],
            'relation' => ['nullable', 'string', 'max:50'],
        ]);

        $command = new AddContactPhoneCommand(
            userId: $userId->value,
            name: (string) $request->input('name'),
            phone: (string) $request->input('phone'),
            relation: (string) $request->input('relation', 'emergency'),
        );

        $this->handler->handle($command);

        return $this->responder->created(
            messageKey: 'profile.phone_added'
        );
    }
}
