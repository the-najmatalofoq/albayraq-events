<?php
// modules/User/Infrastructure/Persistence/Eloquent/EloquentContactPhoneRepository.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent;

use Modules\User\Domain\ContactPhone;
use Modules\User\Domain\Repository\ContactPhoneRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\ContactPhoneId;

final class EloquentContactPhoneRepository implements ContactPhoneRepositoryInterface
{
    public function save(ContactPhone $contactPhone): void
    {
        ContactPhoneModel::query()->updateOrCreate(
            ['id' => $contactPhone->uuid->value],
            [
                'user_id' => $contactPhone->userId->value,
                'name' => $contactPhone->name,
                'phone' => $contactPhone->phone,
                'relation' => $contactPhone->relation,
            ]
        );
    }

    public function findByUserId(UserId $userId): array
    {
        $models = ContactPhoneModel::where('user_id', $userId->value)->get();

        return $models->map(fn($model) => $this->toDomain($model))->toArray();
    }

    public function findById(ContactPhoneId $uuid): ?ContactPhone
    {
        $model = ContactPhoneModel::find($uuid->value);

        return $model ? $this->toDomain($model) : null;
    }

    public function nextIdentity(): ContactPhoneId
    {
        return ContactPhoneId::generate();
    }

    private function toDomain(ContactPhoneModel $model): ContactPhone
    {
        $reflection = new \ReflectionClass(ContactPhone::class);
        $contactPhone = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid' => ContactPhoneId::fromString($model->id),
            'userId' => UserId::fromString($model->user_id),
            'name' => $model->name,
            'phone' => $model->phone,
            'relation' => $model->relation,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($contactPhone, $value);
        }

        return $contactPhone;
    }
}
