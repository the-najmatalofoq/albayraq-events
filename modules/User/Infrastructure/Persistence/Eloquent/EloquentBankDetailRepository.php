<?php
// modules/User/Infrastructure/Persistence/Eloquent/EloquentBankDetailRepository.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent;

use Modules\User\Domain\BankDetail;
use Modules\User\Domain\Repository\BankDetailRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\BankDetailId;

final class EloquentBankDetailRepository implements BankDetailRepositoryInterface
{
    public function save(BankDetail $bankDetail): void
    {
        BankDetailModel::query()->updateOrCreate(
            ['id' => $bankDetail->uuid->value],
            [
                'user_id' => $bankDetail->userId->value,
                'account_owner' => $bankDetail->accountOwner,
                'bank_name' => $bankDetail->bankName,
                'iban' => $bankDetail->iban,
            ]
        );
    }

    public function findByUserId(UserId $userId): ?BankDetail
    {
        $model = BankDetailModel::where('user_id', $userId->value)->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function findById(BankDetailId $uuid): ?BankDetail
    {
        $model = BankDetailModel::find($uuid->value);

        return $model ? $this->toDomain($model) : null;
    }

    public function findByIban(string $iban): ?BankDetail
    {
        $model = BankDetailModel::where('iban', $iban)->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function findByAccountOwner(string $accountOwner): ?BankDetail
    {
        $model = BankDetailModel::where('account_owner', $accountOwner)->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function nextIdentity(): BankDetailId
    {
        return BankDetailId::generate();
    }

    private function toDomain(BankDetailModel $model): BankDetail
    {
        $reflection = new \ReflectionClass(BankDetail::class);
        $bankDetail = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid' => BankDetailId::fromString($model->id),
            'userId' => UserId::fromString($model->user_id),
            'accountOwner' => $model->account_owner,
            'bankName' => $model->bank_name,
            'iban' => $model->iban,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($bankDetail, $value);
        }

        return $bankDetail;
    }
}
