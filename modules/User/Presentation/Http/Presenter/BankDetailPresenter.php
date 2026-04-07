<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Presenter;

use Modules\User\Domain\BankDetail;

final class BankDetailPresenter
{
    public static function fromDomain(?BankDetail $bankDetail): ?array
    {
        if ($bankDetail === null) {
            return null;
        }

        return [
            'id' => $bankDetail->uuid->value,
            'account_owner' => $bankDetail->accountOwner,
            'bank_name' => $bankDetail->bankName,
            'iban' => $bankDetail->iban,
        ];
    }
}
