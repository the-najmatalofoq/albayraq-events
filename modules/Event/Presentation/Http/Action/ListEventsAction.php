<?php
// modules/Event/Presentation/Http/Action/ListEventsAction.php
declare(strict_types=1);

namespace Modules\Event\Presentation\Http\Action;

use Modules\Event\Domain\Repository\EventRepositoryInterface;
use Modules\Event\Presentation\Http\Presenter\EventPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListEventsAction
{
    public function __construct(
        private EventRepositoryInterface $repository,
        private JsonResponder $responder
    ) {}

    public function __invoke(): mixed
    {
        $events = $this->repository->listAll();

        return $this->responder->success(
            data: array_map(fn($event) => EventPresenter::fromDomain($event), $events)
        );
    }
}
