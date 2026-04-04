<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Presenter;

use Illuminate\Support\Facades\App;
use Modules\Geography\Domain\Country;

final class CountryPresenter
{
    public static function present(Country $country): array
    {
        return [
            'id' => $country->id()->value,
            'code' => $country->code(),
            'name' => $country->name(App::getLocale()),
            'phone_code' => $country->phoneCode(),
        ];
    }
}
