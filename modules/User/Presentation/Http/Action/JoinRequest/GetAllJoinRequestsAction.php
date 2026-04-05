<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\JoinRequest;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\UserJoinRequestRepositoryInterface;
use Modules\User\Presentation\Http\Presenter\UserJoinRequestPresenter;

final class GetAllJoinRequestsAction
{
    public function __construct(
        private readonly UserJoinRequestRepositoryInterface $repository,
        private readonly UserJoinRequestPresenter $presenter,
        private readonly JsonResponder $responder,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $all = $this->repository->findAll();

        return $this->responder->success(
            $this->presenter->presentCollection($all)
        );
    }
}
