<?php
// modules/IAM/Presentation/Http/Action/RegisterAction.php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Action;

use Dedoc\Scramble\Attributes\Group;
use Modules\IAM\Application\Command\RegisterUser\RegisterUserCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterUserHandler;
use Modules\User\Presentation\Http\Presenter\UserPresenter;
use Modules\IAM\Presentation\Http\Request\RegisterRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\UserRepositoryInterface;

use Illuminate\Http\Request;
use Modules\Shared\Infrastructure\Services\FileStorage;

#[Group('Auth')]
final readonly class RegisterAction
{
    public function __construct(
        private RegisterUserHandler $handler,
        private UserRepositoryInterface $repository,
        private JsonResponder $responder,
        private FileStorage $fileStorage,
    ) {}

    public function __invoke(Request $request, RegisterRequest $registerRequest)
    {
        $data = $registerRequest->validated($request);

        // todo: move into private method
        if ($request->hasFile('avatar')) {
            $avatarFilePath = $this->fileStorage->upload($request->file('avatar'), 'user//avatars');
            $data['user']['avatar'] = $avatarFilePath->value;
        } else {
            $data['user']['avatar'] = null;
        }

        // todo: move into private method
        $data['attachments'] = [
            'cv' => $request->hasFile('attachments.cv')
                ? $this->fileStorage->upload($request->file('attachments.cv'), 'user/cvs')->value
                : null,
            'medical_record' => $request->hasFile('attachments.medical_record')
                ? $this->fileStorage->upload($request->file('attachments.medical_record'), 'user/medicals')->value
                : null,
            'personal_identity' => $request->hasFile('attachments.personal_identity')
                ? $this->fileStorage->upload($request->file('attachments.personal_identity'), 'user/ids')->value
                : null,
        ];

        $command = RegisterUserCommand::fromRequest($data);

        $userId = $this->handler->handle($command);

        $user = $this->repository->findById($userId);
        if (!$user) {
            throw new \RuntimeException('User registered but not found');
        }

        return $this->responder->success(
            data: UserPresenter::fromDomain($user),
            messageKey: 'messages.auth.registered'
        );
    }
}
