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
use Modules\IAM\Application\Command\RegisterUser\RegisterMedicalRecord\RegisterMedicalRecordCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterMedicalRecord\RegisterMedicalRecordHandler;
use Modules\IAM\Application\Command\RegisterUser\RegisterAttachment\RegisterAttachmentCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterAttachment\RegisterAttachmentHandler;
use Modules\IAM\Application\Command\RegisterUser\RegisterUserSettings\RegisterUserSettingsCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterUserSettings\RegisterUserSettingsHandler;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\User\Domain\Enum\BloodTypeEnum;
use Modules\User\Domain\User;
use Modules\User\Domain\ValueObject\Phone;

final readonly class RegisterUserHandler
{
    public function __construct(
        private RegisterAuthHandler $authHandler,
        private RegisterProfileHandler $profileHandler,
        private RegisterBankDetailsHandler $bankHandler,
        private RegisterContactPhoneHandler $contactPhoneHandler,
        private RegisterAttachmentHandler $attachmentHandler,
        private RegisterUserSettingsHandler $settingsHandler,
        private RegisterMedicalRecordHandler $medicalRecordHandler,
    ) {}

    public function handle(RegisterUserCommand $command): User
    {
        return DB::transaction(function () use ($command) {

            $user =  $this->authHandler->handle(new RegisterAuthCommand(
                name: $command->name,
                email: $command->email,
                phone: $command->phone,
                password: $command->password,
                avatar: $command->avatar,
            ));
            $this->profileHandler->handle(new RegisterProfileCommand(
                userId: $user->uuid,
                fullName: $command->fullName,
                identityNumber: $command->identityNumber,
                nationalityId: $command->nationalityId,
                birthDate: $command->birthDate,
                gender: $command->gender,
                height: $command->height,
                weight: $command->weight
            ));

            $this->settingsHandler->handle(new RegisterUserSettingsCommand(
                userId: $user->uuid,
                preferredLocale: $command->preferredLocale ?? null
            ));

            $this->medicalRecordHandler->handle(new RegisterMedicalRecordCommand(
                userId: $user->uuid,
                bloodType: $command->bloodType,
                chronicDiseases: $command->chronicDiseases,
                allergies: $command->allergies,
                medications: $command->medications
            ));

            $this->bankHandler->handle(new RegisterBankDetailsCommand(
                userId: $user->uuid,
                accountOwner: $command->accountOwner,
                bankName: $command->bankName,
                iban: $command->iban
            ));

            if ($command->contactPhone) {
                $this->contactPhoneHandler->handle(new RegisterContactPhoneCommand(
                    userId: $user->uuid,
                    contactName: $command->contactName,
                    phone: new Phone($command->contactPhone),
                    relation: $command->contactRelation,
                ));
            }

            if ($command->cv) {
                $this->attachmentHandler->handle(new RegisterAttachmentCommand(
                    userId: $user->uuid,
                    file: $command->cv,
                    collection: 'cv'
                ));
            }

            if ($command->personalIdentity) {
                $this->attachmentHandler->handle(new RegisterAttachmentCommand(
                    userId: $user->uuid,
                    file: $command->personalIdentity,
                    collection: 'personal_identity'
                ));
            }

            if ($command->medicalReport) {
                $this->attachmentHandler->handle(new RegisterAttachmentCommand(
                    userId: $user->uuid,
                    file: $command->medicalReport,
                    collection: 'medical_report'
                ));
            }
            return $user;
        });
    }
}
