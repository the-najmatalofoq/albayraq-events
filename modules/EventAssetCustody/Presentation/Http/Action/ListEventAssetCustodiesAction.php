<?php
// modules/EventAssetCustody/Presentation/Http/Action/ListEventAssetCustodiesAction.php
declare(strict_types=1);

namespace Modules\EventAssetCustody\Presentation\Http\Action;

use Illuminate\Http\Request;
use Modules\EventAssetCustody\Domain\Repository\EventAssetCustodyRepositoryInterface;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\EventAssetCustody\Presentation\Http\Presenter\EventAssetCustodyPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListEventAssetCustodiesAction
{
    public function __construct(
        private EventAssetCustodyRepositoryInterface $repository,
        private JsonResponder $responder
    ) {
    }

    public function __invoke(Request $request): mixed
    {
        $participationId = $request->query('participation_id');

        if (!$participationId) {
            return $this->responder->error('MISSING_PARTICIPATION_ID', 400, 'Participation ID is required');
        }

        $custodies = $this->repository->findByParticipationId(ParticipationId::fromString($participationId));

        return $this->responder->success(
            data: array_map(fn($c) => EventAssetCustodyPresenter::fromDomain($c), $custodies)
        );
    }
}
