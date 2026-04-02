<?php
// modules/IAM/Application/Command/RegisterUser/RegisterUserCommand.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser;

use Modules\IAM\Application\Command\RegisterUser\RegisterAuth\RegisterAuthCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterProfile\RegisterProfileCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterBankDetails\RegisterBankDetailsCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterContactPhone\RegisterContactPhoneCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterAttachment\RegisterAttachmentCommand;

final readonly class RegisterUserCommand
{
    public function __construct(
        public RegisterAuthCommand $auth,
        public RegisterProfileCommand $profile,
        public RegisterBankDetailsCommand $bank,
        public RegisterContactPhoneCommand $contact,
        public RegisterAttachmentCommand $attachments,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            auth: RegisterAuthCommand::fromRequest($data['user']),
            profile: RegisterProfileCommand::fromRequest($data['profile']),
            bank: RegisterBankDetailsCommand::fromRequest($data['bank']),
            contact: RegisterContactPhoneCommand::fromRequest($data['contact']),
            attachments: RegisterAttachmentCommand::fromRequest($data['attachments']),
        );
    }
}
