<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\User\Application\Command\UpdateMe\UpdateMeCommand;
use Modules\User\Application\Command\UpdateMe\UpdateMeHandler;
use Modules\User\Presentation\Http\Request\UpdateMeRequest;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\ValueObject\Phone;

final readonly class UpdateMeAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private UpdateMeHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(UpdateMeRequest $request): JsonResponse
    {
        $userId = Auth::user()->id;

        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        $this->handler->handle(new UpdateMeCommand(
            userId: $userId->value,
            name: TranslatableText::fromMixed($request->validated('name')),
            phone: new Phone($request->validated('phone'))
        ));

        return $this->responder->success(
            messageKey: __('messages.updated')
        );
    }
}
