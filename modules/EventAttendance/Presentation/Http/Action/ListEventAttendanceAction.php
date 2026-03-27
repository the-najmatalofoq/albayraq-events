<?php
// modules/EventAttendance/Presentation/Http/Action/ListEventAttendanceAction.php
declare(strict_types=1);

namespace Modules\EventAttendance\Presentation\Http\Action;

use Illuminate\Http\Request;
use Modules\EventAttendance\Domain\Repository\EventAttendanceRepositoryInterface;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\EventAttendance\Presentation\Http\Presenter\EventAttendancePresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListEventAttendanceAction
{
    public function __construct(
        private EventAttendanceRepositoryInterface $repository,
        private JsonResponder $responder
    ) {
    }

    public function __invoke(Request $request): mixed
    {
        $participationId = $request->query('participation_id');

        if (!$participationId) {
            return $this->responder->error('MISSING_PARTICIPATION_ID', 400, 'Participation ID is required');
        }

        $records = $this->repository->findByParticipationId(ParticipationId::fromString($participationId));

        return $this->responder->success(
            data: array_map(fn($r) => EventAttendancePresenter::fromDomain($r), $records)
        );
    }
}
