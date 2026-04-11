<?php
// modules/EventRoleCapability/Presentation/Http/Request/Crm/CrmListEventRoleCapabilitiesRequest.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Presentation\Http\Request\Crm;

use Modules\Shared\Presentation\Http\Request\BaseFilterRequest;

final class CrmListEventRoleCapabilitiesRequest extends BaseFilterRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'assignment_id' => ['sometimes', 'exists:event_role_assignments,id'],
            'capability_key' => ['sometimes', 'string'],
            'trashed' => ['sometimes', 'in:with,only'],
        ]);
    }
}
