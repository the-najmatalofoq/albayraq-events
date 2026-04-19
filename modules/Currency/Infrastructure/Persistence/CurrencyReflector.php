<?php
// modules/Currency/Infrastructure/Persistence/CurrencyReflector.php
declare(strict_types=1);

namespace Modules\Currency\Infrastructure\Persistence;

use Modules\Currency\Domain\Currency;
use Modules\Currency\Infrastructure\Persistence\Eloquent\Models\CurrencyModel;

final class CurrencyReflector
{
    public static function fromModel(CurrencyModel $model): Currency
    {
        return Currency::reconstitute(
            uuid: $model->id,
            name: $model->name,
            code: $model->code,
            symbol: $model->symbol,
            isActive: $model->is_active,
        );
    }

    public static function fromDomain(Currency $currency): array
    {
        return [
            'id' => $currency->uuid->value,
            'name' => $currency->name->values,
            'code' => $currency->code,
            'symbol' => $currency->symbol,
            'is_active' => $currency->isActive,
        ];
    }
}
