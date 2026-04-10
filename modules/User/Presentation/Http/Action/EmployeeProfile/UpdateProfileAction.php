<?php
// modules/User/Presentation/Http/Action/UpdateProfileAction.php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\EmployeeProfile;

use Carbon\Doctrine\DateTimeImmutableType;
use Illuminate\Http\JsonResponse;
use Modules\Geography\Domain\ValueObject\NationalityId;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\User\Application\Command\UpdateUserProfile\UpdateUserProfileCommand;
use Modules\User\Application\Command\UpdateUserProfile\UpdateUserProfileHandler;
use Modules\User\Presentation\Http\Request\UpdateProfileRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class UpdateProfileAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private UpdateUserProfileHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(UpdateProfileRequest $request): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if (!$userId) {
            return $this->responder->unauthorized();
        }

        $command = new UpdateUserProfileCommand(
            userId: $userId,
            fullName: $request->validated('full_name') ? TranslatableText::fromMixed($request->validated('full_name')) : null,
            identityNumber: $request->validated('identity_number'),
            nationalityId: $request->validated('nationality_id') ? new NationalityId($request->validated('nationality_id')) : null,
            birthDate: $request->validated('birth_date') ? $request->validated('birth_date') : null,
            gender: $request->validated('gender'),
            height: $request->validated('height') ? (float) $request->validated('height') : null,
            weight: $request->validated('weight') ? (float) $request->validated('weight') : null,
        );

        $this->handler->handle($command);

        return $this->responder->success(
            messageKey: 'messages.updated'
        );
    }
}
