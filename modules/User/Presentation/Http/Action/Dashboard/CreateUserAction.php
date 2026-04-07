<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Geography\Domain\ValueObject\NationalityId;
use Modules\IAM\Application\Command\RegisterUser\RegisterUserCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterUserHandler;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\ValueObject\Phone;
use Modules\User\Presentation\Http\Request\Dashboard\CreateUserRequest;

final readonly class CreateUserAction
{
    public function __construct(
        private RegisterUserHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(CreateUserRequest $request): JsonResponse
    {
        $command = new RegisterUserCommand(
            name: (string) $request->input('name'),
            email: (string) $request->input('email'),
            phone: new Phone((string) $request->input('phone')),
            password: (string) $request->input('password'),
            fullName: (string) $request->input('full_name'),
            identityNumber: (string) $request->input('identity_number'),
            nationalityId: NationalityId::fromString((string) $request->input('nationality_id')),
            birthDate: (string) $request->input('birth_date'),
            gender: (string) $request->input('gender'),
            height: $request->input('height') ? (float) $request->input('height') : null,
            weight: $request->input('weight') ? (float) $request->input('weight') : null,
            accountOwner: (string) $request->input('account_owner'),
            bankName: (string) $request->input('bank_name'),
            iban: (string) $request->input('iban'),
            contactName: (string) $request->input('contact_name'),
            contactPhone: (string) $request->input('contact_phone'),
            contactRelation: (string) $request->input('contact_relation'),
        );

        $this->handler->handle($command);

        return $this->responder->created(messageKey: 'user.created');
    }
}
