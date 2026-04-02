<?php
// modules/User/Presentation/Http/Action/CompleteProfileAction.php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action;

use Dedoc\Scramble\Attributes\Group;
use Modules\User\Application\Command\CompleteProfile\CompleteProfileCommand;
use Modules\User\Application\Command\CompleteProfile\CompleteProfileHandler;
use Modules\User\Presentation\Http\Request\CompleteProfileRequest;
use Modules\User\Presentation\Http\Presenter\UserProfilePresenter;
use Modules\User\Domain\Repository\EmployeeProfileRepositoryInterface;
use Modules\User\Domain\ValueObject\UserId;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Infrastructure\Services\FileStorage;
use Illuminate\Http\Request;

#[Group('User')]
final readonly class CompleteProfileAction
{
    public function __construct(
        private CompleteProfileHandler $handler,
        private EmployeeProfileRepositoryInterface $repository,
        private JsonResponder $responder,
        private FileStorage $fileStorage,
    ) {}

    public function __invoke(Request $request, CompleteProfileRequest $completeProfileRequest)
    {
        $data = $completeProfileRequest->validated($request);
        $userId = auth()->id();

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvFilePath = $this->fileStorage->upload($request->file('cv'), 'employee');
            $cvPath = $cvFilePath->value;
        }

        $identityPersonalPath = null;
        if ($request->hasFile('identity_personal')) {
            $identityPersonalFilePath = $this->fileStorage->upload($request->file('identity_personal'), 'employee');
            $identityPersonalPath = $identityPersonalFilePath->value;
        }

        $medicalRecord = isset($data['medical_record']) ? TranslatableText::fromMixed($data['medical_record']) : null;

        $completeCommand = new CompleteProfileCommand(
            userId: $userId,
            fullName: TranslatableText::fromMixed($data['full_name']),
            birthDate: $data['birth_date'],
            nationality: $data['nationality'],
            gender: $data['gender'],
            nationalId: $data['national_id'],
            medicalRecord: $medicalRecord,
            height: $data['height'] ?? null,
            weight: $data['weight'] ?? null,
            cvPath: $cvPath,
            identityPersonalPath: $identityPersonalPath,
        );

        $profileId = $this->handler->handle($completeCommand);

        $profile = $this->repository->findById($profileId);
        if (!$profile) {
            throw new \RuntimeException('Profile completed but not found');
        }

        return $this->responder->success(
            data: UserProfilePresenter::fromDomain($profile),
            messageKey: 'messages.profile.completed'
        );
    }
}