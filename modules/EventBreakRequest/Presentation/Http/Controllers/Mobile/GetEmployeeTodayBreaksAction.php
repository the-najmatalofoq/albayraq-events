<?php
// modules/EventBreakRequest/Presentation/Http/Controllers/Mobile/GetEmployeeTodayBreaksAction.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Presentation\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\EventBreakRequest\Infrastructure\Persistence\Models\BreakRequestModel;
use Modules\EventBreakRequest\Presentation\Http\Presenter\BreakRequestPresenter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

final readonly class GetEmployeeTodayBreaksAction
{
    public function __construct(
        private JsonResponder $responder
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $today = Carbon::today()->format('Y-m-d');

        $breaks = BreakRequestModel::where('requested_by', $userId)
            ->where('date', $today)
            ->with(['requestedBy', 'approvedBy', 'coverEmployee', 'participation'])
            ->get();

        $data = $breaks->map(fn($b) => BreakRequestPresenter::fromModel($b))->toArray();

        return $this->responder->success(data: $data);
    }
}
