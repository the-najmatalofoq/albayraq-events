<?php
// modules/IAM/Application/Command/RegisterUser/RegisterUserHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser;

use Illuminate\Support\Facades\DB;
use Modules\IAM\Application\Command\RegisterUser\RegisterAuth\RegisterAuthCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterAuth\RegisterAuthHandler;
use Modules\IAM\Application\Command\RegisterUser\RegisterProfile\RegisterProfileCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterProfile\RegisterProfileHandler;
use Modules\IAM\Application\Command\RegisterUser\RegisterBankDetails\RegisterBankDetailsCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterBankDetails\RegisterBankDetailsHandler;
use Modules\IAM\Application\Command\RegisterUser\RegisterContactPhone\RegisterContactPhoneCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterContactPhone\RegisterContactPhoneHandler;
use Modules\IAM\Application\Command\RegisterUser\RegisterAttachment\RegisterAttachmentCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterAttachment\RegisterAttachmentHandler;

final readonly class RegisterUserHandler
{
    public function __construct(
        private RegisterAuthHandler $authHandler,
        private RegisterProfileHandler $profileHandler,
        private RegisterBankDetailsHandler $bankHandler,
        private RegisterContactPhoneHandler $contactPhoneHandler,
        private RegisterAttachmentHandler $attachmentHandler,
    ) {
    }

    public function handle(RegisterUserCommand $command): void
    {
        DB::transaction(function () use ($command) {
            $this->authHandler->handle(new RegisterAuthCommand(
                userId: $command->userId,
                name: $command->name,
                email: $command->email,
                phone: $command->phone,
                password: $command->password,
                nationalId: $command->nationalId,
            ));

            $this->profileHandler->handle(new RegisterProfileCommand(
                userId: $command->userId,
                birthDate: $command->birthDate,
                cityId: $command->cityId,
                nationalities: $command->nationalities,
                gender: $command->gender,
                height: $command->height,
                weight: $command->weight
            ));

            $this->bankHandler->handle(new RegisterBankDetailsCommand(
                userId: $command->userId,
                accountOwner: $command->accountOwner,
                bankName: $command->bankName,
                iban: $command->iban
            ));

            foreach ($command->contactPhones as $cp) {
                $this->contactPhoneHandler->handle(new RegisterContactPhoneCommand(
                    userId: $command->userId,
                    label: $cp['label'] ?? $cp['name'] ?? 'emergency',
                    phone: $cp['phone']
                ));
            }

            if ($command->avatar) {
                $this->attachmentHandler->handle(new RegisterAttachmentCommand(
                    userId: $command->userId,
                    file: $command->avatar,
                    collection: 'avatar'
                ));
            }

            if ($command->idCopy) {
                $this->attachmentHandler->handle(new RegisterAttachmentCommand(
                    userId: $command->userId,
                    file: $command->idCopy,
                    collection: 'id_copy'
                ));
            }
        });
    }
}
