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

// we have many issues in this file:
// fix: Use of unknown class: 'Modules\IAM\Domain\Service\TokenManagerInterface'PHP(PHP0413)
// fix: Argument '3' passed to __construct() is expected to be of type Modules\User\Domain\ValueObject\Phone, string givenPHP(PHP0406)
// fix: make AddContactPhone FormRequest to move the logic from the validations
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
