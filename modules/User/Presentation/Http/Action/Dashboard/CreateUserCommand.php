<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Geography\Domain\ValueObject\NationalityId;
use Modules\IAM\Application\Command\RegisterUser\RegisterUserCommand as RegisterCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterUserHandler;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\ValueObject\Phone;
use Modules\User\Presentation\Http\Presenter\UserPresenter;
use Modules\User\Presentation\Http\Request\CreateUserRequest;

final readonly class CreateUserCommand
{
    public function __construct(
        private RegisterUserHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(CreateUserRequest $request): JsonResponse
    {
        $command = new RegisterCommand(
            name: (string) $request->validated('name'),
            email: $request->validated('email'),
            phone: new Phone((string) $request->validated('phone')),
            password: (string) $request->validated('password'),
            fullName: (string) $request->validated('full_name'),
            identityNumber: (string) $request->validated('identity_number'),
            nationalityId: new NationalityId((string) $request->validated('nationality_id')),
            birthDate: (string) $request->validated('birth_date'),
            gender: (string) $request->validated('gender'),
            height: (float) $request->validated('height'),
            weight: (float) $request->validated('weight'),
            accountOwner: (string) $request->validated('account_owner'),
            bankName: (string) $request->validated('bank_name'),
            iban: (string) $request->validated('iban'),
            contactName: (string) $request->validated('contact_name'),
            contactPhone: (string) $request->validated('contact_phone'),
            contactRelation: (string) $request->validated('contact_relation'),
            avatar: $request->file('avatar'),
            cv: $request->file('cv'),
            personalIdentity: $request->file('personal_identity'),
            medicalReport: $request->file('medical_report'),
        );

        $user = $this->handler->handle($command);

        return $this->responder->created(
            data: UserPresenter::fromDomain($user),
            messageKey: 'user.created'
        );
    }
}
