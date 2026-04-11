<?php
// modules/User/Domain/Repository/MedicalRecordRepositoryInterface.php
declare(strict_types=1);

namespace Modules\User\Domain\Repository;

use Modules\User\Domain\MedicalRecord;
use Modules\User\Domain\ValueObject\MedicalRecordId;
use Modules\User\Domain\ValueObject\UserId;

interface MedicalRecordRepositoryInterface
{
    public function nextIdentity(): MedicalRecordId;
    public function save(MedicalRecord $medicalRecord): void;
    public function findById(MedicalRecordId $id): ?MedicalRecord;
    public function findByUserId(UserId $userId): ?MedicalRecord;
}
