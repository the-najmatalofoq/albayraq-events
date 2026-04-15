<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Geography\Domain\ValueObject\NationalityId;
use Modules\IAM\Application\Command\RegisterUser\RegisterUserCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterUserHandler;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Enum\BloodTypeEnum;
use Modules\User\Domain\ValueObject\Phone;
use Modules\User\Presentation\Http\Request\Dashboard\CreateUserRequest;

// fix: make a php DOCs in the CreateUserRequest and use the correct method from the CreateUserRequest, don't always use the input. 
// fix: search for the method of the formRequest to get the better resutl and data types.
final readonly class CreateUserAction
{
    public function __construct(
        private RegisterUserHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(CreateUserRequest $request): JsonResponse
    {
        $command = new RegisterUserCommand(
            name: TranslatableText::fromMixed($request->input('name')),
            email: (string) $request->input('email'),
            phone: new Phone((string) $request->input('phone')),
            password: (string) $request->input('password'),
            fullName: TranslatableText::fromMixed($request->input('full_name')),
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
            bloodType: BloodTypeEnum::from($request->validated('blood_type')),
            chronicDiseases: $request->input('chronic_diseases'),
            allergies: $request->input('allergies'),
            medications: $request->input('medications'),
        );

        $this->handler->handle($command);

        return $this->responder->created(messageKey: 'user.created');
    }
}
