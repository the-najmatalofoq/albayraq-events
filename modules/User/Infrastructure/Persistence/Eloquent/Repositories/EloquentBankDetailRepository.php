<?php
// modules/User/Infrastructure/Persistence/Eloquent/EloquentBankDetailRepository.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent\Repositories;

use Modules\User\Domain\BankDetail;
use Modules\User\Domain\Repository\BankDetailRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\BankDetailId;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\BankDetailModel;

final class EloquentBankDetailRepository implements BankDetailRepositoryInterface
{
    public function __construct(
        private readonly BankDetailModel $bankDetailModel
    ) {}

    public function save(BankDetail $bankDetail): void
    {
        $this->bankDetailModel->query()->updateOrCreate(
            ['id' => $bankDetail->uuid->value],
            [
                'user_id' => $bankDetail->userId->value,
                'account_owner' => $bankDetail->accountOwner,
                'bank_name' => $bankDetail->bankName,
                'iban' => $bankDetail->iban,

            ]
        );
    }

    public function updateOrCreate(
        UserId $userId,
        string $accountOwner,
        string $bankName,
        string $iban,
    ): BankDetail {
        $model = $this->bankDetailModel->updateOrCreate(
            ['user_id' => $userId->value],
            [
                'id' => $this->nextIdentity()->value,
                'account_owner' => $accountOwner,
                'bank_name' => $bankName,
                'iban' => $iban,
            ]
        );

        return $this->toDomain($model);
    }

    public function findByUserId(UserId $userId): ?BankDetail
    {
        $model = $this->bankDetailModel->where('user_id', $userId->value)->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function findById(BankDetailId $uuid): ?BankDetail
    {
        $model = $this->bankDetailModel->find($uuid->value);

        return $model ? $this->toDomain($model) : null;
    }

    public function existsWithIban(string $iban): bool
    {
        return $this->bankDetailModel->where('iban', $iban)->exists();
    }

    public function nextIdentity(): BankDetailId
    {
        return BankDetailId::generate();
    }

    private function toDomain(BankDetailModel $model): BankDetail
    {
        return BankDetail::fromPersistence(
            uuid: BankDetailId::fromString($model->id),
            userId: UserId::fromString($model->user_id),
            accountOwner: $model->account_owner,
            bankName: $model->bank_name,
            iban: $model->iban,

        );
    }
}
