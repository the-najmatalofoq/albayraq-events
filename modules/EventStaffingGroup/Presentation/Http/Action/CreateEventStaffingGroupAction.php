<?php
// modules/EventStaffingGroup/Presentation/Http/Action/CreateEventStaffingGroupAction.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\EventStaffingGroup\Domain\Repository\EventStaffingGroupRepositoryInterface;
use Modules\EventStaffingGroup\Domain\EventStaffingGroup;
use Modules\EventStaffingGroup\Domain\ValueObject\GroupId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CreateEventStaffingGroupAction
{
    public function __construct(
        private EventStaffingGroupRepositoryInterface $repository,
        private JsonResponder $responder,
    ) {
    }

    // fix: make a formRequest for it
    public function __invoke(Request $request, string $eventId): JsonResponse
    {
        $id = $this->repository->nextIdentity();
        $group = EventStaffingGroup::create(
            uuid: $id,
            eventId: EventId::fromString($eventId),
            name: TranslatableText::fromArray($request->input('name')),
            color: $request->input('color'),
            isLocked: (bool) $request->input('is_locked', false),
        );

        $this->repository->save($group);

        return $this->responder->created(
            data: ['id' => $id->value],
            messageKey: 'messages.group.created'
        );
    }
}
