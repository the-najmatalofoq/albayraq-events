<?php
// modules/IAM/Presentation/Http/Action/RegisterAction.php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Geography\Domain\ValueObject\NationalityId;
use Modules\IAM\Application\Command\RegisterUser\RegisterUserCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterUserHandler;
use Modules\IAM\Domain\Event\UserRegistered;
use Modules\IAM\Presentation\Http\Request\RegisterRequest;
use Modules\Shared\Application\EventDispatcher;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Enum\BloodTypeEnum;
use Modules\User\Domain\ValueObject\Phone;

final class RegisterAction
{
    public function __construct(
        private readonly RegisterUserHandler $handler,
        private readonly JsonResponder $responder,
        private readonly EventDispatcher $eventDispatcher,
    ) {}

    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $command = new RegisterUserCommand(
            name: TranslatableText::fromMixed($request->validated('name')),
            email: $request->validated('email'),
            phone: new Phone($request->validated('phone')),
            password: $request->validated('password'),
            fullName: TranslatableText::fromMixed($request->validated('full_name')),
            identityNumber: $request->validated('identity_number'),
            nationalityId: new NationalityId($request->validated('nationality_id')),
            birthDate: $request->validated('birth_date'),
            gender: $request->validated('gender'),
            height: $request->validated('height') ? (float) $request->validated('height') : null,
            weight: $request->validated('weight') ? (float) $request->validated('weight') : null,
            accountOwner: $request->validated('account_owner'),
            bankName: $request->validated('bank_name'),
            iban: $request->validated('iban'),
            contactName: $request->validated('contact_name', ''),
            contactPhone: $request->validated('contact_phone', ''),
            contactRelation: $request->validated('contact_relation') ?? 'other',
            bloodType: BloodTypeEnum::from($request->validated('blood_type')),
            chronicDiseases: $request->validated('chronic_diseases'),
            allergies: $request->validated('allergies'),
            medications: $request->validated('medications'),
            avatar: $request->file('avatar'),
            cv: $request->file('cv'),
            personalIdentity: $request->file('personal_identity'),
            medicalReport: $request->file('medical_report'),
            fcmToken: $request->input('fcm_token'),
            deviceId: $request->input('device_id'),
            platform: $request->input('platform'),
            deviceName: $request->input('device_name'),
        );

        $user = $this->handler->handle($command);
        $this->eventDispatcher->dispatch(new UserRegistered($user->uuid));

        return $this->responder->created(
            data: ['id' => $user->uuid->value],
            messageKey: __('messages.auth.registered')
        );
    }
}
