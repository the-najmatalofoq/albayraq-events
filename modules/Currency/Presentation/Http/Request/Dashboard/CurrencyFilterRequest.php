<?php
// modules/Currency/Presentation/Http/Request/Crm/CurrencyFilterRequest.php
declare(strict_types=1);

namespace Modules\Currency\Presentation\Http\Request\Dashboard;

use Modules\Shared\Presentation\Http\Request\BaseFilterRequest;

final class CurrencyFilterRequest extends BaseFilterRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'is_active' => ['sometimes', 'boolean'],
        ]);
    }
}
