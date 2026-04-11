<?php
// modules/IAM/Application/Command/RegisterUser/RegisterMedicalRecord/RegisterMedicalRecordHandler.php
declare(strict_types=1);

namespace Modules\IAM\Application\Command\RegisterUser\RegisterMedicalRecord;

use Modules\User\Domain\Enum\BloodTypeEnum;
use Modules\User\Domain\MedicalRecord;
use Modules\User\Domain\Repository\MedicalRecordRepositoryInterface;

final readonly class RegisterMedicalRecordHandler
{
    public function __construct(
        private MedicalRecordRepositoryInterface $medicalRecordRepository
    ) {}

    public function handle(RegisterMedicalRecordCommand $command): void
    {
        $medicalRecord = MedicalRecord::create(
            uuid: $this->medicalRecordRepository->nextIdentity(),
            userId: $command->userId,
            bloodType: $command->bloodType,
            chronicDiseases: $command->chronicDiseases,
            allergies: $command->allergies,
            medications: $command->medications,
        );

        $this->medicalRecordRepository->save($medicalRecord);
    }
}
