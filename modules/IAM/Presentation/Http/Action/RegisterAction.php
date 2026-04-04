<?php
// modules/IAM/Presentation/Http/Action/RegisterAction.php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Application\Command\RegisterUser\RegisterUserCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterUserHandler;
use Modules\IAM\Presentation\Http\Request\RegisterRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\UserRepositoryInterface;

final class RegisterAction
{
    public function __construct(
        private readonly RegisterUserHandler $handler,
        private readonly JsonResponder $responder,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $userId = $this->userRepository->nextIdentity();

        $command = new RegisterUserCommand(
            userId: $userId->value,
            name: $request->validated('name'),
            email: $request->validated('email'),
            phone: $request->validated('phone'),
            password: $request->validated('password'),
            nationalId: $request->validated('national_id'),
            birthDate: $request->validated('birth_date'),
            cityId: $request->validated('city_id'),
            nationalities: $request->validated('nationalities', []),
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

        $this->handler->handle($command);

        return $this->responder->created(
            data: ['id' => $userId->value],
            messageKey: 'auth.registered'
        );
    }
}
