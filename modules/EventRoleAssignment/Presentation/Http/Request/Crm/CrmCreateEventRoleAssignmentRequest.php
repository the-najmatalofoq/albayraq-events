<?php
// modules/EventRoleAssignment/Presentation/Http/Request/Crm/CrmCreateEventRoleAssignmentRequest.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Presentation\Http\Request\Crm;

use Illuminate\Foundation\Http\FormRequest;

final class CrmCreateEventRoleAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_id' => ['required', 'exists:events,id'],
            'user_id' => ['required', 'exists:users,id'],
            'role_id' => [
                'required',
                'exists:roles,id',
                'unique:event_role_assignments,role_id,NULL,id,event_id,' . $this->input('event_id') . ',user_id,' . $this->input('user_id')
            ],
        ];
    }

    public function eventId(): string
    {
        return $this->input('event_id');
    }

    public function userId(): string
    {
        return $this->input('user_id');
    }

    public function roleId(): string
    {
        return $this->input('role_id');
    }
}
