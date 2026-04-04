<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Presenter;

use Illuminate\Support\Facades\App;
use Modules\Geography\Domain\State;

final class StatePresenter
{
    public static function present(State $state): array
    {
        return [
            'id' => $state->id()->value,
            'name' => $state->name(App::getLocale()),
        ];
    }
}
