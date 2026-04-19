<?php

declare(strict_types=1);

namespace Modules\User\Application\Command\ReviewUpdateRequest;

use Illuminate\Support\Facades\DB;
use Modules\User\Domain\UserUpdateRequest;
use Modules\User\Domain\Repository\UserUpdateRequestRepositoryInterface;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\BankDetailModel;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\EmployeeProfileModel;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\MedicalRecordModel;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Modules\User\Infrastructure\Persistence\Eloquent\Models\ContactPhoneModel;

final readonly class ReviewUpdateRequestHandler
{
    /**
     * Maps the string target_type stored in the database
     * to the Eloquent model class that should be updated.
     */
    private const TARGET_MODEL_MAP = [
        'user_info'        => UserModel::class,
        'bank_account'     => BankDetailModel::class,
        'employee_profile' => EmployeeProfileModel::class,
        'medical_record'   => MedicalRecordModel::class,
        'contact_phone'    => ContactPhoneModel::class,
    ];

    public function __construct(
        private UserUpdateRequestRepositoryInterface $repository
    ) {}

    public function handle(ReviewUpdateRequestCommand $command): UserUpdateRequest
    {
        return DB::transaction(function () use ($command) {
            $request = $this->repository->findById($command->requestId);

            if (!$request) {
                throw new \DomainException("Update request not found.");
            }

            if ($command->action === 'approve') {
                $request->approve($command->adminId);

                $modelClass = self::TARGET_MODEL_MAP[$request->targetType] ?? null;

                if (!$modelClass) {
                    throw new \DomainException(
                        "Unknown target type: [{$request->targetType}]. Cannot apply update."
                    );
                }

                $model = $modelClass::find($request->targetId);

                if (!$model) {
                    throw new \DomainException(
                        "Target record [{$request->targetType}:{$request->targetId}] not found."
                    );
                }

                $model->update($request->newData);
            } else {
                $request->reject($command->adminId, $command->rejectionReason);
            }

            $this->repository->save($request);

            return $request;
        });
    }
}
