<?php
// modules/IAM/Application/Command/RegisterUser/RegisterAttachment/RegisterAttachmentCommand.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterAttachment;

final readonly class RegisterAttachmentCommand
{
    public function __construct(
        public string $cv,
        public string $medical_record,
        public string $personal_identity,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            cv: $data['cv'],
            medical_record: $data['medical_record'],
            personal_identity: $data['personal_identity'],
        );
    }
}
