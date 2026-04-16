<?php
// filePath: modules/EventPositionApplication/Presentation/Http/Request/Crm/CrmListEventPositionApplicationsRequest.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Presentation\Http\Request\Dashboard;

use Modules\Shared\Presentation\Http\Request\BaseFilterRequest;

final class ListEventPositionApplicationsRequest extends BaseFilterRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'user_id' => ['sometimes', 'exists:users,id'],
            'position_id' => ['sometimes', 'exists:event_staffing_positions,id'],
            'status' => ['sometimes', 'string', 'in:pending,approved,rejected,cancelled'],
            'trashed' => ['sometimes', 'string', 'in:with,only'],
        ]);
    }
}
