<?php
// modules/EventRoleCapability/Presentation/Http/Request/Crm/CrmCreateEventRoleCapabilityRequest.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Presentation\Http\Request\Crm;

use Illuminate\Foundation\Http\FormRequest;

final class CrmCreateEventRoleCapabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'assignment_id' => ['required', 'exists:event_role_assignments,id'],
            'is_granted' => ['required', 'boolean'],
            'capability_key' => [
                'required',
                'string',
                'unique:event_role_capabilities,capability_key,NULL,id,event_role_assignment_id,' . $this->input('assignment_id')
            ],
        ];
    }

    public function assignmentId(): string
    {
        return $this->input('assignment_id');
    }

    public function capabilityKey(): string
    {
        return $this->input('capability_key');
    }

    public function isGranted(): bool
    {
        return (bool) $this->input('is_granted');
    }
}
