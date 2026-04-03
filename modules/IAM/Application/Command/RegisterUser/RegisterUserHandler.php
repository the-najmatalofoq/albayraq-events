<?php
// modules/IAM/Application/Command/RegisterUser/RegisterUserHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser;

use Modules\IAM\Application\Command\RegisterUser\RegisterAuth\RegisterAuthCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterProfile\RegisterProfileCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterBankDetails\RegisterBankDetailsCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterContactPhone\RegisterContactPhoneCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterAttachment\RegisterAttachmentCommand;
use Modules\Shared\Application\Command\CommandHandlerInterface;
use Modules\Shared\Application\Command\CommandBusInterface;
use Illuminate\Support\Facades\DB;
// fix: what is the CommandHandlerInterface and the CommandBusInterface?  
final readonly class RegisterUserHandler implements CommandHandlerInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    public function handle(RegisterUserCommand $command): void
    {
        DB::transaction(function () use ($command) {
            $this->commandBus->dispatch(new RegisterAuthCommand(
                userId: $command->userId,
                name: $command->name,
                email: $command->email,
                phone: $command->phone,
                password: $command->password,
                nationalId: $command->nationalId,
            ));

            $this->commandBus->dispatch(new RegisterProfileCommand(
                userId: $command->userId,
                birthDate: $command->birthDate,
                nationality: $command->nationality,
                gender: $command->gender,
                height: $command->height,
                weight: $command->weight
            ));

            $this->commandBus->dispatch(new RegisterBankDetailsCommand(
                userId: $command->userId,
                accountOwner: $command->accountOwner,
                bankName: $command->bankName,
                iban: $command->iban
            ));

            foreach ($command->contactPhones as $cp) {
                $this->commandBus->dispatch(new RegisterContactPhoneCommand(
                    userId: $command->userId,
                    label: $cp['label'],
                    phone: $cp['phone']
                ));
            }

            if ($command->avatar) {
                $this->commandBus->dispatch(new RegisterAttachmentCommand(
                    userId: $command->userId,
                    file: $command->avatar,
                    collection: 'avatar'
                ));
            }

            if ($command->idCopy) {
                $this->commandBus->dispatch(new RegisterAttachmentCommand(
                    userId: $command->userId,
                    file: $command->idCopy,
                    collection: 'id_copy'
                ));
            }
        });
    }
}
