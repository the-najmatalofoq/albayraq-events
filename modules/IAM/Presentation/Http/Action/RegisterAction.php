<?php
// modules/IAM/Presentation/Http/Action/RegisterAction.php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Application\Command\RegisterUser\RegisterUserCommand;
use Modules\IAM\Presentation\Http\Request\RegisterRequest;
use Modules\Shared\Application\Command\CommandBusInterface;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\ValueObject\UserId;

// fix: fix the return type and also we must use the handler ? or what? and what about the CommandBusInterface?

final class RegisterAction
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly JsonResponder $responder,
    ) {
    }

    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $userId = UserId::next();

        $command = new RegisterUserCommand(
            userId: $userId->value(),
            name: $request->validated('name'),
            email: $request->validated('email'),
            phone: $request->validated('phone'),
            password: $request->validated('password'),
            nationalId: $request->validated('national_id'),
            birthDate: $request->validated('birth_date'),
            nationality: $request->validated('nationality'),
            gender: $request->validated('gender'),
            height: $request->validated('height') ? (float) $request->validated('height') : null,
            weight: $request->validated('weight') ? (float) $request->validated('weight') : null,
            accountOwner: $request->validated('account_owner'),
            bankName: $request->validated('bank_name'),
            iban: $request->validated('iban'),
            contactPhones: $request->validated('contact_phones', []),
            avatar: $request->file('avatar'),
            idCopy: $request->file('id_copy'),
        );

        $this->commandBus->dispatch($command);

        return $this->responder->created(
            data: ['id' => $userId->value()],
            messageKey: 'auth.registered'
        );
    }
}
