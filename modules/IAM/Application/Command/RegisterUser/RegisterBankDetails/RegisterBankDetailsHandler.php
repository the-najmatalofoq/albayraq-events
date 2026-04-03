<?php
// modules/IAM/Application/Command/RegisterUser/RegisterBankDetails/RegisterBankDetailsHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterBankDetails;

use Modules\User\Infrastructure\Persistence\Eloquent\Models\BankDetailModel;
use Illuminate\Support\Str;

// fix: make EloquentRepository for the model and inject it in the hanlder
final readonly class RegisterBankDetailsHandler
{
    public function handle(RegisterBankDetailsCommand $command): void
    {
        BankDetailModel::updateOrCreate(
            ['iban' => $command->iban],
            [
                'id' => Str::uuid()->toString(),
                'user_id' => $command->userId,
                'account_owner' => $command->accountOwner,
                'bank_name' => $command->bankName,
            ]
        );
    }
}
