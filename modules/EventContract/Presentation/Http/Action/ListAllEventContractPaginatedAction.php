<?php

declare(strict_types=1);

namespace Modules\EventContract\Presentation\Http\Action;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\Shared\Application\Query\Pagination;
use Modules\EventContract\Application\EventContract\ListAllEventContractPaginatedHandler;
use Modules\EventContract\Presentation\Http\Presenter\EventContractPresenter;

final readonly class ListAllEventContractPaginatedAction
{
    public function __construct(
        private ListAllEventContractPaginatedHandler $handler,
        private JsonResponder $responder,
        private EventContractPresenter $presenter,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $pagination = new Pagination(
            page: (int) $request->query('page', 1),
            perPage: (int) $request->query('perPage', 15)
        );

        $result = $this->handler->handle($pagination);

        return $this->responder->success([
            'items' => $this->presenter->presentCollection($result->items),
            'meta' => [
                'total' => $result->total,
                'per_page' => $result->perPage,
                'current_page' => $result->currentPage,
                'last_page' => $result->lastPage,
            ],
        ]);
    }
}
