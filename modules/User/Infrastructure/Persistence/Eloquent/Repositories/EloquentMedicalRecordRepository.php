<?php
// modules/User/Infrastructure/Persistence/Eloquent/Repositories/EloquentMedicalRecordRepository.php
declare(strict_types=1);

namespace Modules\User\Infrastructure\Persistence\Eloquent\Repositories;

use Modules\User\Domain\MedicalRecord;
use Modules\User\Domain\Repository\MedicalRecordRepositoryInterface;
use Modules\User\Domain\ValueObject\MedicalRecordId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\MedicalRecordModel;

final readonly class EloquentMedicalRecordRepository implements MedicalRecordRepositoryInterface
{
    public function __construct(
        private MedicalRecordModel $model
    ) {
    }

    public function nextIdentity(): MedicalRecordId
    {
        return MedicalRecordId::generate();
    }

    public function save(MedicalRecord $medicalRecord): void
    {
        $this->model->query()->updateOrCreate(
            ['id' => $medicalRecord->uuid->value],
            [
                'user_id' => $medicalRecord->userId->value,
                'blood_type' => $medicalRecord->bloodType,
                'chronic_diseases' => $medicalRecord->chronicDiseases,
                'allergies' => $medicalRecord->allergies,
                'medications' => $medicalRecord->medications,
            ]
        );
    }

    public function findById(MedicalRecordId $id): ?MedicalRecord
    {
        $model = $this->model->find($id->value);

        return $model ? $this->toDomain($model) : null;
    }

    public function findByUserId(UserId $userId): ?MedicalRecord
    {
        $model = $this->model->where('user_id', $userId->value)->first();

        return $model ? $this->toDomain($model) : null;
    }

    private function toDomain(MedicalRecordModel $model): MedicalRecord
    {
        return MedicalRecord::fromPersistence(
            uuid: MedicalRecordId::fromString($model->id),
            userId: UserId::fromString($model->user_id),
            bloodType: $model->blood_type,
            chronicDiseases: $model->chronic_diseases,
            allergies: $model->allergies,
            medications: $model->medications,
            createdAt: $model->created_at?->toDateTimeImmutable(),
            updatedAt: $model->updated_at?->toDateTimeImmutable(),
        );
    }
}
