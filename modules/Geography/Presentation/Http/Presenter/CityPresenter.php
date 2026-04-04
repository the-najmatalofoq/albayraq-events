<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Presenter;

use Illuminate\Support\Facades\App;
use Modules\Geography\Domain\City;

final class CityPresenter
{
    public static function present(City $city): array
    {
        return [
            'id' => $city->id()->value,
            'state_id' => $city->stateId()?->value,
            'name' => $city->name(App::getLocale()),
        ];
    }
}
