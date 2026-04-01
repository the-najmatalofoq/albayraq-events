<?php
// modules/IAM/Presentation/Http/Action/RegisterAction.php
declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Action;

use Dedoc\Scramble\Attributes\Group;
use Modules\IAM\Application\Command\RegisterUser\RegisterUserCommand;
use Modules\IAM\Application\Command\RegisterUser\RegisterUserHandler;
use Modules\User\Presentation\Http\Presenter\UserPresenter;
use Modules\IAM\Presentation\Http\Request\RegisterRequest;
// use Modules\Role\Domain\Repository\RoleRepository;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\UserRepositoryInterface;
use Modules\Shared\Domain\ValueObject\TranslatableText;
// use Psr\Http\Message\ResponseInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Shared\Application\Service\AvatarUploadService;
use Modules\Shared\Infrastructure\Services\FileStorage;

#[Group('Auth')]
final readonly class RegisterAction
{
    public function __construct(
        private RegisterUserHandler $handler,
        private UserRepositoryInterface $repository,
        private JsonResponder $responder,
        private FileStorage $file_storage,
    ) {}

    public function __invoke(Request $request, RegisterRequest $registerRequest)
    {
        $data = $registerRequest->validated($request);
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarFilePath = $this->file_storage->upload($request->file('avatar'), 'user');
            $avatarPath = $avatarFilePath->value;
        }

        $nameData = is_string($data['name']) ? json_decode($data['name'], true) : $data['name'];

        $registerCommand = new RegisterUserCommand(
            name: TranslatableText::fromMixed($nameData),
            phone: $data['phone'],
            password: $data['password'],
            avatar: $avatarPath,
            email: $data['email'] ?? null,
        );

        $userId = $this->handler->handle($registerCommand);

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
