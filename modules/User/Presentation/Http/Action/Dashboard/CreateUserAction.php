<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Role\Domain\ValueObject\RoleId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Application\Command\CreateUser\CreateUserCommand;
use Modules\User\Application\Command\CreateUser\CreateUserHandler;
use Modules\User\Domain\ValueObject\Phone;
use Modules\User\Presentation\Http\Request\Dashboard\CreateUserRequest;

final readonly class CreateUserAction
{
    public function __construct(
        private CreateUserHandler $handler,
        private JsonResponder $responder,
    ) {}
//fix this after confirm the best solution
    public function __invoke(CreateUserRequest $request): JsonResponse
    {
        $command = new CreateUserCommand(
            name: TranslatableText::fromMixed($request->validated('name')),
            email: $request->validated('email'),
            phone: new Phone($request->validated('phone')),
            password: $request->validated('password'),
            roleId: RoleId::fromString($request->validated('role_id')),
            avatar: $request->file('avatar'),
        );

        $this->handler->handle($command);

        return $this->responder->created(messageKey: 'messages.created');
    }
}
