<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Presenter;

use Illuminate\Support\Facades\App;
use Modules\Geography\Domain\Nationality;

final class NationalityPresenter
{
    public static function present(Nationality $nationality): array
    {
        return [
            'id' => $nationality->id()->value,
            'country_id' => $nationality->countryId()->value,
            'name' => $nationality->name(App::getLocale()),
        ];
    }
}
