<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\JoinRequest;

use Illuminate\Http\JsonResponse;
use Modules\User\Domain\Exception\JoinRequestNotFoundException;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\UserJoinRequestRepositoryInterface;
use Modules\User\Domain\ValueObject\UserJoinRequestId;
use Modules\User\Presentation\Http\Presenter\UserJoinRequestPresenter;

final class GetJoinRequestAction
{
    public function __construct(
        private readonly UserJoinRequestRepositoryInterface $repository,
        private readonly UserJoinRequestPresenter $presenter,
        private readonly JsonResponder $responder,
    ) {
    }

    public function __invoke(string $id): JsonResponse
    {
        $joinRequest = $this->repository->findById(new UserJoinRequestId($id));

        if ($joinRequest === null) {
            throw JoinRequestNotFoundException::forId($id);
        }

        return $this->responder->success(
            $this->presenter->present($joinRequest)
        );
    }
}
